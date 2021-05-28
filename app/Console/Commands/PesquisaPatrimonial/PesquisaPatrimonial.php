<?php

namespace App\Console\Commands\PesquisaPatrimonial;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PesquisaPatrimonial\SolicitacaoLiberada;


class PesquisaPatrimonial extends Command {


    protected $signature = 'pesquisapatrimonial:cron';


    protected $description = 'Verificação automatica a cada 1 hora dos pagamentos realizados no dia para envio ao nucleo pesquisa patrimonial.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    //Verifico se houve possui alguma baixa de boleto referente a uma solicitação de pesquisa patrimonial na data de hoje
    
    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->join('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
    ->join('PLCFULL.dbo.Jurid_Boleto', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc', '=', 'PLCFULL.dbo.Jurid_Boleto.num_doc')
    ->select('PLCFULL.dbo.Jurid_Boleto.codigo_boleto')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(5, 12))
    ->where('PLCFULL.dbo.Jurid_Boleto.data_quitacao', '!=', "")
    // ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', 'P')
    ->where('PLCFULL.dbo.Jurid_Boleto.codigo_conta_bancaria', '004')
    ->get();    

    
   //Envia relação para a pesquisa patrimonial via email
   if($datas->count() != 0) {

    foreach($datas as $index) {  

     //Busca as solicitações com este codigo de boleto
     $codigo_boleto = $index->codigo_boleto;
         
    //Verifico se possui mais de uma solicitação para este boleto se sim atualiza
    $verificadatas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.PesquisaPatrimonial_Matrix.status_id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_ContaPR.Numdoc as CPR',
      )
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(5, 12))
      ->where('PLCFULL.dbo.Jurid_ContaPr.codigo_boleto', '=', $codigo_boleto)
      ->get();    

    //Loop para atualizar todas as solicitações com este boleto baixado
    foreach($verificadatas as $data) {

      $id_matrix = $data->id_matrix;
      $emailsolicitante = $data->SolicitanteEmail;
      $solicitanteid = $data->SolicitanteID;
      $pasta = $data->Pasta;
      $codigocliente = $data->ClienteCodigo;
      $valor = $data->Valor;
      $cpr = $data->CPR;
      $setor = $data->Setor;
      $unidade = $data->Unidade;
      $StatusID = $data->StatusID;

      $primeiroscodigo =  substr($codigocliente, 0, 5);  
      $descricao = "PESQUISA" . $primeiroscodigo;

     //Grava na Hist
     $values = array('id_matrix' => $id_matrix, 
    'user_id' => '25', 'status_id' => '23', 'data' => $carbon);
     DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

      //Update status 
     DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->where('id', $id_matrix)  
     ->limit(1) 
     ->update(array('status_id'=> '23'));

    //Manda notificação para o Advogado solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => '25', 'destino_id' => $data->SolicitanteID, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Boleto pago pelo cliente.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Manda notificação para a Vanessa
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => '25', 'destino_id' => '284', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Boleto pago pelo cliente.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Manda notificação para o Felipe
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => '25', 'destino_id' => '885', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Boleto pago pelo cliente.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Insert ContaPRBX Aumentando Saldo Cliente (Tipo R(Receber))
    $cprident = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc', '=', $cpr)->value('Cpr_ident'); 

    $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
    $numdoc = $ultimonumprc + 1;
      
    DB::table('PLCFULL.dbo.Jurid_Default_')
    ->limit(1) 
    ->update(array('Numcpr' => $numdoc));
      
    $values2= array(
       'Tipodoc' => 'TRF', 
       'Numdoc' => $numdoc,
       'Cliente' => $codigocliente,
       'Tipo' => 'R',
       'TipodocOR' => '16.10',
       'NumDocOr' => $cpr,
       'Tipolan' => '16.10',
       'Valor' => $valor,
       'Centro' => $setor,
       'Dt_baixa' => $carbon->format('Y-m-d'),
       'Portador' => $descricao,
       'Obs' => 'Transfêrencia para aumento de saldo do cliente realizada automaticamente pelo Portal referente a Pesquisa Patrimonial Nª: ' . $id_matrix . ' para a CPR: ' . $cpr,
       'Juros' => '0,00',
       'Dt_Compet' => $carbon->format('Y-m-d'),
       'DT_Concil' => $carbon->format('Y-m-d'),
       'Codigo_Comp' => $pasta,
       'Unidade' => $unidade,
       'Ident_Cpr' => $cprident,
       'origem_cpr' => $cprident);
     DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values2);


     //Update Jurid_Debite colocando status em cobrança
     DB::table('PLCFULL.dbo.Jurid_Debite')
     ->join('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix', '=', $id_matrix)     
     ->update(array('PLCFULL.dbo.Jurid_Debite.Status' => '1', 'PLCFULL.dbo.Jurid_Debite.Fatura' => $numdoc));

    //Envia email para o nucleo informando que o saldo do cliente foi aumentado e aguarda o inicio da pesquisa
      Mail::to('vanessa.ferreira@plcadvogados.com.br')
      ->cc('felipe.rocha@especialistaresultados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br')
      ->send(new SolicitacaoLiberada($id_matrix));


    }
    //Fim Foreach
    

  


   }
   //Fim Foreach


   }
   //Se não encontrar nenhum boleto


    }
}
