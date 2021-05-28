<?php

namespace App\Http\Controllers\Painel\Financeiro;

use App\Models\Correspondente;
use App\Models\TipoServico;
use App\Models\Moeda;
use App\Models\Advogado;
use App\Models\Pasta;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\ComprovantePagamento;
use Illuminate\Support\Facades\Mail;
use Excel;
use App\Mail\DebiteAprovadoFinanceiro;
use App\Mail\DebiteReprovadoFinanceiro;
use App\Mail\DebiteAguardandoPagamento;
use App\Mail\StatusContrato;
use App\Mail\SolicitacaoPaga;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;
use App\Models\TiposDoc;
use Illuminate\Support\Facades\Storage;
use App\Mail\SendMailUser;
use App\Mail\Reembolso\SolicitacaoReembolso;
use App\Mail\Reembolso\SolicitacaoReembolsoCorrigida;
use App\Mail\Reembolso\FinanceiroAprovar;
use App\Mail\Reembolso\SolicitacaoReprovada;
use App\Mail\Reembolso\SolicitacaoReprovadaFinanceiro;
use App\Mail\Reembolso\SolicitacaoCancelada;
use App\Mail\Reembolso\FinanceiroAprovado;
use App\Mail\Reembolso\FinanceiroBaixado;
use App\Mail\Reembolso\SolicitacaoDuplicada;
use Illuminate\Support\Facades\URL;
use Illuminate\Routing\UrlGenerator;
use App\Mail\NovoUsuario;
use App\Mail\Correspondente\NovoUsuarioCorrespondente;
use App\Mail\Correspondente\AtualizarSetor;
use File;
use App\Mail\GuiasCusta\SolicitacaoGuiaCusta;
use App\Mail\GuiasCusta\SolicitacaoGuiaCustaRevisada;
use App\Mail\GuiasCusta\SolicitacaoGuiasCustaReprovada;
use App\Mail\GuiasCusta\SolicitacaoGuiaCustaCancelada;
use App\Mail\GuiasCusta\SolicitacaoGuiaCustaBaixado;

class FinanceiroController extends Controller
{

    protected $model;
    protected $totalPage = 100;   
    public $timestamps = false;
    

    public function __construct(Correspondente $model) {
        $this->model = $model;
     //   $this->middleware('can:financeiro', 'financeiro contas a pagar');
   }  

