<?php

namespace App\Console\Commands\PesquisaPatrimonial;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PesquisaPatrimonial\SolicitacaoPagamentoBoleto;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;


class EnvioBoleto extends Command {


    protected $signature = 'pesquisapatrimonial_envioboleto:cron';


    protected $description = 'Verificação diaria ás 23:00 de todos os boletos gerados na data de hoje referente a pesquisa patrimonial que serão enviados ao solicitante/advogado e ao cliente.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();

    //Pego os Cliente que tiverem boletos gerados na Data Hoje
    
    $verifica = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->select(
            'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
            'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
            'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
            'dbo.PesquisaPatrimonial_Matrix.codigo_boleto',
            'dbo.users.email as SolicitanteEmail',
            'dbo.users.id as SolicitanteID',
            'dbo.PesquisaPatrimonial_EmailCliente.email as ClienteEmail',)
    ->groupBy('dbo.PesquisaPatrimonial_Matrix.id','PLCFULL.dbo.Jurid_CliFor.Codigo', 'PLCFULL.dbo.Jurid_CliFor.Razao','dbo.PesquisaPatrimonial_Matrix.codigo_boleto','dbo.users.id','dbo.users.email', 'dbo.PesquisaPatrimonial_EmailCliente.email' )
    ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '=', '22')
    // ->whereDate('dbo.PesquisaPatrimonial_Matrix.data', '=', $carbon->format('Y-m-d'))
    ->get();

    
   if($verifica->count() == 0) {

    //Grava na Hist informando que foi executado com sucesso mas não obteve registros
    $date = date('Y-m-d H:i', strtotime('+24 Hours'));
    $id_matrix = 6;
    $observacao = 'Comando executado com sucesso, mas não teve registros.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

   } else {
    foreach($verifica as $index) {

     $id_matrix = $index->id_matrix;
     $clientecodigo = $index->ClienteCodigo;
     $clienterazao = $index->ClienteRazao;
     $emailcliente = $index->ClienteEmail;
     $emailsolicitante = $index->SolicitanteEmail;
     $solicitanteid = $index->SolicitanteID;
     $codigo_boleto = $index->codigo_boleto;

     //Com o Codigo do Cliente ele vai pegar todos os dados e enviar por email junto com boleto

     $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
     ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
     ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
     ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
     ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.users.id as SolicitanteID',
       'dbo.users.email as SolicitanteEmail',
       'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
       'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
       'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
       'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
       'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
       'PLCFULL.dbo.Jurid_CliFor.Cep as ClienteCEP',
       'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
       'PLCFULL.dbo.Jurid_Unidade.Razao as UnidadeRazao',
       'PLCFULL.dbo.Jurid_Unidade.CNPJ as UnidadeCNPJ',
       'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
       'PLCFULL.dbo.JUrid_Unidade.Bairro as UnidadeBairro',
       'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
       'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
       'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
       'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
       'dbo.PesquisaPatrimonial_EmailCliente.email as ClienteEmail',
       DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'))
       ->where('PLCFULL.dbo.Jurid_CliFor.Codigo','=', $clientecodigo)
    //   ->whereDate('dbo.PesquisaPatrimonial_Matrix.data', '=', $carbon->format('Y-m-d'))
       ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '=', '22')
       ->first();

      //Pego a CPR
       $numdoc = DB::table('PLCFULL.dbo.Jurid_Boleto')
                ->select('num_doc')
                ->where('codigo_boleto', $codigo_boleto)
                ->value('num_doc');

    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
     ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
     ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
     ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id',
       'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
       'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
       'PLCFULL.dbo.Jurid_CliFor.Razao as Razao',
       'PLCFULL.dbo.Jurid_CliFor.Nome as NomeFantasia',
       'dbo.PesquisaPatrimonial_Matrix.data as data',
       'dbo.PesquisaPatrimonial_Matrix.classificacao as classificacao',
       'dbo.PesquisaPatrimonial_Servicos.descricao as tiposervico',
       'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
       'dbo.users.name as solicitante',
       DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as valor'))
       ->where('PLCFULL.dbo.Jurid_CliFor.Codigo','=', $clientecodigo)
    //    ->whereDay('dbo.PesquisaPatrimonial_Matrix.data', '=', date('d'))
    //    ->whereDate('dbo.PesquisaPatrimonial_Matrix.data', '=', '2021-02-03')
       ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(22))
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo_boleto', $codigo_boleto)
    //    ->whereNotIn('dbo.PesquisaPatrimonial_Matrix.id', array(124,125,126,133,137,138,139,148,164,166,167,168,169,170,171,172,173,201))
       ->get();

       $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
       ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
       ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
       ->get();

       
       //Manda notificação para o Advogado solicitante
       $values3= array('data' => $carbon, 'id_ref' => $numdoc, 'user_id' => '25', 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Boleto enviado ao cliente para pagamento.' ,'status' => 'A');
       DB::table('dbo.Hist_Notificacao')->insert($values3);

       //Pego o valor total
       $valor = DB::table('dbo.PesquisaPatrimonial_Matrix')
                ->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))
                ->whereIn('status_id', array(22))
                ->where('codigo_boleto','=',$codigo_boleto)
                ->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));

       $extenso = new NumeroPorExtenso;
       $extenso = $extenso->converter($valor);

        if($emailcliente != null) {
            Mail::to($emailcliente)
            ->cc([$emailsolicitante, 'gumercindo.ribeiro@plcadvogados.com.br', 'vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br'])
            ->send(new SolicitacaoPagamentoBoleto($datas, $solicitacoes, $numdoc, $valor, $extenso, $carbon, $comarcas));
        } else {
            Mail::to([$emailsolicitante, 'gumercindo.ribeiro@plcadvogados.com.br', 'vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br'])
            ->send(new SolicitacaoPagamentoBoleto($datas, $solicitacoes, $numdoc, $valor, $extenso, $carbon, $comarcas));
        }

       //Update na Matrix
       $values = array('status_id' => '5');
       DB::table('dbo.PesquisaPatrimonial_Matrix')->where('dbo.PesquisaPatrimonial_Matrix.codigo_boleto', '=', $codigo_boleto)->update($values);
    }
   
    //Grava na Hist informando que foi executado com sucesso
    $date = date('Y-m-d H:i', strtotime('+24 Hours'));
    $id_matrix = 6;
    $observacao = 'Comando executado com sucesso, e registros enviados por email.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

   }


    }
}