      public function index()
    {
       $title = 'Painel de Solicitações Aguardando Pagamento';
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
              ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'dbo.Jurid_Situacao_Ficha.ressalva as Ressalva',
                     'Pasta',
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                     DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     'dbo.users.name as Correspondente')
             ->where('PLCFULL.dbo.Jurid_Debite.DebPago','=','N')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
             ->where('PLCFULL.dbo.Jurid_Debite.mobile', '=', '0')
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '7') 
             ->where('PLCFULL.dbo.Jurid_Debite.TipoDeb', '=', '023')
             ->get();
              
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
      return view('Painel.Financeiro.SolicitacaoDebite.index', compact('title', 'notas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    
    public function aprovar($numero) {

        $carbon= Carbon::now();
        $dataHoraMinuto = $carbon->format('d-m-Y');

        $data_servico = DB::table('dbo.Jurid_Situacao_Ficha')
        ->select('data_servico')
        ->where('numerodebite','=', $numero)
        ->value('data_servico'); 

        $diaservico = date('d', strtotime($data_servico));
        //Verifico se o dia é 01-15 (Vai ser pago dia 30)
        //Se for 16-30 (Vai ser pago dia 15)
        if($diaservico >= "1" || $diaservico <= "15") {

        $data_vencimento = $carbon->format('Y-m-t');

        } else {

        $data_vencimento = $carbon->format('Y-m-15');

        }

        
        $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', 
                           'PLCFULL.dbo.Jurid_Debite.Status AS Status_Debite',
                          // DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                           'PLCFULL.dbo.Jurid_Debite.Data as DataFicha',
                           'dbo.Jurid_Situacao_Ficha.data_servico as DataServico',
                          // DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'),
                           DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                           'Usuario', 
                           'PLCFULL.dbo.Jurid_Debite.Obs', 
                           'Pasta', 
                           'Hist',
                           'PLCFULL.dbo.Jurid_Advogado.Codigo as SolicitanteCodigo',
                           'PLCFULL.dbo.Jurid_Advogado.Nome as SolicitanteNome',
                           'PLCFULL.dbo.Jurid_Advogado.E_mail as SolicitanteEmail',
                           'PLCFULL.dbo.Jurid_Pastas.Moeda',
                           'PLCFULL.dbo.Jurid_Pastas.UF',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.Contrato',
                           'PLCFULL.dbo.Jurid_Pastas.Setor',
                           'PLCFULL.dbo.Jurid_Pastas.RefCliente',
                           'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
                           'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                           'PLCFULL.dbo.Jurid_Pastas.Comarca',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                           'PLCFULL.dbo.Jurid_Pastas.Unidade',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                           'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                           'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                           'PLCFULL.dbo.Jurid_Ged_Main.Arq_Nick',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'dbo.Jurid_Status_Ficha.descricao',
                           'PLCFULL.dbo.Jurid_Contratos.Despesas as ContratoStatus',
                           'dbo.Jurid_Situacao_Ficha.ressalva as Ressalva',
                           'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
                           'dbo.Jurid_Nota_Tiposervico.descricao as TipoServico',
                           'dbo.Jurid_Nota_Tiposervico.tipo as TipoSolicitacao')  
                   ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Cliente','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->leftjoin('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')   
                   ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
                   ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                   ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'PLCFULL.dbo.Jurid_Debite.Numero', 'PLCFULL.dbo.Jurid_Ged_Main.ID_OR')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
                   ->get()
                   ->first();   

                   if($datas->ContratoStatus == 1 || $datas->ContratoStatus == 2) {
                           $statuscontrato = DB::table('PLCFULL.dbo.Jurid_Desp_Contrato')->select('Aut_Desp')->where('Codigo', '023')->where('Contrato', $datas->Contrato)->value('Aut_Desp');
                        } elseif($datas->ContratoStatus == 0) {
                           $statuscontrato = 'Despesas livres';
                   } elseif($datas->ContratoStatus == 3) {
                          $statuscontrato = 'Não cobrar';
                   }

     $motivos = DB::table('dbo.Jurid_Nota_Motivos')
     ->where('ativo','=','S')
     ->get();
      
     
    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
     $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
    
    return view('Painel.Financeiro.SolicitacaoDebite.aprovar', compact('datas','statuscontrato','motivos','data_vencimento', 'dataHoraMinuto', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function correspondente_anexos($anexo) {

        return Storage::disk('solicitacaopagamento-sftp')->download($anexo);


    }
    
    public function aprovado(Request $request) {
          
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();
    
    $numerodebite = $request->get('numerodebite');
    $pasta = $request->get('pasta');
    $hist = $request->get('hist');
    $prconta = $request->get('prconta');
    $valor = $request->get('ValorT');
    $dataservico = $request->get('dataservico');
    $datasolicitacao = $request->get('datasolicitacao');
    $uf = $request->get('uf');
    $setor = $request->get('setor');
    $unidade = $request->get('unidade');
    $datavencimento = $request->get('datavencimento');
    $dataprogramacao = $request->get('dataprogramacao');
    $contrato = $request->get('contrato');
    $numeroprocesso = $request->get('numeroprocesso');
    $moeda = $request->get('moeda');
    $codigo_banco = $request->get('banco');
    $statusdebite = $request->get('statusdebite');
    $observacaodebite = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Obs')->where('Numero','=', $numerodebite)->value('Obs');


    $statusescolhido = $request->get('statusescolhido');
    $motivo_id = $request->get('motivo');
    $motivo_descricao = $request->get('motivodescricao');

    //Se foi aprovado
    if($statusescolhido == "aprovar") {

         //1 Verifico se não possui CPR gerada para este Debite
    $verificacpr = DB::table('PLCFULL.dbo.Jurid_Debite')->select('NumDocPag')->where('Numero','=', $numerodebite)->value('NumDocPag');

    if($verificacpr == null || $verifica == '') {


   //Se for Reenbonsável vai gravar Jurid_Debite Status = '0' (Debite) e Jurid_Contratos Status = '1'
   if($statusdebite == "1") {
       
        DB::table('PLCFULL.dbo.Jurid_Debite')
        ->limit(1)
        ->where('Numero', '=', $numerodebite)     
        ->update(array('Status' => '0', 'datapag' => $datavencimento));
    } 
    //Se marcar não Reenbonsável vai gravar Jurid_Debite Status = '2' e na Jurid_Contratos Status = '3'
    //Vai mandar um email para o Hudson e Isabelle Silveira
    else if($statusdebite == "2") {
        
       DB::table('PLCFULL.dbo.Jurid_Debite')
        ->limit(1)
        ->where('Numero', '=', $numerodebite)     
        ->update(array('Status' => '2', 'Hist' => $hist, 'datapag' => $datavencimento));
       
       DB::table('PLCFULL.dbo.Jurid_Contratos')
        ->limit(1)
        ->where('Numero', '=', $contrato)     
        ->update(array('Status' => '3'));
       
       Mail::to('hudson.souza@plcadvogados.com.br')
            ->cc('isabela.silveira@plcadvogados.com.br', 'deborah.medina@plcadvogados.com.br') 
            ->send(new StatusContrato($contrato));
    }

    //Aprovado pelo Financeiro
     DB::table('dbo.Jurid_Situacao_Ficha')
       ->where('numerodebite', $numerodebite)  
       ->limit(1) 
       ->update(array('status_id' => '12'));
     
    //Insert na Tabela Historico
    $values = array('id_hist' => $numerodebite, 'id_status' => '12', 'id_user' => $usuarioid, 'data' => $carbon);
    DB::table('dbo.Jurid_Hist_Ficha')->insert($values);  
    
    
    $advogado_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select('Advogado')
              ->where('Numero','=', $numerodebite)
              ->value('Advogado'); 
    
    $destino_id = DB::table('dbo.users')
                ->select('id')  
                ->where('cpf','=',$advogado_cpf)
                ->value('id');  

    $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
                ->select('id_user')
                ->where('id_status','=', '6')
                ->where('id_hist', '=', $numerodebite)
                ->value('id_user'); 
       
    //Envia notificação para o Correspondente
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Revisão financeiro aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Notificação para o Advogado solicitante            
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Revisão financeiro aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Mandar para Roberta alertando que deve realizar a baixa.
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => '242', 'tipo' => '1', 'obs' => 'Revisão financeiro aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);
    
    //Após gravar que foi feita a aprovação do Financeiro, ele vai gerar o CPR
    
    //Pega o ID e Adiciono mais 1 para poder dar o insert na tabela debite
    $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
    $numprc = $ultimonumprc + 1;
       
    $correspondente_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select('AdvServ')
              ->where('Numero','=', $numerodebite)
              ->value('AdvServ'); 

    //Verifica se o banco é 341 (ITAU) vai colocar o TIPODOC como 'TRF', se não 'TED'
    if($codigo_banco == "341") {

        //Verifico o status se é bloqueado ou OK para cobrança
        if($statusdebite == "1") {

                $values= array(
                'Tipodoc' => 'TRF',
                'Numdoc' => $numprc,
                'Cliente' => $correspondente_cpf,
                'Tipo' => 'P',
                'Centro' => $setor,
                'Valor' => $valor,
                'Dt_aceite' => $datasolicitacao,
                'Dt_Vencim' => $datavencimento,
                'Dt_Progr' => $dataprogramacao,
                'Valor_pg' => '0.00',
                'Multa' => '0',
                'Juros' => '0',
                'Tipolan' => '16.01',
                'Desconto' => '0',
                'Baixado' => '0',
                'Status' => '0',
                'Historico' => 'Fatura referente ao debite '. $numerodebite,
                'Obs' => $observacaodebite,
                'Valor_Or' => $valor,
                'Dt_Digit' => $carbon,
                'Codigo_Comp' => $pasta,
                'Unidade' => $unidade,
                'Moeda' => 'R$',
                'CSLL' => '0.00',
                'COFINS' => '0.00',
                'PIS' => '0.00',
                'ISS' => '0.00',
                'INSS' => '0.00',
                'PRConta' => $prconta,
                'Contrato' => $contrato,
                'numprocesso' => $numeroprocesso,
                'cod_pasta' => $pasta);
                    
        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
                    
        DB::table('PLCFULL.dbo.Jurid_Debite')
                ->limit(1)
                ->where('Numero', '=', $numerodebite)     
                ->update(array('DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TRF','NumDocPag' => $numprc, 'data_vencimento' => $datavencimento));

        } else if($statusdebite == "2"){

        $values= array(
                'Tipodoc' => 'TRF',
                'Numdoc' => $numprc,
                'Cliente' => $correspondente_cpf,
                'Tipo' => 'P',
                'Centro' => $setor,
                'Valor' => $valor,
                'Dt_aceite' => $datasolicitacao,
                'Dt_Vencim' => $datavencimento,
                'Dt_Progr' => $dataprogramacao,
                'Valor_pg' => '0.00',
                'Multa' => '0',
                'Juros' => '0',
                'Tipolan' => '11.01.01',
                'Desconto' => '0',
                'Baixado' => '0',
                'Status' => '0',
                'Historico' => 'Fatura referente ao debite '. $numerodebite,
                'Obs' => $observacaodebite,
                'Valor_Or' => $valor,
                'Dt_Digit' => $carbon,
                'Codigo_Comp' => $pasta,
                'Unidade' => $unidade,
                'Moeda' => 'R$',
                'CSLL' => '0.00',
                'COFINS' => '0.00',
                'PIS' => '0.00',
                'ISS' => '0.00',
                'INSS' => '0.00',
                'PRConta' => $prconta,
                'Contrato' => $contrato,
                'numprocesso' => $numeroprocesso,
                'cod_pasta' => $pasta);
                    
        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
                    
        DB::table('PLCFULL.dbo.Jurid_Debite')
        ->limit(1)
        ->where('Numero', '=', $numerodebite)     
        ->update(array('DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TRF','NumDocPag' => $numprc, 'data_vencimento' => $datavencimento));
        }
    

    }
    else if($codigo_banco == "0341") {

   //Verifico o status se é bloqueado ou OK para cobrança
        if($statusdebite == "1") {

                $values= array(
                'Tipodoc' => 'TRF',
                'Numdoc' => $numprc,
                'Cliente' => $correspondente_cpf,
                'Tipo' => 'P',
                'Centro' => $setor,
                'Valor' => $valor,
                'Dt_aceite' => $datasolicitacao,
                'Dt_Vencim' => $datavencimento,
                'Dt_Progr' => $dataprogramacao,
                'Valor_pg' => '0.00',
                'Multa' => '0',
                'Juros' => '0',
                'Tipolan' => '16.01',
                'Desconto' => '0',
                'Baixado' => '0',
                'Status' => '0',
                'Historico' => 'Fatura referente ao debite '. $numerodebite,
                'Obs' => $observacaodebite,
                'Valor_Or' => $valor,
                'Dt_Digit' => $carbon,
                'Codigo_Comp' => $pasta,
                'Unidade' => $unidade,
                'Moeda' => 'R$',
                'CSLL' => '0.00',
                'COFINS' => '0.00',
                'PIS' => '0.00',
                'ISS' => '0.00',
                'INSS' => '0.00',
                'PRConta' => $prconta,
                'Contrato' => $contrato,
                'numprocesso' => $numeroprocesso,
                'cod_pasta' => $pasta);
                    
        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
                    
        DB::table('PLCFULL.dbo.Jurid_Debite')
                ->limit(1)
                ->where('Numero', '=', $numerodebite)     
                ->update(array('DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TRF','NumDocPag' => $numprc, 'data_vencimento' => $datavencimento));

        } else if($statusdebite == "2"){

        $values= array(
                'Tipodoc' => 'TRF',
                'Numdoc' => $numprc,
                'Cliente' => $correspondente_cpf,
                'Tipo' => 'P',
                'Centro' => $setor,
                'Valor' => $valor,
                'Dt_aceite' => $datasolicitacao,
                'Dt_Vencim' => $datavencimento,
                'Dt_Progr' => $dataprogramacao,
                'Valor_pg' => '0.00',
                'Multa' => '0',
                'Juros' => '0',
                'Tipolan' => '11.01.01',
                'Desconto' => '0',
                'Baixado' => '0',
                'Status' => '0',
                'Historico' => 'Fatura referente ao debite '. $numerodebite,
                'Obs' => $observacaodebite,
                'Valor_Or' => $valor,
                'Dt_Digit' => $carbon,
                'Codigo_Comp' => $pasta,
                'Unidade' => $unidade,
                'Moeda' => 'R$',
                'CSLL' => '0.00',
                'COFINS' => '0.00',
                'PIS' => '0.00',
                'ISS' => '0.00',
                'INSS' => '0.00',
                'PRConta' => $prconta,
                'Contrato' => $contrato,
                'numprocesso' => $numeroprocesso,
                'cod_pasta' => $pasta);
                    
        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
                    
        DB::table('PLCFULL.dbo.Jurid_Debite')
        ->limit(1)
        ->where('Numero', '=', $numerodebite)     
        ->update(array('DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TRF','NumDocPag' => $numprc, 'data_vencimento' => $datavencimento));
        }
    
    }
    //Se não for Banco Itau
    else {


        //Verifico se o status do Debite é Ok para cobrança ou Bloqueado
        if($statusdebite == "1") {

                $values= array(
                        'Tipodoc' => 'TED',
                        'Numdoc' => $numprc,
                        'Cliente' => $correspondente_cpf,
                        'Tipo' => 'P',
                        'Centro' => $setor,
                        'Valor' => $valor,
                        'Dt_aceite' => $datasolicitacao,
                        'Dt_Vencim' => $datavencimento,
                        'Dt_Progr' => $dataprogramacao,
                        'Valor_pg' => '0.00',
                        'Multa' => '0',
                        'Juros' => '0',
                        'Tipolan' => '16.01',
                        'Desconto' => '0',
                        'Baixado' => '0',
                        'Status' => '0',
                        'Historico' => 'Fatura referente ao debite '. $numerodebite,
                        'Obs' => $observacaodebite,
                        'Valor_Or' => $valor,
                        'Dt_Digit' => $carbon,
                        'Codigo_Comp' => $pasta,
                        'Unidade' => $unidade,
                        'Moeda' => 'R$',
                        'CSLL' => '0.00',
                        'COFINS' => '0.00',
                        'PIS' => '0.00',
                        'ISS' => '0.00',
                        'INSS' => '0.00',
                        'PRConta' => $prconta,
                        'Contrato' => $contrato,
                        'numprocesso' => $numeroprocesso,
                        'cod_pasta' => $pasta);   
                      
                    DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);  

        } else if($statusdebite == "2") {

                $values= array(
                'Tipodoc' => 'TED',
                'Numdoc' => $numprc,
                'Cliente' => $correspondente_cpf,
                'Tipo' => 'P',
                'Centro' => $setor,
                'Valor' => $valor,
                'Dt_aceite' => $datasolicitacao,
                'Dt_Vencim' => $datavencimento,
                'Dt_Progr' => $dataprogramacao,
                'Valor_pg' => '0.00',
                'Multa' => '0',
                'Juros' => '0',
                'Tipolan' => '11.01.01',
                'Desconto' => '0',
                'Baixado' => '0',
                'Status' => '0',
                'Historico' => 'Fatura referente ao debite '. $numerodebite,
                'Obs' => $observacaodebite,
                'Valor_Or' => $valor,
                'Dt_Digit' => $carbon,
                'Codigo_Comp' => $pasta,
                'Unidade' => $unidade,
                'Moeda' => 'R$',
                'CSLL' => '0.00',
                'COFINS' => '0.00',
                'PIS' => '0.00',
                'ISS' => '0.00',
                'INSS' => '0.00',
                'PRConta' => $prconta,
                'Contrato' => $contrato,
                'numprocesso' => $numeroprocesso,
                'cod_pasta' => $pasta);   
                      
        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);  

        }
    
   DB::table('PLCFULL.dbo.Jurid_Debite')
       ->limit(1)
       ->where('Numero', '=', $numerodebite)     
       ->update(array('DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TED','NumDocPag' => $numprc,  'data_vencimento' => $datavencimento));
    }
    
    //Apos Criado na tabela ContaPR, ele vai da update na Tabela_Debite e Tabela_Default_
    DB::table('PLCFULL.dbo.Jurid_Default_')
       ->limit(1) 
       ->update(array('Numcpr' => $numprc));

    }
    //Fim Verifica se existe CPR
    
    //Mostrar que foi feito a programação de pagamento logo em seguida
    DB::table('dbo.Jurid_Situacao_Ficha')
       ->where('numerodebite', $numerodebite)  
       ->limit(1) 
       ->update(array('status_id' => '14'));
     
    //Insert na Tabela Historico
    $values4 = array('id_hist' => $numerodebite, 'id_status' => '14', 'id_user' => $usuarioid, 'data' => $carbon);
    DB::table('dbo.Jurid_Hist_Ficha')->insert($values4);  
    
      
    //Com a notificação criada ele vai enviar o email
    //Verifica se o email esta NULL se não ira enviar
    $emailAdvogado =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('E_mail')
              ->where('Codigo','=', $advogado_cpf)
              ->value('E_mail'); 
     
    
    $emailCorrespondente = DB::table('dbo.users')
              ->select('email')
              ->where('id','=', $idCorrespondente)
              ->value('email'); 
              
    $emailCoordenador =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', '=', 'dbo.users.cpf')
              ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
              ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
              ->select('E_mail')
              ->where('dbo.profiles.id', '=', '20')
              ->value('E_mail'); 
    
    
       
    if($emailAdvogado == NULL) {

    $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
        ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
        ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'dbo.Jurid_Hist_Ficha.id_hist', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
        ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
        ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
        ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                 'PLCFULL.dbo.Jurid_Debite.Pasta',
                 DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                 DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                 DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                'dbo.Jurid_Status_Ficha.*',
                'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                'PLCFULL.dbo.Jurid_Ged_Main.Obs as obs',
                'PLCFULL.dbo.jurid_ged_main.obs as obs',
                'dbo.Jurid_Nota_TipoServico.descricao as TipoServico')
                 ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numerodebite)
       ->limit(1)    
       ->get();    

    flash('Solicitação aprovada e programada com sucesso !')->success();

   //Enviar um email para quem esta aprovando a solicitação e para quem paga no Advwin (Roberta) alertando que esta aguardando programação e pagamento
//     Mail::to(Auth::user()->email)
//            ->cc('roberta.povoa@plcadvogados.com.br') 
//            ->send(new DebiteAguardandoPagamento($notas));

    return redirect()->route('Painel.Financeiro.index');
      
     } 
     else {
     
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
             ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'dbo.Jurid_Hist_Ficha.id_hist', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                      'PLCFULL.dbo.Jurid_Debite.Pasta',
                      DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                      DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                      DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.*',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'PLCFULL.dbo.Jurid_Ged_Main.Obs as obs',
                     'PLCFULL.dbo.jurid_ged_main.obs as obs',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico')
                      ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numerodebite)
            ->limit(1)    
            ->get();
    
    //Manda email para o Correspondente e Solicitante da Pasta informando que foi feita a revisão Financeiro e CR 
    Mail::to($emailAdvogado)
           ->cc($emailCorrespondente, $emailCoordenador) 
           ->send(new DebiteAprovadoFinanceiro($notas));
            
    //Enviar um email para quem esta aprovando a solicitação e para quem paga no Advwin (Roberta) alertando que esta aguardando programação e pagamento
    
    Mail::to(Auth::user()->email)
           ->cc('roberta.povoa@plcadvogados.com.br') 
           ->send(new DebiteAguardandoPagamento($notas));
      }      


    }
    //Se foi reprovado
    elseif($statusescolhido == "reprovar") {

        //Atualiza na Jurid_Situacao_Ficha
        DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numerodebite)  
        ->limit(1) 
        ->update(array('status_id' => '8','observacao' => $motivodescricao, 'motivo_id' => $motivo_id));

        //Grava na Hist 
        $values = array('id_hist' => $numerodebite, 'id_status' => '8', 'id_user' => Auth::user()->id, 'data' => $carbon);
        DB::table('dbo.Jurid_Hist_Ficha')->insert($values);

    }
    //Se foi cancelado
    elseif($statusescolhido == "cancelar") {

        //Atualiza na Jurid_Situacao_Ficha
        DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numerodebite)  
        ->limit(1) 
        ->update(array('status_id' => '13','observacao' => $motivodescricao, 'motivo_id' => $motivo_id));

        //Grava na Hist 
        $values = array('id_hist' => $numerodebite, 'id_status' => '13', 'id_user' => Auth::user()->id, 'data' => $carbon);
        DB::table('dbo.Jurid_Hist_Ficha')->insert($values);
    }


   return redirect()->route('Painel.Financeiro.index'); 
      
   }
   
   public function programadas() {
           
       $title = 'Painel de Solicitações Programação pagamento';
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.NumDocPag','=','PLCFULL.dbo.Jurid_ContaPr.Numdoc')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta as Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.data_vencimento AS Date) as DataVencimento'), 
                     DB::raw('CAST(PLCFULL.dbo.Jurid_ContaPr.Dt_Progr AS Date) as DataProgramacao'), 
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                     DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                     'dbo.users.name')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '14')
             ->where('PLCFULL.dbo.Jurid_Debite.TipoDeb', '=', '023')
             ->get();
       
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
      
       return view('Painel.Financeiro.SolicitacaoDebite.programacaopagamento', compact('title', 'notas', 'totalNotificacaoAbertas', 'notificacoes'));
   }
   
   public function aprovadas() {
       $title = 'Painel de Solicitações Aguardando Pagamento';
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'PLCFULL.dbo.Jurid_Advogado.E_mail',
                     'dbo.users.name')
             ->where('PLCFULL.dbo.Jurid_Debite.DebPago','=','N')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
             ->where('PLCFULL.dbo.Jurid_Debite.mobile', '=', '0')
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '12')
             ->where('PLCFULL.dbo.Jurid_Debite.TipoDeb', '=', '023')
             ->get();
       
          $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
          $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
     
       return view('Painel.Financeiro.SolicitacaoDebite.aprovadas', compact('title', 'notas', 'totalNotificacaoAbertas', 'notificacoes'));
   
   }
    
    public function pagas()
    {
       $title = 'Painel de Solicitações Á Anexar Comprovante';

       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
       ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
       ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
       ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
       ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
       ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
       ->select(
               'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
               'dbo.Jurid_Situacao_Ficha.ressalva as Ressalva',
               'Pasta',
               'dbo.Jurid_Status_Ficha.id as StatusID',
               'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
               'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
               'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
               'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
               DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.datapag AS Date) as DataPagamento'), 
               DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
               DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
               'dbo.users.name')
       ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(9,11))
       ->where('PLCFULL.dbo.Jurid_Debite.TipoDeb', '=', '023')
       ->get();

    
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
      
       return view('Painel.Financeiro.SolicitacaoDebite.pagas', compact('title', 'notas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
     public function show($Numero) {
        
          $numeroprocesso= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'dbo.users.name as Nome')  
                   ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('dbo.users', 'dbo.Jurid_Hist_Ficha.id_user', 'dbo.users.id')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '6')
                   ->get()
                   ->first();
          
          
            $statusAtual = 
             DB::table('dbo.Jurid_Hist_Ficha')
                        ->select('id_status')  
                        ->where('id_hist','=',$Numero)
                        ->orderBy('id', 'desc')
                        ->value('id'); 
                              
      return view('Painel.Financeiro.SolicitacaoDebite.show', compact('numeroprocesso', 'statusAtual'));
    }
    
    
    public function anexar($Numero)     {
        //Recupera a ficha
        //$numeroprocesso = $this->model->find($Numero);
        
        $numeroprocesso= DB::table('PLCFULL.dbo.Jurid_Debite')
                  ->select('Numero as NumeroDebite',
                          DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                          'Status as StatusFicha',
                          DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'),
                          'Usuario',
                          'DebPago as Pago', 
                          'Pasta', 'PRConta',
                          DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                          'dbo.Jurid_Status_Ficha.descricao')  
                  ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                  ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                  ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                  ->get()
                  ->first();
          
          
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
         
       //  dd($numeroprocesso);
       
       return view('Painel.Financeiro.SolicitacaoDebite.anexar', compact('numeroprocesso', 'totalNotificacaoAbertas', 'notificacoes'));
    }
   
    public function reprovar($numero) {
       
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', 
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                           'Usuario', 'PLCFULL.dbo.Jurid_Debite.Obs', 
                           'DebPago as Pago', 
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'),
                           'dbo.Jurid_Status_Ficha.descricao')  
                   ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
                   ->get()
                   ->first();   
     
    $motivos = DB::table('dbo.Jurid_Nota_Motivos')
             ->where('ativo','=','S')
             ->get();

     $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
      $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
                    
     return view('Painel.Financeiro.SolicitacaoDebite.reprovar', compact('notas', 'motivos', 'totalNotificacaoAbertas' ,'notificacoes'));    
    }
    
    
     public function pendenciar(Request $request) {
        
      $usuarioid = Auth::user()->id;
      $carbon= Carbon::now();
      
      $numero = $request->get('numerodebite');
      $motivo = $request->get('motivo');
      $observacao = $request->get('observacao');
      
      //Pega o ID do Motivo Selecionado para gravar na Tabela Situação Ficha
      $motivo_id = DB::table('dbo.Jurid_Nota_Motivos')
              ->select('id')
              ->where('descricao','=', $motivo)
              ->value('id'); 
       
      $values = array('id_hist' => $numero, 'id_status' => '10', 'id_user' => $usuarioid, 'data' => $carbon);
      DB::table('dbo.Jurid_Hist_Ficha')->insert($values);
      
     $advogado_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select('Advogado')
              ->where('Numero','=', $numero)
              ->value('Advogado'); 
      $destino_id = DB::table('dbo.users')
                ->select('id')  
                ->where('cpf','=',$advogado_cpf)
                ->value('id');  
       
      $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação cancelada', 'status' => 'A');

      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Com a notificação criada ele vai enviar o email
      //Verifica se o email esta NULL se não ira enviar
     $emailAdvogado =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('E_mail')
              ->where('Codigo','=', $advogado_cpf)
              ->value('E_mail'); 
      
    $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
              ->select('id_user')
              ->where('id_status','=', '6')
              ->where('id_hist', '=', $numero)
              ->value('id_user'); 
    
    $emailCorrespondente = DB::table('dbo.users')
              ->select('email')
              ->where('id','=', $idCorrespondente)
              ->value('email'); 
              
     $emailCoordenador =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', '=', 'dbo.users.cpf')
              ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
              ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
              ->select('E_mail')
              ->where('dbo.profiles.id', '=', '20')
              ->value('E_mail');    
    
       
      //Update  na Tabela Situacao
      DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numero)  
        ->limit(1) 
        ->update(array('status_id' => '10','observacao' => $observacao, 'motivo_id' => $motivo_id));
      
       //Update Status 4  e Revisado DB 0 na Tabela Debite
      DB::table('PLCFULL.dbo.Jurid_Debite')
        ->where('Numero', $numero)  
        ->limit(1) 
        ->update(array('status' => '4', 'Revisado_DB' => '0'));

     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Jurid_Situacao_Ficha.motivo_id', '=', 'dbo.Jurid_Nota_Motivos.id')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                      'PLCFULL.dbo.Jurid_Debite.Pasta',
                      DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                      DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                      DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.*',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'dbo.Jurid_Situacao_Ficha.observacao as ObservacaoMotivo',
                     'dbo.Jurid_Nota_Motivos.descricao as Motivo')
            ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
            ->limit(1)    
            ->get();
      
      
      flash('Solicitação enviada para correção com sucesso !')->success();
      
      Mail::to($emailAdvogado)
           ->cc($emailCorrespondente) 
           ->send(new DebiteReprovadoFinanceiro($notas));
            return redirect()->route('Painel.Financeiro.index');    
        
      return redirect()->route('Painel.Financeiro.acompanharSolicitacoes'); 
    }
    
    
     public function imprimir($Numero){  
       
        $carbon= Carbon::now();
        $numeroprocesso= 
               DB::table('PLCFULL.dbo.Jurid_Debite')
                 ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                         'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                         DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                         DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                         'DebPago as Pago',
                         'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                         'Pasta', 
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                         'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                         'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                         'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                         DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                         'dbo.Jurid_Status_Ficha.descricao', 
                         'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                         'PLCFULL.dbo.Jurid_Advogado.E_mail as AdvogadoEmail',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
                         'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
                         'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
                         'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
                         'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
                         'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
                         'PLCFULL.dbo.Jurid_Unidade.complemento_endereco as UnidadeComplemento',
                         'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                         'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                         'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                         'dbo.Jurid_Situacao_Ficha.data_servico as DataServico',
                         'dbo.Jurid_Hist_Ficha.data as DataSolicitacao',
                         'dbo.users.name as Nome',
                         'dbo.users.email as email',
                         'PLCFULL.dbo.Jurid_Ged_Main.Obs as Obs',
                         'PLCFULL.dbo.Jurid_Ged_Main.Link as anexo',
                        'dbo.Jurid_Nota_TipoServico.descricao as TipoDebite')  
                 ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                 ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'PLCFULL.dbo.Jurid_Debite.Numero', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
                 ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                 ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                 ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                 ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                 ->leftjoin('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                 ->leftjoin('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                 ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                 ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                 ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
                 ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                 ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                 ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '6')
                 ->first();

    $anexo2 = $numeroprocesso->anexo;
    $anexoformatado =  str_replace ('\\\192.168.1.65\advwin\GED\Vault$\portal\solicitacaopagamento/', '', $anexo2);
        
   //Busca o caminho do arquivo
   $datacomprovante = DB::table('PLCFULL.dbo.Jurid_Ged_Main')
            ->select('Link as anexo')
            ->where('Id_OR','=', $Numero)
            ->where('Texto', '==', 'Comprovante pagamento')
            ->orderby('ID_doc', 'desc')
            ->get()
            ->first();
     
       return view('Painel.Financeiro.SolicitacaoDebite.print', compact('numeroprocesso','anexoformatado','datacomprovante', 'carbon'));
    }

    public function anexo($anexo) {
                
        return Storage::disk('solicitacaopagamento-sftp')->download($anexo);   
    }
    
    public function recibo($Numero)     {
        
        $carbon= Carbon::now();
        $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                  ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                          'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCNPJ',
                          'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                          'PLCFULL.dbo.Jurid_Pastas.Moeda as ValorMoeda',
                          'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                          'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
                          'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
                          'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
                          'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
                          'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
                          'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
                          'PLCFULL.dbo.Jurid_Unidade.complemento_endereco as UnidadeComplemento',
                          DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'))  
                  ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                  ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                  ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                  ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                  ->get()
                  ->first();
        
        $numero = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select(DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'))
              ->where('Numero','=', $Numero)
              ->value('ValorT'); 
        
        $extenso = new NumeroPorExtenso;
        $extenso = $extenso->converter($numero);
                  
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
    
       //  dd($numeroprocesso);
       
       return view('Painel.Financeiro.SolicitacaoDebite.recibo', compact('carbon', 'extenso', 'datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

 
    public function advogado_guiascusta() {
          
        
        $carbon= Carbon::now();
        $advogado = Auth::user()->cpf;

        $datas = DB::select( DB::raw("
        SELECT G.Arq_nick,
        G.descricao as Identificacao,
        D.Numero as NumeroDebite,
        Cli.nome as ClienteRazao,
        D.Pasta,
        D.Data
        ,s.descricao as Setor
        ,D.Status,
        TD.Descricao AS TipoDebite,
        D.ValorT AS Valor


FROM PLCFULL.dbo.JURID_DEBITE D
LEFT JOIN PLCFULL.dbo.JURID_GED_MAIN G ON CONVERT(char,g.codigo_or)=CONVERT(VARCHAR,D.numero)
INNER JOIN PLCFULL.dbo.JURID_CLIFOR CLI ON CLI.Codigo = D.Cliente
INNER JOIN PLCFULL.dbo.JURID_SETOR S on S.Codigo = D.Setor
INNER JOIN PLCFULL.dbo.Jurid_TipoDebite TD ON TD.Codigo = D.TipoDeb
WHERE 
 
D.Revisado_DB='1'
and d.tipodeb in ('010','004','006')
and d.status IN ('0','1','3','2')
and d.Advogado = '$advogado'
and g.Tabela_or='Debite'
and g.tabela_Or not in ('CPR')

ORDER BY D.numero asc"));
                 
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
             ->where('status', 'A')
             ->where('destino_id','=', Auth::user()->id)
             ->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
             'data',
             'id_ref', 
             'user_id',
             'tipo', 
             'obs',
             'Hist_Notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();
       
        return view('Painel.Financeiro.GuiasCusta.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

 

    public function gerarAnexoGuia($numero) {

     $link = DB::table('PLCFULL.dbo.Jurid_Ged_Main')
             ->where('Arq_Nick', '=', $numero)   
             ->value('Arq_Nick');  
             //\\192.168.1.65\advwin\GED\Vault$\Debite

     return Storage::disk('guiascusta-sftp2')->download($link);
//      $linkformatado = str_replace ('\\192.168.1.65\advwin\ged\vault$\CPR', '', str_replace ('\\192.168.1.65\advwin\ged\vault$\Debite', '', $link));        
//      $novolink = substr($linkformatado, 2);

//      return Storage::disk('guias-sftp')->download($novolink);

     }
    
     public function gerarExcelGuiasFinanceiro() {
    
     $customer_data =  
             DB::select('EXEC dbo.Intranet_GuiasCusta_Financeiro');
     
     $customer_array[] = array(
         'NumeroDebite', 
         'Nome',
         'Pasta',
         'Cliente',
         'refcliente',
         'Setor',
         'Tipo',
         'STATUS',
         'Valor',
         'Data',
         'Grupo',
         'Processo',
         'Contrato');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'NúmeroDebite'  => $customer->NumeroDebite,
       'Nome' => $customer->Nome,
       'Pasta'  => $customer->Pasta,
       'Cliente'  => $customer->Cliente,
       'refcliente' => $customer->refcliente,
       'Setor' => $customer->Setor,
       'Tipo' => $customer->Tipo,
       'STATUS' => $customer->STATUS,
       'Valor' => $customer->Valor,
       'Data'=> date('d/m/Y H:m:s', strtotime($customer->Data)),
       'Grupo Econômico'=> $customer->Grupo,
       'Processo'=> $customer->Processo,
       'Contrato'=> $customer->Contrato,
      );
     }
     Excel::create('Guias de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Guias de Pagamento');
      $excel->sheet('Guias de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
    
     public function gerarExcelGuiasAdvogado() {
    
     $customer_data =  
             DB::select('EXEC dbo.Intranet_GuiasCusta_Advogado');
     
     $customer_array[] = array(
         'NumeroDebite', 
         'Pasta',
         'Cliente',
         'refcliente',
         'Setor',
         'Tipo',
         'STATUS',
         'Valor',
         'Data',
         'Grupo',
         'Processo',
         'Contrato');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'NúmeroDebite'  => $customer->NumeroDebite,
       'Pasta'  => $customer->Pasta,
       'Cliente'  => $customer->Cliente,
       'refcliente' => $customer->refcliente,
       'Setor' => $customer->Setor,
       'Tipo' => $customer->Tipo,
       'STATUS' => $customer->STATUS,
       'Valor' => $customer->Valor,
       'Data'=> date('d/m/Y H:m:s', strtotime($customer->Data)),
       'Grupo Econômico'=> $customer->Grupo,
       'Processo'=> $customer->Processo,
       'Contrato'=> $customer->Contrato,
      );
     }
     Excel::create('Guias de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Guias de Pagamento');
      $excel->sheet('Guias de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
    
     public function telefones() {
         
        $carbon= Carbon::now();

        $datahoje = $carbon->format('Y-m-d H:i');
        $datahojeformato = $carbon->format('Y-m-d');
         
        $datas = DB::table('dbo.Financeiro_Telefones_Corporativos')
             ->select('dbo.Financeiro_Telefones_Corporativos.id as id',
                      'dbo.Financeiro_Telefones_Corporativos.telefone as telefone',
                      'dbo.Financeiro_Telefones_Corporativos.user_id as user_id',
                      'dbo.Financeiro_Telefones_Corporativos.operadora',
                      'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade_codigo',
                      'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                      'dbo.Financeiro_Telefones_Corporativos.conta',
                      'dbo.Financeiro_Telefones_Corporativos.status',
                      'dbo.Financeiro_Telefones_Corporativos.data',
                      'dbo.Financeiro_Telefones_Corporativos.modelo',
                      'dbo.Financeiro_Telefones_Corporativos.observacoes',
                      'dbo.Financeiro_Telefones_Corporativos.dataentrega'
                      )
             //->leftjoin('dbo.users','dbo.Financeiro_Telefones_Corporativos.user_id','=','dbo.users.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Telefones_Corporativos.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Telefones_Corporativos.unidade_id', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')  
             ->get(); 

        $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
        ->select('codigo' ,'descricao')
        ->get(); 

        $users = DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')   
        ->where('dbo.profiles.id', '!=', '1')   
        ->orderby('dbo.users.name', 'asc')
        ->select('dbo.users.name')   
        ->get();  
                         
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
             ->where('status', 'A')
             ->where('destino_id','=', Auth::user()->id)
             ->count();
              
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
      return view('Painel.Financeiro.Telefone.index', compact('datas','users','totalNotificacaoAbertas', 'notificacoes', 'unidades', 'datahojeformato'));
    
    
     }
     
     public function telefonescreate() {
         
        //Busca todos os usuarios exceto Correspondente 
        $users = DB::table('dbo.users')
             ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
             ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')   
             ->where('dbo.profiles.id', '!=', '1')   
             ->orderby('dbo.users.name', 'asc')
             ->select('dbo.users.name')   
             ->get();  
                 
        $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
             ->get(); 
        
        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
             ->where('ativo', '=', '1')   
             ->orderby('Descricao', 'asc')   
             ->get(); 
             
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
             ->where('status', 'A')
             ->where('destino_id','=', Auth::user()->id)
             ->count();
              
       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
         
      
         return view('Painel.Financeiro.Telefone.create-edit', compact('users','unidades', 'setores', 'totalNotificacaoAbertas', 'notificacoes'));
     }
     
     public function telefonecriado(Request $request) {
                 
       $usuario_nome = $request->get('usuario'); 
       $unidade = $request->get('unidade'); 
       $setor = $request->get('setor'); 
               
       
       
       //Gravar os dados na tabela 
       $telefone = $request->get('telefone');
       $operadora = $request->get('operadora');
       $unidade_id = DB::table('PLCFULL.dbo.Jurid_Unidade')
                  ->select('Codigo')  
                  ->where('Descricao','=',$unidade)
                  ->value('Codigo'); 
       $conta = $request->get('conta');      
       $user_id = DB::table('dbo.users')
                  ->select('id')  
                  ->where('name','=',$usuario_nome)
                  ->value('id'); 
       $setor_id = DB::table('PLCFULL.dbo.Jurid_Setor')
                  ->select('Id')  
                  ->where('Descricao','=',$setor)
                  ->value('Id'); 
       $data = Carbon::now();       
       $status = $request->get('status');
               
       $values = array('telefone' => $telefone, 'operadora' => $operadora, 'unidade_id' => $unidade_id, 'conta' => $conta, 'user_id' => $user_id, 'setor_id' => $setor_id, 'data' => $data, 'status' => $status);
       DB::table('dbo.Financeiro_Telefones_Corporativos')->insert($values); 
       
       flash('Número corporativo cadastrado com sucesso !')->success();    
       
       return redirect()->route('Painel.Financeiro.telefones');
      
     }

     public function telefones_desativarnumero($id) {

        //Desativa o telefone
        DB::table('Financeiro_Telefones_Corporativos')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => 'Inativo'));

        //Return rota
        flash('Número corporativo desativado com sucesso !')->success();    
       
        return redirect()->route('Painel.Financeiro.telefones');
     }
     
     
    public function gerarExcelTelefone() {
    
     $customer_data = DB::table('dbo.Financeiro_Telefones_Corporativos')
           //  ->leftjoin('dbo.users','dbo.Financeiro_Telefones_Corporativos.user_id','=','dbo.users.id')
           //  ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Telefones_Corporativos.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Telefones_Corporativos.unidade_id', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')  
             ->get()
             ->toArray();
     
          
     $customer_array[] = array(
         'telefone', 
         'user_id',
         'operadora',
         'conta',
         'unidade_id',
         'Descricao',
         'status',
         'data');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'telefone'  => $customer->telefone,
       'user_id'  => $customer->user_id,
       'operadora'  => $customer->operadora,
       'conta' => $customer->conta,
       'unidade_id' => $customer->unidade_id,
       'Descricao' => $customer->Descricao,
       'status' => $customer->status,
       'data'=> date('d/m/Y H:m:s', strtotime($customer->data)),
      );
     }
     Excel::create('Relação Numeros Corporativos', function($excel) use ($customer_array){
      $excel->setTitle('Relação Numeros Corporativos');
      $excel->sheet('Relação Numeros Corporativos', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }

    public function salvarEdicaoTelefones(Request $request){


        $numero = $request->numero;
        $responsavel = $request->responsavel;
        $operadora = $request->operadora;
        $unidade = $request->unidade;
        $conta = $request->conta;
        $status = $request->ativoinativo;
        $datacriacao = $request->datacriacao;
        $modelo = $request->modelo;
        $observacoes = $request->observacoes;
        $dataentrega = $request->dataentrega;
        $idTelefone = $request->idTelefone;

        $arrayTelefones  = array(
                'telefone' => $numero,
                'user_id' => $responsavel,
                'operadora' => $operadora,
                'unidade_id' => $unidade,
                'conta' => $conta,
                'status' => $status,
                'data' => $datacriacao,
                'modelo' => $modelo,
                'observacoes' => $observacoes,
                'dataentrega' => $dataentrega
        );

        $update = DB::table('dbo.Financeiro_Telefones_Corporativos')
        ->where('id', $idTelefone)  
        ->update($arrayTelefones);

        return redirect()->route('Painel.Financeiro.telefones'); 

    }
    
    public function salvarNovoTelefone(Request $request){

        $numero = $request->numero;
        $responsavel = $request->responsavel;
        $operadora = $request->operadora;
        $unidade = $request->unidade;
        $conta = $request->conta;
        $status = $request->ativoinativo;
        $datacriacao = $request->datacriacao;
        $modelo = $request->modelo;
        $observacoes = $request->observacoes;
        $dataentrega = $request->dataentrega;
        $idTelefone = $request->idTelefone;

        $valuesTelefone  = array(
                'telefone' => $numero,
                'user_id' => $responsavel,
                'operadora' => $operadora,
                'unidade_id' => $unidade,
                'conta' => $conta,
                'status' => $status,
                'data' => $datacriacao,
                'modelo' => $modelo,
                'observacoes' => $observacoes,
                'dataentrega' => $dataentrega
        );

        DB::table('dbo.Financeiro_Telefones_Corporativos')->insert($valuesTelefone);       

        return redirect()->route('Painel.Financeiro.telefones'); 
    }
    
     public function gerarExcelAbertas() {
    
     $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
             ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->join('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', '=', 'PLCFULL.dbo.Jurid_empreendimento.codigo_empreendimento')
             ->join('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->join('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'PLCFULL.dbo.Jurid_Pastas.UF as Estado',
                     'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     'PLCFULL.dbo.Jurid_empreendimento.nome_empreendimento as Empreendimento',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as Data'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as Status',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                     'dbo.users.name as Correspondente')
             ->where('PLCFULL.dbo.Jurid_Debite.DebPago','=','N')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
             ->where('PLCFULL.dbo.Jurid_Debite.mobile', '=', '0')
             ->get()
             ->toArray();
     $customer_array[] = array('Numero',  'Solicitante', 'Correspondente' ,'Cliente', 'Unidade', 'Setor', 'Estado', 'Comarca', 'TipoServico',  'Empreendimento', 'Grupo', 'Valor', 'Data', 'Status');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Número'  => $customer->Numero,
       'Solicitante'  => $customer->Solicitante,
       'Correspondente'  => $customer->Correspondente,
       'Cliente' => $customer->Cliente,
       'Unidade' => $customer->Unidade,
       'Setor' => $customer->Setor,
       'Estado' => $customer->Estado,
       'Comarca' => $customer->Comarca,
       'TipoServico' => $customer->TipoServico,
       'Empreendimento' => $customer->Empreendimento,   
       'Grupo'=> $customer->Grupo,
       'Valor'=> $customer->Valor,
       'Data'=> date('d/m/Y H:m:s', strtotime($customer->Data)),
       'Status'=> $customer->Status,
      );
     }
     Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Solicitações Pagamento Abertas');
      $excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
    
      public function gerarExcelAguardandoPagamento() {

     //Pego a view
     $customer_data  = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.NumDocPag','=','PLCFULL.dbo.Jurid_ContaPr.Numdoc')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta as Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.data_vencimento AS Date) as DataVencimento'), 
                     DB::raw('CAST(PLCFULL.dbo.Jurid_ContaPr.Dt_Progr AS Date) as DataProgramacao'),
                     'PLCFULL.dbo.Jurid_ContaPr.Numdoc as numdoc', 
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
                     'PLCFULL.dbo.Jurid_Setor.Codigo as CodigoSetor',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'PLCFULL.dbo.Jurid_Unidade.Codigo as CodigoUnidade',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                     DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                     'dbo.users.name')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '14')
             ->get()
             ->toArray();
         
     $customer_array[] = array(
     'NumeroDebite',
     'Correspondente',
     'Solicitante',
     'Numdoc',  
     'Cod.Cliente', 
     'Grupo',
     'Cliente',
     'Cod.Setor', 
     'Setor',
     'Cod.Unidade',
     'Unidade',
     'DataVencimento',
     'DataProgramação',
     'Tipo',
     'Valor',
      );
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'NumeroDebite' => $customer->NumeroDebite,
       'Correspondente' => $customer->name,
       'Solicitante' => $customer->Solicitante,
       'Numdoc'  => $customer->numdoc,
       'CodigoCliente' =>$customer->CodigoCliente,
       'Grupo' => $customer->Grupo,
       'Cliente' => $customer->Cliente,
       'CodigoSetor' => $customer->CodigoSetor,
       'Setor'=> $customer->Setor,
       'CodigoUnidade' => $customer->CodigoUnidade,
       'Unidade' => $customer->Unidade,
       'DataVencimento'=> date('d/m/Y', strtotime($customer->DataVencimento)),
       'DataProgramação'=> date('d/m/Y', strtotime($customer->DataProgramacao)),
       'Tipo' => $customer->TipoServico,
       'Valor' => $customer->Valor,
      );

     }
     Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Solicitação de Pagamento');
      $excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }

    public function gerarExcelPagas() {
    
        $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
                ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
                ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
                ->leftjoin('PLCFULL.dbo.Jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', '=', 'PLCFULL.dbo.Jurid_empreendimento.codigo_empreendimento')
                ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
                        'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                        'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'PLCFULL.dbo.Jurid_Pastas.UF as Estado',
                        'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                        'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                        'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                        'PLCFULL.dbo.Jurid_empreendimento.nome_empreendimento as Empreendimento',
                        DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.datapag AS Date) as DataPagamento'), 
                        DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as Data'), 
                        DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                        DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                        'dbo.Jurid_Status_Ficha.descricao as Status',
                        'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                        'dbo.users.name as Correspondente')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(9,11))
                ->get()
                ->toArray();
        $customer_array[] = array('Numero',  'Solicitante', 'Correspondente' ,'Cliente', 'Unidade', 'Setor', 'Estado', 'Comarca', 'TipoServico',  'Empreendimento', 'Grupo', 'Valor', 'Data', 'DataPagamento' , 'Status');
        foreach($customer_data as $customer)
        {
         $customer_array[] = array(
          'Número'  => $customer->Numero,
          'Solicitante'  => $customer->Solicitante,
          'Correspondente'  => $customer->Correspondente,
          'Cliente' => $customer->Cliente,
          'Unidade' => $customer->Unidade,
          'Setor' => $customer->Setor,
          'Estado' => $customer->Estado,
          'Comarca' => $customer->Comarca,
          'TipoServico' => $customer->TipoServico,
          'Empreendimento' => $customer->Empreendimento,   
          'Grupo'=> $customer->Grupo,
          'Valor'=> $customer->Valor,
          'Data'=> date('d/m/Y H:m:s', strtotime($customer->Data)),
          'DataPagamento'=> date('d/m/Y H:m:s', strtotime($customer->DataPagamento)),
          'Status'=> $customer->Status,
         );
        }
        Excel::create('Solicitação de pagamento pagas', function($excel) use ($customer_array){
         $excel->setTitle('Solicitações Pagamento Pagas');
         $excel->sheet('Solicitação de pagamento pagas', function($sheet) use ($customer_array){
          $sheet->fromArray($customer_array, null, 'A1', false, false);
         });
        })->download('xlsx');
       
   
       //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
       }
    
    public function baixar($numero) {

        $carbon= Carbon::now();
        $dataHoraMinuto = $carbon->format('d-m-Y');

        
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', 
                           DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'),  
                           'Pasta',
                           'Hist',
                           'PLCFULL.dbo.Jurid_ContaPR.Tipolan as tipolan',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.data_vencimento AS Date) as DataProgramacao'),
                           DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico')
                           )
                   ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
                   ->get()
                   ->first();   
      
     
    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
       
    $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();

        
    $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  
        
    return view('Painel.Financeiro.SolicitacaoDebite.baixar', compact('notas', 'dataHoraMinuto', 'tiposdoc' ,'totalNotificacaoAbertas', 'notificacoes'));    
        
    }
    



    public function comprovantepagamento(Request $request) {

        $usuarioid = Auth::user()->id;
        $carbon= Carbon::now();
        $dataehora = $carbon->format('dmY_HHis');
        $numerodebite = $request->get('numerodebite');
        $setor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Setor')->where('Numero','=', $numerodebite)->value('Setor');
       
        $this->validate($request, [
                'select_file'  => 'file|mimes:pdf|max:16384'] );

        $image = $request->file('select_file');
        $new_name = $numerodebite . '_comprovantepagamento_' . $dataehora . '.'  . $image->getClientOriginalExtension();
        Storage::disk('comprovantepagamento-sftp')->put($new_name, fopen($image, 'r+'));
        Storage::disk('reembolso-local')->put($new_name, fopen($image, 'r+'));

        //Insert Jurid_Ged_Main
        $values = array(
        'Tabela_OR' => 'Debite',
        'Codigo_OR' => $numerodebite,
        'Id_OR' => $numerodebite,
        'Descricao' => $image->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\ged\vault$\Debite/'.$new_name, 
        'Data' => $carbon,
        'Nome' => $image->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $image->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $new_name,
        'Texto' => 'Comprovante pagamento');
         DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);       
        
        //Update Jurid_Situacao_Ficha
       DB::table('dbo.Jurid_Situacao_Ficha')->where('numerodebite', $numerodebite)->limit(1)->update(array('status_id' => '11'));

       //Insert Jurid_Hist_Ficha
       $values3 = array('id_hist' => $numerodebite, 'id_status' => '11', 'id_user' => $usuarioid, 'data' => $carbon);
        DB::table('dbo.Jurid_Hist_Ficha')->insert($values3);

       //Envia email e notificação
       $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
       ->select('id_user')
       ->where('id_status','=', '6')
       ->where('id_hist', '=', $numerodebite)
       ->value('id_user'); 

       $destino_id = DB::table('dbo.users')
         ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
         ->select('dbo.users.id')  
         ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
         ->value('dbo.users.id');  

        $coordenador_id = DB::table('dbo.users')
         ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
         ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
         ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
         ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
         ->select('dbo.users.id') 
         ->where('dbo.profiles.id', '=', '20')
         ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=', $setor)
         ->value('dbo.users.id'); 

       $emailCorrespondente = DB::table('dbo.users')
       ->select('email')
       ->where('id','=', $idCorrespondente)
       ->value('email');

      $emailAdvogado = DB::table('dbo.users')
         ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
         ->select('dbo.users.email')  
         ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
         ->value('dbo.users.email');
 
      $emailCoordenador = DB::table('dbo.users')
         ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
         ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
         ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
         ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
         ->select('dbo.users.email') 
         ->where('dbo.profiles.id', '=', '20')
         ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=', $setor)
         ->value('dbo.users.email'); 

       //Notificação para o Correspondente
       $values4= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Comprovante de pagamento anexado.', 'status' => 'A');
       DB::table('dbo.Hist_Notificacao')->insert($values4);

       //Notificação para o Solicitante da Pasta
       $values4= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Comprovante de pagamento anexado.', 'status' => 'A');
       DB::table('dbo.Hist_Notificacao')->insert($values4);

      //Notificação para o Coordenador
      $values5= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $coordenador_id, 'tipo' => '1', 'obs' => 'Comprovante de pagamento anexado.', 'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values5);

      flash('Comprovante de pagamento anexado com sucesso !')->success();

      if($emailAdvogado == null && $emailCoordenador == null) {
        return redirect()->route('Painel.Financeiro.pagas'); 
      } 
      else {
      Mail::to($emailCorrespondente)
     ->cc($emailAdvogado, $emailCoordenador) 

     ->send(new ComprovantePagamento($numerodebite));
     return redirect()->route('Painel.Financeiro.pagas'); 
     }
     }

     public function realizarconciliacao() {

        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', '=', 'dbo.users.cpf' )
              ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
              ->select(
                      'dbo.users.id as CorrespondenteID',
                      'dbo.users.cpf as CorrespondenteCodigo',
                      'dbo.users.name as CorrespondenteNome',
                      DB::raw('sum(ValorT ) as ValorTotal'),
                       DB::raw('count(*) as QuantidadeSolicitacoes'))
              ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
              ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '14')
              ->where('PLCFULL.dbo.Jurid_Debite.TipoDeb', '=', '023')
              ->groupby('dbo.users.id','dbo.users.cpf', 'dbo.users.name')
              ->get();        

        
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                 ->where('status', 'A')
                 ->where('destino_id','=', Auth::user()->id)
                 ->count();
               
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'Hist_Notificacao.status', 'dbo.users.*')  
                 ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                 ->where('dbo.Hist_Notificacao.status','=','A')
                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                 ->orderby('dbo.Hist_Notificacao.data', 'desc')
                 ->get();
       
        return view('Painel.Financeiro.SolicitacaoDebite.realizarconciliacao', compact('notas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function listasolicitacoespagar($id_cliente) {


        // $codigo_cliente = DB::table('PLCFULL.dbo.Jurid_CliFor')
        // ->select('Codigo')  
        // ->where('id_cliente','=', $id_cliente)
        // ->value('Codigo');

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    
        $dataHoraMinuto = $carbon->format('d-m-Y');

        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', '=', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
        ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc') 
        ->select(
                 'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                 'PLCFULL.dbo.Jurid_Debite.Setor',
                 'PLCFULL.dbo.Jurid_ContaPr.Tipolan as TipoLancamentoCPR',
                 'PLCFULL.dbo.Jurid_CliFor.Nome as NomeFornecedor',
                 'dbo.users.name as Correspondente',
                 'PLCFULL.dbo.Jurid_Debite.moeda as Moeda',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                 'dbo.Jurid_Nota_Tiposervico.descricao as TipoServico',
                 'dbo.Jurid_Situacao_Ficha.data_servico as DataServico',
                 'PLCFULL.dbo.Jurid_ContaPr.Tipodoc as TipoDocCPR',
                 'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
                 'PLCFULL.dbo.Jurid_Debite.Hist',
                 'PLCFULL.dbo.Jurid_ContaPr.Dt_Progr as DataProgramacao',
                 'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
                 'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoFornecedor',
                 'PLCFULL.dbo.Jurid_CliFor.id_cliente as id_cliente',
                 DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'))
        ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
        ->where('dbo.users.id', '=', $id_cliente)
        ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '14')
        ->get();

        $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  
        
        $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')->where('Status', '1')->get();    

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
        ->where('status', 'A')
        ->where('destino_id','=', Auth::user()->id)
        ->count();
      
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data',
                'id_ref', 
                'user_id',
                'tipo', 
                'obs',
                'hist_notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();

        return view('Painel.Financeiro.SolicitacaoDebite.listasolicitacoespagar', compact('dataHoraMinuto','datahoje','tiposdoc','bancos','notas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function listasolicitacoespagarexcel($correspondente_id) {

        $carbon= Carbon::now();

        $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', '=', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
        ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc') 
        ->select(
                 'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                 'PLCFULL.dbo.Jurid_Debite.Setor',
                 'PLCFULL.dbo.Jurid_ContaPr.Tipolan as TipoLancamentoCPR',
                 'PLCFULL.dbo.Jurid_CliFor.Nome as NomeFornecedor',
                 'dbo.users.name as Correspondente',
                 'PLCFULL.dbo.Jurid_Debite.moeda as Moeda',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                 'dbo.Jurid_Nota_Tiposervico.descricao as TipoServico',
                 'dbo.Jurid_Situacao_Ficha.data_servico as DataServico',
                 'PLCFULL.dbo.Jurid_Debite.Hist',
                 'PLCFULL.dbo.Jurid_ContaPr.Tipodoc as TipoDocCPR',
                 'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
                 'PLCFULL.dbo.Jurid_ContaPr.Dt_Progr as DataProgramacao',
                 'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
                 'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoFornecedor',
                 'PLCFULL.dbo.Jurid_CliFor.id_cliente as id_cliente',
                 DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'))
        ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
        ->where('dbo.users.id', '=', $correspondente_id)
        ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '14')
        ->get();

        $customer_array[] = array(
                'NumeroDebite', 
                'Correspondente',
                'Solicitante',
                'Cliente',
                'TipoLancamentoCPR',
                'TipoDocCPR',
                'Setor',
                'Moeda',
                'ValorTotal',
                'DataServico',
                'DataProgramacao',
                'DataVencimento');


        foreach($customer_data as $customer) {


            $customer_array[] = array(
                    'NumeroDebite'  => $customer->NumeroDebite,
                    'Correspondente' => $customer->Correspondente,
                    'Solicitante' => $customer->Solicitante,
                    'Cliente' => $customer->NomeFornecedor,
                    'TipoLancamentoCPR' => $customer->TipoLancamentoCPR,
                    'TipoDocCPR' => $customer->TipoDocCPR,
                    'Setor' => $customer->Setor,
                    'Moeda' => $customer->Moeda,
                    'ValorTotal' => number_format($customer->ValorTotal,2,".",","),
                    'DataServico' => date('d/m/Y', strtotime($customer->DataServico)),
                    'DataProgramacao' => date('d/m/Y', strtotime($customer->DataProgramacao)),
                    'DataVencimento' => date('d/m/Y', strtotime($customer->DataVencimento)),


            );
        }

        ini_set('memory_limit','-1'); 
        Excel::create('Solicitações de pagamento em aberto', function($excel) use ($customer_array){
            $excel->setTitle('Solicitações de pagamento em aberto');
            $excel->sheet('Solicitações em aberto', function($sheet) use ($customer_array) {
            $sheet->fromArray($customer_array, null, 'A1', false, false);

         });
        })->download('xlsx');        

    }

    public function solicitacoesbaixadas(Request $request) {

       // dd($request->get('numerodebite'));

       $usuarioid = Auth::user()->id;
       $carbon= Carbon::now();
       $dataehora = $carbon->format('dmY_HHis');
       $fornecedor = $request->get('fornecedor');
       $codigo_banco = $request->get('portador'); 
       $data_concil = $request->get('dataconciliacao');
       $data_baixa = $request->get('databaixa');

       $numerodebite = $request->get('numerodebite');
       $tipodoc = $request->get('tipodoc');
       $hist = $request->get('hist');

       $data = array();
       foreach($numerodebite as $index => $numerodebite)
       {

        $item = array('numerodebite' => $numerodebite);
        array_push($data,$item);
               
       $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
       $numdoc = $ultimonumprc + 1;
       $cliente = DB::table('PLCFULL.dbo.Jurid_Debite')->select('AdvServ')->where('Numero','=', $numerodebite)->value('AdvServ');
       $tipo = 'P';
       $tipodocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('tipodocpag')->where('Numero','=', $numerodebite)->value('tipodocpag');
       $numdocor = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->select('numdocpag')
               ->where('Numero','=', $numerodebite)
               ->value('numdocpag');
       $tipolan = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
               ->select('PLCFULL.dbo.Jurid_ContaPR.Tipolan')
               ->where('Numero','=', $numerodebite)
               ->value('PLCFULL.dbo.Jurid_ContaPR.Tipolan');
       $valor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('ValorT')->where('Numero','=', $numerodebite)->value('ValorT');
       $setor = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->select('Setor')
               ->where('Numero','=', $numerodebite)
               ->value('Setor');     
       $observacao = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
               ->select('PLCFULL.dbo.Jurid_ContaPR.Obs')
               ->where('Numero','=', $numerodebite)
               ->value('PLCFULL.dbo.Jurid_ContaPR.Obs');
       $datacompetencia = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
               ->select('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr')
               ->where('Numero','=', $numerodebite)
               ->value('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr');
       $codigo_comp = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Pasta')->where('Numero','=', $numerodebite)->value('Pasta');
       $unidade = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->select('Unidade')
               ->where('Numero','=', $numerodebite)
               ->value('Unidade'); 
       $prconta = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
               ->select('PLCFULL.dbo.Jurid_ContaPR.PRConta')
               ->where('Numero','=', $numerodebite)
               ->value('PLCFULL.dbo.Jurid_ContaPR.PRConta');
       $contrato = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
               ->select('PLCFULL.dbo.Jurid_ContaPR.Contrato')
               ->where('Numero','=', $numerodebite)
               ->value('PLCFULL.dbo.Jurid_ContaPR.Contrato');
       $ident_cpr = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
               ->select('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident')
               ->where('Numero','=', $numerodebite)
               ->value('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident');
       $origem_cpr = $ident_cpr;
       $ident_rate = '0';
       $hist_usuario = 'portal.plc';
       $moeda = DB::table('PLCFULL.dbo.Jurid_Debite')->select('moeda')->where('Numero','=', $numerodebite)->value('moeda'); 
       $desconto = '0.00';

       //Pego as variaveis
       //Se for Itau (341) = TED, se não TRF 
       if($codigo_banco == "341") {
       
           $values= array(
                'Tipodoc' => "TED",
                'Numdoc' => $numdoc,
                'Cliente' => $cliente,
                'Tipo' => $tipo,
                'TipodocOr' => $tipodocor,
                'NumDocOr' => $numdocor,
                'Tipolan' => $tipolan,
                'Valor' => $valor,
                'Centro' => $setor,
                'Dt_baixa' => $data_baixa,
                'Portador' => $codigo_banco,
                'Obs' => $observacao,
                'Juros' => '0.00',
                'Dt_Compet' => $datacompetencia,
                'DT_Concil' => $data_concil,
                'Codigo_Comp' =>$codigo_comp,
                'Unidade' => $unidade,
                'PRConta' => $prconta,
                'Contrato' =>$contrato,
                'Ident_Cpr' => $ident_cpr,
                'origem_cpr' => $origem_cpr,
                'Ident_Rate' => $ident_rate,
                'moeda' => $moeda);    
             DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);  
           }
            else {
                $values= array(
                 'Tipodoc' => "TRF",
                 'Numdoc' => $numdoc,
                 'Cliente' => $cliente,
                 'Tipo' => $tipo,
                 'TipodocOr' => $tipodocor,
                 'NumDocOr' => $numdocor,
                 'Tipolan' => $tipolan,
                 'Valor' => $valor,
                 'Centro' => $setor,
                 'Dt_baixa' => $data_baixa,
                 'Portador' => $codigo_banco,
                 'Obs' => $observacao,
                 'Juros' => '0.00',
                 'Dt_Compet' => $datacompetencia,
                 'DT_Concil' => $data_concil,
                 'Codigo_Comp' =>$codigo_comp,
                 'Unidade' => $unidade,
                 'PRConta' => $prconta,
                 'Contrato' =>$contrato,
                 'Ident_Cpr' => $ident_cpr,
                 'origem_cpr' => $origem_cpr,
                 'Ident_Rate' => $ident_rate,
                 'moeda' => $moeda);    
                 DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);       
           }

     //Update Conta PR  (Falando Baixado = 4, Status = 'baixado' e Data Pag = Data baixa)
     DB::table('PLCFULL.dbo.Jurid_ContaPr')
        ->limit(1)
        ->where('Cpr_ident', '=', $ident_cpr)     
        ->update(array('Baixado' => '1', 'Status' => '4', 'Dt_baixa' => $data_baixa, 'Historico' => $hist));
     
    //Update Jurid_Debite (Fatura= NumDoc PRBX e Data_PAG = Data Baixa)
     DB::table('PLCFULL.dbo.Jurid_Debite')
        ->limit(1)
        ->where('Numero', '=', $numerodebite)     
        ->update(array('datapag' => $data_baixa, 'Hist' => $hist));

     //Se tiver comprovante ele ja atualiza
     if($request->get('comprovante') == "SIM") {


        //Grava na GED
        $image = $request->file('select_file');
        $new_name = $numerodebite . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
        Storage::disk('comprovantepagamento-sftp')->put($new_name, fopen($image, 'r+'));
        Storage::disk('reembolso-local')->put($new_name, fopen($image, 'r+'));

        //Insert Jurid_Ged_Main
        $values = array(
        'Tabela_OR' => 'Debite',
        'Codigo_OR' => $numerodebite,
        'Id_OR' => $numerodebite,
        'Descricao' => $image->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\ged\vault$\Debite/'.$new_name, 
        'Data' => $carbon,
        'Nome' => $image->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $image->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $new_name,
        'Texto' => 'Comprovante pagamento');
         DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        //Update Status
        DB::table('dbo.Jurid_Situacao_Ficha')
         ->where('numerodebite', $numerodebite)  
         ->limit(1) 
         ->update(array('status_id' => '11'));

        //Grava na Hist
        $values2 = array('id_hist' => $numerodebite, 'id_status' => '9', 'id_user' => $usuarioid, 'data' => $carbon);
        DB::table('dbo.Jurid_Hist_Ficha')->insert($values2);

        $values2 = array('id_hist' => $numerodebite, 'id_status' => '11', 'id_user' => $usuarioid, 'data' => $carbon);
        DB::table('dbo.Jurid_Hist_Ficha')->insert($values2);

        $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
        ->select('id_user')
        ->where('id_status','=', '6')
        ->where('id_hist', '=', $numerodebite)
        ->value('id_user'); 

        $emailCorrespondente = DB::table('dbo.users')
               ->select('email')
               ->where('id','=', $idCorrespondente)
               ->value('email');
     
        $emailAdvogado = DB::table('dbo.users')
                 ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
                 ->select('dbo.users.email')  
                 ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
                 ->value('dbo.users.email');
         
        $emailCoordenador = DB::table('dbo.users')
                 ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                 ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                 ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                 ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                 ->select('dbo.users.email') 
                 ->where('dbo.profiles.id', '=', '20')
                 ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                 ->value('dbo.users.email'); 

        if($emailAdvogado != null) {
                Mail::to($emailCorrespondente)
                ->cc($emailCoordenador) 
                ->send(new ComprovantePagamento($numerodebite));
        }  else {
                Mail::to($emailCorrespondente)
                ->cc($emailCoordenador) 
                ->send(new ComprovantePagamento($numerodebite));
        }  
     }  
     //Não anexou comprovante
    else {

        //Update Status
        DB::table('dbo.Jurid_Situacao_Ficha')
         ->where('numerodebite', $numerodebite)  
         ->limit(1) 
         ->update(array('status_id' => '9'));

        //Grava na Hist
        $values2 = array('id_hist' => $numerodebite, 'id_status' => '9', 'id_user' => $usuarioid, 'data' => $carbon);
        DB::table('dbo.Jurid_Hist_Ficha')->insert($values2);

     }
     
           
     //Update Jurid_Default (Num + 1)    
     $numprcNovo = $numdoc + 1;
     DB::table('PLCFULL.dbo.Jurid_Default_')
        ->limit(1) 
        ->update(array('Numcpr' => $numprcNovo));
           
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
              ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
              ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
              ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
              ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                       'PLCFULL.dbo.Jurid_Debite.Pasta',
                       DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                       DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                       DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                      'dbo.Jurid_Status_Ficha.*',
                      'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta')
                      ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numerodebite)
             ->limit(1)    
             ->get();
      
     $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
               ->select('id_user')
               ->where('id_status','=', '6')
               ->where('id_hist', '=', $numerodebite)
               ->value('id_user'); 
     
     $emailCorrespondente = DB::table('dbo.users')
               ->select('email')
               ->where('id','=', $idCorrespondente)
               ->value('email');
     
     $emailAdvogado = DB::table('dbo.users')
                 ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
                 ->select('dbo.users.email')  
                 ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
                 ->value('dbo.users.email');
         
     $emailCoordenador = DB::table('dbo.users')
                 ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                 ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                 ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                 ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                 ->select('dbo.users.email') 
                 ->where('dbo.profiles.id', '=', '20')
                 ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                 ->value('dbo.users.email'); 
     
      //Pegar o id User do Advogado para Receber a notificacao
      $destino_id = DB::table('dbo.users')
                 ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
                 ->select('dbo.users.id')  
                 ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
                 ->value('dbo.users.id');  
      
     
      $coordenador_id = DB::table('dbo.users')
                 ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                 ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                 ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                 ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                 ->select('dbo.users.id') 
                 ->where('dbo.profiles.id', '=', '20')
                 ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                 ->value('dbo.users.id'); 
     
        
     //Envia notificação para o Correspondente
     $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Solicitação pagamento baixada.', 'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values3);
 
     
     //Notificação para Danielle Financeiro Aprovar
     $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => '59', 'tipo' => '1', 'obs' => 'Solicitação pagamento realizado.', 'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values3);
     
     //Notificação para o Solicitante da Pasta
     $values4= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento realizado.', 'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);
     
     //Notificação para o Coordenador
     $values5= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $coordenador_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento realizado.', 'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values5);
      
     //Manda notificação para Isabelle Alves informando que é necessário anexar comprovante pagamento
     $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => '127', 'tipo' => '1', 'obs' => 'Solicitação pagamento aguardando comprovante.', 'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values3);
      

     //Envia email informando que a Solicitação foi paga 
     if($emailAdvogado == null) {
     Mail::to($emailCorrespondente)
     ->cc($emailCoordenador) 
     ->send(new SolicitacaoPaga($numerodebite));
     }
     else {   
     Mail::to($emailCorrespondente)
     ->cc($emailAdvogado, $emailCoordenador) 
     ->send(new SolicitacaoPaga($numerodebite));   
     }   

     }
     flash('Conciliação bancária realizada com sucesso !')->success();

     //Fim Foreach
     return redirect()->route('Painel.Financeiro.pagas');  
       
    }


    public function baixado(Request $request) {
          
        $usuarioid = Auth::user()->id;
        $carbon= Carbon::now();
        $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
        $numerodebite = $request->get('numerodebite');
        $dataehora = $carbon->format('dmY_HHis');
        $hist = $request->get('hist');
        $data_baixa = $request->get('databaixa');
        $tipolan = $request->get('tipolan');

        
        //Pego as variaveis 
        $tipodoc = $request->get('tipodoc');
        $numdoc = $ultimonumprc + 1;
        $cliente = DB::table('PLCFULL.dbo.Jurid_Debite')->select('AdvServ')->where('Numero','=', $numerodebite)->value('AdvServ');
        $tipo = 'P';
        $tipodocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('tipodocpag')->where('Numero','=', $numerodebite)->value('tipodocpag');
        $numdocor = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->select('numdocpag')
                ->where('Numero','=', $numerodebite)
                ->value('numdocpag');

        $valor = $request->get('valortotal');
        $setor = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->select('Setor')
                ->where('Numero','=', $numerodebite)
                ->value('Setor');     
        $portador = $request->get('portador');
        $Obs = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
                ->select('PLCFULL.dbo.Jurid_ContaPR.Obs')
                ->where('Numero','=', $numerodebite)
                ->value('PLCFULL.dbo.Jurid_ContaPR.Obs');
        $datacompetencia = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
                ->select('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr')
                ->where('Numero','=', $numerodebite)
                ->value('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr');
        $data_concil = $request->get('dataconciliacao');
        $codigo_comp = $request->get('pasta');
        $unidade = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->select('Unidade')
                ->where('Numero','=', $numerodebite)
                ->value('Unidade'); 
        $prconta = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
                ->select('PLCFULL.dbo.Jurid_ContaPR.PRConta')
                ->where('Numero','=', $numerodebite)
                ->value('PLCFULL.dbo.Jurid_ContaPR.PRConta');
        $contrato = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
                ->select('PLCFULL.dbo.Jurid_ContaPR.Contrato')
                ->where('Numero','=', $numerodebite)
                ->value('PLCFULL.dbo.Jurid_ContaPR.Contrato');
        $ident_cpr = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')
                ->select('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident')
                ->where('Numero','=', $numerodebite)
                ->value('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident');
        $origem_cpr = $ident_cpr;
        $ident_rate = '0';
        $hist_usuario = 'portal.plc';
        $moeda = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->select('Moeda')
                ->where('Numero','=', $numerodebite)
                ->value('Moeda'); 
        $desconto = '0.00';
        
        //Insert Jurid_ContaPRBX
            
        $values= array(
            'Tipodoc' => $tipodoc,
            'Numdoc' => $numdoc,
            'Cliente' => $cliente,
            'Tipo' => $tipo,
            'TipodocOr' => $tipodocor,
            'NumDocOr' => $numdocor,
            'Tipolan' => $tipolan,
            'Valor' => $valor,
            'Centro' => $setor,
            'Dt_baixa' => $data_baixa,
            'Portador' => $portador,
            'Obs' => $Obs,
            'Juros' => '0.00',
            'Dt_Compet' => $datacompetencia,
            'DT_Concil' => $data_concil,
            'Codigo_Comp' => $codigo_comp,
            'Unidade' => $unidade,
            'PRConta' => $prconta,
            'Contrato' => $contrato,
            'Ident_Cpr' => $ident_cpr,
            'origem_cpr' => $ident_cpr,
            'Ident_Rate' => $ident_rate,
            'moeda' => 'R$');    
        DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);   
        
        //Update Conta PR  (Falando Baixado = 4, Status = 'baixado' e Data Pag = Data baixa)
        DB::table('PLCFULL.dbo.Jurid_ContaPr')
           ->limit(1)
           ->where('Cpr_ident', '=', $ident_cpr)     
           ->update(array('Baixado' => '1', 'Status' => '4', 'Dt_baixa' => $data_baixa, 'Historico' => $hist));
        
       //Update Jurid_Debite (Fatura= NumDoc PRBX e Data_PAG = Data Baixa)
        DB::table('PLCFULL.dbo.Jurid_Debite')
           ->limit(1)
           ->where('Numero', '=', $numerodebite)     
           ->update(array('Hist'=> $hist, 'datapag' => $data_baixa));
        
              
        //Update Jurid_Default (Num + 1)    
        $numprcNovo = $numdoc + 1;
        DB::table('PLCFULL.dbo.Jurid_Default_')
           ->limit(1) 
           ->update(array('Numcpr' => $numprcNovo));
        
        //Grava na Hist Ficha informando que foi feito o pagamento mas aguarda comprovante
         $values2 = array('id_hist' => $numerodebite, 'id_status' => '9', 'id_user' => $usuarioid, 'data' => $carbon);
         DB::table('dbo.Jurid_Hist_Ficha')->insert($values2);
         
         
        //Update Jurid_Situacao_Ficha
         DB::table('dbo.Jurid_Situacao_Ficha')
            ->where('numerodebite', $numerodebite)  
            ->limit(1) 
            ->update(array('status_id' => '9'));
              
        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                 ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                 ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                 ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
                 ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
                 ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                          'PLCFULL.dbo.Jurid_Debite.Pasta',
                          DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                          DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                          DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                         'dbo.Jurid_Status_Ficha.*',
                         'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta')
                         ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numerodebite)
                ->limit(1)    
                ->get();
         
        $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
                  ->select('id_user')
                  ->where('id_status','=', '6')
                  ->where('id_hist', '=', $numerodebite)
                  ->value('id_user'); 
        
        $emailCorrespondente = DB::table('dbo.users')
                  ->select('email')
                  ->where('id','=', $idCorrespondente)
                  ->value('email');
        
        $emailAdvogado = DB::table('dbo.users')
                    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
                    ->select('dbo.users.email')  
                    ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
                    ->value('dbo.users.email');
            
        $emailCoordenador = DB::table('dbo.users')
                    ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                    ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                    ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                    ->select('dbo.users.email') 
                    ->where('dbo.profiles.id', '=', '20')
                    ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                    ->value('dbo.users.email'); 
        
         //Pegar o id User do Advogado para Receber a notificacao
         $destino_id = DB::table('dbo.users')
                    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.users.cpf', '=', 'PLCFULL.dbo.Jurid_Debite.Advogado')
                    ->select('dbo.users.id')  
                    ->where('PLCFULL.dbo.Jurid_Debite.Numero','=', $numerodebite)
                    ->value('dbo.users.id');  
         
        
         $coordenador_id = DB::table('dbo.users')
                    ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                    ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                    ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                    ->select('dbo.users.id') 
                    ->where('dbo.profiles.id', '=', '20')
                    ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                    ->value('dbo.users.id'); 
        
           
        //Envia notificação para o Correspondente
        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Solicitação pagamento baixada.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);
    
        
        //Notificação para Danielle Financeiro Aprovar
        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => '59', 'tipo' => '1', 'obs' => 'Solicitação pagamento realizado.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);
        
        //Notificação para o Solicitante da Pasta
        $values4= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento realizado.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);
        
        //Notificação para o Coordenador
        $values5= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $coordenador_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento realizado.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values5);
        
        flash('Conciliação bancária realizada com sucesso !')->success();
    
        //Manda notificação para Isabelle Alves informando que é necessário anexar comprovante pagamento
         $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => '127', 'tipo' => '1', 'obs' => 'Solicitação pagamento aguardando comprovante.', 'status' => 'A');
         DB::table('dbo.Hist_Notificacao')->insert($values3);
    
         
        //Envia email informando que a Solicitação foi paga 
        if($emailCorrespondente == null && $emailAdvogado == null && $emailCoordenador == null) {
    
        return redirect()->route('Painel.Financeiro.pagas');    
        }
    
        else {      
        Mail::to($emailCorrespondente)
        ->cc($emailAdvogado, $emailCoordenador) 
        ->send(new SolicitacaoPaga($numerodebite));
        return redirect()->route('Painel.Financeiro.pagas');    
        }    
}

        public function faturamento() {

                $grupos = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
                         ->select('Codigo', 'Descricao')
                         ->orderby('Descricao', 'asc')
                         ->get();

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                         ->where('status', 'A')
                         ->where('destino_id','=', Auth::user()->id)
                         ->count();
                       
                 $notificacoes = DB::table('dbo.Hist_Notificacao')
                         ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                         'data',
                         'id_ref', 
                         'user_id',
                         'tipo', 
                         'obs',
                         'Hist_Notificacao.status', 
                         'dbo.users.*')  
                         ->limit(3)
                         ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                         ->where('dbo.Hist_Notificacao.status','=','A')
                         ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                         ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                         ->get();
                
               return view('Painel.Financeiro.Faturamento.index', compact('grupos', 'totalNotificacaoAbertas', 'notificacoes'));
        }

        function fetch(Request $request)
        {
         $select = $request->get('select');
         $value = $request->get('value');
         $dependent = $request->get('dependent');

         $datas = DB::table('PLCFULL.dbo.Jurid_Contratos')
           ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Contratos.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
           ->join('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
           ->where('PLCFULL.dbo.Jurid_GrupoCliente.Codigo', '=', $value)
           ->groupby('PLCFULL.dbo.Jurid_Contratos.Numero')
           ->select('PLCFULL.dbo.Jurid_Contratos.Numero')
           ->orderby('PLCFULL.dbo.Jurid_Contratos.Numero', 'desc')
           ->get();

         foreach($datas as $index) {  
         $output = '<option value="'.$index->Numero.'">'.$index->Numero.'</option>';
         echo $output;
         }
        }

       public function faturamento_pastas(Request $request) {

         $codigo_grupo = $request->get('country');
         $numero_contrato = $request->get('state');
         $status = $request->get('status');

         //Se selecionar Ativo
         if($status == "Ativa") {

                $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Financeiro_Faturamento_Matrix', 'PLCFULL.dbo.Jurid_Pastas.id_pasta', '=', 'dbo.Financeiro_Faturamento_Matrix.id_pasta')
                ->leftjoin('dbo.Financeiro_Faturamento_Hist', 'PLCFULL.dbo.Jurid_Pastas.id_pasta', '=', 'dbo.Financeiro_Faturamento_Hist.id_pasta')
                ->where('PLCFULL.dbo.Jurid_Pastas.Contrato', '=', $numero_contrato)
                ->where('PLCFULL.dbo.Jurid_Pastas.status', '=', 'Ativa')
               // ->where('dbo.Financeiro_Faturamento_Matrix.dt_update', '<=', Carbon::now()->startOfMonth()->subMonth()->endOfMonth())
               ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as CodigoPasta',
                'PLCFULL.dbo.Jurid_Pastas.Descricao as DescricaoPasta',
                'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                'PLCFULL.dbo.Jurid_Pastas.status as StatusAtual',
                DB::raw('sum(dbo.Financeiro_Faturamento_Hist.dias_count) as QtdDiasAtiva'),
                'PLCFULL.dbo.Jurid_Pastas.Dt_Cad as DataCadastro',
                'PLCFULL.dbo.Jurid_Pastas.Dt_Saida as DataSaida',
                'dbo.Financeiro_Faturamento_Matrix.dt_update as DataUpdate')
                ->groupby(
                        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                        'PLCFULL.dbo.Jurid_Pastas.Descricao',
                        'PLCFULL.dbo.Jurid_CliFor.Nome',
                        'PLCFULL.dbo.Jurid_Setor.Descricao',
                        'PLCFULL.dbo.Jurid_Unidade.Descricao',
                        'PLCFULL.dbo.Jurid_Pastas.status',
                        'PLCFULL.dbo.Jurid_Pastas.Dt_Cad',
                        'PLCFULL.dbo.Jurid_Pastas.Dt_Saida',
                        'dbo.Financeiro_Faturamento_Matrix.dt_update')
                ->get();
         } 
         //Se selecionar Inativo
         else if($status == "Inativa") {

                $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Financeiro_Faturamento_Matrix', 'PLCFULL.dbo.Jurid_Pastas.id_pasta', '=', 'dbo.Financeiro_Faturamento_Matrix.id_pasta')
                ->leftjoin('dbo.Financeiro_Faturamento_Hist', 'PLCFULL.dbo.Jurid_Pastas.id_pasta', '=', 'dbo.Financeiro_Faturamento_Hist.id_pasta')
                ->where('PLCFULL.dbo.Jurid_Pastas.Contrato', '=', $numero_contrato)
                ->where('PLCFULL.dbo.Jurid_Pastas.status', '=', 'Inativa')
               // ->where('dbo.Financeiro_Faturamento_Matrix.dt_update', '<=', Carbon::now()->startOfMonth()->subMonth()->endOfMonth())
               ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as CodigoPasta',
                'PLCFULL.dbo.Jurid_Pastas.Descricao as DescricaoPasta',
                'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                'PLCFULL.dbo.Jurid_Pastas.status as StatusAtual',
                DB::raw('sum(dbo.Financeiro_Faturamento_Hist.dias_count) as QtdDiasAtiva'),
                'PLCFULL.dbo.Jurid_Pastas.Dt_Cad as DataCadastro',
                'PLCFULL.dbo.Jurid_Pastas.Dt_Saida as DataSaida',
                'dbo.Financeiro_Faturamento_Matrix.dt_update as DataUpdate')
                ->groupby(
                        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                        'PLCFULL.dbo.Jurid_Pastas.Descricao',
                        'PLCFULL.dbo.Jurid_CliFor.Nome',
                        'PLCFULL.dbo.Jurid_Setor.Descricao',
                        'PLCFULL.dbo.Jurid_Unidade.Descricao',
                        'PLCFULL.dbo.Jurid_Pastas.status',
                        'PLCFULL.dbo.Jurid_Pastas.Dt_Cad',
                        'PLCFULL.dbo.Jurid_Pastas.Dt_Saida',
                        'dbo.Financeiro_Faturamento_Matrix.dt_update')
                ->get();

         } 
         //Se for ambos
         else {
                $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Financeiro_Faturamento_Matrix', 'PLCFULL.dbo.Jurid_Pastas.id_pasta', '=', 'dbo.Financeiro_Faturamento_Matrix.id_pasta')
                ->leftjoin('dbo.Financeiro_Faturamento_Hist', 'PLCFULL.dbo.Jurid_Pastas.id_pasta', '=', 'dbo.Financeiro_Faturamento_Hist.id_pasta')
                ->where('PLCFULL.dbo.Jurid_Pastas.Contrato', '=', $numero_contrato)
               // ->where('dbo.Financeiro_Faturamento_Matrix.dt_update', '<=', Carbon::now()->startOfMonth()->subMonth()->endOfMonth())
               ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as CodigoPasta',
                'PLCFULL.dbo.Jurid_Pastas.Descricao as DescricaoPasta',
                'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                'PLCFULL.dbo.Jurid_Pastas.status as StatusAtual',
                DB::raw('sum(dbo.Financeiro_Faturamento_Hist.dias_count) as QtdDiasAtiva'),
                'PLCFULL.dbo.Jurid_Pastas.Dt_Cad as DataCadastro',
                'PLCFULL.dbo.Jurid_Pastas.Dt_Saida as DataSaida',
                'dbo.Financeiro_Faturamento_Matrix.dt_update as DataUpdate')
                ->groupby(
                        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                        'PLCFULL.dbo.Jurid_Pastas.Descricao',
                        'PLCFULL.dbo.Jurid_CliFor.Nome',
                        'PLCFULL.dbo.Jurid_Setor.Descricao',
                        'PLCFULL.dbo.Jurid_Unidade.Descricao',
                        'PLCFULL.dbo.Jurid_Pastas.status',
                        'PLCFULL.dbo.Jurid_Pastas.Dt_Cad',
                        'PLCFULL.dbo.Jurid_Pastas.Dt_Saida',
                        'dbo.Financeiro_Faturamento_Matrix.dt_update')
                ->get();
       

         }

         $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                         ->where('status', 'A')
                         ->where('destino_id','=', Auth::user()->id)
                         ->count();
                       
         $notificacoes = DB::table('dbo.Hist_Notificacao')
                         ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                         'data',
                         'id_ref', 
                         'user_id',
                         'tipo', 
                         'obs',
                         'Hist_Notificacao.status', 
                         'dbo.users.*')  
                         ->limit(3)
                         ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                         ->where('dbo.Hist_Notificacao.status','=','A')
                         ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                         ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                         ->get();

         return view('Painel.Financeiro.Faturamento.pastas', compact('datas', 'numero_contrato', 'totalNotificacaoAbertas', 'notificacoes'));
       } 

       public function solicitacoesfaturamento(Request $request) {

         //Verifico se o status da pasta é Inativo, se for dar Update Jurid_Pastas e gravar na tabela TEMP

         $usuarioid = Auth::user()->id;
         $carbon= Carbon::now();
         $dataehora = $carbon->format('dmY_HHis');


         if($request->get('codigopasta') == null){

               flash('É necessário selecionar o código da pasta antes de atualizar para faturamento!')->warning();
                return redirect()->route('Painel.Financeiro.faturamento');
         } else {

         $pastasalteradas = 0;
         $codigopasta = $request->get('codigopasta');
         
         $data = array();
         foreach($codigopasta as $index => $codigopasta) {
             $item = array('codigopasta' => $codigopasta);
             array_push($data,$item);

         //Verifico status da pasta
         $statuatual =  DB::table('PLCFULL.dbo.Jurid_Pastas')->select('status')->where('Codigo_Comp','=', $codigopasta)->value('status');

        //Se for Inativo, passo para ativo e vise versa 
        if($statuatual == "Inativa") {

         $idpasta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $codigopasta)->value('id_pasta');        

         DB::table('PLCFULL.dbo.Jurid_Pastas')
         ->where('Codigo_Comp', $codigopasta) 
         ->limit(1) 
         ->update(array('status' => 'Ativa'));

         $values = array(
         'id_pasta' => $idpasta, 
         'status_atual' => 'Ativa', 
         'updated_at' => $carbon);
         DB::table('dbo.Financeiro_Faturamento_Temp')->insert($values);  

         $count = $pastasalteradas + 1;
        } 
        else if($statuatual == "Ativa") {

         $idpasta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $codigopasta)->value('id_pasta');        

         DB::table('PLCFULL.dbo.Jurid_Pastas')
         ->where('Codigo_Comp', $codigopasta) 
         ->limit(1) 
         ->update(array('status' => 'Inativa'));

         $values = array(
         'id_pasta' => $idpasta, 
         'status_atual' => 'Inativa', 
         'updated_at' => $carbon);
         DB::table('dbo.Financeiro_Faturamento_Temp')->insert($values);  

         $count = $pastasalteradas + 1;
        }

       }
       //Fim Foreach

       flash('Foi alterado o status de: ' . $count. ' pastas.')->success();    

       return redirect()->route('Painel.Financeiro.faturamento');
}
       }

  public function gerarExcelFaturamento() {


    $customer_data = DB::table('PLCFULL.dbo.Jurid_Pastas')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')  
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->leftjoin('PLCFULL.dbo.jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', '=', 'PLCFULL.dbo.jurid_empreendimento.codigo_empreendimento')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Pastas.advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.jurid_tipop', 'PLCFULL.dbo.Jurid_Pastas.tipop', '=', 'PLCFULL.dbo.Jurid_tipop.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Tribunais', 'PLCFULL.dbo.Jurid_Pastas.Tribunal', '=', 'PLCFULL.dbo.Jurid_Tribunais.Codigo')
        ->select(
                'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
                'PLCFULL.dbo.Jurid_Pastas.numprc1 as Processo',
                DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.Dt_Cad AS DateTime) as DataEntrada'), 
                'PLCFULL.dbo.Jurid_Pastas.status as StatusPasta',
                'PLCFULL.dbo.Jurid_Pastas.statusprc as StatusProcesso',
                'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
                'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoEconomico',
                'PLCFULL.dbo.jurid_empreendimento.nome_empreendimento as Negocio',
                'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                'PLCFULL.dbo.Jurid_Pastas.outraparte as OutraParte',
                'PLCFULL.dbo.Jurid_Pastas.Advogado as AdvogadoResponsavel',
                'PLCFULL.dbo.jurid_tipop.descricao as TipoProcesso',
                'PLCFULL.dbo.Jurid_Tribunais.Descricao as Tribunal',
                'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                'PLCFULL.dbo.Jurid_Pastas.uf as UF',
                DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'), 
                DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCond AS NUMERIC(15,2)) as ValorArbitrado'),
                'PLCFULL.dbo.Jurid_Pastas.Psucesso as PossibilidadePerda')
        ->limit(100000)
        ->get()
        ->toArray();
  
           $customer_array[] = array(
                   'Pasta', 
                   'Processo',
                   'DataEntrada',
                   'StatusPasta',
                   'StatusProcesso',
                   'Setor',
                   'GrupoEconomico',
                   'Negocio',
                   'Cliente',
                   'OutraParte',
                   'AdvogadoResponsavel',
                   'TipoProcesso',
                   'Tribunal',
                   'Comarca',
                   'UF',
                   'ValorCausa',
                   'ValorArbitrado',
                   'PossibilidadePerda',
                );
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'Pasta'  => $customer->Pasta,
                                'Processo' => $customer->Processo,
                                'DataEntrada'=> date('d/m/Y', strtotime($customer->DataEntrada)),
                                'StatusPasta' => $customer->StatusPasta,
                                'StatusProcesso' => $customer->StatusProcesso,
                                'Setor' => $customer->Setor,
                                'GrupoEconomico' => $customer->GrupoEconomico,
                                'Negocio' => $customer->Negocio,
                                'Cliente' => $customer->Cliente,
                                'OutraParte' => $customer->OutraParte,
                                'AdvogadoResponsavel' => $customer->AdvogadoResponsavel,
                                'TipoProcesso' => $customer->TipoProcesso,
                                'Tribunal' => $customer->Tribunal,
                                'Comarca' => $customer->Comarca,
                                'UF' => $customer->UF,
                                'ValorCausa' => $customer->ValorCausa,
                                'ValorArbitrado' => $customer->ValorArbitrado,
                                'PossibilidadePerda' => $customer->PossibilidadePerda,

                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Export faturamento', function($excel) use ($customer_array){
                        $excel->setTitle('Export faturamento');
                        $excel->sheet('Export faturamento', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');
        } 

        public function gerarExcelFaturamentoContrato($numero_contrato) {


                $customer_data = DB::table('PLCFULL.dbo.Jurid_Pastas')
                    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')  
                    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                    ->leftjoin('PLCFULL.dbo.jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', '=', 'PLCFULL.dbo.jurid_empreendimento.codigo_empreendimento')
                    ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Pastas.advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                    ->leftjoin('PLCFULL.dbo.jurid_tipop', 'PLCFULL.dbo.Jurid_Pastas.tipop', '=', 'PLCFULL.dbo.Jurid_tipop.codigo')
                    ->leftjoin('PLCFULL.dbo.Jurid_Tribunais', 'PLCFULL.dbo.Jurid_Pastas.Tribunal', '=', 'PLCFULL.dbo.Jurid_Tribunais.Codigo')
                    ->select(
                            'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
                            'PLCFULL.dbo.Jurid_Pastas.numprc1 as Processo',
                            DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.Dt_Cad AS DateTime) as DataEntrada'), 
                            'PLCFULL.dbo.Jurid_Pastas.status as StatusPasta',
                            'PLCFULL.dbo.Jurid_Pastas.statusprc as StatusProcesso',
                            'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
                            'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoEconomico',
                            'PLCFULL.dbo.jurid_empreendimento.nome_empreendimento as Negocio',
                            'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                            'PLCFULL.dbo.Jurid_Pastas.outraparte as OutraParte',
                            'PLCFULL.dbo.Jurid_Pastas.Advogado as AdvogadoResponsavel',
                            'PLCFULL.dbo.jurid_tipop.descricao as TipoProcesso',
                            'PLCFULL.dbo.Jurid_Tribunais.Descricao as Tribunal',
                            'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                            'PLCFULL.dbo.Jurid_Pastas.uf as UF',
                            DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'), 
                            DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCond AS NUMERIC(15,2)) as ValorArbitrado'),
                            'PLCFULL.dbo.Jurid_Pastas.Psucesso as PossibilidadePerda')
                    ->where('PLCFULL.dbo.Jurid_Pastas.Contrato', '=', $numero_contrato)        
                    ->get()
                    ->toArray();
              
                       $customer_array[] = array(
                               'Pasta', 
                               'Processo',
                               'DataEntrada',
                               'StatusPasta',
                               'StatusProcesso',
                               'Setor',
                               'GrupoEconomico',
                               'Negocio',
                               'Cliente',
                               'OutraParte',
                               'AdvogadoResponsavel',
                               'TipoProcesso',
                               'Tribunal',
                               'Comarca',
                               'UF',
                               'ValorCausa',
                               'ValorArbitrado',
                               'PossibilidadePerda',
                            );
                      
                            foreach($customer_data as $customer) {
                                    $customer_array[] = array(
                                            'Pasta'  => $customer->Pasta,
                                            'Processo' => $customer->Processo,
                                            'DataEntrada'=> date('d/m/Y', strtotime($customer->DataEntrada)),
                                            'StatusPasta' => $customer->StatusPasta,
                                            'StatusProcesso' => $customer->StatusProcesso,
                                            'Setor' => $customer->Setor,
                                            'GrupoEconomico' => $customer->GrupoEconomico,
                                            'Negocio' => $customer->Negocio,
                                            'Cliente' => $customer->Cliente,
                                            'OutraParte' => $customer->OutraParte,
                                            'AdvogadoResponsavel' => $customer->AdvogadoResponsavel,
                                            'TipoProcesso' => $customer->TipoProcesso,
                                            'Tribunal' => $customer->Tribunal,
                                            'Comarca' => $customer->Comarca,
                                            'UF' => $customer->UF,
                                            'ValorCausa' => $customer->ValorCausa,
                                            'ValorArbitrado' => $customer->ValorArbitrado,
                                            'PossibilidadePerda' => $customer->PossibilidadePerda,
            
                                    );
                            }
                            ini_set('memory_limit','-1'); 
                            Excel::create('Export faturamento', function($excel) use ($customer_array){
                                    $excel->setTitle('Export faturamento');
                                    $excel->sheet('Export faturamento', function($sheet) use ($customer_array) {
                                    $sheet->fromArray($customer_array, null, 'A1', false, false);
                            });
                    })->download('xlsx');
                    } 


 
        public function relatoriobancario_index() {

        //Deleta os registros temporarios
        DB::table('dbo.Financeiro_RelatorioBancario_Temp')->delete();        

        //Busco todas as entradas(R) gravando na tabela temporaria
        $entradas = DB::table("PLCFULL.dbo.Jurid_ContPrBx")
                    ->select('PLCFULL.dbo.Jurid_Banco.Codigo as codigo_banco',
                             'PLCFULL.dbo.Jurid_Banco.Descricao as descricao_banco',
                             DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor) as entrada'))
                    ->join('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_ContPrBx.Portador', 'PLCFULL.dbo.Jurid_Banco.Codigo')
                    ->where('PLCFULL.dbo.Jurid_ContPrBx.Tipo', 'R')
                    ->groupBy('PLCFULL.dbo.Jurid_Banco.Codigo', 'PLCFULL.dbo.Jurid_Banco.Descricao')
                    ->get();

                    foreach ($entradas as $entrada) {

                    $codigo_banco = $entrada->codigo_banco;
                    $descricao_banco = $entrada->descricao_banco;
                    $valorentrada = $entrada->entrada;

                    //Busco o codigo do cliente/adv ou empresa para buscar o cliente
                    $codigo = DB::table('PLCFULL.dbo.Jurid_Banco')
                    ->select('PLCFULL.dbo.Jurid_Banco.codCliente')
                    ->where('PLCFULL.dbo.Jurid_Banco.Codigo','=',$codigo_banco)
                    ->value('PLCFULL.dbo.Jurid_Banco.codCliente'); 

                    if($codigo == null) {
                        $codigo = DB::table('PLCFULL.dbo.Jurid_Banco')
                        ->select('PLCFULL.dbo.Jurid_Banco.codAdvogado')
                        ->where('PLCFULL.dbo.Jurid_Banco.Codigo','=',$codigo_banco)
                        ->value('PLCFULL.dbo.Jurid_Banco.codAdvogado'); 
                    } 
                    else if($codigo == null) {
                        $codigo = DB::table('PLCFULL.dbo.Jurid_Banco')
                        ->select('PLCFULL.dbo.Jurid_Banco.cnpj_empresa')
                        ->where('PLCFULL.dbo.Jurid_Banco.Codigo','=',$codigo_banco)
                        ->value('PLCFULL.dbo.Jurid_Banco.cnpj_empresa'); 
                    } else if($codigo == null) {
                        $codigo = $codigo_banco;
                    }

                    $grupo = DB::table('PLCFULL.dbo.Jurid_CliFor')
                    ->select('PLCFULL.dbo.Jurid_GrupoCliente.Descricao')
                    ->join('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')  
                    ->where('PLCFULL.dbo.Jurid_CliFor.Codigo','=',$codigo)
                    ->value('PLCFULL.dbo.Jurid_GrupoCliente.Descricao');  
                    
                    $value1 = array(
                        'grupo' => $grupo,
                        'codigo_banco' => $codigo_banco, 
                        'descricao_banco' => $descricao_banco, 
                        'entrada' => $valorentrada,
                        'saldo' => $valorentrada);
                    DB::table('dbo.Financeiro_RelatorioBancario_Temp')->insert($value1);

                    }

        //Busco todas as saidas(P) dando update na tabela temporaria
        $saidas = DB::table("PLCFULL.dbo.Jurid_ContPrBx")
        ->select('PLCFULL.dbo.Jurid_Banco.Codigo as codigo_banco',
                 'PLCFULL.dbo.Jurid_Banco.Descricao as descricao_banco',
                 DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor) as saida'))
        ->join('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_ContPrBx.Portador', 'PLCFULL.dbo.Jurid_Banco.Codigo')
        ->where('PLCFULL.dbo.Jurid_ContPrBx.Tipo', 'P')
        ->groupBy('PLCFULL.dbo.Jurid_Banco.Codigo', 'PLCFULL.dbo.Jurid_Banco.Descricao')
        ->get();

                            foreach ($saidas as $saida) {

                                $codigo_banco = $saida->codigo_banco;
                                $descricao_banco = $saida->descricao_banco;
                                $valorsaida = $saida->saida;

                                //Vejo se existe registro na tabela(Se sim update, se não Insert)

                                $verifica = DB::table('dbo.Financeiro_RelatorioBancario_Temp')
                                ->select('entrada')  
                                ->where('codigo_banco','=',$codigo_banco)
                                ->value('entrada');  

                                if($verifica != null) {

                                        $saldo = $verifica - $valorsaida;

                                        DB::table('dbo.Financeiro_RelatorioBancario_Temp')
                                        ->where('codigo_banco', '=' ,$codigo_banco) 
                                        ->limit(1) 
                                        ->update(array('saida' => $valorsaida, 'saldo' => $saldo));

                                } else {
 
                                        $saldo = 0.00 - $valorsaida;

                    //Busco o codigo do cliente/adv ou empresa para buscar o cliente
                    $codigo = DB::table('PLCFULL.dbo.Jurid_Banco')
                    ->select('PLCFULL.dbo.Jurid_Banco.codCliente')
                    ->where('PLCFULL.dbo.Jurid_Banco.Codigo','=',$codigo_banco)
                    ->value('PLCFULL.dbo.Jurid_Banco.codCliente'); 

                    if($codigo == null) {
                        $codigo = DB::table('PLCFULL.dbo.Jurid_Banco')
                        ->select('PLCFULL.dbo.Jurid_Banco.codAdvogado')
                        ->where('PLCFULL.dbo.Jurid_Banco.Codigo','=',$codigo_banco)
                        ->value('PLCFULL.dbo.Jurid_Banco.codAdvogado'); 
                    } 
                    else if($codigo == null) {
                        $codigo = DB::table('PLCFULL.dbo.Jurid_Banco')
                        ->select('PLCFULL.dbo.Jurid_Banco.cnpj_empresa')
                        ->where('PLCFULL.dbo.Jurid_Banco.Codigo','=',$codigo_banco)
                        ->value('PLCFULL.dbo.Jurid_Banco.cnpj_empresa'); 
                    } else if($codigo == null) {
                        $codigo = $codigo_banco;
                    }

                    $grupo = DB::table('PLCFULL.dbo.Jurid_CliFor')
                    ->select('PLCFULL.dbo.Jurid_GrupoCliente.Descricao')
                    ->join('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')  
                    ->where('PLCFULL.dbo.Jurid_CliFor.Codigo','=',$codigo)
                    ->value('PLCFULL.dbo.Jurid_GrupoCliente.Descricao');    

                                        $value1 = array(
                                                'grupo' => $grupo,
                                                'codigo_banco' => $codigo_banco, 
                                                'descricao_banco' => $descricao_banco, 
                                                'entrada' => '0.00',
                                                'saida' => $valorsaida,
                                                'saldo' => $saldo);
                                            DB::table('dbo.Financeiro_RelatorioBancario_Temp')->insert($value1);

                                }

                            }


        //Select na tabela temporaria trazendo os dados 
        $datas = DB::table("dbo.Financeiro_RelatorioBancario_Temp")
        ->select(
                 'dbo.Financeiro_RelatorioBancario_Temp.grupo as grupo',
                 'PLCFULL.dbo.Jurid_Banco.Codigo as codigo_banco',
                 'PLCFULL.dbo.Jurid_Banco.Descricao as descricao_banco',
                 'PLCFULL.dbo.Jurid_Banco.Agencia as agencia_banco',
                 'PLCFULL.dbo.Jurid_Banco.Conta as conta_banco',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'dbo.Financeiro_RelatorioBancario_Temp.entrada as entrada',
                 'dbo.Financeiro_RelatorioBancario_Temp.saida as saida',
                 'dbo.Financeiro_RelatorioBancario_Temp.saldo as saldo')
        ->join('PLCFULL.dbo.Jurid_Banco', 'dbo.Financeiro_RelatorioBancario_Temp.codigo_banco', 'PLCFULL.dbo.Jurid_Banco.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Banco.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->get();


        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                         ->where('status', 'A')
                         ->where('destino_id','=', Auth::user()->id)
                         ->count();
                       
         $notificacoes = DB::table('dbo.Hist_Notificacao')
                         ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                         'data',
                         'id_ref', 
                         'user_id',
                         'tipo', 
                         'obs',
                         'Hist_Notificacao.status', 
                         'dbo.users.*')  
                         ->limit(3)
                         ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                         ->where('dbo.Hist_Notificacao.status','=','A')
                         ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                         ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                         ->get();

         return view('Painel.Financeiro.RelatorioBancario.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

        }


        public function relatoriobancario_exportar() {

                $customer_data = DB::table("dbo.Financeiro_RelatorioBancario_Temp")
                ->select('PLCFULL.dbo.Jurid_Banco.Codigo as codigo',
                         'PLCFULL.dbo.Jurid_Banco.Descricao as descricao',
                         'PLCFULL.dbo.Jurid_Banco.Agencia as agencia_banco',
                         'PLCFULL.dbo.Jurid_Banco.Conta as conta_banco',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                         'dbo.Financeiro_RelatorioBancario_Temp.grupo as grupo',
                         'dbo.Financeiro_RelatorioBancario_Temp.entrada as entrada',
                         'dbo.Financeiro_RelatorioBancario_Temp.saida as saida',
                         'dbo.Financeiro_RelatorioBancario_Temp.saldo as saldo')
                ->join('PLCFULL.dbo.Jurid_Banco', 'dbo.Financeiro_RelatorioBancario_Temp.codigo_banco', 'PLCFULL.dbo.Jurid_Banco.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Banco.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->get();
          
                   $customer_array[] = array(
                           'codigo', 
                           'grupo',
                           'descricao',
                           'agencia_banco',
                           'conta_banco',
                           'unidade',
                           'entrada',
                           'saida',
                           'saldo');
                  
                        foreach($customer_data as $customer) {
                                $customer_array[] = array(
                                        'codigo'  => $customer->codigo,
                                        'grupo' => $customer->grupo,
                                        'descricao' => $customer->descricao,
                                        'agencia_banco' => $customer->agencia_banco,
                                        'conta_banco' => $customer->conta_banco,
                                        'unidade' => $customer->unidade,
                                        'entrada' => number_format($customer->entrada,2,",","."),
                                        'saida' => number_format($customer->saida,2,",","."),
                                        'saldo' => number_format($customer->saldo,2,",","."),
                                );
                        }
                        ini_set('memory_limit','-1'); 
                        Excel::create('Relatorio bancario', function($excel) use ($customer_array){
                                $excel->setTitle('Relatorio bancario');
                                $excel->sheet('Relatorio bancario', function($sheet) use ($customer_array) {
                                $sheet->fromArray($customer_array, null, 'A1', false, false);
                        });
                })->download('xlsx');
        

        }

        public function associacaonf_index() {

        $datas = DB::table("PLCFULL.dbo.Jurid_ContaPr")
        ->select('PLCFULL.dbo.Jurid_ContaPr.Tipodoc',
                 'PLCFULL.dbo.Jurid_ContaPr.Numdoc',
                 'PLCFULL.dbo.Jurid_ContaPr.Cliente as ClienteCodigoCPR',
                 'PLCFULL.dbo.Jurid_NotaF.Cliente as ClienteCodigoNF',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                 'PLCFULL.dbo.Jurid_ContaPr.Valor',
                 'PLCFULL.dbo.Jurid_NotaF.Numero as NumeroNF',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_NotaF.Codigo_Comp as Pasta',
                 'PLCFULL.dbo.Jurid_NotaF.Data as DataEmissao',
                 'PLCFULL.dbo.Jurid_NotaF.Vencimento as DataVencimento',
                 'PLCFULL.dbo.Jurid_NotaF.Fatura as Fatura')
        ->join('PLCFULL.dbo.Jurid_NotaF', 'PLCFULL.dbo.Jurid_ContaPr.NumNf', 'PLCFULL.dbo.Jurid_NotaF.Numero')
        ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_ContaPr.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_ContaPr.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_ContaPr.Centro', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->whereNull('PLCFULL.dbo.Jurid_NotaF.Numero_NFSE')
        ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo','R')
        ->whereNotIn('PLCFULL.dbo.Jurid_ContaPr.Unidade', array('1.3','1.6'))
        ->get();
        
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                         ->where('status', 'A')
                         ->where('destino_id','=', Auth::user()->id)
                         ->count();
                       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                         ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                         'data',
                         'id_ref', 
                         'user_id',
                         'tipo', 
                         'obs',
                         'Hist_Notificacao.status', 
                         'dbo.users.*')  
                         ->limit(3)
                         ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                         ->where('dbo.Hist_Notificacao.status','=','A')
                         ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                         ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                         ->get();

        return view('Painel.Financeiro.AssociacaoNF.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));


        }

        public function associacaonf_atualizar($cpr) {


        $datas = DB::table("PLCFULL.dbo.Jurid_ContaPr")
        ->select('PLCFULL.dbo.Jurid_ContaPr.Tipodoc',
                 'PLCFULL.dbo.Jurid_ContaPr.Numdoc',
                 'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                 'PLCFULL.dbo.Jurid_ContaPr.Valor',
                 'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
                 'PLCFULL.dbo.Jurid_ContaPr.Dt_Progr as DataProgramacao',
                 'PLCFULL.dbo.Jurid_ContaPr.Obs as Observacao',
                 'PLCFULL.dbo.Jurid_ContaPr.CSLL',
                 'PLCFULL.dbo.Jurid_ContaPr.COFINS',
                 'PLCFULL.dbo.Jurid_ContaPr.PIS',
                 'PLCFULL.dbo.Jurid_ContaPr.ISS',
                 'PLCFULL.dbo.Jurid_ContaPr.INSS',
                 'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
                 'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
                 'PLCFULL.dbo.Jurid_NotaF.Numero as NumeroNF',
                 'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_ContaPr.Codigo_Comp as Pasta',
                 'PLCFULL.dbo.Jurid_NotaF.Fatura as Fatura')
        ->join('PLCFULL.dbo.Jurid_NotaF', 'PLCFULL.dbo.Jurid_ContaPr.NumNf', 'PLCFULL.dbo.Jurid_NotaF.Numero')
        ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_ContaPr.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_ContaPr.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_ContaPr.Centro', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_ContaPr.Contrato', 'PLCFULL.dbo.Jurid_Contratos.Numero')
        ->where('PLCFULL.dbo.Jurid_ContaPr.Numdoc', $cpr)
        ->first();
        
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                         ->where('status', 'A')
                         ->where('destino_id','=', Auth::user()->id)
                         ->count();
                       
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                         ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                         'data',
                         'id_ref', 
                         'user_id',
                         'tipo', 
                         'obs',
                         'Hist_Notificacao.status', 
                         'dbo.users.*')  
                         ->limit(3)
                         ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                         ->where('dbo.Hist_Notificacao.status','=','A')
                         ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                         ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                         ->get();

        return view('Painel.Financeiro.AssociacaoNF.atualizar', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));    

        }

        public function associacaonf_atualizado(Request $request) {

        $cpr = $request->get('cpr');
        $numeronf = $request->get('numeronf');
        $numeronfprefeitura = $request->get('numeronfprefeitura');
        $teveanexo = $request->get('pergunta');
        $usuarionome = Auth::user()->name;
        $carbon= Carbon::now();
        $dataehora = $carbon->format('dmY_HHis');
        $pasta = $request->get('pasta');
        $unidade_codigo = $request->get('unidadecodigo');
        $cliente_codigo = $request->get('clientecodigo');

        //Update na Jurid_NotaF
        DB::table('PLCFULL.dbo.Jurid_NotaF')
        ->where('Numero', $numeronf)  
        ->where('Unidade', $unidade_codigo)
        ->where('Cliente', $cliente_codigo)
        ->limit(1) 
        ->update(array('Numero_NFSE' => $numeronfprefeitura, 'Status' => '1', 'rps_status' => '1' ,'rps_NumRPS' => $numeronfprefeitura, 'rps_Enviada' => '0','protocolo_numero' => $usuarionome));

             //Se tiver anexo Grava na GedMain relacionando a CPR
             if($teveanexo == "SIM") {

                $image = $request->file('select_file');
                $new_name = $numeronf . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
                
                $image->storeAs('associacaonf', $new_name);

                $ident_cpr = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc','=', $cpr)->where('Tipo','=','R')->value('Cpr_ident');
           
                //Insert Jurid_Ged_Main
                $values = array(
                    'Tabela_OR' => 'CPR',
                    'Codigo_OR' => $ident_cpr,
                    'Id_OR' => '0',
                    'Descricao' => $image->getClientOriginalName(),
                    'Link' => '\\\192.168.1.65\advwin\ged\vault$\associacaonf/'.$new_name, 
                    'Data' => $carbon,
                    'Nome' => $image->getClientOriginalName(),
                    'Responsavel' => 'portal.plc',
                    'Arq_tipo' => $image->getClientOriginalExtension(),
                    'Arq_Versao' => '1',
                    'Arq_Status' => 'Guardado',
                    'Arq_usuario' => 'portal.plc',
                    'Arq_nick' => $new_name,
                    'Obs' => $request->get('observacao'),
                    'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);      

             } 

        flash('Solicitação atualizada com sucesso!')->success();

        return redirect()->route('Painel.Financeiro.AssociacaoNF.index');

        }

        public function associacaonf_gerarexcel() {

                $customer_data = DB::table("PLCFULL.dbo.Jurid_ContaPr")
                ->select('PLCFULL.dbo.Jurid_ContaPr.Tipodoc',
                         'PLCFULL.dbo.Jurid_ContaPr.Numdoc',
                         'PLCFULL.dbo.Jurid_ContaPr.Cliente as ClienteCodigoCPR',
                         'PLCFULL.dbo.Jurid_NotaF.Cliente as ClienteCodigoNF',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                         'PLCFULL.dbo.Jurid_ContaPr.Valor',
                         'PLCFULL.dbo.Jurid_NotaF.Numero as NumeroNF',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_NotaF.Codigo_Comp as Pasta',
                         'PLCFULL.dbo.Jurid_NotaF.Data as DataEmissao',
                         'PLCFULL.dbo.Jurid_NotaF.Vencimento as DataVencimento',
                         'PLCFULL.dbo.Jurid_NotaF.Fatura as Fatura')
                ->join('PLCFULL.dbo.Jurid_NotaF', 'PLCFULL.dbo.Jurid_ContaPr.NumNf', 'PLCFULL.dbo.Jurid_NotaF.Numero')
                ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_ContaPr.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_ContaPr.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_ContaPr.Centro', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->whereNull('PLCFULL.dbo.Jurid_NotaF.Numero_NFSE')
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo','R')
                ->whereNotIn('PLCFULL.dbo.Jurid_ContaPr.Unidade', array('1.3','1.6'))
                ->get()
                ->toArray();

                $customer_array[] = array(
                        'CPR', 
                        'Tipodoc',
                        'Cliente',
                        'Pasta',
                        'Setor',
                        'Unidade',
                        'NumeroNFAdvwin',
                        'Fatura',
                        'DataEmissao',
                        'DataVencimento',
                        'Valor');
                    foreach($customer_data as $customer)
                    {
                     $customer_array[] = array(
                      'CPR'  => $customer->Numdoc,
                      'Tipodoc'  => $customer->Tipodoc,
                      'Cliente'  => $customer->Cliente,
                      'Pasta' => $customer->Pasta,
                      'Setor' => $customer->Setor,
                      'Unidade' => $customer->Unidade,
                      'NumeroNFAdvwin' => $customer->NumeroNF,
                      'Fatura' => $customer->Fatura,
                      'DataEmissao' => date('d/m/Y H:m:s', strtotime($customer->DataEmissao)),
                      'DataVencimento' => date('d/m/Y H:m:s', strtotime($customer->DataVencimento)),
                      'Valor'=> number_format($customer->Valor,2,",","."),
                     );
                    }
                    Excel::create('Relação Notas Fiscais', function($excel) use ($customer_array){
                     $excel->setTitle('Relação Notas Fiscais');
                     $excel->sheet('Relação Notas Fiscais', function($sheet) use ($customer_array){
                      $sheet->fromArray($customer_array, null, 'A1', false, false);
                     });
                    })->download('xlsx');

        }

        public function reembolso_anexos($numerodebite) {

                //Busco os arquivos gravados na GED
                $datas = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
                ->where('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
                ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
                ->get();

                $QuantidadeAnexos = $datas->count();

                return view('Painel.Financeiro.Reembolso.anexos', compact('numerodebite','datas', 'QuantidadeAnexos')); 
        }

        public function reembolso_importaranexos(Request $request) {

          $numerodebite = $request->get('numerodebite');
          $carbon= Carbon::now();
          $dataehora = $carbon->format('dmY_His');

          //Foreach Anexos
          $image = $request->file('select_file');

          foreach($image as $index => $image) {

          $new_name = $numerodebite . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
          $image->storeAs('reembolso', $new_name);
          Storage::disk('reembolso-local')->put($new_name, File::get($image));

          //Grava no GED
          $values = array(
                'Tabela_OR' => 'Debite',
                'Codigo_OR' => $numerodebite,
                'Id_OR' => $numerodebite,
                'Descricao' => $image->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\ged\vault$\reembolso/'.$new_name, 
                'Data' => $carbon,
                'Nome' => $image->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $image->getClientOriginalName());
          DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

          }

         //Busco os arquivos gravados na GED
         $datas = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
         ->where('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
         ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
         ->get();

         $QuantidadeAnexos = $datas->count();

         return view('Painel.Financeiro.Reembolso.anexos', compact('numerodebite','datas', 'QuantidadeAnexos')); 



        }

        public function reembolso_baixaranexo($caminho) {

                return Storage::disk('reembolso-sftp')->download($caminho);

        }

        public function reembolso_solicitante_buscardados(Request $request) {

          $codigo = $request->get('codigo');

          $response = DB::table('PLCFULL.dbo.Jurid_Pastas')
          ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
          ->where('PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros', 'LIKE', '%'. $codigo . '%')
          ->orWhere('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp', 'LIKE', '%'. $codigo . '%')
          ->orderBy('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp', 'ASC')
          ->get();  

          echo $response;

        }

        public function reembolso_solicitante_buscadadoscontrato(Request $request) {

            $tiposervico = $request->get('tiposervico');
            $codigocontrato = $request->get('codigocontrato');


            $response = DB::table('PLCFULL.dbo.Jurid_Desp_Contrato')
            ->select('Valor', 'Aut_Desp', 'Desp_Tipo')  
            ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Codigo', $tiposervico)
            ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $codigocontrato)
            ->get();


            echo $response;
        }
        

        public function reembolso_solicitante_buscaduplicidade(Request $request) {

              $carbon= Carbon::now();

              $valor = $request->get('valor');
              $tiposervico = $request->get('tiposervico');
              $data = $request->get('data');
              $setor = $request->get('setor');

              //Verifica se encontra 
              $response = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
              ->where('Tipodeb', $tiposervico)
              ->where('PLCFULL.dbo.Jurid_Debite.ValorT', '=', $valor)
              ->where('PLCFULL.dbo.Jurid_Debite.Data', $data)
              ->count();

              //Se existir algum registro duplicado envia e-mail para os responsáveis
              if($response != 0) {


                //Envia e-mail para a revisão técnica copiando Gerente Financeiro(Douglas,Roberta,Daniele)
                $coordenadores = DB::table('dbo.users')
                ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
                ->where('dbo.profiles.id', '=', '20')
                ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                ->get();     
               
                foreach ($coordenadores as $data) {

                 $coordenador_email = $data->email;
                 Mail::to($coordenador_email)
                 ->cc('ronaldo.amaral@plcadvogados.com.br')
                 ->send(new SolicitacaoDuplicada());
                }

              }


               echo $response;


        }

        public function reembolso_solicitante_historico() {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'dbo.Financeiro_Reembolso_Matrix.observacao as Observacao',
                         'dbo.Jurid_Nota_Motivos.descricao as Motivo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                ->where('PLCFULL.dbo.Jurid_Debite.Advogado', Auth::user()->cpf)
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(6,7))
                ->get();

        
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Solicitante.historico', compact('dataehora','datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_solicitante_editado(Request $request) {

             $numerodebite = $request->get('numerodebite');
             $pasta = $request->get('pasta');
             $setor = $request->get('setor');
             $data = $request->get('data');
             $quantidade = $request->get('quantidade');
             $valor_unitario = str_replace (',', '.', str_replace ('.', '', $request->get('valor_unitario')));
             $valor_total = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_total')));
             $tiposervico = $request->get('tiposervico');
             $observacao = $request->get('observacao');
             $hist = $request->get('hist');
             $carbon= Carbon::now();
             $dataehora = $carbon->format('dmY His');


             //Atualiza debite
             DB::table('PLCFULL.dbo.Jurid_Debite')
             ->where('Numero', $numerodebite)  
             ->limit(1) 
             ->update(array('Hist' => $hist, 'Quantidade' => $quantidade, 'ValorT' => $valor_total ,'ValorUnitario_Adv' => $valor_unitario, 'ValorUnitarioCliente' => $valor_unitario, 'Valor_Adv' => $valor_total));

             //Atualiza matrix
             DB::table('Financeiro_Reembolso_Matrix')
             ->where('numerodebite', $numerodebite)  
             ->limit(1) 
             ->update(array('status_id' => '1', 'motivo_id' => '', 'observacao' => $observacao));

             //Grava na hist
             $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numerodebite, 'data' => $carbon, 'status_id' => '1');
             DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

             //Armazena no SFTP
             $image = $request->file('select_file');
             $new_name = $numerodebite . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
             $image->storeAs('reembolso', $new_name);

             //Grava no GED
             $values = array(
                'Tabela_OR' => 'Debite',
                'Codigo_OR' => $numerodebite,
                'Id_OR' => $numerodebite,
                'Descricao' => $image->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\reembolso/'.$new_name, 
                'Data' => $carbon,
                'Nome' => $image->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image->getClientOriginalExtension(),
                'Arq_Versao' => '2',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $observacao,
                'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
              DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

              $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
              ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
               'PLCFULL.dbo.Jurid_Debite.Pasta',
               'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
               'dbo.Financeiro_Reembolso_Status.Descricao as Status',
               'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
               'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
               'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
               'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
               'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
               'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
               'dbo.users.name as SolicitanteNome',
               'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
               'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
               'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
               'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
              ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
              ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
              ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
              ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
              ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
              ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
              ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
              ->limit(1)
              ->get();

        /////////////////////////////////////FOREACH COORDENADORES DO SETOR///////////////////////////
         $coordenadores = DB::table('dbo.users')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->select('dbo.users.id', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '20')
        ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
        ->get();     
       
        foreach ($coordenadores as $data) {

         $coordenador_id = $data->id;
         $coordenador_email = $data->email;

         Mail::to($coordenador_email)
         ->send(new SolicitacaoReembolsoCorrigida($datas));
 
         $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso editada.' ,'status' => 'A');
         DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
       /////////////////////////////////////FIM//////////////////////////////////////////////////////

       flash('Solicitação de reembolso editada com sucesso!')->success();
       return redirect()->route('Painel.Financeiro.Reembolso.Solicitante.index');



        }

        public function reembolso_solicitante_novasolicitacao(Request $request) {


             $carbon= Carbon::now();
             $codigopasta = $request->get('dado');
             $datahoje = $carbon->format('Y-m-d');    
             $dataehora = $carbon->format('d/m/Y H:i:s');
             $dataminimo = Carbon::now()->subDays(45);
             $dataminimo = $dataminimo->format('Y-m-d');  

             $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
             ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                     'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                     'PLCFULL.dbo.Jurid_Pastas.PRConta',
                     'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                     'PLCFULL.dbo.Jurid_Pastas.Advogado',
                     'dbo.users.name as Responsavel',
                     'PLCFULL.dbo.Jurid_Pastas.OutraParte',
                     'PLCFULL.dbo.Jurid_Pastas.Setor as SetorCodigo',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                     'PLCFULL.dbo.Jurid_Pastas.Unidade as UnidadeCodigo',
                     'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                     'PLCFULL.dbo.Jurid_Tribunais.Descricao as Tribunal',
                     'PLCFULL.dbo.Jurid_Varas.Descricao as Vara',
                     'PLCFULL.dbo.Jurid_Pastas.RefCliente',
                     'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                     'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                     'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                     'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                     'PLCFULL.dbo.Jurid_Contratos.Despesas as Status',
                     'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                     'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
             ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Pastas.Advogado', 'dbo.users.cpf')
             ->leftjoin('PLCFULL.dbo.Jurid_Tribunais', 'PLCFULL.dbo.Jurid_Pastas.Tribunal', 'PLCFULL.dbo.Jurid_Tribunais.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_Varas', 'PLCFULL.dbo.Jurid_Pastas.Varas', 'PLCFULL.dbo.Jurid_Varas.Codigo')
             ->where('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp','=',$codigopasta)
             ->orwhere('PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros', '=', $codigopasta)
             ->first();

             //Verifica se achou
             if($datas == null) {
                \Session::flash('message', ['msg'=>'Não foi possível encontrar nenhuma pasta com este código ou número de processo.', 'class'=>'red']);
                return redirect()->route('Painel.Financeiro.Reembolso.Solicitante.index');
              }

              $valor_disponivel = DB::table("dbo.Financeiro_Adiantamento_ValorReutilizar")
              ->select(DB::raw('sum(dbo.Financeiro_Adiantamento_ValorReutilizar.valor_disponivel)'))
              ->where('dbo.Financeiro_Adiantamento_ValorReutilizar.usuario_cpf', Auth::user()->cpf)
              ->value('dbo.Financeiro_Adiantamento_ValorReutilizar.valor_disponivel');

             $setor = $datas->SetorCodigo;

             $responsaveis = DB::table('dbo.users')
             ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
             ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
             ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
             ->select('dbo.users.id', 'dbo.users.name') 
             ->where('dbo.profiles.id', '=', '20')
             ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
             ->get();     
       
             $comarcas = DB::table('dbo.PesquisaPatrimonial_Cidades')
             ->join('dbo.Financeiro_Reembolso_UF', 'dbo.PesquisaPatrimonial_Cidades.uf_sigla', 'dbo.Financeiro_Reembolso_UF.UF')
             ->select('municipio')  
             ->get();
      
              $valor_unitario = DB::table('dbo.Financeiro_Reembolso_UF')->select('valor_km')->where('uf','=', $datas->UnidadeUF)->value('valr_km');
              $valor_unitario = number_format($valor_unitario, 2, '.', '');

              $contratocodigo = $datas->ContratoCodigo;

              $contratos = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
              ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'PLCFULL.dbo.Jurid_Desp_Contrato.Codigo')
              ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'Descricao', 'PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp as Status', 'PLCFULL.dbo.Jurid_Desp_Contrato.Valor as Valor', 'PLCFULL.dbo.Jurid_Desp_Contrato.Quantidade', 'PLCFULL.dbo.Jurid_Desp_Contrato.ValorCliente')  
              ->whereNull('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
              ->orwhere('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $contratocodigo)
              ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp', '!=', 'Bloqueada')
              ->orderby('Descricao', 'asc')
              ->get();
 

             //Verifico se informa no Advwin se este tipo de serviço foi gravado
             $despesas = DB::table('PLCFULL.dbo.Jurid_Contratos')->select('Despesas')->where('Numero','=', $contratocodigo)->value('Despesas');

                        //Despesas Livres
                        if($despesas == 0) {

                        $statuscontrato = 'Despesas livres';

                        $tiposservico = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                        ->select('Codigo', 'Descricao')  
                        ->where('Status','=','Ativo')
                        ->whereIn('PLCFULL.dbo.Jurid_TipoDebite.Codigo', array(1,2,6,11,12,15,16,18,19,21,25,26,29,030))
                        ->orderby('Descricao', 'asc')
                        ->get();
                                 
                       } 
                       //Todas livres respeitando a lista
                       else if($despesas == 1) {
                        $statuscontrato = 'Todas livres respeitando a lista';

                        $tiposservico = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                        ->select('Codigo', 'Descricao')  
                        ->where('Status','=','Ativo')
                        ->whereIn('PLCFULL.dbo.Jurid_TipoDebite.Codigo', array(1,2,6,11,12,15,16,18,19,21,25,26,29,030))
                        ->orderby('Descricao', 'asc')
                        ->get();

                        } 
                        //Todas bloqueadas respeitando a lista
                        else if($despesas == 2) {
                        $statuscontrato = 'Todas bloqueadas respeitando a lista';

                        $tiposservico = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                        ->join('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'PLCFULL.dbo.Jurid_Desp_Contrato.Codigo')
                        ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'Descricao')  
                        ->where('Status','=','Ativo')
                        ->whereIn('PLCFULL.dbo.Jurid_TipoDebite.Codigo', array(1,2,6,11,12,15,16,18,19,21,25,26,29,030))
                        ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $contratocodigo)
                        ->orderby('Descricao', 'asc')
                        ->get();

                        }
                        //Não cobrar, exceto a lista
                        else {
                        $statuscontrato = 'Não cobrar, exceto a lista';

                        $tiposservico = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                        ->select('Codigo', 'Descricao')  
                        ->where('Status','=','Ativo')
                        ->whereIn('PLCFULL.dbo.Jurid_TipoDebite.Codigo', array(1,2,6,11,12,15,16,18,19,21,25,26,29,030))
                        ->orderby('Descricao', 'asc')
                        ->get();
                        }

                
                 $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                        ->where('status', 'A')
                        ->where('destino_id','=', Auth::user()->id)
                        ->count();
                      
                 $notificacoes = DB::table('dbo.Hist_Notificacao')
                     ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'Hist_Notificacao.status', 'dbo.users.*')  
                     ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                     ->where('dbo.Hist_Notificacao.status','=','A')
                     ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                     ->get();
               
               
                return view('Painel.Financeiro.Reembolso.Solicitante.dados', compact('despesas','valor_unitario','comarcas','contratos','dataminimo','responsaveis','valor_disponivel','carbon','statuscontrato','datahoje','dataehora','datas','tiposservico','totalNotificacaoAbertas', 'notificacoes'));
                          
        }

        public function reembolso_solicitante_store(Request $request) {

          $carbon= Carbon::now();
          $dataehora = $carbon->format('Y-m-d');
          $dataehoraBR = $carbon->format('d-m-Y H:i:s');
          
          //Pega os dados do form 
          $pasta = $request->get('pasta');
          $numeroprocesso = $request->get('numeroprocesso');
          $descricaopasta = $request->get('descricaopasta');
          $setordescricao = $request->get('setordescricao');
          $setor = $request->get('setor');
          $unidadedescricao = $request->get('unidadedescricao');
          $unidade = $request->get('unidade');
          $grupo = $request->get('grupo');
          $clienterazao = $request->get('clienterazao');
          $cliente = $request->get('cliente');
          $tiposervico = $request->get('tiposervico');
          $data = $request->get('data');
          $quantidade = str_replace (',', '.', str_replace ('.', '', $request->get('quantidade')));
          $valor_unitario = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_unitario')));
          $valor_total = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_total')));
          $observacao = str_replace ('&amp;', '&', $request->get('observacao'));
          $status = $request->get('status');
          $prconta = $request->get('prconta');
          $numeroprocesso = $request->get('numeroprocesso');
          $statuscontrato = $request->get('statuscontrato');
          $comarcaorigem = $request->get('comarcaorigem');
          $comarcadestino = $request->get('comarcadestino');
          $tipocontrato = $request->get('tipocontrato');
          $observacaoadicionais = $request->get('observacaoadicionais');


          $outraparte = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('OutraParte')->where('Codigo_Comp','=', $pasta)->value('OutraParte');

          //Gera um novo numero pro Debite
          $ultimonumero = Correspondente::orderBy('Numero', 'desc')->value('Numero'); 
          $numero = $ultimonumero + 1;

          $verificapermissao =  DB::table('dbo.users')
          ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
          ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
          ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
          ->where('dbo.profiles.id', '=', '17')
          ->where('dbo.profile_user.user_id', Auth::user()->id)
          ->first();  

          //Se o solicitante for um Advogado, deve passar pela revisão técnica
          if($verificapermissao != null) {

          $model2 = new Correspondente();
          $model2->Numero =  $numero;
          $model2->Advogado = Auth::user()->cpf; 
          $model2->Cliente = $cliente; 
          $model2->Data = $data;
          $model2->Tipo = 'D';
          $model2->Obs =  'Número da solicitação: ' . $numero . ' ' . $observacao . ' ' . $observacaoadicionais;
          if($tipocontrato == 2 || $tipocontrato == 3) {
          $model2->Status = '2';
          } else {
          $model2->Status = '4';
          }
          $model2->Hist = 'Número da solicitação: ' . $numero . ' Solicitação de reembolso inserida pelo(a): '. Auth::user()->name .' no Portal PL&C no valor total de: R$ ' . $valor_total . ' na data: ' . $carbon->format('d-m-Y H:i:s');
          $model2->ValorT = $valor_total;
          $model2->Usuario = 'portal.plc';
          $model2->DebPago = 'N';
          $model2->TipoDeb = $tiposervico; 
          $model2->AdvServ = Auth::user()->cpf;
          $model2->Setor = $setor;
          $model2->Pasta = $pasta;
          $model2->Unidade = $unidade;
          $model2->PRConta = $prconta;
          $model2->Valor_Adv = $valor_total;
          $model2->Quantidade = $quantidade; 
          $model2->ValorUnitario_Adv = $valor_unitario;
          $model2->ValorUnitarioCliente = $valor_unitario;
          $model2->Revisado_DB = '0';
          $model2->moeda = 'R$';
          $model2->save();

          //Foreach Anexos
          $image = $request->file('select_file');

          foreach($image as $index => $image) {

          $new_name = $numero . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
          $image->storeAs('reembolso', $new_name);

          Storage::disk('reembolso-local')->put($new_name, File::get($image));

          //Grava no GED
          $values = array(
                'Tabela_OR' => 'Debite',
                'Codigo_OR' => $numero,
                'Id_OR' => $numero,
                'Descricao' => $image->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\reembolso/'.$new_name, 
                'Data' => $carbon,
                'Nome' => $image->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $observacao . ' ' . $observacaoadicionais,
                'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
          DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

          }

         if($tipocontrato == 2 || $tipocontrato == 3) {
         $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '1', 'observacao' => $observacao . ' ' . $observacaoadicionais, 'status_cobravel' => 'Não reembolsável', 'comarcaorigem' => $comarcaorigem, 'comarcadestino' => $comarcadestino, 'observacoesadicionais' => $observacaoadicionais);
         DB::table('dbo.Financeiro_Reembolso_Matrix')->insert($values);
         } else {
         $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '1', 'observacao' => $observacao . ' ' . $observacaoadicionais, 'status_cobravel' => 'Reembolsável pelo cliente', 'comarcaorigem' => $comarcaorigem, 'comarcadestino' => $comarcadestino, 'observacoesadicionais' => $observacaoadicionais);
         DB::table('dbo.Financeiro_Reembolso_Matrix')->insert($values);       
        }

         //Grava na tabela Hist
         $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '1');
         DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

         //Envia notificação para o proprio solicitante
         $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '8', 'obs' => 'Reembolso: Nova solicitação de reembolso gerada.' ,'status' => 'A');
         DB::table('dbo.Hist_Notificacao')->insert($values4);

         $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
         ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                  'PLCFULL.dbo.Jurid_Debite.Pasta',
                  'PLCFULL.dbo.Jurid_Debite.Quantidade',
                  'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                  'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                  'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                  'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                  'PLCFULL.dbo.Jurid_Debite.Data as DataSolicitacao',
                  'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                  'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                  'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
         ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
         ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
         ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
         ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
         ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
         ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numero)
         ->get();

         /////////////////////////////////////FOREACH COORDENADORES DO SETOR///////////////////////////
         $coordenadores = DB::table('dbo.users')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '20')
        ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
        ->get();     
       
        foreach ($coordenadores as $data) {

         $coordenador_id = $data->id;
         $coordenador_email = $data->email;
         $coordenador_nome = $data->nome;

         Mail::to($coordenador_email)
         ->cc(Auth::user()->email)
         ->send(new SolicitacaoReembolso($datas, $coordenador_nome));
 
         $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '8', 'obs' => 'Reembolso: Nova solicitação de reembolso gerada.' ,'status' => 'A');
         DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        /////////////////////////////////////FIM//////////////////////////////////////////////////////

          }
          //Se não, vai direto pro financeiro
          else {

                $model2 = new Correspondente();
                $model2->Numero =  $numero;
                $model2->Advogado = Auth::user()->cpf; 
                $model2->Cliente = $cliente; 
                $model2->Data = $data;
                $model2->Tipo = 'D';
                $model2->Obs =  'Número da solicitação: ' . $numero . ' ' . $observacao . ' ' . $observacaoadicionais;
                if($tipocontrato == 2 || $tipocontrato == 3) {
                $model2->Status = '2';
                } else {
                $model2->Status = '4';
                }
                $model2->Hist = 'Número da solicitação: ' . $numero . ' Solicitação de reembolso inserida pelo(a): '. Auth::user()->name .' no Portal PL&C no valor total de: R$ ' . $valor_total . ' na data: ' . $carbon->format('d-m-Y H:i:s');
                $model2->ValorT = $valor_total;
                $model2->Usuario = 'portal.plc';
                $model2->DebPago = 'N';
                $model2->TipoDeb = $tiposervico; 
                $model2->AdvServ = Auth::user()->cpf;
                $model2->Setor = $setor;
                $model2->Pasta = $pasta;
                $model2->Unidade = $unidade;
                $model2->PRConta = $prconta;
                $model2->Valor_Adv = $valor_total;
                $model2->Quantidade = $quantidade; 
                $model2->ValorUnitario_Adv = $valor_unitario;
                $model2->ValorUnitarioCliente = $valor_unitario;
                $model2->Revisado_DB = '1';
                $model2->moeda = 'R$';
                $model2->save();
      
                //Foreach Anexos
                $image = $request->file('select_file');
      
                foreach($image as $index => $image) {
      
                $new_name = $numero . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
                $image->storeAs('reembolso', $new_name);
      
                Storage::disk('reembolso-local')->put($new_name, File::get($image));
      
                //Grava no GED
                $values = array(
                      'Tabela_OR' => 'Debite',
                      'Codigo_OR' => $numero,
                      'Id_OR' => $numero,
                      'Descricao' => $image->getClientOriginalName(),
                      'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\reembolso/'.$new_name, 
                      'Data' => $carbon,
                      'Nome' => $image->getClientOriginalName(),
                      'Responsavel' => 'portal.plc',
                      'Arq_tipo' => $image->getClientOriginalExtension(),
                      'Arq_Versao' => '1',
                      'Arq_Status' => 'Guardado',
                      'Arq_usuario' => 'portal.plc',
                      'Arq_nick' => $new_name,
                      'Obs' => $observacao . ' ' . $observacaoadicionais,
                      'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
      
                }
      
               if($tipocontrato == 2 || $tipocontrato == 3) {
               $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '2', 'observacao' => $observacao . ' ' . $observacaoadicionais, 'status_cobravel' => 'Não reembolsável', 'comarcaorigem' => $comarcaorigem, 'comarcadestino' => $comarcadestino, 'observacoesadicionais' => $observacaoadicionais);
               DB::table('dbo.Financeiro_Reembolso_Matrix')->insert($values);
               } else {
               $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '2', 'observacao' => $observacao . ' ' . $observacaoadicionais, 'status_cobravel' => 'Reembolsável pelo cliente', 'comarcaorigem' => $comarcaorigem, 'comarcadestino' => $comarcadestino, 'observacoesadicionais' => $observacaoadicionais);
               DB::table('dbo.Financeiro_Reembolso_Matrix')->insert($values);       
              }
      
               //Grava na tabela Hist
               $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '2');
               DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);
      
               //Envia notificação para o proprio solicitante
               $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '8', 'obs' => 'Reembolso: Nova solicitação de reembolso gerada.' ,'status' => 'A');
               DB::table('dbo.Hist_Notificacao')->insert($values4);
      
               $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
               ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                'PLCFULL.dbo.Jurid_Debite.Pasta',
                'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                'dbo.users.name as SolicitanteNome',
                'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
               ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
               ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
               ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
               ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
               ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
               ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
               ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
               ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
               ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numero)
               ->limit(1)
               ->get();
      
                //Notificação para o Financeiro(235)
                $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => '235', 'tipo' => '8', 'obs' => 'Reembolso: Nova solicitação de reembolso cadastrada no portal.', 'status' => 'A');
                DB::table('dbo.Hist_Notificacao')->insert($values3);
            
                Mail::to('ronaldo.amaral@plcadvogados.com.br')
                ->cc(Auth::user()->email)
                ->send(new FinanceiroAprovar($datas));
      
          }


       flash('Nova solicitação de reembolso cadastrada com sucesso!')->success();
       return redirect()->route('Painel.Financeiro.Reembolso.Solicitante.index');
   
        }

        public function reembolso_cancelar(Request $request) {

              $numerodebite = $request->get('numerodebite');
              $codigopasta = $request->get('pasta');
              $setor = $request->get('setor');
              $data = $request->get('data');
              $valortotal = $request->get('valor');
              $tiposervico = $request->get('tiposervico');
              $hist = $request->get('hist');
              $motivodescricao = $request->get('motivodescricao');
              $carbon= Carbon::now();

              //Coloca o status cancelado no Debite
              DB::table('PLCFULL.dbo.Jurid_Debite')
              ->where('Numero', $numerodebite)  
              ->limit(1) 
              ->update(array('Status' => '3',  'Hist' => $hist . $motivodescricao));

              //Coloca o status cancelado na tabela interna
              DB::table('dbo.Financeiro_Reembolso_Matrix')
              ->where('numerodebite', $numerodebite)  
              ->limit(1) 
              ->update(array('status_id' => '6'));

              //Grava na Hist
              $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numerodebite, 'data' => $carbon, 'status_id' => '6');
              DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

              //Manda e-mail
              $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
              ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
               'PLCFULL.dbo.Jurid_Debite.Pasta',
               'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
               'dbo.Financeiro_Reembolso_Status.Descricao as Status',
               'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
               'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
               'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
               'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
               'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
               'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
               'dbo.users.name as SolicitanteNome',
               'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
               'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
               'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
               'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
              ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
              ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
              ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
              ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
              ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
              ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
              ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
              ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
              ->limit(1)
              ->get();

              Mail::to(Auth::user()->email)
              ->send(new SolicitacaoCancelada($datas, $motivodescricao));

              return redirect()->route('Painel.Financeiro.Reembolso.Solicitante.index');    
        }


        public function reembolso_revisao_index() {

                $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'dbo.users.cpf' )
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')
                ->select(
                        'dbo.users.cpf as SolicitanteCodigo',
                        'dbo.users.email as SolicitanteEmail',
                        'dbo.users.name as SolicitanteNome',
                        DB::raw('sum(ValorT ) as Valor'),
                        DB::raw('count(*) as QuantidadeSolicitacoes'))
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(1))
                ->groupby('dbo.users.cpf','dbo.users.email', 'dbo.users.name')
                ->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id) 
                ->get();    
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Revisao.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        } 

        public function reembolso_prestacaoconta_index() {

                $carbon= Carbon::now();
                $datahoje = $carbon->format('Y-m-d');    
          
                $gruposcliente = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
                ->orderby('Descricao', 'asc')
                ->get();

                $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
                ->where('DaEmpresa', '0')
                ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
                ->where('Status', '1')
                ->orderby('Descricao', 'ASC')
                ->get();   
            
          
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
          
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
                ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->get();
          
          
                return view('Painel.Financeiro.Reembolso.PrestacaoConta.index', compact('datahoje','gruposcliente','bancos','totalNotificacaoAbertas', 'notificacoes'));
        }

        public function reembolso_prestacaoconta_buscafatura(Request $request) {

                $clientecodigo = $request->get('cliente');
                $datainicio = $request->get('datainicio');
                $datafim = $request->get('datafim');

                $response = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->leftjoin('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.Fatura', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
                ->select(
                         'PLCFULL.dbo.Jurid_Debite.Fatura',
                         )
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $clientecodigo)
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->where('PLCFULL.dbo.Jurid_Debite.Status', '=', '1')
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                // ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(7))
                ->whereBetween('PLCFULL.dbo.Jurid_ContaPr.Dt_Digit', [$datainicio, $datafim])
                ->groupby('PLCFULL.dbo.Jurid_Debite.Fatura')
                ->get();
                
                echo $response;


        }

        public function reembolso_prestacaoconta_direto($fatura, $banco) {

                $carbon= Carbon::now();
                $banco_descricao = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.Descricao')->where('Codigo', $banco)->value('PLCFULL.dbo.Jurid_Banco.Descricao');
                $banco_cpfcnpj = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.cnpj_empresa')->where('Codigo', $banco)->value('PLCFULL.dbo.Jurid_Banco.cnpj_empresa');
                $DataVencimento = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim')->where('Numdoc', $fatura)->where('Tipo', 'R')->value('PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim');
                $codigo_boleto = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('PLCFULL.dbo.Jurid_ContaPr.codigo_boleto')->where('Numdoc', $fatura)->where('Tipo', 'R')->value('PLCFULL.dbo.Jurid_ContaPr.codigo_boleto');
                $cliente = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('PLCFULL.dbo.Jurid_ContaPr.Cliente')->where('Numdoc', $fatura)->where('Tipo', 'R')->value('PLCFULL.dbo.Jurid_ContaPr.Cliente');
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
            
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
                ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->get();
            
                $datas = DB::table('PLCFULL.dbo.Jurid_CliFor')
                ->select('PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
                         'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
                         'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
                         'PLCFULL.dbo.Jurid_CliFor.Cep as ClienteCEP',
                         'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
                         'PLCFULL.dbo.Jurid_CliFor.Unidade as ClienteUnidade',
                         )
                ->where('PLCFULL.dbo.Jurid_CliFor.Codigo', $cliente)
                ->first();

                $valortotal = DB::table('PLCFULL.dbo.Jurid_ContaPr')
                ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContaPr.Valor ) as valor'))
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                ->where('PLCFULL.dbo.Jurid_ContaPr.Numdoc', $fatura)
                ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContaPr.Valor)'));

                $extenso = new NumeroPorExtenso;
                $extenso = $extenso->converter($valortotal);

                $debites = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->select('PLCFULL.dbo.Jurid_Debite.Numero',
                                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                                         'PLCFULL.dbo.Jurid_Debite.Data',
                                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                                         'PLCFULL.dbo.Jurid_Advogado.Nome as AdvogadoNome',
                                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso')
                ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->join('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.fatura', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                // ->join('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $cliente)
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', 'S')
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                ->where('PLCFULL.dbo.Jurid_Debite.Fatura', $fatura)
                ->where('PLCFULL.dbo.Jurid_Debite.ValorT', '!=', '0.00')
                // ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.assertiva', '!=', 'SIM')
                // ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '1')
                ->get();

                $arquivos = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
                ->join('PLCFULL.dbo.Jurid_Debite', 'PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', 'PLCFULL.dbo.Jurid_Debite.Numero')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.fatura', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $cliente)
                ->where('PLCFULL.dbo.Jurid_Debite.Fatura', $fatura)
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                ->where('PLCFULL.dbo.Jurid_Ged_Main.Tabela_OR', 'Debite')
                ->where('PLCFULL.dbo.Jurid_Debite.ValorT', '!=', '0.00')
                ->get();


                return view('Painel.Financeiro.Reembolso.PrestacaoConta.relatorio', compact('carbon','banco','codigo_boleto','banco_descricao','DataVencimento','banco_cpfcnpj','valortotal','extenso','fatura','totalNotificacaoAbertas', 'notificacoes','datas','arquivos', 'debites'));
                
        }

        public function reembolso_prestacaoconta_relatorio(Request $request) {

                $grupocliente = $request->get('grupocliente');
                $cliente = $request->get('cliente');
                $fatura = $request->get('fatura');
                $banco = $request->get('banco');
                // $datainicio = $request->get('datainicio');
                // $datafim = $request->get('datafim');
                $carbon= Carbon::now();

                $banco_descricao = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.Descricao')->where('Codigo', $banco)->value('PLCFULL.dbo.Jurid_Banco.Descricao');
                $banco_cpfcnpj = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.cnpj_empresa')->where('Codigo', $banco)->value('PLCFULL.dbo.Jurid_Banco.cnpj_empresa');
                $DataVencimento = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim')->where('Numdoc', $fatura)->where('Tipo', 'R')->value('PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim');
                $codigo_boleto = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('PLCFULL.dbo.Jurid_ContaPr.codigo_boleto')->where('Numdoc', $fatura)->where('Tipo', 'R')->value('PLCFULL.dbo.Jurid_ContaPr.codigo_boleto');

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
            
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
                ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->get();
            
                $datas = DB::table('PLCFULL.dbo.Jurid_CliFor')
                ->select('PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
                         'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
                         'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
                         'PLCFULL.dbo.Jurid_CliFor.Cep as ClienteCEP',
                         'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
                         'PLCFULL.dbo.Jurid_CliFor.Unidade as ClienteUnidade',
                         )
                ->where('PLCFULL.dbo.Jurid_CliFor.Codigo', $cliente)
                ->first();

                $valortotal = DB::table('PLCFULL.dbo.Jurid_ContaPr')
                ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContaPr.Valor ) as valor'))
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                ->where('PLCFULL.dbo.Jurid_ContaPr.Numdoc', $fatura)
                ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContaPr.Valor)'));

                $extenso = new NumeroPorExtenso;
                $extenso = $extenso->converter($valortotal);

                $debites = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->select('PLCFULL.dbo.Jurid_Debite.Numero',
                                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                                         'PLCFULL.dbo.Jurid_Debite.Data',
                                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                                         'PLCFULL.dbo.Jurid_Advogado.Nome as AdvogadoNome',
                                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso')
                ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->join('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.fatura', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->join('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->join('PLCFULL.dbo.Jurid_Ged_Main', 'PLCFULL.dbo.Jurid_Debite.Numero', 'PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $cliente)
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', 'S')
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                ->where('PLCFULL.dbo.Jurid_Debite.Fatura', $fatura)
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '1')
                ->groupby('PLCFULL.dbo.Jurid_Debite.Numero', 'PLCFULL.dbo.Jurid_CliFor.Razao', 'PLCFULL.dbo.Jurid_Debite.Data',
                'PLCFULL.dbo.Jurid_TipoDebite.Descricao', 'PLCFULL.dbo.Jurid_Debite.ValorT', 'PLCFULL.dbo.Jurid_Debite.Obs',
                'PLCFULL.dbo.Jurid_Setor.Descricao', 'PLCFULL.dbo.Jurid_Advogado.Nome', 'PLCFULL.dbo.Jurid_Debite.Pasta',
                'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros')
                ->get();

                $arquivos = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
                ->join('PLCFULL.dbo.Jurid_Debite', 'PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', 'PLCFULL.dbo.Jurid_Debite.Numero')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.fatura', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $cliente)
                ->where('PLCFULL.dbo.Jurid_Debite.Fatura', $fatura)
                ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', '=', 'R')
                ->where('PLCFULL.dbo.Jurid_Ged_Main.Tabela_OR', 'Debite')
                ->get();



                return view('Painel.Financeiro.Reembolso.PrestacaoConta.relatorio', compact('carbon','banco','codigo_boleto','banco_descricao','DataVencimento','banco_cpfcnpj','valortotal','extenso','fatura','totalNotificacaoAbertas', 'notificacoes','datas','arquivos', 'debites'));
        }

        public function reembolso_prestacaoconta_baixarboleto($codigo_cliente, $fatura) {

                return Storage::disk('reembolso-local')->download($codigo_cliente.'_'.$fatura.'.pdf');

        }

        public function reembolso_prestacaoconta_gerarboleto(Request $request) {

                $carbon= Carbon::now();
                $fatura = $request->get('fatura');
                $datavencimento = $request->get('datavencimento');
                $datavencimento = Carbon::parse($datavencimento);
                $datavencimentobr = $request->get('datavencimentobr');


                $clientecodigo = $request->get('clientecodigo');
                $clienterazao = $request->get('clienterazao');
                $codigobanco = $request->get('codigobanco');
                $clienteendereco = $request->get('clienteendereco');
                $clientebairro = $request->get('clientebairro');
                $clientecidade = $request->get('clientecidade');
                $clientecep = $request->get('clientecep');
                $clienteuf = $request->get('clienteuf');
                $clienteunidade = $request->get('clienteunidade');
                $valor =  str_replace (',', '.', str_replace ('.', ',', $request->get('valor')));

                //Gera o boleto
                $agencia = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.Agencia')->where('Codigo', $codigobanco)->value('PLCFULL.dbo.Jurid_Banco.Agencia');
                $conta = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.Conta')->where('Codigo', $codigobanco)->value('PLCFULL.dbo.Jurid_Banco.Conta');
                $conta = str_replace ('-', '', $conta);
                $contasemdigito = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.b_conta')->where('Codigo', $codigobanco)->value('PLCFULL.dbo.Jurid_Banco.b_conta');
                $carteira = DB::table('PLCFULL.dbo.Jurid_Banco')->select('PLCFULL.dbo.Jurid_Banco.b_carteira')->where('Codigo', $codigobanco)->value('PLCFULL.dbo.Jurid_Banco.b_carteira');

                $unidadecnpj = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.CNPJ')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.CNPJ');
                $unidaderazao = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.Razao')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.Razao');
                $unidadecep = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.CEP')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.CEP');
                $unidadeendereco = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.Endereco')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.Endereco');
                $unidadebairro = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.Bairro')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.Bairro');
                $unidadeuf = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.UF')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.UF');
                $unidadecidade = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('PLCFULL.dbo.Jurid_Unidade.Cidade')->where('Codigo', $clienteunidade)->value('PLCFULL.dbo.Jurid_Unidade.Cidade');

                $nossonumero = DB::table('PLCFULL.dbo.Jurid_Banco')->select('NossoNumeroGer')->where('Codigo', '=', $codigobanco)->value('NossoNumeroGer');
                $novonumero = '00000'.$nossonumero + 1;

                DB::table('PLCFULL.dbo.Jurid_Banco')
                ->where('Codigo', '=', $codigobanco)  
                ->limit(1) 
                ->update(array('NossoNumeroGer' => $novonumero));

                $values = array(
                'codigo_conta_bancaria' => $codigobanco,
                'nosso_numero' => $novonumero,
                'especie_doc' => 'DS',
                'especie_moeda' => 'R$',
                'aceite' => 'NAO',
                'carteira' => $carteira,
                'num_doc' => $fatura,
                'valor_boleto' => $valor,
                'valor_juros' => '0.00',
                'valor_multa' => '0.00',
                'valor_abatimento' => '0.00',
                'valor_desconto' => '0.00',
                'valor_liquido' => $valor,
                'data_emissao' => $carbon,
                'data_vencimento' => $datavencimento,
                'codigo_cliente' => $clientecodigo,
                'nome_sacado' => $clienterazao,
                'cpfcnpj_sacado' => $clientecodigo,
                'endereco_sacado' => $clienteendereco,
                'bairro_sacado' => $clientebairro,
                'cidade_sacado' => $clientecidade,
                'cep_sacado' => $clientecep,
                'uf_sacado' => $clienteuf,
                'layout_impressao' => '0',
                'usuario' => 'portal.plc',
                'situacao' => '0',
                'tipo_boleto' => '0',
                'unidade' => $clienteunidade);
                 DB::table('PLCFULL.dbo.Jurid_Boleto')->insert($values);  

                //Associa na CPR
                $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')->where('num_doc', $fatura)->orderBy('codigo_boleto', 'desc')->value('codigo_boleto');

                DB::table('PLCFULL.dbo.Jurid_ContaPr')
                ->where('Numdoc', $fatura)
                ->where('Tipo', 'R')  
                ->limit(1) 
                ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));

                $values = array(
                        'codigo_boleto' => $codigoboleto,
                        'codigo_cpr' => $fatura);
                DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 

                $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
                $beneficiario->setDocumento($unidadecnpj)
                  ->setNome($unidaderazao)
                  ->setCep($unidadecnpj)
                  ->setEndereco($unidadeendereco)
                  ->setBairro($unidadebairro)
                  ->setUf($unidadeuf)
                  ->setCidade($unidadecidade);

                $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
                $pagador->setDocumento($clientecodigo)
                    ->setNome($clienterazao)
                    ->setCep($clientecep)
                    ->setEndereco($clienteendereco)
                    ->setBairro($clientebairro)
                    ->setUf($clienteuf)
                    ->setCidade($clientecidade);   
                    
                $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
                [
               'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
               'dataVencimento'         => Carbon::parse($datavencimento),
               'valor'                  => $valor,
               'multa'                  => false,
               'juros'                  => false,
               'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
               'numero'                 => $novonumero,
               'numeroDocumento'        => $fatura,
               'pagador'                => $pagador,
               'beneficiario'           => $beneficiario,
               'diasBaixaAutomatica'    => false,
               'carteira'               => $carteira,
               'agencia'                => $agencia,
               'conta'                  => $contasemdigito,
               'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
               'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
               'aceite'                 => 'N',
               'especieDoc'             => 'DS',
              ]
        );  
                $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
                $pdf->addBoleto($boleto);
                $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'public/imgs/reembolso/'.$clientecodigo. '_' . $fatura.'.pdf');

                //Grava na GED pra mostrar no relatório de prestação de conta 
                $values = array(
                        'Tabela_OR' => 'CPR',
                        'Codigo_OR' => $fatura,
                        'Id_OR' => $fatura,
                        'Descricao' => 'Boleto de reembolso gerado pelo portal plc referente a fatura: ' . $fatura,
                        'Link' => '\\\192.168.1.65\advwin\GED\Vault$\portal\reembolso/' . $clientecodigo .'_'.$fatura, 
                        'Data' => $carbon,
                        'Nome' => $clientecodigo.'_'.$fatura,
                        'Responsavel' => 'portal.plc',
                        'Arq_tipo' => 'pdf',
                        'Arq_Versao' => '1',
                        'Arq_Status' => 'Guardado',
                        'Arq_usuario' => 'portal.plc',
                        'Arq_nick' => $clientecodigo.'_'.$fatura,
                        'Obs' => 'Boleto de reembolso gerado pelo portal plc referente a fatura: ' . $fatura,
                        'id_pasta' => '');
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);

                //Retorna para a route
                // return Storage::disk('reembolso-local')->download($clientecodigo.'_'.$fatura.'.pdf');

                \Session::flash('message', ['msg'=>'Deseja imprimir o boleto gerado ao cliente?', 'class'=>'green']);

                return redirect()->route('Painel.Financeiro.Reembolso.PrestacaoConta.index');

                // return redirect()->route('Painel.Financeiro.Reembolso.PrestacaoConta.relatorio', ['fatura'=>$fatura, 'cliente'=>$clientecodigo, 'banco' => $codigobanco]);
                // return back()->withInput(['fatura' => 'fatura']);  // L5.5


        }

        public function reembolso_solicitante_index() {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("dbo.Financeiro_Reembolso_Matrix")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'dbo.Financeiro_Reembolso_Matrix.observacao as Observacao',
                         'dbo.Jurid_Nota_Motivos.id as MotivoID',
                         'dbo.Jurid_Nota_Motivos.descricao as Motivo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('PLCFULL.dbo.Jurid_Debite', 'dbo.Financeiro_Reembolso_Matrix.numerodebite', 'PLCFULL.dbo.Jurid_Debite.Numero')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                ->where('PLCFULL.dbo.Jurid_Debite.Advogado', Auth::user()->cpf)
                ->where('PLCFULL.dbo.Jurid_Debite.Tipo', 'D')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(1,2,3,4,5))
                ->get();

                //Verifico se o solicitante possui algum valor para prestação de conta
                $debito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
                ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
                ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
                ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
                ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', Auth::user()->cpf)
                ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
                ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
                    
                $credito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
                ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
                ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
                ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
                ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', Auth::user()->cpf)
                ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
                ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
                  
                $valor_aguardandoprestacao =  $credito - $debito;

                $valor_aguardandoprestacao = number_format($valor_aguardandoprestacao, 2, '.', '');


                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Solicitante.index', compact('carbon','valor_aguardandoprestacao','datas','dataehora','totalNotificacaoAbertas', 'notificacoes')); 

        }


        public function reembolso_revisao_solicitacoessocio($solicitante_codigo) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                         'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Matrix.status_cobravel as statuscontrato',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_Debite.Tipodeb as TipoDebiteCodigo',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
                         'dbo.Financeiro_Reembolso_Matrix.visualizada',
                         'dbo.users.id as SolicitanteID',
                         'dbo.users.email as SolicitanteEmail',
                         'dbo.users.cpf as SolicitanteCPF',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                         'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                         'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(1))
                ->where('PLCFULL.dbo.Jurid_Debite.Advogado', $solicitante_codigo)
                ->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id) 
                ->get();

                $motivos = DB::table('dbo.Jurid_Nota_Motivos')
                ->where('ativo','=','S')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Revisao.solicitacoessocio', compact('motivos','solicitante_codigo','datas', 'dataehora','totalNotificacaoAbertas', 'notificacoes')); 
        }

        public function reembolso_revisao_revisar($numerodebite) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                //Update Debite informando que foi visualizado (Caso queira da aprovação em massa)
                DB::table('dbo.Financeiro_Reembolso_Matrix')
                ->where('numerodebite', $numerodebite)  
                ->limit(1) 
                ->update(array('visualizada' => 'S'));

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                         'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Matrix.status_cobravel as statuscontrato',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_Debite.Tipodeb as TipoDebiteCodigo',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
                         'dbo.Financeiro_Reembolso_Matrix.visualizada',
                         'dbo.Financeiro_Reembolso_Matrix.status_cobravel',
                         'dbo.users.id as SolicitanteID',
                         'dbo.users.email as SolicitanteEmail',
                         'dbo.users.cpf as SolicitanteCPF',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                         'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                         'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                ->first();

                $contratocodigo = $datas->ContratoCodigo;
       
                $contratos = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'PLCFULL.dbo.Jurid_Desp_Contrato.Codigo')
                ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'Descricao', 'PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp as Status', 'PLCFULL.dbo.Jurid_Desp_Contrato.Valor as Valor', 'PLCFULL.dbo.Jurid_Desp_Contrato.Quantidade', 'PLCFULL.dbo.Jurid_Desp_Contrato.ValorCliente')  
                ->whereNull('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
                ->orwhere('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $contratocodigo)
                ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp', '!=', 'Bloqueada')
                ->orderby('Descricao', 'asc')
                ->get();


                $historicos = DB::table('dbo.Financeiro_Reembolso_Hist')
                ->select('dbo.users.name as nome', 'dbo.Financeiro_Reembolso_Hist.data', 'dbo.Financeiro_Reembolso_Status.descricao as status')  
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Hist.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('dbo.users', 'dbo.Financeiro_Reembolso_Hist.user_id', 'dbo.users.id')
                ->where('dbo.Financeiro_Reembolso_Hist.numerodebite','=', $numerodebite)
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();

                $motivos = DB::table('dbo.Jurid_Nota_Motivos')
                ->where('ativo','=','S')
                ->get();

                return view('Painel.Financeiro.Reembolso.Revisao.revisar', compact('motivos','datas', 'contratos','historicos','dataehora','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_revisao_historico() {

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'dbo.users.name as Solicitante',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->join('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(2,3,4,5,6,7))
                ->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id) 
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Revisao.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 
        }

        

        public function reembolso_revisao_revisado(Request $request) {

                $carbon= Carbon::now();
                $numerodebite = $request->get('numerodebite');
                $prconta = $request->get('prconta');
                $pasta = $request->get('pasta');
                $numeroprocesso = $request->get('numeroprocesso');
                $descricaopasta = $request->get('descricaopasta');
                $solicitantenome = $request->get('solicitantenome');
                $solicitanteid = $request->get('solicitanteid');
                $solicitanteemail = $request->get('solicitanteemail');
                $solicitantecpf = $request->get('solicitantecpf');
                $setordescricao = $request->get('setordescricao');
                $setor = $request->get('setor');
                $unidadedescricao = $request->get('unidadedescricao');
                $unidade = $request->get('unidade');
                $grupo = $request->get('grupo');
                $clienterazao = $request->get('clienterazao');
                $cliente = $request->get('cliente');
                $tiposervico = $request->get('tiposervico');
                $dataservico = $request->get('dataservico');
                $datasolicitacao = $request->get('datasolicitacao');
                $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));
                $statuspasta = $request->get('statuspasta');
                $contrato = $request->get('contratocodigo');
                $statusescolhido = $request->get('statusescolhido');
                $motivo = $request->get('motivo');
                $motivodescricao = $request->get('motivodescricao');
                $observacao = $request->get('observacao');
                $hist = $request->get('hist');
                $histreprovada = $request->get('histreprovada');
                $histcancelada = $request->get('histcancelada');


                //Se foi aprovado 
                if($statusescolhido == "aprovar") {

                        //Update Jurid_Debite
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->where('Numero', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('Revisado_DB' => '1', 'Obs' => $observacao, 'Hist' => $hist));

                        //Update Financeiro_Reembolso_Matrix
                        DB::table('dbo.Financeiro_Reembolso_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '2', 'observacao' => $observacao));

                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                        'numerodebite' => $numerodebite, 
                                        'data' => $carbon, 
                                        'status_id' => '2');
                        DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                        ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();


                        //Envia notificação e e-mail para o financeiro copiando o solicitante
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso aprovada pela revisão técnica.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);

                        //Notificação para o Financeiro(235)
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => '235', 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso aprovada pela revisão técnica.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);

                        Mail::to('ronaldo.amaral@plcadvogados.com.br')
                        ->cc($solicitanteemail)
                        ->send(new FinanceiroAprovar($datas));
                
                }
                //Se foi reprovado 
                else if($statusescolhido == "reprovar") {

                         //Update Hist
                         DB::table('PLCFULL.dbo.Jurid_Debite')
                         ->where('Numero', '=' ,$numerodebite) 
                         ->limit(1) 
                         ->update(array('Hist' => $histreprovada . $motivodescricao));

                         //Update Financeiro_Reembolso_Matrix
                        DB::table('dbo.Financeiro_Reembolso_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '5', 'motivo_id' => $motivo, 'observacao' => $observacao));

                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                        'numerodebite' => $numerodebite, 
                                        'data' => $carbon, 
                                        'status_id' => '5');
                        DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);


                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                        ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();

                        //Envia notificação e e-mail para o financeiro copiando o solicitante
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso reprovada pela revisão técnica.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);

                        Mail::to($solicitanteemail)
                        ->send(new SolicitacaoReprovada($datas, $motivodescricao));
                }
                //Se foi cancelado
                else {

                            //Update Jurid_Debite (Coloca status 3 de cancelado)
                            DB::table('PLCFULL.dbo.Jurid_Debite')
                            ->where('Numero', '=' ,$numerodebite) 
                            ->limit(1) 
                            ->update(array('Status' => '3','Revisado_DB' => '1', 'Hist' => $histcancelada . $motivodescricao));

                             //Update Financeiro_Reembolso_Matrix
                             DB::table('dbo.Financeiro_Reembolso_Matrix')
                             ->where('numerodebite', '=' ,$numerodebite) 
                             ->limit(1) 
                             ->update(array('status_id' => '5', 'motivo_id' => $motivo, 'observacao' => $observacao));
     
                             //Insert na Hist
                             $values = array('user_id' => Auth::user()->id, 
                                             'numerodebite' => $numerodebite, 
                                             'data' => $carbon, 
                                             'status_id' => '5');
                             DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);
     
     
                             $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                              'PLCFULL.dbo.Jurid_Debite.Pasta',
                              'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                              'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                              'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                              'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                              'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                              'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                              'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                              'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                              'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                              'dbo.users.name as SolicitanteNome',
                              'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                              'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                              'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                              'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                             ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                             ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                             ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                             ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                             ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                             ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                             ->limit(1)
                             ->get();

                        //Envia notificação e e-mail para o solicitante 
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso cancelada pela revisão técnica.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);

     
                        Mail::to($solicitanteemail)
                        ->send(new SolicitacaoCancelada($datas, $motivodescricao));

                } 


                return redirect()->route("Painel.Financeiro.Reembolso.Revisao.solicitacoessocio", ["codigo" => $solicitantecpf]);
        }

        public function reembolso_revisao_revisaoemmassa(Request $request) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('dmY_HHis');
                $solicitante_codigo = $request->get('solicitante_codigo');
                $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf','=', $solicitante_codigo)->value('id');
                $solicitante_email = DB::table('dbo.users')->select('email')->where('cpf','=', $solicitante_codigo)->value('email');
        

                $numerodebite = $request->get('numerodebite');
                $hist = $request->get('hist');

                $data = array();
                foreach($numerodebite as $index => $numerodebite) {

                    //Update Jurid_Debite
                    DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->where('Numero', '=' ,$numerodebite) 
                   ->limit(1) 
                   ->update(array('Revisado_DB' => '1', 'Hist' => $hist[$index]));

                    //Update Financeiro_Reembolso_Matrix
                    DB::table('dbo.Financeiro_Reembolso_Matrix')
                    ->where('numerodebite', '=' ,$numerodebite) 
                    ->limit(1) 
                    ->update(array('status_id' => '2'));

                    //Insert na Hist
                    $values = array('user_id' => Auth::user()->id, 
                                    'numerodebite' => $numerodebite, 
                                    'data' => $carbon, 
                                    'status_id' => '2');
                    DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

                    //Informa ao solicitante por notificação
                    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso aprovada pela revisão técnica.', 'status' => 'A');
                    DB::table('dbo.Hist_Notificacao')->insert($values3);

                    //Informa ao financeiro por notificação e e-mail(235)
                    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => '1', 'tipo' => '8', 'obs' => 'Reembolso: Nova solicitação de reembolso aguardando sua revisão.', 'status' => 'A');
                    DB::table('dbo.Hist_Notificacao')->insert($values3);

                    $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                        ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();

                        Mail::to('ronaldo.amaral@plcadvogados.com.br')
                        ->cc($solicitante_email)
                        ->send(new FinanceiroAprovar($datas));

                }
                //Fim Foreach

               return redirect()->route("Painel.Financeiro.Reembolso.Revisao.solicitacoessocio", ["codigo" => $solicitante_codigo]);

        }

        public function reembolso_financeiro_index() {

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                        //  'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'dbo.users.name as Solicitante',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(2,3,4,5))
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Financeiro.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_financeiro_exportarexcel() {
                setlocale(LC_MONETARY, 'pt_BR');

                $customer_data = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'dbo.users.name as Solicitante',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->join('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(2,3,4,5))
                ->get()
                ->toArray();

                $customer_array[] = array('NumeroDebite',  
                                         'Solicitante',
                                         'Pasta', 
                                         'Setor' ,
                                         'Unidade', 
                                         'Status', 
                                         'TipoDebite', 
                                         'DataServico', 
                                         'DataSolicitacao',
                                         'Valor');
                foreach($customer_data as $customer)
                {
                 $customer_array[] = array(
                  'NumeroDebite'  => $customer->NumeroDebite,
                  'Solicitante'  => mb_convert_case($customer->Solicitante, MB_CASE_TITLE, "UTF-8"),
                  'Pasta' => $customer->Pasta,
                  'Setor' => $customer->Setor,
                  'Unidade' => $customer->Unidade,
                  'Status' => $customer->Status,
                  'TipoDebite' => $customer->TipoDebite,
                  'DataServico'=> date('d/m/Y', strtotime($customer->DataServico)),
                  'DataSolicitacao'=> date('d/m/Y', strtotime($customer->DataSolicitacao)),
                  'Valor' => 'R$ '.number_format($customer->Valor, 2, ',', '.'),
                
                 );
                }
                Excel::create('Solicitações de pagamento', function($excel) use ($customer_array){
                 $excel->setTitle('Solicitações de pagamento');
                 $excel->sheet('Solicitações de pagamento', function($sheet) use ($customer_array){
                 $sheet->getStyle('J')->getNumberFormat()->setFormatCode("R$ #.##0,00");
                 $sheet->fromArray($customer_array, null, 'A1', false, false);

                
                 }
                
                 );
                })->download('xlsx');

        }

        public function reembolso_financeiro_historico() {

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'dbo.users.name as Solicitante',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->join('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(6,7))
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Financeiro.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_financeiro_revisar($numerodebite) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');
                $datapagamento = date('Y-m-d', strtotime('+1 weekdays'));

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                         'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_Debite.Tipodeb as TipoDebiteCodigo',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
                         'dbo.Financeiro_Reembolso_Matrix.visualizada',
                         'dbo.Financeiro_Reembolso_Matrix.status_cobravel',
                         'dbo.users.id as SolicitanteID',
                         'dbo.users.email as SolicitanteEmail',
                         'dbo.users.cpf as SolicitanteCPF',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                         'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                         'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                ->first();

                $contratocodigo = $datas->ContratoCodigo;
       
                $contratos = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'PLCFULL.dbo.Jurid_Desp_Contrato.Codigo')
                ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'Descricao', 'PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp as Status', 'PLCFULL.dbo.Jurid_Desp_Contrato.Valor as Valor', 'PLCFULL.dbo.Jurid_Desp_Contrato.Quantidade', 'PLCFULL.dbo.Jurid_Desp_Contrato.ValorCliente')  
                ->whereNull('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
                ->orwhere('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $contratocodigo)
                ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp', '!=', 'Bloqueada')
                ->orderby('Descricao', 'asc')
                ->get();

                $historicos = DB::table('dbo.Financeiro_Reembolso_Hist')
                ->select('dbo.users.name as nome', 'dbo.Financeiro_Reembolso_Hist.data', 'dbo.Financeiro_Reembolso_Status.id as StatusID','dbo.Financeiro_Reembolso_Status.descricao as status')  
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Hist.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('dbo.users', 'dbo.Financeiro_Reembolso_Hist.user_id', 'dbo.users.id')
                ->where('dbo.Financeiro_Reembolso_Hist.numerodebite','=', $numerodebite)
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->get();

                $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')
                           ->where('Tipo','P')
                           ->where('Codigo', 'NOT LIKE', '%.')
                           ->orderby('Codigo', 'asc')->get();  
              
                $motivos = DB::table('dbo.Jurid_Nota_Motivos')
                ->where('ativo','=','S')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.Financeiro.revisar', compact('dataehora','contratos','historicos','tiposlan','datas','datapagamento','motivos','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_financeiro_revisado(Request $request) {

                $carbon= Carbon::now();
                $numerodebite = $request->get('numerodebite');
                $prconta = $request->get('prconta');
                $pasta = $request->get('pasta');
                $numeroprocesso = $request->get('numeroprocesso');
                $descricaopasta = $request->get('descricaopasta');
                $solicitantenome = $request->get('solicitantenome');
                $solicitanteid = $request->get('solicitanteid');
                $solicitanteemail = $request->get('solicitanteemail');
                $solicitantecpf = $request->get('solicitantecpf');
                $setordescricao = $request->get('setordescricao');
                $setor = $request->get('setor');
                $unidadedescricao = $request->get('unidadedescricao');
                $unidade = $request->get('unidade');
                $grupo = $request->get('grupo');
                $clienterazao = $request->get('clienterazao');
                $cliente = $request->get('cliente');
                $tiposervico = $request->get('tiposervico');
                $dataservico = $request->get('dataservico');
                $datasolicitacao = $request->get('datasolicitacao');
                $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_total')));
                $statuspasta = $request->get('statuspasta');
                $contrato = $request->get('contratocodigo');
                $ressalva = $request->get('ressalva');
                $statusescolhido = $request->get('statusescolhido');
                $motivo = $request->get('motivo');
                $motivodescricao = $request->get('motivodescricao');
                $observacao = $request->get('observacao');
                $hist = $request->get('hist');
                $histreprovada = $request->get('histreprovada');
                $histcancelada = $request->get('histcancelada');
                $datapagamento = $request->get('datapagamento');
                $datavencimento = $request->get('datavencimento');
                $statusdebite = $request->get('statusdebite');

                $tipolan = $request->get('tipolan');
                
                //Se foi aprovado 
                if($statusescolhido == "aprovar") {

                        //Gera CPR
                        $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                        $numprc = $ultimonumprc + 1;

                        //Verificação reembonsavel
                        if($statusdebite == 1) {

                                $values= array(
                                        'Tipodoc' => 'TRF',
                                        'Numdoc' => $numprc,
                                        'Cliente' => $solicitantecpf,
                                        'Tipo' => 'P',
                                        'Centro' => $setor,
                                        'Valor' => $valor,
                                        'Dt_aceite' => $carbon,
                                        'Dt_Vencim' => $datavencimento,
                                        'Dt_Progr' => $datapagamento,
                                        'Dt_envio' => $carbon,
                                        'Valor_pg' => '0.00',
                                        'Multa' => '0',
                                        'Juros' => '0',
                                        'Tipolan' => $tipolan,
                                        'Desconto' => '0',
                                        'Baixado' => '0',
                                        'Status' => '0',
                                        'Historico' => $hist,
                                        'Obs' => $observacao,
                                        'Valor_Or' => $valor,
                                        'Dt_Digit' => $carbon,
                                        'Codigo_Comp' => $pasta,
                                        'Unidade' => $unidade,
                                        'Moeda' => 'R$',
                                        'CSLL' => '0.00',
                                        'COFINS' => '0.00',
                                        'PIS' => '0.00',
                                        'ISS' => '0.00',
                                        'INSS' => '0.00',
                                        'PRConta' => $prconta,
                                        'Contrato' => $contrato,
                                        'numprocesso' => $numeroprocesso,
                                        'cod_pasta' => $pasta);
                                DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
                                            
                                DB::table('PLCFULL.dbo.Jurid_Debite')
                                        ->limit(1)
                                        ->where('Numero', '=', $numerodebite)     
                                        ->update(array('Status'=> '0', 'Obs' => $observacao, 'Hist' => $hist, 'datapag' => $datapagamento, 'DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TRF','NumDocPag' => $numprc, 'data_vencimento' => $datapagamento));  

                               //Update Financeiro_Reembolso_Matrix
                               DB::table('dbo.Financeiro_Reembolso_Matrix')
                               ->where('numerodebite', '=' ,$numerodebite) 
                               ->limit(1) 
                               ->update(array('status_id' => '3', 'status_cobravel' => 'Reembolsável pelo cliente' ));
        
                        }
                        //Se marcar não Reenbonsável vai gravar Jurid_Debite Status = '2' e na Jurid_Contratos Status = '3'
                        else {

                                $values= array(
                                        'Tipodoc' => 'TRF',
                                        'Numdoc' => $numprc,
                                        'Cliente' => $cliente,
                                        'Tipo' => 'P',
                                        'Centro' => $setor,
                                        'Valor' => $valor,
                                        'Dt_aceite' => $carbon,
                                        'Dt_Vencim' => $datavencimento,
                                        'Dt_Progr' => $datapagamento,
                                        'Dt_envio' => $carbon,
                                        'Valor_pg' => '0.00',
                                        'Multa' => '0',
                                        'Juros' => '0',
                                        'Tipolan' => $tipolan,
                                        'Desconto' => '0',
                                        'Baixado' => '0',
                                        'Status' => '0',
                                        'Historico' => $hist,
                                        'Obs' => $observacao,
                                        'Valor_Or' => $valor,
                                        'Dt_Digit' => $carbon,
                                        'Codigo_Comp' => $pasta,
                                        'Unidade' => $unidade,
                                        'Moeda' => 'R$',
                                        'CSLL' => '0.00',
                                        'COFINS' => '0.00',
                                        'PIS' => '0.00',
                                        'ISS' => '0.00',
                                        'INSS' => '0.00',
                                        'PRConta' => $prconta,
                                        'Contrato' => $contrato,
                                        'Origem_cpr' => $numerodebite,
                                        'numprocesso' => $numeroprocesso,
                                        'cod_pasta' => $pasta);
                                            
                                DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
                                            
                                DB::table('PLCFULL.dbo.Jurid_Debite')
                                ->limit(1)
                                ->where('Numero', '=', $numerodebite)     
                                ->update(array('Status'=> '2', 'Obs' => $observacao, 'Hist' => $hist, 'datapag' => $datapagamento, 'DebPago' => 'S', 'Hist' => $hist, 'TipoDocPag' => 'TRF','NumDocPag' => $numprc, 'data_vencimento' => $datapagamento)); 
                               
                               //Update Financeiro_Reembolso_Matrix
                               DB::table('dbo.Financeiro_Reembolso_Matrix')
                               ->where('numerodebite', '=' ,$numerodebite) 
                               ->limit(1) 
                               ->update(array('status_id' => '3', 'status_cobravel' => 'Não reembolsável'));


                        }

                              //Update Jurid_Default (Num + 1)    
                              DB::table('PLCFULL.dbo.Jurid_Default_')
                              ->limit(1) 
                              ->update(array('Numcpr' => $numprc)); 


                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                        'numerodebite' => $numerodebite, 
                                        'data' => $carbon, 
                                        'status_id' => '3');
                        DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

                        //Informa ao solicitante por notificação
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso aprovada pela revisão do financeiro.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);


                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                        ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();

                        Mail::to($solicitanteemail)
                         ->send(new FinanceiroAprovado($datas));
                
                }
                //Se foi reprovado 
                else if($statusescolhido == "reprovar") {


                        //Update Hist
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->where('Numero', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('Hist' => $histreprovada . $motivodescricao));

                         //Update Financeiro_Reembolso_Matrix
                        DB::table('dbo.Financeiro_Reembolso_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '5', 'motivo_id' => $motivo, 'observacao' => $observacao));

                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                        'numerodebite' => $numerodebite, 
                                        'data' => $carbon, 
                                        'status_id' => '5');
                        DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

                        //Informa ao solicitante por notificação
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso reprovada pela revisão do financeiro.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);


                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                        ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();

                        Mail::to($solicitanteemail)
                        ->send(new SolicitacaoReprovadaFinanceiro($datas, $motivodescricao));
                }
                //Se foi cancelado
                else {

                            //Update Jurid_Debite (Coloca status 3 de cancelado)
                            DB::table('PLCFULL.dbo.Jurid_Debite')
                            ->where('Numero', '=' ,$numerodebite) 
                            ->limit(1) 
                            ->update(array('Status' => '3','Revisado_DB' => '1', 'Obs' => $observacao, 'Hist' => $histcancelada . $motivodescricao));

                             //Update Financeiro_Reembolso_Matrix
                             DB::table('dbo.Financeiro_Reembolso_Matrix')
                             ->where('numerodebite', '=' ,$numerodebite) 
                             ->limit(1) 
                             ->update(array('status_id' => '6', 'motivo_id' => $motivo, 'observacao' => $observacao));
     
                             //Insert na Hist
                             $values = array('user_id' => Auth::user()->id, 
                                             'numerodebite' => $numerodebite, 
                                             'data' => $carbon, 
                                             'status_id' => '6');
                             DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);
     
                             //Informa ao solicitante por notificação
                             $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '8', 'obs' => 'Reembolso: Solicitação de reembolso cancelada pela revisão do financeiro.', 'status' => 'A');
                             DB::table('dbo.Hist_Notificacao')->insert($values3);
     
     
                             $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                              'PLCFULL.dbo.Jurid_Debite.Pasta',
                              'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                              'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                              'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                              'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                              'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                              'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                              'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                              'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                              'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                              'dbo.users.name as SolicitanteNome',
                              'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                              'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                              'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                              'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                             ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                             ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                             ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                             ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                             ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                             ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                             ->limit(1)
                             ->get();
     
                             Mail::to($solicitanteemail)
                             ->send(new SolicitacaoCancelada($datas, $motivodescricao));

                } 


                  return redirect()->route('Painel.Financeiro.Reembolso.Financeiro.index');

        }


        public function reembolso_conciliacaobancaria_index() {

                $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'dbo.users.cpf' )
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->select(
                        'dbo.users.cpf as SolicitanteCodigo',
                        'dbo.users.email as SolicitanteEmail',
                        'dbo.users.name as SolicitanteNome',
                        DB::raw('sum(ValorT ) as Valor'),
                        DB::raw('count(*) as QuantidadeSolicitacoes'))
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(3,4))
                ->groupby('dbo.users.cpf','dbo.users.email', 'dbo.users.name')
                ->get();    
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.ConciliacaoBancaria.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_conciliacaobancaria_historico() {

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'dbo.users.name as Solicitante',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->join('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(6,7))
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.ConciliacaoBancaria.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_conciliacaobancaria_solicitacoessocio($codigo) {

                $carbon= Carbon::now();
                $datahoje = $carbon->format('Y-m-d');    
                $dataehora = $carbon->format('d-m-Y H:i:s');

        
                $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc') 
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.TipoDeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->select(
                         'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Setor',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_ContaPr.Tipolan as TipoLancamentoCPR',
                         'PLCFULL.dbo.Jurid_CliFor.Nome as NomeFornecedor',
                         'dbo.users.name as Correspondente',
                         'PLCFULL.dbo.Jurid_Debite.moeda as Moeda',
                         'PLCFULL.dbo.Jurid_Debite.Status as StatusDebite',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                         'PLCFULL.dbo.Jurid_ContaPr.Tipodoc as TipoDocCPR',
                         'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_ContaPr.Dt_Progr as DataProgramacao',
                         'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoFornecedor',
                         'PLCFULL.dbo.Jurid_CliFor.id_cliente as id_cliente',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'))
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->where('dbo.users.cpf', '=', $codigo)
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(3))
                ->get();

                $valor_disponivel = DB::table("dbo.Financeiro_Adiantamento_ValorReutilizar")
                ->select(DB::raw('sum(dbo.Financeiro_Adiantamento_ValorReutilizar.valor_disponivel)'))
                ->where('dbo.Financeiro_Adiantamento_ValorReutilizar.usuario_cpf', $codigo)
                ->value('dbo.Financeiro_Adiantamento_ValorReutilizar.valor_disponivel');

                $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  
                
                $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
                ->where('DaEmpresa', '0')
                ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
                ->where('Status', '1')
                ->orderby('Descricao', 'ASC')
                ->get();   
             

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                        ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                        'data',
                        'id_ref', 
                        'user_id',
                        'tipo', 
                        'obs',
                        'hist_notificacao.status', 
                        'dbo.users.*')  
                        ->limit(3)
                        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                        ->where('dbo.Hist_Notificacao.status','=','A')
                        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                        ->get();
        
                return view('Painel.Financeiro.Reembolso.ConciliacaoBancaria.solicitacoessocio', compact('codigo','dataehora','datahoje','tiposdoc','valor_disponivel','bancos','datas', 'totalNotificacaoAbertas', 'notificacoes'));


        }

        public function reembolso_conciliacaobancaria_individual($numerodebite) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');
                $datahoje = $carbon->format('Y-m-d');    

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                         'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_Debite.Tipodeb as TipoDebiteCodigo',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
                         'dbo.Financeiro_Reembolso_Matrix.visualizada',
                         'dbo.Financeiro_Reembolso_Matrix.status_cobravel',
                         'dbo.users.id as SolicitanteID',
                         'dbo.users.email as SolicitanteEmail',
                         'dbo.users.cpf as SolicitanteCPF',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                         'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                         'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                ->first();

                $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  
                
                $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
                ->where('DaEmpresa', '0')
                ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
                ->where('Status', '1')
                ->orderby('Descricao', 'ASC')
                ->get();   
              

                $contratocodigo = $datas->ContratoCodigo;
       
                $contratos = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'PLCFULL.dbo.Jurid_Desp_Contrato.Codigo')
                ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'Descricao', 'PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp as Status', 'PLCFULL.dbo.Jurid_Desp_Contrato.Valor as Valor', 'PLCFULL.dbo.Jurid_Desp_Contrato.Quantidade', 'PLCFULL.dbo.Jurid_Desp_Contrato.ValorCliente')  
                ->whereNull('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
                ->orwhere('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $contratocodigo)
                ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp', '!=', 'Bloqueada')
                ->orderby('Descricao', 'asc')
                ->get();

                $historicos = DB::table('dbo.Financeiro_Reembolso_Hist')
                ->select('dbo.users.name as nome', 'dbo.Financeiro_Reembolso_Hist.data', 'dbo.Financeiro_Reembolso_Status.id as StatusID','dbo.Financeiro_Reembolso_Status.descricao as status')  
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Hist.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('dbo.users', 'dbo.Financeiro_Reembolso_Hist.user_id', 'dbo.users.id')
                ->where('dbo.Financeiro_Reembolso_Hist.numerodebite','=', $numerodebite)
                ->orderBy('dbo.Financeiro_Reembolso_Status.id', 'asc')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.ConciliacaoBancaria.individual', compact('dataehora','datahoje','tiposdoc','bancos','contratos','historicos','datas','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_conciliacaobancaria_individual_store(Request $request) {

                $usuarioid = Auth::user()->id;
                $carbon= Carbon::now();
                $dataehora = $carbon->format('dmY_HHis');
                $fornecedor = $request->get('cliente');
                $statusdebite = $request->get('statusdebite');
                $codigo_banco = $request->get('portador'); 
                $data_concil = $request->get('dataconciliacao');
                $data_baixa = $request->get('databaixa');
                $solicitante_codigo = $request->get('solicitantecpf');
                $hist = $request->get('hist');
                $gerarcpr = $request->get('gerarcpr');
         
                $numerodebite = $request->get('numerodebite');
                $tipodoc = $request->get('tipodoc');
                $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                  
                $advserv = DB::table('PLCFULL.dbo.Jurid_Debite')->select('AdvServ')->where('Numero','=', $numerodebite)->value('AdvServ');
                $tipo = 'P';
                $tipodocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('tipodocpag')->where('Numero','=', $numerodebite)->value('tipodocpag');
                $numdocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('numdocpag')->where('Numero','=', $numerodebite)->value('numdocpag');
                $tipolan = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Tipolan')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Tipolan');
                $valor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('ValorT')->where('Numero','=', $numerodebite)->value('ValorT');
                $setor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Setor')->where('Numero','=', $numerodebite)->value('Setor');     
                $observacao = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Obs')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Obs');
                $datacompetencia = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr');
                $codigo_comp = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Pasta')->where('Numero','=', $numerodebite)->value('Pasta');
                $unidade = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Unidade')->where('Numero','=', $numerodebite)->value('Unidade'); 
                $prconta = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.PRConta')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.PRConta');
                $contrato = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Contrato')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Contrato');
                $ident_cpr = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident');
                $origem_cpr = $ident_cpr;
                $ident_rate = '0';
                $hist_usuario = 'portal.plc';
                $moeda = DB::table('PLCFULL.dbo.Jurid_Debite')->select('moeda')->where('Numero','=', $numerodebite)->value('moeda'); 
                $desconto = '0.00';

                $values= array(
                        'Tipodoc' => $tipodoc,
                        'Numdoc' => $numdoc,
                        'Cliente' => $advserv,
                        'Tipo' => $tipo,
                        'TipodocOr' => $tipodocor,
                        'NumDocOr' => $numdocor,
                        'Tipolan' => $tipolan,
                        'Valor' => $valor,
                        'Centro' => $setor,
                        'Dt_baixa' => $data_baixa,
                        'Portador' => $codigo_banco,
                        'Obs' => $observacao,
                        'Juros' => '0.00',
                        'Dt_Compet' => $datacompetencia,
                        'DT_Concil' => $data_concil,
                        'Codigo_Comp' => $codigo_comp,
                        'Unidade' => $unidade,
                        'PRConta' => $prconta,
                        'Contrato' =>$contrato,
                        'Ident_Cpr' => $ident_cpr,
                        'origem_cpr' => $origem_cpr,
                        'Ident_Rate' => $ident_rate,
                        'moeda' => $moeda);    
                DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);  


                DB::table('PLCFULL.dbo.Jurid_ContaPr')
                ->limit(1)
                ->where('Cpr_ident', '=', $ident_cpr)     
                ->update(array('Historico' => $hist,'Baixado' => '1', 'Status' => '4', 'Dt_baixa' => $data_baixa));
        
                if($gerarcpr == "SIM") {
        
                //Se for reembolsavel vai gravar numa tabela temp para depois gerar a CPR
                if($statusdebite == "0") {
        
                        //Grava na tabela temp o número do debite 
                        $values= array(
                                'numerodebite' => $numerodebite,
                                'cliente' => $fornecedor);    
                          DB::table('dbo.Financeiro_Reembolso_temp')->insert($values);  
                } 
                }
        
                DB::table('PLCFULL.dbo.Jurid_Debite')->limit(1)->where('Numero', '=', $numerodebite)->update(array('Hist' => $hist,'datapag' => $data_baixa, 'banco' => $codigo_banco));
        
                //Grava na GED
                $image = $request->file('select_file');
                $new_name = 'comprovante_'.$numerodebite . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
                $image->storeAs('reembolso', $new_name);
                Storage::disk('reembolso-local')->put($new_name, File::get($image));
        
                //Insert Jurid_Ged_Main
                $values = array(
                'Tabela_OR' => 'Debite',
                'Codigo_OR' => $numerodebite,
                'Id_OR' => $numerodebite,
                'Descricao' => $image->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\GED\Vault$\portal\reembolso/' . $new_name, 
                'Data' => $carbon,
                'Nome' => $image->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $observacao,
                'Texto' => 'Comprovante pagamento');
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
        
                //Update na Matrix 
                DB::table('dbo.Financeiro_Reembolso_Matrix')
                ->where('numerodebite', '=' ,$numerodebite) 
                ->limit(1) 
                ->update(array('status_id' => '7', 'observacao' => $observacao));
        
                //Grava na Hist
                $values = array('user_id' => Auth::user()->id, 
                'numerodebite' => $numerodebite, 
                'data' => $carbon, 
                'status_id' => '7');
                DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);
        
          
                //Update Jurid_Default (Num + 1)    
                $numprcNovo = $numdoc + 1;
                DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));
        
                
                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                 'PLCFULL.dbo.Jurid_Debite.Pasta',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                 'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                 'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                 'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                 'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                 'dbo.users.name as SolicitanteNome',
                 'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                 'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                 'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                 'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                ->limit(1)
                ->get();
              
                $solicitanteemail = DB::table('dbo.users')->select('email')->where('cpf','=', $solicitante_codigo)->value('email');
           
                 //Envia email informando que a Solicitação foi paga 
                 Mail::to($solicitanteemail)
                       ->send(new FinanceiroBaixado($datas));
         
        
                if($gerarcpr == "SIM") {
                //Verifica os debites na temp para criar a CPR do cliente 
                  //Pego os valores totais por cliente agrupado
                  $clientes = DB::table("dbo.Financeiro_Reembolso_temp")
                  ->select('dbo.Financeiro_Reembolso_temp.cliente')
                  ->groupby('dbo.Financeiro_Reembolso_temp.cliente')
                  ->get();
                
                //Pego o valor total deste cliente para gerar a CPR
                $valortotal_cliente = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select(DB::raw('sum(PLCFULL.dbo.Jurid_Debite.ValorT)'))
                ->join('dbo.Financeiro_Reembolso_temp', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_temp.numerodebite')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $fornecedor)
                ->value('PLCFULL.dbo.Jurid_Debite.ValorT');
        
                $debites = DB::table("dbo.Financeiro_Reembolso_temp")
                ->select('dbo.Financeiro_Reembolso_temp.numerodebite')
                ->where('cliente', $fornecedor)
                ->get()
                ->toArray();
        
                $setor = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Setor')->where('Codigo', $fornecedor)->value('Setor'); 
                $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo', $fornecedor)->value('Unidade'); 
        
                //Gero a CPR
                $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                $numdoc2 = $numdoc + 1;
                DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numdoc2));
        
                $values= array(
                        'Tipodoc' => 'BOL',
                        'Numdoc' => $numdoc,
                        'Cliente' => '004',
                        'Tipo' => 'R',
                        'Centro' => $setor,
                        'Valor' => $valortotal_cliente,
                        'Dt_aceite' => $carbon->format('Y-m-d'),
                        'Dt_Vencim' => date('Y-m-d', strtotime('+15 days')),
                        'Dt_Progr' => date('Y-m-d', strtotime('+15 days')),
                        'Multa' => '0',
                        'Juros' => '0',
                        'Tipolan' => '16.02',
                        'Desconto' => '0',
                        'Baixado' => '',
                        'Portador' => $fornecedor,
                        'Status' => '0',
                        'Historico' => 'Solicitações de reembolso gerada no portal plc no valor total de: R$ ' . number_format($valortotal_cliente,2,",","."),
                        'Obs' => 'Solicitações de reembolso gerada no portal plc no valor total de: R$ ' . number_format($valortotal_cliente,2,",",".") . ' referente aos debites: ' . $numerodebite,
                        'Valor_Or' => $valortotal_cliente,
                        'Dt_Digit' => $carbon->format('Y-m-d'),
                        'Codigo_Comp' => '',
                        'Unidade' => $unidade,
                        'Moeda' => 'R$',
                        'CSLL' => '0.00',
                        'COFINS' => '0.00',
                        'PIS' => '0.00',
                        'ISS' => '0.00',
                        'INSS' => '0.00',
                        'Contrato' => '',
                        'Origem_cpr' => '',
                        'numprocesso' => '',
                        'cod_pasta' => '');
                DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
        
        
                        //Update na Jurid_Debite colocando o numero da fatura e status em cobrança
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->join('dbo.Financeiro_Reembolso_temp', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_temp.numerodebite')
                        ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $fornecedor)  
                        ->where('dbo.Financeiro_Reembolso_temp.cliente', $fornecedor)
                        ->update(array('Status' => '1', 'Fatura' => $numdoc));
                  }
                
                flash('Solicitação de reembolso baixada com sucesso !')->success();
                return redirect()->route('Painel.Financeiro.Reembolso.ConciliacaoBancaria.index');

        }



        public function reembolso_conciliacaobancaria_baixado(Request $request) {

                $usuarioid = Auth::user()->id;
                $carbon= Carbon::now();
                $dataehora = $carbon->format('dmY_HHis');
                $fornecedor = $request->get('fornecedor');
                $statusdebite = $request->get('statusdebite');
                $codigo_banco = $request->get('portador'); 
                $data_concil = $request->get('dataconciliacao');
                $data_baixa = $request->get('databaixa');
                $solicitante_codigo = $request->get('solicitante_codigo');
                $hist = $request->get('hist');
                $gerarcpr = $request->get('gerarcpr');
         
                $numerodebite = $request->get('numerodebite');
                $tipodoc = $request->get('tipodoc');

                $debitesstring = implode(",", $numerodebite);

                $data = array();
                foreach($numerodebite as $index => $numerodebite) {

                 $item = array('numerodebite' => $numerodebite);
                 array_push($data,$item);
               
                  $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                  
                  $advserv = DB::table('PLCFULL.dbo.Jurid_Debite')->select('AdvServ')->where('Numero','=', $numerodebite)->value('AdvServ');
                  $tipo = 'P';
                  $tipodocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('tipodocpag')->where('Numero','=', $numerodebite)->value('tipodocpag');
                  $numdocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('numdocpag')->where('Numero','=', $numerodebite)->value('numdocpag');
                  $tipolan = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Tipolan')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Tipolan');
                  $valor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('ValorT')->where('Numero','=', $numerodebite)->value('ValorT');
                  $setor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Setor')->where('Numero','=', $numerodebite)->value('Setor');     
                  $observacao = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Obs')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Obs');
                  $datacompetencia = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr');
                  $codigo_comp = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Pasta')->where('Numero','=', $numerodebite)->value('Pasta');
                  $unidade = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Unidade')->where('Numero','=', $numerodebite)->value('Unidade'); 
                  $prconta = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.PRConta')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.PRConta');
                  $contrato = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Contrato')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Contrato');
                  $ident_cpr = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident');
                  $origem_cpr = $ident_cpr;
                  $ident_rate = '0';
                  $hist_usuario = 'portal.plc';
                  $moeda = DB::table('PLCFULL.dbo.Jurid_Debite')->select('moeda')->where('Numero','=', $numerodebite)->value('moeda'); 
                  $desconto = '0.00';
       
                 $values= array(
                          'Tipodoc' => $tipodoc,
                          'Numdoc' => $numdoc,
                          'Cliente' => $advserv,
                          'Tipo' => $tipo,
                          'TipodocOr' => $tipodocor,
                          'NumDocOr' => $numdocor,
                          'Tipolan' => $tipolan,
                          'Valor' => $valor,
                          'Centro' => $setor,
                          'Dt_baixa' => $data_baixa,
                          'Portador' => $codigo_banco,
                          'Obs' => $observacao,
                          'Juros' => '0.00',
                          'Dt_Compet' => $datacompetencia,
                          'DT_Concil' => $data_concil,
                          'Codigo_Comp' => $codigo_comp,
                          'Unidade' => $unidade,
                          'PRConta' => $prconta,
                          'Contrato' =>$contrato,
                          'Ident_Cpr' => $ident_cpr,
                          'origem_cpr' => $origem_cpr,
                          'Ident_Rate' => $ident_rate,
                          'moeda' => $moeda);    
                    DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);  
              
        //Update Conta PR  (Falando Baixado = 4, Status = 'baixado' e Data Pag = Data baixa)
        DB::table('PLCFULL.dbo.Jurid_ContaPr')
        ->limit(1)
        ->where('Cpr_ident', '=', $ident_cpr)     
        ->update(array('Historico' => $hist,'Baixado' => '1', 'Status' => '4', 'Dt_baixa' => $data_baixa));

        if($gerarcpr == "SIM") {

        //Se for reembolsavel vai gravar numa tabela temp para depois gerar a CPR
        if($statusdebite[$index] == "0") {

                //Grava na tabela temp o número do debite 
                $values= array(
                        'numerodebite' => $numerodebite,
                        'cliente' => $fornecedor[$index]);    
                  DB::table('dbo.Financeiro_Reembolso_temp')->insert($values);  
        } 
        }

        DB::table('PLCFULL.dbo.Jurid_Debite')->limit(1)->where('Numero', '=', $numerodebite)->update(array('Hist' => $hist,'datapag' => $data_baixa, 'banco' => $codigo_banco));

        //Grava na GED
        $image = $request->file('select_file');
        $new_name = 'comprovante_'.$numerodebite . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
        $image->storeAs('reembolso', $new_name);
        Storage::disk('reembolso-local')->put($new_name, File::get($image));

        //Insert Jurid_Ged_Main
        $values = array(
        'Tabela_OR' => 'Debite',
        'Codigo_OR' => $numerodebite,
        'Id_OR' => $numerodebite,
        'Descricao' => $image->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\reembolso/'.$new_name, 
        'Data' => $carbon,
        'Nome' => $image->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $image->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $new_name,
        'Obs' => $observacao,
        'Texto' => 'Comprovante pagamento');
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        //Update na Matrix 
        DB::table('dbo.Financeiro_Reembolso_Matrix')
        ->where('numerodebite', '=' ,$numerodebite) 
        ->limit(1) 
        ->update(array('status_id' => '7', 'observacao' => $observacao));

        //Grava na Hist
        $values = array('user_id' => Auth::user()->id, 
        'numerodebite' => $numerodebite, 
        'data' => $carbon, 
        'status_id' => '7');
        DB::table('dbo.Financeiro_Reembolso_Hist')->insert($values);

  
        //Update Jurid_Default (Num + 1)    
        $numprcNovo = $numdoc + 1;
        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));

        
        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
         'PLCFULL.dbo.Jurid_Debite.Pasta',
         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
         'dbo.users.name as SolicitanteNome',
         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
        ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
        ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
        ->limit(1)
        ->get();
      
        $solicitanteemail = DB::table('dbo.users')->select('email')->where('cpf','=', $solicitante_codigo)->value('email');
   
         //Envia email informando que a Solicitação foi paga 
         Mail::to($solicitanteemail)
               ->send(new FinanceiroBaixado($datas));
 
        }
        //Fim Foreach

        if($gerarcpr == "SIM") {
        //Verifica os debites na temp para criar a CPR do cliente 
          //Pego os valores totais por cliente agrupado
          $clientes = DB::table("dbo.Financeiro_Reembolso_temp")
          ->select('dbo.Financeiro_Reembolso_temp.cliente')
          ->groupby('dbo.Financeiro_Reembolso_temp.cliente')
          ->get();

          foreach ($clientes as $cliente) {

                //Pego o valor total deste cliente para gerar a CPR
                $valortotal_cliente = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select(DB::raw('sum(PLCFULL.dbo.Jurid_Debite.ValorT)'))
                ->join('dbo.Financeiro_Reembolso_temp', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_temp.numerodebite')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $cliente->cliente)
                ->value('PLCFULL.dbo.Jurid_Debite.ValorT');

                $debites = DB::table("dbo.Financeiro_Reembolso_temp")
                ->select('dbo.Financeiro_Reembolso_temp.numerodebite')
                ->where('cliente', $cliente->cliente)
                ->get()
                ->toArray();

                $setor = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Setor')->where('Codigo', $cliente->cliente)->value('Setor'); 
                $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo', $cliente->cliente)->value('Unidade'); 

                //Gero a CPR
                $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                $numdoc2 = $numdoc + 1;
                DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numdoc2));

                $values= array(
                'Tipodoc' => 'BOL',
                'Numdoc' => $numdoc,
                'Cliente' => $cliente->cliente,
                'Tipo' => 'R',
                'Centro' => $setor,
                'Valor' => $valortotal_cliente,
                'Dt_aceite' => $carbon->format('Y-m-d'),
                'Dt_Vencim' => date('Y-m-d', strtotime('+15 days')),
                'Dt_Progr' => date('Y-m-d', strtotime('+15 days')),
                'Multa' => '0',
                'Juros' => '0',
                'Tipolan' => '16.02',
                'Desconto' => '0',
                'Baixado' => '',
                'Portador' => $cliente->cliente,
                'Status' => '0',
                'Historico' => 'Solicitações de reembolso gerada no portal plc no valor total de: R$ ' . number_format($valortotal_cliente,2,",","."),
                'Obs' => 'Solicitações de reembolso gerada no portal plc no valor total de: R$ ' . number_format($valortotal_cliente,2,",",".") . ' referente aos debites: ' . $debitesstring,
                'Valor_Or' => $valortotal_cliente,
                'Dt_Digit' => $carbon->format('Y-m-d'),
                'Codigo_Comp' => '',
                'Unidade' => $unidade,
                'Moeda' => 'R$',
                'CSLL' => '0.00',
                'COFINS' => '0.00',
                'PIS' => '0.00',
                'ISS' => '0.00',
                'INSS' => '0.00',
                'Contrato' => '',
                'Origem_cpr' => '',
                'numprocesso' => '',
                'cod_pasta' => '');
                DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);


                //Update na Jurid_Debite colocando o numero da fatura e status em cobrança
                DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Financeiro_Reembolso_temp', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_temp.numerodebite')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $cliente->cliente)  
                ->where('dbo.Financeiro_Reembolso_temp.cliente', $cliente->cliente)
                ->update(array('Status' => '1', 'Fatura' => $numdoc));
          }

        //Deleta a tabela temp após todo o processo
        DB::table('dbo.Financeiro_Reembolso_temp')->delete();       
        } 

        flash('Solicitação de reembolso baixada com sucesso !')->success();
        return redirect()->route('Painel.Financeiro.Reembolso.ConciliacaoBancaria.index');

        }

        public function reembolso_pagamentodebite_index() {

                $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', '=', 'dbo.users.cpf' )
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->leftjoin('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', 'PLCFULL.dbo.Jurid_GrupoFinanceiro.codigo_grupofinanceiro')
                ->leftjoin('PLCFULL.dbo.jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', 'PLCFULL.dbo.Jurid_empreendimento.codigo_empreendimento')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->select(
                        'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                        'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                        'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
                        'PLCFULL.dbo.Jurid_empreendimento.nome_empreendimento as GrupoEmpreendimento',
                        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                        DB::raw('sum(ValorT ) as Valor'),
                        DB::raw('count(*) as QuantidadeSolicitacoes'))
                // ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->where('PLCFULL.dbo.Jurid_Debite.Status', '=', '0')
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', '=', 'S')
                // ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(7))
                ->groupby('PLCFULL.dbo.Jurid_CliFor.Codigo',
                          'PLCFULL.dbo.Jurid_CliFor.Razao', 
                          'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro',
                          'PLCFULL.dbo.Jurid_empreendimento.nome_empreendimento',
                          'PLCFULL.dbo.Jurid_GrupoCliente.Descricao')
                ->orderby('PLCFULL.dbo.Jurid_CliFor.Razao', 'ASC')
                ->get();    
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Reembolso.PagamentoDebite.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function reembolso_pagamentodebite_verificasolicitacoes($codigo) {

                //Update nos debites que estão com Revisado_DB = '0'
                DB::table('PLCFULL.dbo.Jurid_Debite')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $codigo)
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '0')
                ->where('PLCFULL.dbo.Jurid_Debite.Status', '=', '0')
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', 'S')
                ->where('PLCFULL.dbo.Jurid_Debite.numdocpag', '!=', '')
                ->whereNotNull('PLCFULL.dbo.Jurid_Debite.numdocpag')
                ->update(array('Revisado_DB' => '1'));
      
                return redirect()->route("Painel.Financeiro.Reembolso.PagamentoDebite.solicitacoes", ["codigo" => $codigo]);

        }

        public function reembolso_pagamentodebite_solicitacoes($codigo) {

                $carbon= Carbon::now();
                $datahoje = $carbon->format('Y-m-d');    
                $dataehora = $carbon->format('d-m-Y H:i:s');


                $verificasolicitacoes = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $codigo)
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '0')
                ->where('PLCFULL.dbo.Jurid_Debite.Status', '=', '0')
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', 'S')
                ->where('PLCFULL.dbo.Jurid_Debite.numdocpag', '!=', '')
                ->whereNotNull('PLCFULL.dbo.Jurid_Debite.numdocpag')
                ->count();    
      
                $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->leftjoin('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', '=', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.TipoDeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->select(
                         'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Setor',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_CliFor.Nome as NomeFornecedor',
                         'dbo.users.name as Correspondente',
                         'PLCFULL.dbo.Jurid_Debite.moeda as Moeda',
                         'PLCFULL.dbo.Jurid_Debite.Status as StatusDebite',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoFornecedor',
                         'PLCFULL.dbo.Jurid_CliFor.id_cliente as id_cliente',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'))
                ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $codigo)
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->where('PLCFULL.dbo.Jurid_Debite.Status', '=', '0')
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', '=', 'S')
                ->orderby('PLCFULL.dbo.Jurid_Debite.Numero', 'asc')
                // ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(7))
                ->get();
                
                $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  
                        
                $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')->where('Tipo','R')->orderby('Codigo', 'asc')->get();  

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
              
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                        ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                        'data',
                        'id_ref', 
                        'user_id',
                        'tipo', 
                        'obs',
                        'hist_notificacao.status', 
                        'dbo.users.*')  
                        ->limit(3)
                        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                        ->where('dbo.Hist_Notificacao.status','=','A')
                        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                        ->get();
        
                return view('Painel.Financeiro.Reembolso.PagamentoDebite.solicitacoes', compact('codigo','verificasolicitacoes','dataehora','datahoje','tiposdoc','tiposlan','datas', 'totalNotificacaoAbertas', 'notificacoes'));
        }

        public function reembolso_pagamentodebite_baixado(Request $request) {

                $usuarioid = Auth::user()->id;
                $carbon= Carbon::now();
                $dataehora = $carbon->format('dmY_HHis');
                $fornecedor = $request->get('fornecedor');
                $numerodebite = $request->get('numerodebite');
                $datavencimento = $request->get('datavencimento');

                $tipodoc = $request->get('tipodoc');
                $tipolan = $request->get('tipolan');

                $debitesstring = implode(",", $numerodebite);

                $data = array();
                foreach($numerodebite as $index => $numerodebite) {

                 $setor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Setor')->where('Numero', $numerodebite)->value('Setor'); 

                 $item = array('numerodebite' => $numerodebite);
                 array_push($data,$item);

                 $values= array(
                        'numerodebite' => $numerodebite,
                        'cliente' => $fornecedor[$index],
                        'setor' => $setor);    
                 DB::table('dbo.Financeiro_Reembolso_temp')->insert($values);  

                }

                $clientes = DB::table("dbo.Financeiro_Reembolso_temp")
                ->select('dbo.Financeiro_Reembolso_temp.cliente')
                ->groupby('dbo.Financeiro_Reembolso_temp.cliente')
                ->get();

                foreach ($clientes as $cliente) {

                        //Pego o valor total deste cliente para gerar a CPR
                        $valortotal_cliente = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select(DB::raw('sum(PLCFULL.dbo.Jurid_Debite.ValorT)'))
                        ->join('dbo.Financeiro_Reembolso_temp', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_temp.numerodebite')
                        ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $cliente->cliente)
                        ->value('PLCFULL.dbo.Jurid_Debite.ValorT');

                        //Pego um setor de custo
                        $setor = DB::table('dbo.Financeiro_Reembolso_temp')
                                ->select('setor')
                                ->where('cliente', $cliente->cliente)
                                ->value('setor'); 
        
                        $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo', $cliente->cliente)->value('Unidade'); 
        
                        //Gero a CPR
                        $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                        $numdoc2 = $numdoc + 1;
                        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numdoc2));
        
                        $values= array(
                        'Tipodoc' => $tipodoc,
                        'Numdoc' => $numdoc2,
                        'Cliente' => $cliente->cliente,
                        'Tipo' => 'R',
                        'Centro' => $setor,
                        'Valor' => $valortotal_cliente,
                        'Dt_aceite' => $carbon->format('Y-m-d'),
                        'Dt_Vencim' => $datavencimento,
                        'Dt_Progr' => $datavencimento,
                        'Multa' => '0',
                        'Juros' => '0',
                        'Tipolan' => $tipolan,
                        'Desconto' => '0',
                        'Baixado' => '',
                        'Portador' => $cliente->cliente,
                        'Status' => '2',
                        'Historico' => 'Solicitações de reembolso geradas no portal plc no valor total de: R$ ' . number_format($valortotal_cliente,2,",","."),
                        'Obs' => 'Solicitações de reembolso geradas no portal plc no valor total de: R$ ' . number_format($valortotal_cliente,2,",",".") . ' referente aos debites: ' . $debitesstring,
                        'Valor_Or' => $valortotal_cliente,
                        'Dt_Digit' => $carbon->format('Y-m-d'),
                        'Codigo_Comp' => '',
                        'Unidade' => $unidade,
                        'Moeda' => 'R$',
                        'CSLL' => '0.00',
                        'COFINS' => '0.00',
                        'PIS' => '0.00',
                        'ISS' => '0.00',
                        'INSS' => '0.00',
                        'Contrato' => '',
                        'Origem_cpr' => '',
                        'numprocesso' => '',
                        'cod_pasta' => '');
                        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);

                        //Grava o rateio
                        $cpr_ident = DB::table('PLCFULL.dbo.Jurid_ContaPr')
                                    ->select('Cpr_ident')
                                    ->where('PLCFULL.dbo.Jurid_ContaPr.Cliente', $cliente->cliente)
                                    ->where('PLCFULL.dbo.Jurid_ContaPr.Tipo', 'R')
                                    ->where('PLCFULL.dbo.Jurid_ContaPr.Valor', $valortotal_cliente)
                                    ->where('PLCFULL.dbo.Jurid_ContaPr.Dt_aceite', $carbon->format('Y-m-d'))
                                    ->where('Tipo', '=', 'R')
                                    ->where('PLCFULL.dbo.Jurid_ContaPr.Status', '2')
                                    ->orderby('PLCFULL.dbo.Jurid_ContaPr.cpr_ident', 'desc')
                                    ->value('Cpr_ident'); 

                        //Pego a soma dos setores de custo
                        $rateios = DB::table("dbo.Financeiro_Reembolso_temp")
                        ->select('dbo.Financeiro_Reembolso_temp.setor',
                                 DB::raw('sum(PLCFULL.dbo.Jurid_Debite.ValorT) as Valor'))
                        ->join('PLCFULL.dbo.Jurid_Debite', 'dbo.Financeiro_Reembolso_temp.numerodebite', 'PLCFULL.dbo.Jurid_Debite.Numero')
                        ->where('dbo.Financeiro_Reembolso_temp.cliente', $cliente->cliente)
                        ->groupBy('dbo.Financeiro_Reembolso_temp.setor')
                        ->get();

                        //Foreach para gravar o rateio
                        foreach ($rateios as $rateio) {


                                $setor = $rateio->setor;
                                $valor = $rateio->Valor;

                                $unidade = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Unidade')->where('Codigo', $setor)->value('Unidade'); 

                                $values= array(
                                        'setor' => $setor,
                                        'Valor_R' => $valor,
                                        'Rateio_Or' => $cpr_ident,
                                        'Unidade' => $unidade,
                                        'Tipolan' => $tipolan);    
                                 DB::table('PLCFULL.dbo.Jurid_Rateio_Cpr_Setor')->insert($values);  

                        }
                        //Fim
        
                        //Update na Jurid_Debite colocando o numero da fatura e status em cobrança
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->join('dbo.Financeiro_Reembolso_temp', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_temp.numerodebite')
                        ->where('PLCFULL.dbo.Jurid_Debite.Cliente', $cliente->cliente)  
                        ->where('dbo.Financeiro_Reembolso_temp.cliente', $cliente->cliente)
                        ->update(array('Status' => '1', 'Fatura' => $numdoc2));
                  }
        
                //Deleta a tabela temp após todo o processo
                DB::table('dbo.Financeiro_Reembolso_temp')->delete();       


                flash('CPR gerada com sucesso !')->success();
                return redirect()->route('Painel.Financeiro.Reembolso.PagamentoDebite.index');
                
        }

        public function novoprestador_index() {

                $datas = DB::table("dbo.Correspondente_Temp")
                ->select('dbo.Correspondente_Temp.id as id',
                         'dbo.Correspondente_Temp.descricao as Descricao',
                         'dbo.Correspondente_Temp.data_nascimento as DataNascimento',
                         'dbo.Correspondente_Temp.endereco as Endereco',
                         'dbo.Correspondente_Temp.bairro as Bairro',
                         'dbo.Correspondente_Temp.cidade as Cidade',
                         'dbo.Correspondente_Temp.uf as UF',
                         'dbo.Correspondente_Temp.telefone as Telefone',
                         'dbo.Correspondente_Temp.email as Email',
                         'dbo.Correspondente_Temp.codigo as Codigo',
                         'dbo.Correspondente_Temp.identidade as Identidade',
                         'dbo.Bancos.Descricao as Banco',
                         'PLCFULL.dbo.Jurid_TipoContaBanco.descricao_tipo as TipoConta',
                         'dbo.Correspondente_Temp.agencia as Agencia',
                         'dbo.Correspondente_Temp.conta as Conta',
                         'dbo.Correspondente_Temp.created_at as DataSolicitacao',
                         'dbo.users.name as Advogado')
                ->leftjoin('dbo.users', 'dbo.Correspondente_Temp.user_id', 'dbo.users.id')
                ->leftjoin('dbo.Bancos', 'dbo.Correspondente_Temp.codigo_banco', 'dbo.Bancos.codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoContaBanco', 'dbo.Correspondente_Temp.tipoconta', 'PLCFULL.dbo.Jurid_TipoContaBanco.codigo')
                ->orderBy('dbo.Correspondente_Temp.id', 'asc')
                ->whereNotNull('dbo.Correspondente_Temp.codigo')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Correspondente.NovoPrestador.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function novoprestador_revisar($id) {

                $datas = DB::table("dbo.Correspondente_Temp")
                ->select('dbo.Correspondente_Temp.id as id',
                         'dbo.Correspondente_Temp.descricao as Descricao',
                         'dbo.Correspondente_Temp.data_nascimento as DataNascimento',
                         'dbo.Correspondente_Temp.endereco as Endereco',
                         'dbo.Correspondente_Temp.bairro as Bairro',
                         'dbo.Correspondente_Temp.cidade as Cidade',
                         'dbo.Correspondente_Temp.cep as Cep',
                         'dbo.Correspondente_Temp.uf as UF',
                         'dbo.Correspondente_Temp.telefone as Telefone',
                         'dbo.Correspondente_Temp.celular as Celular',
                         'dbo.Correspondente_Temp.email as Email',
                         'dbo.Correspondente_Temp.codigo as Codigo',
                         'dbo.Correspondente_Temp.identidade as Identidade',
                         'dbo.Bancos.Descricao as Banco',
                         'PLCFULL.dbo.Jurid_TipoContaBanco.descricao_tipo as TipoConta',
                         'dbo.Correspondente_Temp.agencia as Agencia',
                         'dbo.Correspondente_Temp.conta as Conta',
                         'dbo.Correspondente_Temp.created_at as DataSolicitacao',
                         'dbo.users.name as Advogado',
                         'dbo.users.id as AdvogadoID',
                         'dbo.users.email as AdvogadoEmail')
                ->leftjoin('dbo.users', 'dbo.Correspondente_Temp.user_id', 'dbo.users.id')
                ->leftjoin('dbo.Bancos', 'dbo.Correspondente_Temp.codigo_banco', 'dbo.Bancos.codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoContaBanco', 'dbo.Correspondente_Temp.tipoconta', 'PLCFULL.dbo.Jurid_TipoContaBanco.codigo')
                ->where('dbo.Correspondente_Temp.id', $id)
                ->first();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.Correspondente.NovoPrestador.revisar', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function novoprestador_revisado(Request $request) {

              $id = $request->get('id');
              $descricao = $request->get('descricao');
              $advogado_nome = $request->get('advogado');
              $advogado_id = $request->get('advogado_id');
              $advogado_email = $request->get('advogado_email');
              $datanascimento = $request->get('datanascimento');
              $endereco = $request->get('endereco');
              $bairro = $request->get('bairro');
              $cidade = $request->get('cidade');
              $cep = $request->get('cep');
              $uf = $request->get('uf');
              $telefone = $request->get('telefone');
              $celular = $request->get('celular');
              $email = $request->get('email');
              $codigo = $request->get('cpf_cnpj');
              $identidade = $request->get('identidade');
              $banco = $request->get('banco');
              $tipoconta = $request->get('tipoconta');
              $agencia = $request->get('agencia');
              $conta = $request->get('conta');
              $carbon= Carbon::now();


              $verifica = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Codigo')->where('Codigo','=', $codigo)->value('Codigo');

              if($verifica == null) {

              //Grava na Jurid_Advogado
              $values = array('Codigo' => $codigo, 
                             'Dt_cad' => $carbon, 
                             'Nome' => $descricao, 
                             'Razao' => $descricao, 
                             'Endereco' => $endereco, 
                             'Bairro' => $bairro,
                             'Cidade' => $cidade,
                             'Cep' => $cep,
                             'UF' => $uf,
                             'Pais' => 'Brasil',
                             'Fone1' => $telefone,
                             'Obs' => 'Correspondente cadastrado pelo Portal no fluxo de nova contratação.',
                             'Status' => 'Ativo',
                             'Cad_por' => 'portal',
                             'E_mail' => $email,
                             'Correspondente' => '1',
                             'dt_entrada' => $carbon,
                             'CUSTO_CLIENTE_PERC' => '1',
                             'Dt_Nasc' => $datanascimento,
                             'VisualizarAdvPasta' => '1',
                             'VisualizarAdvAgenda' => '1',
                             'VisualizarCorrespPasta' => '1',
                             'VisualizarDelegaAgenda' => '1');
             DB::table('PLCFULL.dbo.Jurid_Advogado')->insert($values);

             //Grava na Jurid_CliFor
             $values = array('Codigo' => $codigo, 
                             'Dt_cad' => $carbon, 
                             'Nome' => $descricao, 
                             'Razao' => $descricao, 
                             'Endereco' => $endereco, 
                             'Bairro' => $bairro,
                             'Cidade' => $cidade,
                             'Cep' => $cep,
                             'UF' => $uf,
                             'Pais' => 'Brasil',
                             'Fone1' => $telefone,
                             'Obs' => 'Correspondente cadastrado pelo Portal no fluxo de nova contratação.',
                             'Status' => 'Ativo',
                             'Cad_por' => 'portal',
                             'E_mail' => $email,
                             'TipoCF' => 'F',
                             'banco' => $banco,
                             'agencia' => $agencia,
                             'conta' => $conta,
                             'pessoa_fisica' => '0',
                             'StatusFornecedor' => 'Ativo',
                             'Orgao_Publico' => '0');
             DB::table('PLCFULL.dbo.Jurid_CliFor')->insert($values);

              } else {

                DB::table('PLCFULL.dbo.Jurid_Advogado')
                ->where('Codigo', $codigo)
                ->limit(1) 
                ->update(array('Nome' => $descricao, 
                               'Razao' => $codigo, 
                               'Endereco' => $endereco, 
                               'Bairro' => $bairro,
                               'Cidade' => $cidade,
                               'Cep' => $cep,
                               'UF' => $uf,
                               'Pais' => 'Brasil',
                               'Fone1' => $telefone,
                               'Status' => 'Ativo',
                               'Cad_por' => 'portal',
                               'E_mail' => $email,
                               'Correspondente' => '1',
                               'CUSTO_CLIENTE_PERC' => '1',
                               'Dt_Nasc' => $datanascimento,
                               'VisualizarAdvPasta' => '1',
                               'VisualizarAdvAgenda' => '1',
                               'VisualizarCorrespPasta' => '1',
                               'VisualizarDelegaAgenda' => '1'));

                DB::table('PLCFULL.dbo.Jurid_CliFor')
                ->where('Codigo', $codigo)
                ->limit(1) 
                ->update(array('Nome' => $descricao, 
                             'Razao' => $descricao, 
                             'Endereco' => $endereco, 
                             'Bairro' => $bairro,
                             'Cidade' => $cidade,
                             'Cep' => $cep,
                             'UF' => $uf,
                             'Pais' => 'Brasil',
                             'Fone1' => $telefone,
                             'Status' => 'Ativo',
                             'E_mail' => $email,
                             'TipoCF' => 'F',
                             'banco' => $banco,
                             'agencia' => $agencia,
                             'conta' => $conta,
                             'pessoa_fisica' => '0',
                             'StatusFornecedor' => 'Ativo',
                             'Orgao_Publico' => '0'));               

              }

             //Update na Correspondente_Matrix
             DB::table('dbo.Correspondente_Matrix')
             ->where('email', $email)
             ->limit(1) 
             ->update(array('nome' => $descricao, 'codigo' => $codigo, 'telefone' => $telefone, 'celular' => $celular, 'status_id' => '1', 'classificacao' => 'Utilizar', 'data_modificacao' => $carbon));

             //Grava na users
             $ultimos4caracteres = substr($codigo,-4);
             $senha = 'plc@'.$ultimos4caracteres;

             $password = bcrypt($senha);

             $value1 = array(
                'name' => $descricao, 
                'email' => $email, 
                'password' => $password, 
                'cpf' => $codigo);
            DB::table('dbo.users')->insert($value1);

            $user_id = DB::table('dbo.users')->select('id')->where('cpf','=', $codigo)->orderBy('id', 'desc')->value('id');

             //Grava na profile_user
             $values = array(
                'profile_id' => '1', 
                'user_id' => $user_id);
                DB::table('dbo.profile_user')->insert($values); 

             //Deleta na tabela temp
             DB::table('dbo.Correspondente_Temp')->where('id', $id)->delete();        

             //Envia email de boas vindas em copia para o Adv e Isabella Silveira
             Mail::to($email)
             ->cc($advogado_email)
             ->send(new NovoUsuario($descricao, $email, $senha));

             return redirect()->route('Painel.Financeiro.NovoPrestador.index');

        }

        public function guiascustas_anenxos($numerodebite) {
                 //Busco os arquivos gravados na GED
                 $datas = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
                 ->where('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
                 ->where('PLCFULL.dbo.Jurid_Ged_Main.Tabela_OR', 'Debite')
                 ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
                 ->where('PLCFULL.dbo.Jurid_Ged_Main.Tabela_OR', 'Debite')
                 ->orderby('PLCFULL.dbo.Jurid_Ged_Main.Data', 'asc')
                 ->get();
 
                 $QuantidadeAnexos = $datas->count();
 
                 return view('Painel.Financeiro.GuiasCusta.anexos', compact('numerodebite','datas', 'QuantidadeAnexos')); 
        }

        public function guiascusta_importaranexos(Request $request) {

                $numerodebite = $request->get('numerodebite');
                $carbon= Carbon::now();
                $dataehora = $carbon->format('dmY_His');
      
                //Foreach Anexos
                $image = $request->file('select_file');
      
                foreach($image as $index => $image) {
      
                 $new_name = 'solicitacaodeguiasdecusta_'. $numerodebite . '_' . $image->getClientOriginalName() . '.'  . $image->getClientOriginalExtension();
                 $image->storeAs('guiascustas', $new_name);
                
                Storage::disk('reembolso-local')->put($new_name, File::get($image));
                
                //Grava no GED
                $values = array(
                      'Tabela_OR' => 'Debite',
                      'Codigo_OR' => $numerodebite,
                      'Id_OR' => $numerodebite,
                      'Descricao' => $image->getClientOriginalName(),
                      'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\reembolso/'.$new_name, 
                      'Data' => $carbon,
                      'Nome' => $image->getClientOriginalName(),
                      'Responsavel' => 'portal.plc',
                      'Arq_tipo' => $image->getClientOriginalExtension(),
                      'Arq_Versao' => '1',
                      'Arq_Status' => 'Guardado',
                      'Arq_usuario' => 'portal.plc',
                      'Arq_nick' => $new_name,
                      'Obs' => $image->getClientOriginalName());
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
      
                }
      
               //Busco os arquivos gravados na GED
               $datas = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
               ->where('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
               ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
               ->get();
      
               $QuantidadeAnexos = $datas->count();
      
               return view('Painel.Financeiro.GuiasCusta.anexos', compact('numerodebite','datas', 'QuantidadeAnexos')); 
      
      
      
              }


        public function guiascustas_solicitante_index() {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'dbo.Financeiro_GuiasCustas_Matrix.observacao as Observacao',
                         'dbo.Jurid_Nota_Motivos.id as MotivoID',
                         'dbo.Jurid_Nota_Motivos.descricao as Motivo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_GuiasCustas_Status.id as StatusID',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_GuiasCustas_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                ->where('PLCFULL.dbo.Jurid_Debite.Advogado', Auth::user()->cpf)
                ->whereIn('dbo.Financeiro_GuiasCustas_Status.id', array(1,2,3))
                ->get();

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.Solicitante.index', compact('carbon','datas','dataehora','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function guiascustas_solicitante_historico() {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'dbo.Financeiro_GuiasCustas_Matrix.observacao as Observacao',
                         'dbo.Jurid_Nota_Motivos.id as MotivoID',
                         'dbo.Jurid_Nota_Motivos.descricao as Motivo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_GuiasCustas_Status.id as StatusID',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_GuiasCustas_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                ->where('PLCFULL.dbo.Jurid_Debite.Advogado', Auth::user()->cpf)
                ->whereIn('dbo.Financeiro_GuiasCustas_Status.id', array(4,5))
                ->get();

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.Solicitante.historico', compact('carbon','datas','dataehora','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function guiascustas_solicitante_novasolicitacao(Request $request) {

                $carbon= Carbon::now();
                $codigopasta = $request->get('dado');
                $dataehora = $carbon->format('d/m/Y H:i:s');
   
                $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                        'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                        'PLCFULL.dbo.Jurid_Pastas.PRConta',
                        'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                        'PLCFULL.dbo.Jurid_Pastas.Advogado',
                        'dbo.users.name as Responsavel',
                        'PLCFULL.dbo.Jurid_Pastas.OutraParte',
                        'PLCFULL.dbo.Jurid_Pastas.Setor as SetorCodigo',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                        'PLCFULL.dbo.Jurid_Pastas.Unidade as UnidadeCodigo',
                        'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                        'PLCFULL.dbo.Jurid_Tribunais.Descricao as Tribunal',
                        'PLCFULL.dbo.Jurid_Varas.Descricao as Vara',
                        'PLCFULL.dbo.Jurid_Pastas.RefCliente',
                        'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                        'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                        'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                        'PLCFULL.dbo.Jurid_Contratos.Despesas as Status',
                        'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                        'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Pastas.Advogado', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Tribunais', 'PLCFULL.dbo.Jurid_Pastas.Tribunal', 'PLCFULL.dbo.Jurid_Tribunais.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Varas', 'PLCFULL.dbo.Jurid_Pastas.Varas', 'PLCFULL.dbo.Jurid_Varas.Codigo')
                ->where('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp','=',$codigopasta)
                ->orwhere('PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros', '=', $codigopasta)
                ->first();
   
                //Verifica se achou
                if($datas == null) {
                   \Session::flash('message', ['msg'=>'Não foi possível encontrar nenhuma pasta com este código ou número de processo.', 'class'=>'red']);
                   return redirect()->route('Painel.Financeiro.GuiasCustas.Solicitante.index');
                 }

                      
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
                         
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'Hist_Notificacao.status', 'dbo.users.*')  
                ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->get();
                  
                  
                return view('Painel.Financeiro.GuiasCusta.Solicitante.dados', compact('carbon','dataehora','datas','totalNotificacaoAbertas', 'notificacoes'));

        }

        public function guiascustas_solicitante_store(Request $request) {


                $carbon= Carbon::now();
                $dataehora = $carbon->format('Y-m-d');
                $dataehoraBR = $carbon->format('d-m-Y H:i:s');
                $datahoje = $carbon->format('Y-m-d');    

                //Pega os dados do form 
                $pasta = $request->get('pasta');
                $numeroprocesso = $request->get('numeroprocesso');
                $descricaopasta = $request->get('descricaopasta');
                $setordescricao = $request->get('setordescricao');
                $setor = $request->get('setor');
                $unidadedescricao = $request->get('unidadedescricao');
                $unidade = $request->get('unidade');
                $grupo = $request->get('grupo');
                $clienterazao = $request->get('clienterazao');
                $cliente = $request->get('cliente');
                $tiposervico = $request->get('tiposervico');
                $quantidade = str_replace (',', '.', str_replace ('.', '', $request->get('quantidade')));
                $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));
                $observacao = str_replace ('&amp;', '&', $request->get('observacao'));
                $observacaoadicionais = $request->get('observacaoadicionais');
                $status = $request->get('status');
                $prconta = $request->get('prconta');
                $numeroprocesso = $request->get('numeroprocesso');
                $autorizadocliente = $request->get('autorizadocliente');
                $statuscontrato = $request->get('statuscontrato');
      
                $outraparte = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('OutraParte')->where('Codigo_Comp','=', $pasta)->value('OutraParte');
      
                //Gera um novo numero pro Debite
                $ultimonumero = Correspondente::orderBy('Numero', 'desc')->value('Numero'); 
                $numero = $ultimonumero + 1;
      
      
                //Gera o Debite
                $model2 = new Correspondente();
                $model2->Numero =  $numero;
                $model2->Advogado = Auth::user()->cpf; 
                $model2->Cliente = $cliente; 
                $model2->Data = $datahoje;
                $model2->Tipo = 'D';
                $model2->Obs =  'Número da solicitação: ' . $numero . ' ' . $observacao . ' ' . $observacaoadicionais;
                $model2->Status = '4';
                $model2->Hist = 'Número da solicitação: ' . $numero . ' Solicitação de pagamento de guia de custa inserida pelo(a): '. Auth::user()->name .' no Portal PL&C no valor total de: R$ ' . $valor . ' na data: ' . $carbon->format('d-m-Y H:i:s');
                $model2->ValorT = $valor;
                $model2->Usuario = 'portal.plc';
                $model2->DebPago = 'N';
                $model2->TipoDeb = $tiposervico;
                $model2->AdvServ = Auth::user()->cpf;
                $model2->Setor = $setor;
                $model2->Pasta = $pasta;
                $model2->Unidade = $unidade;
                $model2->PRConta = $prconta;
                $model2->Valor_Adv = $valor;
                $model2->Quantidade = '1'; 
                $model2->ValorUnitario_Adv = $valor;
                $model2->ValorUnitarioCliente = $valor;
                $model2->Revisado_DB = '1';
                $model2->moeda = 'R$';
                $model2->save();
      
                $image = $request->file('select_file');
                //Foreach anexos
                foreach($image as $index => $image) {

                //Anexo Guia de Custa
                $new_name = 'solicitacaodeguiasdecusta_'. $numero . '_' . $image->getClientOriginalName() . '.'  . $image->getClientOriginalExtension();
                $image->storeAs('guiascusta', $new_name);
                Storage::disk('reembolso-local')->put($new_name, File::get($image));

                //Grava no GED
                $values = array(
                      'Tabela_OR' => 'Debite',
                      'Codigo_OR' => $numero,
                      'Id_OR' => $numero,
                      'Descricao' => $image->getClientOriginalName(),
                      'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\guiascusta/'.$new_name, 
                      'Data' => $carbon,
                      'Nome' => $image->getClientOriginalName(),
                      'Responsavel' => 'portal.plc',
                      'Arq_tipo' => $image->getClientOriginalExtension(),
                      'Arq_Versao' => '1',
                      'Arq_Status' => 'Guardado',
                      'Arq_usuario' => 'portal.plc',
                      'Arq_nick' => $new_name,
                      'Obs' => $observacao . ' ' . $observacaoadicionais,
                      'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

                }
                //Fim Foreach

                //Anexo Comprovante autorização cliente
                $comprovante_file = $request->file('comprovante_file');
                if($comprovante_file != null) {
                $new_name2 = 'guiascustacomprovanteautorizacao' . '_' . $numero . '_' . $dataehora . '.'  . $comprovante_file->getClientOriginalExtension();
                $comprovante_file->storeAs('guiascusta', $new_name2);
                Storage::disk('reembolso-local')->put($new_name2, File::get($comprovante_file));

                //Grava no GED
                $values = array(
                      'Tabela_OR' => 'Debite',
                      'Codigo_OR' => $numero,
                      'Id_OR' => $numero,
                      'Descricao' => $comprovante_file->getClientOriginalName(),
                      'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\guiascusta/'.$new_name, 
                      'Data' => $carbon,
                      'Nome' => $comprovante_file->getClientOriginalName(),
                      'Responsavel' => 'portal.plc',
                      'Arq_tipo' => $comprovante_file->getClientOriginalExtension(),
                      'Arq_Versao' => '1',
                      'Arq_Status' => 'Guardado',
                      'Arq_usuario' => 'portal.plc',
                      'Arq_nick' => $new_name2,
                      'Obs' => $observacao . ' ' . $observacaoadicionais,
                      'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
                }

                // //Se o valor for >= 1000 ele precisa da revisão tecnica 
                // if($valor >= 1000) {

                //   //Grava na tabela Matrix 
                //   $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '1', 'observacao' => $observacao, 'observacoesadicionais' => $observacaoadicionais,'autorizadocliente' => $autorizadocliente ,'status_cobravel' => $statuscontrato);
                //   DB::table('dbo.Financeiro_GuiasCustas_Matrix')->insert($values);

                //   //Update Jurid_Debite
                //   DB::table('PLCFULL.dbo.Jurid_Debite')
                //   ->where('Numero', $numero)  
                //   ->limit(1) 
                //   ->update(array('Revisado_DB'=> '1'));

                //   //Grava na tabela Hist
                //   $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '1');
                //   DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);

                // }
                // //Se o valor for menor que 1000 vai direto para o financeiro
                // else {

                  //Grava na tabela Matrix 
                  $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '2', 'observacao' => $observacao, 'observacoesadicionais' => $observacaoadicionais ,'autorizadocliente' => $autorizadocliente ,'status_cobravel' => $statuscontrato);
                  DB::table('dbo.Financeiro_GuiasCustas_Matrix')->insert($values);

                  //Grava na tabela Hist
                  $values = array('user_id' => Auth::user()->id, 'numerodebite' => $numero, 'data' => $carbon, 'status_id' => '2');
                  DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);
      
                // }
      
               $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
               ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                        'PLCFULL.dbo.Jurid_Debite.Pasta',
                        'PLCFULL.dbo.Jurid_Debite.Quantidade',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                        'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                        'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                        'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                        'PLCFULL.dbo.Jurid_Debite.Data as DataSolicitacao',
                        'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                        'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                        'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
               ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
               ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
               ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
               ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numero)
               ->get();
                         
               //Envia para a Roberta
               Mail::to('roberta.povoa@plcadvogados.com.br')
               ->cc(Auth::user()->email, 'daniele.oliveira@plcadvogados.com.br')
               ->send(new SolicitacaoGuiaCusta($datas));
       
                $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => '242', 'tipo' => '15', 'obs' => 'Guias de custa: Nova solicitação de guias de custa gerada.' ,'status' => 'A');
                DB::table('dbo.Hist_Notificacao')->insert($values4);

                //Envia notificação para o proprio solicitante
                $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '15', 'obs' => 'Guias de custa: Nova solicitação de guias de custa gerada.' ,'status' => 'A');
                DB::table('dbo.Hist_Notificacao')->insert($values4);
            
               flash('Nova solicitação de guia de custa cadastrada com sucesso!')->success();
               return redirect()->route('Painel.Financeiro.GuiasCustas.Solicitante.index');
         
        }

        public function guiascustas_revisao_index() {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'dbo.Financeiro_GuiasCustas_Matrix.observacao as Observacao',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_GuiasCustas_Status.id as StatusID',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')
                ->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id) 
                ->whereIn('dbo.Financeiro_GuiasCustas_Status.id', array(1,2,3,4))
                ->get();

                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.Revisao.index', compact('carbon','datas','dataehora','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function guiascustas_revisao_revisar($numerodebite) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                         'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_GuiasCustas_Status.id as StatusID',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_Debite.Tipodeb as TipoDebiteCodigo',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
                         'dbo.Financeiro_GuiasCustas_Matrix.visualizada',
                         'dbo.Financeiro_GuiasCustas_Matrix.autorizadocliente',
                         'dbo.Financeiro_GuiasCustas_Matrix.status_cobravel',
                         'dbo.users.id as SolicitanteID',
                         'dbo.users.email as SolicitanteEmail',
                         'dbo.users.cpf as SolicitanteCPF',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                         'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                         'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                ->first();                

                $motivos = DB::table('dbo.Jurid_Nota_Motivos')
                ->where('ativo','=','S')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.Revisao.revisar', compact('dataehora','datas','motivos','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function guiascustas_revisao_revisado(Request $request) {

                $carbon= Carbon::now();
                $numerodebite = $request->get('numerodebite');
                $prconta = $request->get('prconta');
                $pasta = $request->get('pasta');
                $numeroprocesso = $request->get('numeroprocesso');
                $descricaopasta = $request->get('descricaopasta');
                $solicitantenome = $request->get('solicitantenome');
                $solicitanteid = $request->get('solicitanteid');
                $solicitanteemail = $request->get('solicitanteemail');
                $solicitantecpf = $request->get('solicitantecpf');
                $setordescricao = $request->get('setordescricao');
                $setor = $request->get('setor');
                $unidadedescricao = $request->get('unidadedescricao');
                $unidade = $request->get('unidade');
                $clienterazao = $request->get('clienterazao');
                $cliente = $request->get('cliente');
                $tiposervico = $request->get('tiposervico');
                $dataservico = $request->get('dataservico');
                $datasolicitacao = $request->get('datasolicitacao');
                $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
                $statuspasta = $request->get('statuspasta');
                $contrato = $request->get('contratocodigo');
                $ressalva = $request->get('ressalva');
                $statusescolhido = $request->get('statusescolhido');
                $motivo = $request->get('motivo');
                $motivodescricao = $request->get('motivodescricao');
                $observacao = $request->get('observacao');
                $hist = $request->get('hist');
                $histreprovada = $request->get('histreprovada');
                $histcancelada = $request->get('histcancelada');
                $statusdebite = $request->get('statusdebite');
                $dataehora = $carbon->format('dmY_His');

                //Se foi aprovado
                if($statusescolhido == "aprovar") {

                     //Update na Matrix
                     DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                     ->where('numerodebite', '=' ,$numerodebite) 
                     ->limit(1) 
                     ->update(array('status_id' => '2'));

                     $values = array('user_id' => Auth::user()->id, 
                     'numerodebite' => $numerodebite, 
                     'data' => $carbon, 
                     'status_id' => '2');
                     DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);

                     //Update no Debite
                     DB::table('PLCFULL.dbo.Jurid_Debite')
                     ->limit(1)
                     ->where('Numero', '=', $numerodebite)     
                     ->update(array('Revisado_DB' => '1','Hist' => $hist));

                     //Envia notificação 


                     //Envia e-mail informando que foi aprovado pela revisão tecnica


                }
                //Se foi reprovado
                else if($statusescolhido == "reprovar") {

                      //Update Debite
                      DB::table('PLCFULL.dbo.Jurid_Debite')
                      ->where('Numero', '=' ,$numerodebite) 
                      ->limit(1) 
                      ->update(array('Hist' => $histreprovada . $motivodescricao));

                       //Update Financeiro_Reembolso_Matrix
                       DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                       ->where('numerodebite', '=' ,$numerodebite) 
                       ->limit(1) 
                       ->update(array('status_id' => '4', 'motivo_id' => $motivo, 'observacao' => $observacao));

                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                        'numerodebite' => $numerodebite, 
                                        'data' => $carbon, 
                                        'status_id' => '4');
                        DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);

                        //Informa ao solicitante por notificação
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '15', 'obs' => 'Guias de custas: Solicitação de guia de custa reprovada pela revisão técnica.', 'status' => 'A');
                         DB::table('dbo.Hist_Notificacao')->insert($values3);

                         $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                         ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                          'PLCFULL.dbo.Jurid_Debite.Pasta',
                          'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                          'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                          'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                          'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                          'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                          'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                          'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                          'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                          'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                          'dbo.users.name as SolicitanteNome',
                          'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                          'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                          'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                          'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                         ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                         ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                         ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                         ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                         ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                         ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                         ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                         ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                         ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                         ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_GuiasCustas_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                         ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                         ->limit(1)
                         ->get();
 
                         Mail::to($solicitanteemail)
                         ->send(new SolicitacaoGuiasCustaReprovada($datas, $motivodescricao));

                }
                //Se foi cancelado
                else {

                        //Update Jurid_Debite (Coloca status 3 de cancelado)
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->where('Numero', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('Status' => '3','Revisado_DB' => '1', 'Obs' => $observacao, 'Hist' => $histcancelada . $motivodescricao));

                        //Update Matrix
                        DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '5', 'motivo_id' => $motivo, 'observacao' => $observacao));
               
                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                       'numerodebite' => $numerodebite, 
                                       'data' => $carbon, 
                                       'status_id' => '5');
                         DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);

                        //Informa ao solicitante por notificação
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '15', 'obs' => 'Guias Custas: Solicitação de guia de custa cancelada pela revisão técnica.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);

                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                        ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_GuiasCustas_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();

                        Mail::to($solicitanteemail)
                        ->send(new SolicitacaoGuiaCustaCancelada($datas, $motivodescricao));
               
                }

                return redirect()->route("Painel.Financeiro.GuiasCustas.Revisao.index");

        }

        public function guiascustas_conciliacaobancaria_index() {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'dbo.Financeiro_GuiasCustas_Matrix.observacao as Observacao',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_GuiasCustas_Status.id as StatusID',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'dbo.Financeiro_GuiasCustas_Matrix.user_id', 'dbo.users.id')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->whereIn('dbo.Financeiro_GuiasCustas_Status.id', array(2,3))
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.ConciliacaoBancaria.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 
        }

        public function guiascustas_conciliacaobancaria_historico() {

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Debite.Hist',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'dbo.Financeiro_Reembolso_Matrix.observacao as Observacao',
                         'dbo.Jurid_Nota_Motivos.id as MotivoID',
                         'dbo.Jurid_Nota_Motivos.descricao as Motivo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Financeiro_Reembolso_Status.id as StatusID',
                         'dbo.Financeiro_Reembolso_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_Reembolso_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor')
                ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')
                ->join('dbo.Financeiro_Reembolso_Status', 'dbo.Financeiro_Reembolso_Matrix.status_id', 'dbo.Financeiro_Reembolso_Status.id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_Reembolso_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                ->whereIn('dbo.Financeiro_Reembolso_Status.id', array(4,5))
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.ConciliacaoBancaria.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes')); 
        }


        public function guiascustas_conciliacaobancaria_revisar($numerodebite) {

                $carbon= Carbon::now();
                $dataehora = $carbon->format('d-m-Y H:i:s');
                $datapagamento = date('Y-m-d', strtotime('+1 weekdays'));

                $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Pastas.PRConta',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_Pastas.Descricao as Descricao',
                         'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_GuiasCustas_Status.id as StatusID',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_Debite.Tipodeb as TipoDebiteCodigo',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
                         'dbo.Financeiro_GuiasCustas_Matrix.visualizada',
                         'dbo.Financeiro_GuiasCustas_Matrix.autorizadocliente',
                         'dbo.Financeiro_GuiasCustas_Matrix.status_cobravel',
                         'dbo.users.id as SolicitanteID',
                         'dbo.users.email as SolicitanteEmail',
                         'dbo.users.cpf as SolicitanteCPF',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                         'PLCFULL.dbo.Jurid_Contratos.Numero as ContratoCodigo',
                         'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')
                ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
                ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                ->first();

                $contratocodigo = $datas->ContratoCodigo;

                $contratos = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
                ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'PLCFULL.dbo.Jurid_Desp_Contrato.Codigo')
                ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo', 'Descricao', 'PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp as Status', 'PLCFULL.dbo.Jurid_Desp_Contrato.Valor as Valor', 'PLCFULL.dbo.Jurid_Desp_Contrato.Quantidade', 'PLCFULL.dbo.Jurid_Desp_Contrato.ValorCliente')  
                ->whereNull('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
                ->orwhere('PLCFULL.dbo.Jurid_Desp_Contrato.Contrato', $contratocodigo)
                // ->where('PLCFULL.dbo.Jurid_Desp_Contrato.Aut_Desp', '!=', 'Bloqueada')
                ->orderby('Descricao', 'asc')
                ->get();

                $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  

                $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')->where('Tipo','P')->where('Codigo', 'NOT LIKE', '%.')->orderby('Codigo', 'asc')->get();  

                $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
                ->where('DaEmpresa', '0')
                ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
                ->where('Status', '1')
                ->orderby('Descricao', 'ASC')
                ->get();               

                
                $motivos = DB::table('dbo.Jurid_Nota_Motivos')
                ->where('ativo','=','S')
                ->get();
                
                $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                                 ->where('status', 'A')
                                 ->where('destino_id','=', Auth::user()->id)
                                 ->count();
                               
                $notificacoes = DB::table('dbo.Hist_Notificacao')
                                 ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                                 'data',
                                 'id_ref', 
                                 'user_id',
                                 'tipo', 
                                 'obs',
                                 'Hist_Notificacao.status', 
                                 'dbo.users.*')  
                                 ->limit(3)
                                 ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                                 ->where('dbo.Hist_Notificacao.status','=','A')
                                 ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                                 ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                                 ->get();
        
                return view('Painel.Financeiro.GuiasCusta.ConciliacaoBancaria.revisar', compact('dataehora','tiposdoc','bancos','tiposlan','datas','contratos','datapagamento','motivos','totalNotificacaoAbertas', 'notificacoes')); 

        }

        public function guiascustas_conciliacaobancaria_revisado(Request $request) {

                $carbon= Carbon::now();
                $numerodebite = $request->get('numerodebite');
                $prconta = $request->get('prconta');
                $pasta = $request->get('pasta');
                $numeroprocesso = $request->get('numeroprocesso');
                $descricaopasta = $request->get('descricaopasta');
                $solicitantenome = $request->get('solicitantenome');
                $solicitanteid = $request->get('solicitanteid');
                $solicitanteemail = $request->get('solicitanteemail');
                $solicitantecpf = $request->get('solicitantecpf');
                $setordescricao = $request->get('setordescricao');
                $setor = $request->get('setor');
                $unidadedescricao = $request->get('unidadedescricao');
                $unidade = $request->get('unidade');
                $grupo = $request->get('grupo');
                $clienterazao = $request->get('clienterazao');
                $cliente = $request->get('cliente');
                $tiposervico = $request->get('tiposervico');
                $dataservico = $request->get('dataservico');
                $datasolicitacao = $request->get('datasolicitacao');
                $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
                $statuspasta = $request->get('statuspasta');
                $contrato = $request->get('contratocodigo');
                $ressalva = $request->get('ressalva');
                $statusescolhido = $request->get('statusescolhido');
                $motivo = $request->get('motivo');
                $motivodescricao = $request->get('motivodescricao');
                $observacao = $request->get('observacao');
                $hist = $request->get('hist');
                $histreprovada = $request->get('histreprovada');
                $histcancelada = $request->get('histcancelada');
                $histbaixado = $request->get('histbaixado');
                $datapagamento = $request->get('datapagamento');
                $datavencimento = $request->get('datavencimento');
                $statusdebite = $request->get('statusdebite');
                $codigo_banco = $request->get('portador'); 
                $data_concil = $request->get('dataconciliacao');
                $data_baixa = $request->get('databaixa');
                $solicitante_codigo = $request->get('solicitante_codigo');
                $tipodoc = $request->get('tipodocumento');
                $tipolan = $request->get('tipolan');
                $dataehora = $carbon->format('dmY_His');
                $gerarcpr = $request->get('gerarcpr');
                $datavencimento = $request->get('datavencimento');

                //Se foi selecionado a opção: Baixar solicitação
                if($statusescolhido == "baixarsolicitacao") {

                        $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                        $numdoc = $ultimonumprc + 1;


                        //Se for Reembonsavel
                        if($statusdebite == 1) {

                        //Grava na ContPrBx
                        $values= array(
                                'Tipodoc' => $tipodoc,
                                'Numdoc' => $numdoc,
                                'Cliente' => $cliente,
                                'Tipo' => 'P',
                                'TipodocOr' => $tipodoc,
                                'NumDocOr' => '',
                                'Tipolan' => $tipolan,
                                'Valor' => $valor,
                                'Centro' => $setor,
                                'Dt_baixa' => $data_baixa,
                                'Portador' => $codigo_banco,
                                'Obs' => $observacao,
                                'Juros' => '0.00',
                                'Dt_Compet' => $carbon,
                                'DT_Concil' => $data_concil,
                                'Codigo_Comp' =>$pasta,
                                'Unidade' => $unidade,
                                'PRConta' => $prconta,
                                'Contrato' =>$contrato,
                                'Ident_Cpr' => '',
                                'origem_cpr' => '',
                                'Ident_Rate' => '',
                                'moeda' => 'R$');    
                        DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

                        //Se for pra gerar a CPR pro cliente
                        if($gerarcpr == "SIM") {

                        //Gero a CPR
                        $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                        $numdoc2 = $numdoc + 1;
                        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numdoc2));
        
                        $values= array(
                        'Tipodoc' => 'BOL',
                        'Numdoc' => $numdoc,
                        'Cliente' => $cliente,
                        'Tipo' => 'R',
                        'Centro' => $setor,
                        'Valor' => $valor,
                        'Dt_aceite' => $carbon->format('Y-m-d'),
                        'Dt_Vencim' => $datavencimento,
                        'Dt_Progr' => $datavencimento,
                        'Multa' => '0',
                        'Juros' => '0',
                        'Tipolan' => '16.02',
                        'Desconto' => '0',
                        'Baixado' => '',
                        'Portador' => $cliente,
                        'Status' => '2',
                        'Historico' => 'Solicitação de pagamento de guia de custa gerada no portal plc no valor total de: R$ ' . number_format($valor,2,",","."),
                        'Obs' => 'Solicitação de pagamento de guia de custa gerada no portal plc no valor total de: R$ ' . number_format($valor,2,",",".") . ' referente aos debite: ' . $numerodebite,
                        'Valor_Or' => $valor,
                        'Dt_Digit' => $carbon->format('Y-m-d'),
                        'Codigo_Comp' => $pasta,
                        'Unidade' => $unidade,
                        'Moeda' => 'R$',
                        'CSLL' => '0.00',
                        'COFINS' => '0.00',
                        'PIS' => '0.00',
                        'ISS' => '0.00',
                        'INSS' => '0.00',
                        'Contrato' => $contrato,
                        'Origem_cpr' => $numerodebite,
                        'numprocesso' => $numeroprocesso,
                        'cod_pasta' => $pasta);
                        DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);

                        //Update no debite
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->limit(1)
                        ->where('Numero', '=', $numerodebite)     
                        ->update(array('Status' => '1', 'Fatura' => $numdoc, 'Revisado_DB' => '1', 'DebPago' => 'S'));

                        }

                        //Update na Matrix
                        DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '4', 'observacao' => $observacao, 'status_cobravel' => 'Não reembolsável'));

                        } else {

                        //Grava na ContPrBx
                        $values= array(
                                'Tipodoc' => $tipodoc,
                                'Numdoc' => $numdoc,
                                'Cliente' => $cliente,
                                'Tipo' => 'P',
                                'TipodocOr' => $tipodoc,
                                'NumDocOr' => '',
                                'Tipolan' => $tipolan,
                                'Valor' => $valor,
                                'Centro' => $setor,
                                'Dt_baixa' => $data_baixa,
                                'Portador' => $codigo_banco,
                                'Obs' => $observacao,
                                'Juros' => '0.00',
                                'Dt_Compet' => $carbon,
                                'DT_Concil' => $data_concil,
                                'Codigo_Comp' =>$pasta,
                                'Unidade' => $unidade,
                                'PRConta' => $prconta,
                                'Contrato' =>$contrato,
                                'Ident_Cpr' => '',
                                'origem_cpr' => '',
                                'Ident_Rate' => '',
                                'moeda' => 'R$');    
                         DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

                        //Update na Matrix
                        DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '4', 'observacao' => $observacao, 'status_cobravel' => 'Reembolsável pelo cliente'));

                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->limit(1)
                        ->where('Numero', '=', $numerodebite)     
                        ->update(array('Status' => '0','Revisado_DB' => '1', 'DebPago' => 'S'));

                        }


                        $values = array('user_id' => Auth::user()->id, 
                        'numerodebite' => $numerodebite, 
                        'data' => $carbon, 
                        'status_id' => '4');
                        DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);
    
                        //Update Jurid_Default (Num + 1)    
                        $numprcNovo = $numdoc + 1;
                        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));
                              

                        //Grava na GED o comprovante de pagamento
                        $image = $request->file('select_file');
                        
                        foreach($image as $index => $image) {
                        $new_name = 'comprovantedepagamento_' . $numerodebite . '_' . $dataehora . '_' . $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();
                        $image->storeAs('guiascusta', $new_name);
                        Storage::disk('reembolso-local')->put($new_name, File::get($image));

                        //Insert Jurid_Ged_Main
                        $values = array(
                        'Tabela_OR' => 'Debite',
                        'Codigo_OR' => $numerodebite,
                        'Id_OR' => $numerodebite,
                        'Descricao' => $image->getClientOriginalName(),
                        'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\guiascusta/'.$new_name, 
                        'Data' => $carbon,
                        'Nome' => $image->getClientOriginalName(),
                        'Responsavel' => 'portal.plc',
                        'Arq_tipo' => $image->getClientOriginalExtension(),
                        'Arq_Versao' => '1',
                        'Arq_Status' => 'Guardado',
                        'Arq_usuario' => 'portal.plc',
                        'Arq_nick' => $new_name,
                        'Obs' => $observacao,
                        'Texto' => 'Comprovante de pagamento');
                        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

                        }
                        //Fim Foreach


                        //Insert na ProcMovimentação
                        $values = array('Codigo_Comp' => $pasta, 
                                        'Data' => $carbon->format('Y-m-d'),
                                        'Advogado' => $solicitantecpf, 
                                        'CodMov' => 'MOV086', 
                                        'Valor' => $valor, 
                                        'MCliente' => '1',
                                        'Descricao' => $observacao,
                                        'Arquivado' => '0',
                                        'Data_Mov' => $carbon,
                                        'documento_criado' => '0',
                                        'faturada' => '0',
                                        'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'),
                                        'usuario_inseriu' => 'portal.plc');
                        DB::table('PLCFULL.dbo.Jurid_ProCmOV')->insert($values);

                        $mov_id = DB::table('PLCFULL.dbo.Jurid_ProCmOV')
                                  ->select('Ident')
                                  ->where('Codigo_Comp','=', $pasta)
                                  ->where('Advogado', $solicitantecpf)
                                  ->where('CodMov', 'MOV086')
                                //   ->where('usuario_inseriu', 'portal.plc')
                                  ->orderby('Ident', 'desc')
                                  ->value('Ident');

                        //Update Jurid_Debite 
                         //Se for Reembolsavel
                        if($statusdebite == 1) {

                          //Se for pra gerar o debite sim coloca status(1) se não status 0

                          DB::table('PLCFULL.dbo.Jurid_Debite')
                          ->limit(1)
                          ->where('Numero', '=', $numerodebite)     
                          ->update(array('Status' => '0', 'DebPago' => 'S', 'Revisado_DB' => '1','Hist' => $histbaixado,'Mov' => $mov_id,'tipodocpag' => $tipodoc, 'numdocpag' => $numdoc, 'nomebanco' => $codigo_banco,'datapag' => $data_baixa));

                        } else {
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->limit(1)
                        ->where('Numero', '=', $numerodebite)     
                        ->update(array('Status' => '2','DebPago' => 'S','Revisado_DB' => '1','Hist' => $histbaixado,'Mov' => $mov_id,'tipodocpag' => $tipodoc, 'numdocpag' => $numdoc, 'nomebanco' => $codigo_banco,'datapag' => $data_baixa));
                        }


                        //Foreach para gravar os arquivos da GED na Movimentação
                        $arquivos = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
                        ->where('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
                        ->where('PLCFULL.dbo.Jurid_Ged_Main.Tabela_OR', '=', 'Debite')
                        ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
                        ->where('PLCFULL.dbo.Jurid_Ged_Main.Tabela_OR', '=', 'Debite')
                        ->get();

                        foreach ($arquivos as $arquivo) {

                                $values = array(
                                        'Tabela_OR' => 'Pastas',
                                        'Codigo_OR' => $pasta,
                                        'Id_OR' => $mov_id,
                                        'Descricao' => $arquivo->Descricao,
                                        'Link' => $arquivo->Link, 
                                        'Data' => $carbon,
                                        'Nome' => $arquivo->Nome,
                                        'Responsavel' => $arquivo->Responsavel,
                                        'Arq_tipo' => $arquivo->Arq_tipo,
                                        'Arq_Versao' => $arquivo->Arq_Versao,
                                        'Arq_Status' => $arquivo->Arq_Status,
                                        'Arq_usuario' => $arquivo->Arq_usuario,
                                        'Arq_nick' => $arquivo->Arq_nick,
                                        'Obs' => $arquivo->Obs,
                                        'Texto' => $arquivo->Texto);
                                        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

                                // //Grava na GedLig
                                // $values = array(
                                //         'Id_tabela_or' => 'Pastas',
                                //         'Id_codigo_or' => $pasta,
                                //         'Id_ID_OR' => $mov_id,
                                //         'Id_id_doc' => $arquivo->ID_doc,
                                //         'dt_inserido' => $carbon,
                                //         'usuario_insercao' => $arquivo->Arq_usuario);
                                // DB::table('PLCFULL.dbo.Jurid_GEDLig')->insert($values);  
                
                                // //Grava na Ged_Hist
                                // $values = array(
                                //         'Id_doc' => $arquivo->ID_doc,
                                //         'Data_hist' => $carbon,
                                //         'Hist_versao' => '1',
                                //         'Hist_link' => $arquivo->Link, 
                                //         'Hist_nick' => $arquivo->Arq_nick,
                                //         'Hist_usuario' => $arquivo->Arq_usuario);
                                //         DB::table('PLCFULL.dbo.Jurid_Ged_Hist')->insert($values);  
                                }
                                //Fim Foreach


                        //Envia e-mail
                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->leftjoin('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                        ->leftjoin('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->first();
                        
                        $solicitanteemail = DB::table('dbo.users')->select('email')->where('cpf','=', $solicitantecpf)->value('email');
                        $solicitanteid = DB::table('dbo.users')->select('id')->where('cpf','=', $solicitantecpf)->value('id');

                        //Envia notificação portal
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '7', 'obs' => 'Pagamento de guia de custa: A solicitação: '. $numerodebite . ' foi baixada pelo financeiro.' ,'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);


                        //Envia email informando que a Solicitação foi paga 
                        Mail::to($solicitanteemail)
                        ->cc(Auth::user()->email, 'gumercindo.ribeiro@plcadvogados.com.br', 'daniele.oliveira@plcadvogados.com.br')
                        ->send(new SolicitacaoGuiaCustaBaixado($datas));

                
                }
                //Se foi reprovado 
                else if($statusescolhido == "reprovar") {


                        //Update Hist
                        DB::table('PLCFULL.dbo.Jurid_Debite')
                        ->where('Numero', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('Hist' => $histreprovada . $motivodescricao));

                         //Update Financeiro_Reembolso_Matrix
                        DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                        ->where('numerodebite', '=' ,$numerodebite) 
                        ->limit(1) 
                        ->update(array('status_id' => '3', 'motivo_id' => $motivo, 'observacao' => $observacao));

                        //Insert na Hist
                        $values = array('user_id' => Auth::user()->id, 
                                        'numerodebite' => $numerodebite, 
                                        'data' => $carbon, 
                                        'status_id' => '3');
                        DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);

                        //Informa ao solicitante por notificação
                        $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '15', 'obs' => 'Guias de custas: Solicitação de guia de custa reprovada pela equipe do financeiro.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);


                        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                         'PLCFULL.dbo.Jurid_Debite.Pasta',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                         'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                         'dbo.users.name as SolicitanteNome',
                         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                         'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                         'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                        ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                        ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                        ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_GuiasCustas_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                        ->limit(1)
                        ->get();

                        Mail::to($solicitanteemail)
                        ->send(new SolicitacaoGuiasCustaReprovada($datas, $motivodescricao));
                }
                //Se foi cancelado
                else {

                            //Update Jurid_Debite (Coloca status 3 de cancelado)
                            DB::table('PLCFULL.dbo.Jurid_Debite')
                            ->where('Numero', '=' ,$numerodebite) 
                            ->limit(1) 
                            ->update(array('Status' => '3','Revisado_DB' => '1', 'Obs' => $observacao, 'Hist' => $histcancelada . $motivodescricao));

                             //Update Matrix
                             DB::table('dbo.Financeiro_GuiasCustas_Matrix')
                             ->where('numerodebite', '=' ,$numerodebite) 
                             ->limit(1) 
                             ->update(array('status_id' => '5', 'motivo_id' => $motivo, 'observacao' => $observacao));
     
                             //Insert na Hist
                             $values = array('user_id' => Auth::user()->id, 
                                             'numerodebite' => $numerodebite, 
                                             'data' => $carbon, 
                                             'status_id' => '5');
                             DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);
     
                             //Informa ao solicitante por notificação
                             $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '15', 'obs' => 'Guias Custas: Solicitação de guia de custa cancelada pela equipe do financeiro.', 'status' => 'A');
                             DB::table('dbo.Hist_Notificacao')->insert($values3);
     
     
                             $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
                             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                              'PLCFULL.dbo.Jurid_Debite.Pasta',
                              'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                              'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                              'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
                              'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
                              'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
                              'dbo.Jurid_Nota_Motivos.Descricao as Motivo',
                              'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
                              'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
                              'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                              'dbo.users.name as SolicitanteNome',
                              'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                              'PLCFULL.dbo.Jurid_Debite.Quantidade as Quantidade',
                              'PLCFULL.dbo.Jurid_Debite.ValorUnitario_Adv as ValorUnitario',
                              'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
                             ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
                             ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
                             ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
                             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                             ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                             ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Financeiro_GuiasCustas_Matrix.motivo_id', 'dbo.Jurid_Nota_Motivos.id')
                             ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
                             ->limit(1)
                             ->get();
     
                             Mail::to($solicitanteemail)
                             ->send(new SolicitacaoGuiaCustaCancelada($datas, $motivodescricao));

                } 


                return redirect()->route("Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.index");

        }

        public function guiascustas_conciliacaobancaria_baixado(Request $request) {


                $usuarioid = Auth::user()->id;
                $carbon= Carbon::now();
                $dataehora = $carbon->format('dmY_HHis');
                $fornecedor = $request->get('fornecedor');
                $codigo_banco = $request->get('portador'); 
                $data_concil = $request->get('dataconciliacao');
                $data_baixa = $request->get('databaixa');
                $solicitante_codigo = $request->get('solicitante_codigo');
                $hist = $request->get('hist');
         
                $numerodebite = $request->get('numerodebite');
                $tipodoc = $request->get('tipodoc');

                $data = array();
                foreach($numerodebite as $index => $numerodebite) {

                 $item = array('numerodebite' => $numerodebite);
                 array_push($data,$item);
               
                  $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
                  $numdoc = $ultimonumprc + 1;
                  $cliente = DB::table('PLCFULL.dbo.Jurid_Debite')->select('AdvServ')->where('Numero','=', $numerodebite)->value('AdvServ');
                  $tipo = 'P';
                  $tipodocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('tipodocpag')->where('Numero','=', $numerodebite)->value('tipodocpag');
                  $numdocor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('numdocpag')->where('Numero','=', $numerodebite)->value('numdocpag');
                  $tipolan = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Tipolan')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Tipolan');
                  $valor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('ValorT')->where('Numero','=', $numerodebite)->value('ValorT');
                  $setor = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Setor')->where('Numero','=', $numerodebite)->value('Setor');     
                  $observacao = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Obs')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Obs');
                  $datacompetencia = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Dt_Progr');
                  $codigo_comp = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Pasta')->where('Numero','=', $numerodebite)->value('Pasta');
                  $unidade = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Unidade')->where('Numero','=', $numerodebite)->value('Unidade'); 
                  $prconta = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.PRConta')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.PRConta');
                  $contrato = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Contrato')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Contrato');
                  $ident_cpr = DB::table('PLCFULL.dbo.Jurid_Debite')->leftjoin('PLCFULL.dbo.Jurid_ContaPR', 'PLCFULL.dbo.Jurid_Debite.numdocpag', '=', 'PLCFULL.dbo.Jurid_ContaPR.Numdoc')->select('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident')->where('Numero','=', $numerodebite)->value('PLCFULL.dbo.Jurid_ContaPR.Cpr_ident');
                  $origem_cpr = $ident_cpr;
                  $ident_rate = '0';
                  $hist_usuario = 'portal.plc';
                  $moeda = DB::table('PLCFULL.dbo.Jurid_Debite')->select('moeda')->where('Numero','=', $numerodebite)->value('moeda'); 
                  $desconto = '0.00';

                  $values= array(
                   'Tipodoc' => $tipodoc,
                   'Numdoc' => $numdoc,
                   'Cliente' => $cliente,
                   'Tipo' => $tipo,
                   'TipodocOr' => $tipodocor,
                   'NumDocOr' => $numdocor,
                   'Tipolan' => $tipolan,
                   'Valor' => $valor,
                   'Centro' => $setor,
                   'Dt_baixa' => $data_baixa,
                   'Portador' => $codigo_banco,
                   'Obs' => $observacao,
                   'Juros' => '0.00',
                   'Dt_Compet' => $datacompetencia,
                   'DT_Concil' => $data_concil,
                   'Codigo_Comp' =>$codigo_comp,
                   'Unidade' => $unidade,
                   'PRConta' => $prconta,
                   'Contrato' =>$contrato,
                   'Ident_Cpr' => $ident_cpr,
                   'origem_cpr' => $origem_cpr,
                   'Ident_Rate' => $ident_rate,
                   'moeda' => $moeda);    
                DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);       

        //Update Conta PR  (Falando Baixado = 4, Status = 'baixado' e Data Pag = Data baixa)
        DB::table('PLCFULL.dbo.Jurid_ContaPr')
        ->limit(1)
        ->where('Cpr_ident', '=', $ident_cpr)     
        ->update(array('Historico' => $hist,'Baixado' => '1', 'Status' => '4', 'Dt_baixa' => $data_baixa));

        //Grava na GED
        $image = $request->file('select_file');
        $new_name = 'comprovantedepagamento_' . $numerodebite . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
        $image->storeAs('guiascusta', $new_name);
        Storage::disk('reembolso-local')->put($new_name, File::get($image));

        //Insert Jurid_Ged_Main
        $values = array(
        'Tabela_OR' => 'Debite',
        'Codigo_OR' => $numerodebite,
        'Id_OR' => $numerodebite,
        'Descricao' => $image->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\guiascusta/'.$new_name, 
        'Data' => $carbon,
        'Nome' => $image->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $image->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $new_name,
        'Obs' => $observacao,
        'Texto' => 'Comprovante de pagamento');
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        //Insert na ProcMovimentação
        //Código da Movimentação: MOV086
        $values = array('Codigo_Comp' => $codigo_comp, 
                        'Data' => $carbon, 
                        'Advogado' => $solicitante_codigo, 
                        'CodMov' => 'MOV086', 
                        'Valor' => $valor, 
                        'MCliente' => '1',
                        'Descricao' => $observacao,
                        'Arquivado' => '0',
                        'Data_Mov' => $carbon,
                        'documento_criado' => '0',
                        'faturada' => '0',
                        'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $codigo_comp)->value('id_pasta'),
                        'usuario_inseriu' => 'portal.plc');
        DB::table('PLCFULL.dbo.Jurid_ProCmOV')->insert($values);

        $mov_id = DB::table('PLCFULL.dbo.Jurid_ProCmOV')
        ->select('Ident')
        ->where('Codigo_Comp','=', $codigo_comp)
        ->where('Advogado', $solicitante_codigo)
        ->where('CodMov', 'MOV086')
      //   ->where('usuario_inseriu', 'portal.plc')
        ->orderby('Ident', 'desc')
        ->value('Ident');


        //Update Jurid_Debite
        DB::table('PLCFULL.dbo.Jurid_Debite')
        ->limit(1)
        ->where('Numero', '=', $numerodebite)     
        ->update(array('Hist' => $hist,'Mov' => $mov_id,'nomebanco' => $codigo_banco,'datapag' => $data_baixa));

        //Foreach para gravar os arquivos da GED na Movimentação
        $arquivos = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
        ->where('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
        ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
        ->get();

                foreach ($arquivos as $arquivo) {

                $values = array(
                        'Tabela_OR' => 'Pastas',
                        'Codigo_OR' => $codigo_comp,
                        'Id_OR' => $mov_id,
                        'Descricao' => $arquivo->Descricao,
                        'Link' => $arquivo->Link, 
                        'Data' => $carbon,
                        'Nome' => $arquivo->Nome,
                        'Responsavel' => $arquivo->Responsavel,
                        'Arq_tipo' => $arquivo->Arq_tipo,
                        'Arq_Versao' => $arquivo->Arq_Versao,
                        'Arq_Status' => $arquivo->Arq_Status,
                        'Arq_usuario' => $arquivo->Arq_usuario,
                        'Arq_nick' => $arquivo->Arq_nick,
                        'Obs' => $arquivo->Obs,
                        'Texto' => $arquivo->Texto);
                        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
                
                //Grava na GedLig
                $values = array(
                        'Id_tabela_or' => 'Pastas',
                        'Id_codigo_or' => $codigo_comp,
                        'Id_ID_OR' => $mov_id,
                        'Id_id_doc' => $arquivo->ID_doc,
                        'dt_inserido' => $carbon,
                        'usuario_insercao' => $arquivo->Arq_usuario);
                        DB::table('PLCFULL.dbo.Jurid_GEDLig')->insert($values);  

                //Grava na Ged_Hist
                $values = array(
                        'Id_doc' => $arquivo->ID_doc,
                        'Data_hist' => $carbon,
                        'Hist_versao' => '1',
                        'Hist_link' => $arquivo->Link, 
                        'Hist_nick' => $arquivo->Arq_nick,
                        'Hist_usuario' => $arquivo->Arq_usuario);
                        DB::table('PLCFULL.dbo.Jurid_Ged_Hist')->insert($values);  
                }
                //Fim Foreach


        //Update na Matrix 
        DB::table('dbo.Financeiro_GuiasCustas_Matrix')
        ->where('numerodebite', '=' ,$numerodebite) 
        ->limit(1) 
        ->update(array('status_id' => '4', 'observacao' => $observacao));

        //Grava na Hist
        $values = array('user_id' => Auth::user()->id, 
        'numerodebite' => $numerodebite, 
        'data' => $carbon, 
        'status_id' => '4');
        DB::table('dbo.Financeiro_GuiasCustas_Hist')->insert($values);

  
        //Update Jurid_Default (Num + 1)    
        $numprcNovo = $numdoc + 1;
        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));

        
        $datas = DB::table("PLCFULL.dbo.Jurid_Debite")
        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
         'PLCFULL.dbo.Jurid_Debite.Pasta',
         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
         'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
         'dbo.Financeiro_GuiasCustas_Status.Descricao as Status',
         'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
         'PLCFULL.dbo.Jurid_Debite.Data as DataServico',
         'dbo.Financeiro_GuiasCustas_Matrix.data as DataSolicitacao',
         'dbo.users.name as SolicitanteNome',
         'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
         'PLCFULL.dbo.Jurid_Debite.ValorT as ValorTotal')
        ->join('dbo.Financeiro_GuiasCustas_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_GuiasCustas_Matrix.numerodebite')
        ->join('dbo.Financeiro_GuiasCustas_Status', 'dbo.Financeiro_GuiasCustas_Matrix.status_id', 'dbo.Financeiro_GuiasCustas_Status.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb','PLCFULL.dbo.Jurid_TipoDebite.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'dbo.users.cpf')
        ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
        ->first();
      
        $solicitanteemail = DB::table('dbo.users')->select('email')->where('cpf','=', $solicitante_codigo)->value('email');
   
        //Envia email informando que a Solicitação foi paga 
        Mail::to($solicitanteemail)->send(new SolicitacaoGuiaCustaBaixado($datas));
 
        }
        //Fim Foreach
        
        flash('Solicitação de pagamento de guia de custa baixada com sucesso !')->success();
        return redirect()->route("Painel.Financeiro.GuiasCustas.ConciliacaoBancaria.index");

        }

        
    }
    
      
      

    
     

 
 

