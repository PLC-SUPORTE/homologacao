<?php

namespace App\Http\Controllers\Painel\PesquisaPatrimonial;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use App\Mail\PesquisaPatrimonial\SolicitacaoPesquisaPatrimonial;
use App\Mail\PesquisaPatrimonial\SolicitacaoPagamentoDeposito;
use App\Mail\PesquisaPatrimonial\SolicitacaoLiberada;
use App\Mail\PesquisaPatrimonial\SolicitacaoFinanceiro;
use App\Mail\PesquisaPatrimonial\SolicitacaoBaixadaFinanceiro;
use App\Mail\PesquisaPatrimonial\RevisarSolicitacaoCorrespondente;
use App\Mail\PesquisaPatrimonial\RevisarSolicitacaoSupervisao;
use App\Mail\PesquisaPatrimonial\RevisarSolicitacaoFinanceiro;
use App\Mail\PesquisaPatrimonial\NovaSolicitacaoNaoCobravel;
use App\Mail\PesquisaPatrimonial\AbaFinalizada;
use App\Mail\PesquisaPatrimonial\PesquisaFinalizada;
use App\Mail\PesquisaPatrimonial\SolicitacaoEditadaNucleo;
use App\Mail\PesquisaPatrimonial\SolicitacaoReprovadaFinanceiro;
use Illuminate\Support\Facades\Storage;
use Excel;
use DateTime;
use App\Models\Correspondente;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;
use File;
use Imagick;
use Spatie\PdfToImage\Pdf;
use App\Mail\PesquisaPatrimonial\SolicitacaoCancelada;



class PesquisaPatrimonialController extends Controller
{

    protected $model;

    // public function __construct(Advogado $model)
    // {
    //     $this->model = $model;
    //     $this->middleware('can:advogado');
    // }

    public function solicitacao_index() {

      $meuid = Auth::user()->id;

      $QuantidadeSolicitacoesAguardandoFichaFinanceira = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '1')->where('solicitante_id', $meuid)->count();

      $QuantidadeSolicitacoesAguardandoPagamentoCliente = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(5,21))->where('solicitante_id', $meuid)->count();

      $QuantidadeSolicitacoesNaoCobravel = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('cobravel', 'Nao')->where('solicitante_id', $meuid)->count();

      $QuantidadeSolicitacoesEmAndamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(4,8,11))->where('solicitante_id', $meuid)->count();

      $QuantidadeSolicitacoesAguardandoRevisao = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '9')->where('solicitante_id', $meuid)->count();

      $QuantidadeSolicitacoesAguardandoRevisaoFinanceiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '10')->where('solicitante_id', $meuid)->count();

      $QuantidadeSolicitacoesCriadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('solicitante_id', $meuid)->count();

      $QuantidadePesquisados = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')->where('TipoOutraParte', '1')->count();

      $ValorReceber = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(5,21))->where('solicitante_id', $meuid)->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));

      $ValorRecebido = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(4,7,8))->WHERE('solicitante_id', $meuid)->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));

      $QuantidadeSolicitacoesFinalizadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(15,17))->where('solicitante_id', $meuid)->count();

      $TotalSolicitacoesJaneiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '01')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesFevereiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '02')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesMarco = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '03')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesAbril = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '04')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesMaio = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '05')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesJunho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '06')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesJulho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '07')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesAgosto = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '08')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesSetembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '09')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesOutubro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '10')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesNovembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '11')->where('solicitante_id', $meuid)->count();
      $TotalSolicitacoesDezembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '12')->where('solicitante_id', $meuid)->count();

      $usuarios =  DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
      ->select('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
      ->orderBy('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'ASC')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'dbo.pesquisaPatrimonial_Matrix.codigo')
      ->groupby('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
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


      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'PLCFULL.dbo.Jurid_Outra_Parte.I_cod as i_cod',
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        'PLCFULL.dbo.Jurid_Pastas.Unidade as Unidade',
        'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
       ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id) 
       ->get();
  
      return view('Painel.PesquisaPatrimonial.solicitacao.index', compact('usuarios','datas','QuantidadeSolicitacoesAguardandoFichaFinanceira','QuantidadeSolicitacoesAguardandoPagamentoCliente','QuantidadeSolicitacoesEmAndamento','QuantidadeSolicitacoesNaoCobravel','QuantidadeSolicitacoesAguardandoRevisao','QuantidadeSolicitacoesAguardandoRevisaoFinanceiro','QuantidadeSolicitacoesCriadas','QuantidadePesquisados','ValorReceber','ValorRecebido','QuantidadeSolicitacoesFinalizadas','totalNotificacaoAbertas', 'notificacoes', 'TotalSolicitacoesJaneiro', 'TotalSolicitacoesFevereiro', 'TotalSolicitacoesMarco', 'TotalSolicitacoesAbril', 'TotalSolicitacoesMaio', 'TotalSolicitacoesJunho', 'TotalSolicitacoesJulho', 'TotalSolicitacoesAgosto', 'TotalSolicitacoesSetembro', 'TotalSolicitacoesOutubro','TotalSolicitacoesNovembro', 'TotalSolicitacoesDezembro'));
    }

 
    public function solicitacao_solicitacoes() {

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


      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'PLCFULL.dbo.Jurid_Outra_Parte.I_cod as i_cod',
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        'PLCFULL.dbo.Jurid_Pastas.Unidade as Unidade',
        'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id) 
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(1,4,5,7,8,9,10,11,12,13,14,19,20,21,22))
      ->get();

      return view('Painel.PesquisaPatrimonial.solicitacao.minhassolicitacoes', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function solicitacao_solicitacoesemandamento() {

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


      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'PLCFULL.dbo.Jurid_Outra_Parte.I_cod as i_cod',
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        'PLCFULL.dbo.Jurid_Pastas.Unidade as Unidade',
        'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id) 
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(4,8,11))
      ->get();

      return view('Painel.PesquisaPatrimonial.solicitacao.solicitacoesemandamento', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function solicitacao_solicitacoesfinalizadas() {

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


      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'PLCFULL.dbo.Jurid_Outra_Parte.I_cod as i_cod',
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        'PLCFULL.dbo.Jurid_Pastas.Unidade as Unidade',
        'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id) 
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(15,17))
      ->get();

      return view('Painel.PesquisaPatrimonial.solicitacao.solicitacoesfinalizadas', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

   
    public function preenchetabela(Request $request) {

      $data = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
      ->select(
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as verificacao',
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as codigo',
        'PLCFULL.dbo.Jurid_Outra_Parte.Descricao as nome',
        'dbo.PesquisaPatrimonial_Status.descricao as status',
        DB::raw('COUNT(dbo.PesquisaPatrimonial_Matrix.id) as quantidade'))
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', '=', 'dbo.PesquisaPatrimonial_Matrix.codigo')  
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->where('PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'LIKE',  $request->input . '%')
      ->orWhere('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'LIKE',  $request->input . '%')
      ->groupby('dbo.PesquisaPatrimonial_Matrix.id','PLCFULL.dbo.Jurid_Outra_Parte.Codigo',
      'PLCFULL.dbo.Jurid_Outra_Parte.Descricao',
      'dbo.PesquisaPatrimonial_Status.descricao')
      ->get();
      
      echo $data;

    }
    
    public function solicitacao_create() {
    
      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');

      $estados = DB::table('dbo.PesquisaPatrimonial_Estados')
      ->where('Status', '=', 'A')
      ->get();

      $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
      ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
      ->where('Ativo', '=', '1')
      ->where('setor_custo_user.user_id', '=', Auth::user()->id)
      ->orderBy('Codigo', 'asc')
      ->get();

      $gruposcliente = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
      ->orderby('Descricao', 'asc')
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
       
    return view('Painel.PesquisaPatrimonial.solicitacao.novasolicitacao', compact('estados','gruposcliente','setores','datahoje', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function tiposdeservico() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Servicos')
      ->leftjoin('dbo.PesquisaPatrimonial_Estados','dbo.PesquisaPatrimonial_Servicos.uf_id','=','dbo.PesquisaPatrimonial_Estados.id')
      ->select('dbo.PesquisaPatrimonial_Servicos.id as Id',
      'dbo.PesquisaPatrimonial_Estados.descricao as Estado',
      'dbo.PesquisaPatrimonial_Servicos.descricao as Descricao',
     // DB::raw('CAST(dbo.PesquisaPatrimonial_Servicos.valor AS NUMERIC(15,2)) as Valor'), 
      DB::raw('CAST(dbo.PesquisaPatrimonial_Servicos.taxa_servico AS NUMERIC(15,2)) as TaxaServico'), 
      )
      ->where('dbo.PesquisaPatrimonial_Servicos.status', '=', 'A') 
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

      return view('Painel.PesquisaPatrimonial.tiposervico.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
    }

    public function tiposdeservico_create() {

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

      return view('Painel.PesquisaPatrimonial.tiposervico.create-edit', compact('totalNotificacaoAbertas', 'notificacoes'));
    }

    public function tiposdeservico_store(Request $request) {

      $uf_id = $request->get('estado');
      $tipodeservico = $request->get('tipodeservico');
      $taxasemformato =  $request->get('taxa_servico');
      $taxaservico = str_replace (',', '.', str_replace ('.', '.', $taxasemformato));
      $carbon= Carbon::now();
      $status = 'A';

      $values = array(
      'uf_id' => $uf_id, 
      'user_id' => Auth::user()->id, 
      'descricao' => $tipodeservico, 
      'taxa_servico' => $taxaservico,
      'data' => $carbon,
      'status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Servicos')->insert($values);

      flash('Novo tipo de serviço criado com sucesso !')->success();    

      return redirect()->route('Painel.PesquisaPatrimonial.tiposdeservico');

    }

    public function tiposdesolicitacao() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Servicos_UF.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Estados','dbo.PesquisaPatrimonial_Servicos.uf_id','=','dbo.PesquisaPatrimonial_Estados.id')
      ->select('dbo.PesquisaPatrimonial_Servicos_UF.id as Id',
      'dbo.PesquisaPatrimonial_Estados.descricao as Estado',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Servicos_UF.descricao as Descricao',
      )
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

      return view('Painel.PesquisaPatrimonial.tiposolicitacao.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
   
    }
    public function tiposdesolicitacao_create() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Estados')
      ->orderBy('descricao', 'asc')
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

      return view('Painel.PesquisaPatrimonial.tiposolicitacao.create-edit', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function tiposdesolicitacao_store(Request $request) {

      $tiposervico_id= $request->get('tiposervico');
      $descricao = $request->get('descricao');
      $status = 'A';

      $values = array(
      'tiposervico_id' => $tiposervico_id, 
      'descricao' => $descricao,
      'status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->insert($values);

      flash('Novo tipo de solicitação criado com sucesso !')->success();    

      return redirect()->route('Painel.PesquisaPatrimonial.tiposdesolicitacao');

    }

    public function solicitacao_buscapesquisado(Request $request) {

  
       $query = $request->get('query');


       $data = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
      ->select(
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as verificacao',
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as codigo',
        'PLCFULL.dbo.Jurid_Outra_Parte.Descricao as nome',
        'dbo.PesquisaPatrimonial_Status.descricao as status',
        DB::raw('COUNT(dbo.PesquisaPatrimonial_Matrix.id) as quantidade'))
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', '=', 'dbo.PesquisaPatrimonial_Matrix.codigo')  
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->where('PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'LIKE',  $query . '%')
      ->orWhere('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'LIKE',  $query . '%')
      ->groupby('dbo.PesquisaPatrimonial_Matrix.id','PLCFULL.dbo.Jurid_Outra_Parte.Codigo',
      'PLCFULL.dbo.Jurid_Outra_Parte.Descricao',
      'dbo.PesquisaPatrimonial_Status.descricao')
      ->get();

       $output .= '</ul>';
       echo $output;
      
    }

    public function solicitacao_buscapesquisado2(Request $request) {

  
      $query = $request->get('query');


      $data = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
     ->select(
       'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as verificacao',
       'dbo.PesquisaPatrimonial_Matrix.id as Id',
       'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as codigo',
       'PLCFULL.dbo.Jurid_Outra_Parte.Descricao as nome',
       'dbo.PesquisaPatrimonial_Status.descricao as status',
       DB::raw('COUNT(dbo.PesquisaPatrimonial_Matrix.id) as quantidade'))
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', '=', 'dbo.PesquisaPatrimonial_Matrix.codigo')  
     ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
     ->where('PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'LIKE',  $query . '%')
     ->orWhere('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'LIKE',  $query . '%')
     ->groupby('dbo.PesquisaPatrimonial_Matrix.id','PLCFULL.dbo.Jurid_Outra_Parte.Codigo',
     'PLCFULL.dbo.Jurid_Outra_Parte.Descricao',
     'dbo.PesquisaPatrimonial_Status.descricao')
     ->get();

       $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
      foreach($data as $row)
      {
       $output .= '
       <li><a href="../'.$row->codigo.'/capa">'.$row->nome.'</a></li>
       ';
      }
      $output .= '</ul>';
      echo $output;
     
     
   }

    public function solicitacao_trazpesquisado($nome) {

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

  
        return view('Painel.PesquisaPatrimonial.solicitacao.buscapesquisado', compact('nome','totalNotificacaoAbertas', 'notificacoes'));
  
    
    }

    public function solicitacao_buscacapa($codigo) {

      $datas = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
      ->where('PLCFULL.dbo.Jurid_Outra_Parte.Codigo','=', $codigo)
      ->first();

      $QuantidadePesquisa = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeStatus = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeImovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeVeiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeEmpresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeInfojud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Infojud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Infojud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeBacenjud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeProtestos = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadePesquisaCadastral = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeDossieComercial = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeRedesSociais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeProcessosJudiciais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      // $QuantidadeParticipacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();


      $saldodevedor = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
      ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.NumDocOr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
      ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
      ->where('PLCFULL.dbo.Jurid_ContPrBx.Cliente', '=', $codigo)
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '=', '5')
      ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

      $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->select(
               'dbo.PesquisaPatrimonial_Matrix.id as id',
               'dbo.PesquisaPatrimonial_Matrix.codigo as codigo',
               'dbo.PesquisaPatrimonial_Matrix.data as data',
               'dbo.PesquisaPatrimonial_Status.descricao as status')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo','=', $codigo)
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id)
      ->get();


       //Se não houver nenhuma pesquisa pra este pesquisado o score = 0
       if($QuantidadePesquisa == 0) {

        $porcentagem = 0.00;
        $score = 0.00;
        $somabruto = 0.00;

       } else {

              //Veiculo 
       $veiculo_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor))'));
       $veiculo_soma = number_format((float)$veiculo_soma, 2, '.', '');      

       $veiculo_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc))'));
       $veiculo_avaliacaoplc = number_format((float)$veiculo_avaliacaoplc, 2, '.', '');      
       
       $veiculo_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base))'));
       $veiculo_valorbase = number_format((float)$veiculo_valorbase, 2, '.', '');      
       //Fim Veiculo

       //Imovel
       $imovel_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc))'));
       $imovel_avaliacaoplc = number_format((float)$imovel_avaliacaoplc, 2, '.', ''); 

       $imovel_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor))'));
       $imovel_soma = number_format((float)$imovel_soma, 2, '.', ''); 

       $imovel_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base))'));
       $imovel_valorbase = number_format((float)$imovel_valorbase, 2, '.', ''); 
       //Fim Imovel


       //Empresa
       $empresa_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc))'));
       $empresa_avaliacaoplc = number_format((float)$empresa_avaliacaoplc, 2, '.', ''); 

       $empresa_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial))'));
       $empresa_soma = number_format((float)$empresa_soma, 2, '.', ''); 

       $empresa_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base))'));
       $empresa_valorbase = number_format((float)$empresa_valorbase, 2, '.', ''); 
       //Fim Empresa

       //Diversos
       $diversos_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc))'));
       $diversos_avaliacaoplc = number_format((float)$diversos_avaliacaoplc, 2, '.', ''); 
       
       $diversos_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor))'));
       $diversos_soma = number_format((float)$diversos_soma, 2, '.', ''); 

       $diversos_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base))'));
       $diversos_valorbase = number_format((float)$diversos_valorbase, 2, '.', ''); 
       
       //Fim Diversos


       //Moeda
       $moeda_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc))'));
       $moeda_avaliacaoplc = number_format((float)$moeda_avaliacaoplc, 2, '.', ''); 
       
       $moeda_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor))'));
       $moeda_soma = number_format((float)$moeda_soma, 2, '.', ''); 

       $moeda_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base))'));
       $moeda_valorbase = number_format((float)$moeda_valorbase, 2, '.', ''); 
       //Fim Moeda


       //Joias
       $joias_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc))'));
       $joias_avaliacaoplc = number_format((float)$joias_avaliacaoplc, 2, '.', ''); 
       
       $joias_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor))'));
       $joias_soma = number_format((float)$joias_soma, 2, '.', ''); 

       $joias_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base))'));
       $joias_valorbase = number_format((float)$joias_valorbase, 2, '.', ''); 
       //Fim Joias

       $somaavaliacaoplc = $veiculo_avaliacaoplc + $imovel_avaliacaoplc + $empresa_avaliacaoplc + $diversos_avaliacaoplc + $moeda_avaliacaoplc + $joias_avaliacaoplc;
       $somabruto = $veiculo_soma + $imovel_soma + $empresa_soma + $empresa_soma + $diversos_soma + $moeda_soma + $joias_soma;
       $somavalorbase =   $veiculo_valorbase + $imovel_valorbase + $empresa_valorbase + $diversos_valorbase + $moeda_valorbase + $joias_valorbase;    

       $valordivida = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')->select(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa)'))->where('CPF_CNPJ', $codigo)->value(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa))'));
       $valordivida = number_format((float)$valordivida, 2, '.', '');  

      //Se o valor da divida for vazio
       if($valordivida == 0.00) {
        $porcentagem = '100.00';
       }

      else {
        $porcentagem = $somaavaliacaoplc / $valordivida;

      }
        
        //Se a porcentagem for entre 0 e 50
        if($porcentagem <= 50.00) {

          $score = '0.00';

        }
        //Se a porcentagem for maior ou igual a 130
        elseif($porcentagem >= 130.00) {

          $score = '100.00';
       
        } else {
           
          $score = $porcentagem * 100 / 200;
        }

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
    'hist_notificacao.status', 
    'dbo.users.*')  
    ->limit(3)
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.status','=','A')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->orderBy('dbo.Hist_Notificacao.data', 'desc')
    ->get();

    $status = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PesquisaPatrimonial_Clientes.id_referencia')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
      'dbo.PesquisaPatrimonial_Clientes.descricao as Cliente',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as Classificacao',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
    ->get();

    $imoveis =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
      'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'), 
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
      ->get();

      $veiculos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor  as valor', 
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
       ->get();

       $empresas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
        ->get();

        $infojuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
        ->join('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.user_id', '=', 'dbo.users.id')
        ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix', 
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
         ->get();

         $bacenjuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
         ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
         ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
         ->select(
          'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as id',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
         ->get();

         $protestos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
         ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
         ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
         ->select(
          'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as id',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
         ->get();
   
         $redessociais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.rede_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
        ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as id',
        'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as redesocial',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.status as status',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $processosjudiciais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $pesquisascadastral =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $dossiecomercials =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $dadosprocessos =  DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
        ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
        ->leftjoin('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
                 'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                 'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
                 DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'),
                 'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
        ->where('CPF_CNPJ', '=', $codigo) 
        ->get();
    

    return view('Painel.PesquisaPatrimonial.solicitacao.capa', compact('score','somabruto','codigo', 'datas','saldodevedor','solicitacoes','totalNotificacaoAbertas', 'notificacoes', 'status', 'imoveis', 'veiculos', 'empresas', 'infojuds','bacenjuds', 'protestos', 'redessociais', 'processosjudiciais', 'pesquisascadastral','dossiecomercials', 'dadosprocessos', 'QuantidadePesquisa','QuantidadeStatus','QuantidadeImovel','QuantidadeVeiculo','QuantidadeEmpresa','QuantidadeInfojud','QuantidadeBacenjud','QuantidadeProtestos','QuantidadePesquisaCadastral','QuantidadeDossieComercial','QuantidadeRedesSociais','QuantidadeProcessosJudiciais'));


    }

    public function solicitacao_capa($nome) {


      $codigo = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')->select('Codigo')->where('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', '=', $nome)->value('Codigo');
  
      $datas = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
      ->where('PLCFULL.dbo.Jurid_Outra_Parte.Codigo','=', $codigo)
      ->first();

      $QuantidadePesquisa = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeStatus = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeImovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeVeiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeEmpresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeInfojud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Infojud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Infojud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeBacenjud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeProtestos = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadePesquisaCadastral = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeDossieComercial = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeRedesSociais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeProcessosJudiciais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      // $QuantidadeParticipacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();


      $saldodevedor = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
      ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.NumDocOr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
      ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
      ->where('PLCFULL.dbo.Jurid_ContPrBx.Cliente', '=', $codigo)
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '=', '5')
      ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

      $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->select(
               'dbo.PesquisaPatrimonial_Matrix.id as id',
               'dbo.PesquisaPatrimonial_Matrix.codigo as codigo',
               'dbo.PesquisaPatrimonial_Matrix.data as data',
               'dbo.PesquisaPatrimonial_Status.descricao as status')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo','=', $codigo)
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id)
      ->get();


       //Se não houver nenhuma pesquisa pra este pesquisado o score = 0
       if($QuantidadePesquisa == 0) {

        $porcentagem = 0.00;
        $score = 0.00;
        $somabruto = 0.00;

       } else {

              //Veiculo 
       $veiculo_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor))'));
       $veiculo_soma = number_format((float)$veiculo_soma, 2, '.', '');      

       $veiculo_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc))'));
       $veiculo_avaliacaoplc = number_format((float)$veiculo_avaliacaoplc, 2, '.', '');      
       
       $veiculo_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base))'));
       $veiculo_valorbase = number_format((float)$veiculo_valorbase, 2, '.', '');      
       //Fim Veiculo

       //Imovel
       $imovel_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc))'));
       $imovel_avaliacaoplc = number_format((float)$imovel_avaliacaoplc, 2, '.', ''); 

       $imovel_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor))'));
       $imovel_soma = number_format((float)$imovel_soma, 2, '.', ''); 

       $imovel_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base))'));
       $imovel_valorbase = number_format((float)$imovel_valorbase, 2, '.', ''); 
       //Fim Imovel


       //Empresa
       $empresa_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc))'));
       $empresa_avaliacaoplc = number_format((float)$empresa_avaliacaoplc, 2, '.', ''); 

       $empresa_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial))'));
       $empresa_soma = number_format((float)$empresa_soma, 2, '.', ''); 

       $empresa_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base))'));
       $empresa_valorbase = number_format((float)$empresa_valorbase, 2, '.', ''); 
       //Fim Empresa

       //Diversos
       $diversos_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc))'));
       $diversos_avaliacaoplc = number_format((float)$diversos_avaliacaoplc, 2, '.', ''); 
       
       $diversos_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor))'));
       $diversos_soma = number_format((float)$diversos_soma, 2, '.', ''); 

       $diversos_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base))'));
       $diversos_valorbase = number_format((float)$diversos_valorbase, 2, '.', ''); 
       
       //Fim Diversos


       //Moeda
       $moeda_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc))'));
       $moeda_avaliacaoplc = number_format((float)$moeda_avaliacaoplc, 2, '.', ''); 
       
       $moeda_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor))'));
       $moeda_soma = number_format((float)$moeda_soma, 2, '.', ''); 

       $moeda_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base))'));
       $moeda_valorbase = number_format((float)$moeda_valorbase, 2, '.', ''); 
       //Fim Moeda


       //Joias
       $joias_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc))'));
       $joias_avaliacaoplc = number_format((float)$joias_avaliacaoplc, 2, '.', ''); 
       
       $joias_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor))'));
       $joias_soma = number_format((float)$joias_soma, 2, '.', ''); 

       $joias_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base))'));
       $joias_valorbase = number_format((float)$joias_valorbase, 2, '.', ''); 
       //Fim Joias

       $somaavaliacaoplc = $veiculo_avaliacaoplc + $imovel_avaliacaoplc + $empresa_avaliacaoplc + $diversos_avaliacaoplc + $moeda_avaliacaoplc + $joias_avaliacaoplc;
       $somabruto = $veiculo_soma + $imovel_soma + $empresa_soma + $empresa_soma + $diversos_soma + $moeda_soma + $joias_soma;
       $somavalorbase =   $veiculo_valorbase + $imovel_valorbase + $empresa_valorbase + $diversos_valorbase + $moeda_valorbase + $joias_valorbase;    

       $valordivida = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')->select(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa)'))->where('CPF_CNPJ', $codigo)->value(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa))'));
       $valordivida = number_format((float)$valordivida, 2, '.', '');  

      //Se o valor da divida for vazio
       if($valordivida == 0.00) {
        $porcentagem = '100.00';
       }

      else {
        $porcentagem = $somaavaliacaoplc / $valordivida;

      }
        
        //Se a porcentagem for entre 0 e 50
        if($porcentagem <= 50.00) {

          $score = '0.00';

        }
        //Se a porcentagem for maior ou igual a 130
        elseif($porcentagem >= 130.00) {

          $score = '100.00';
       
        } else {
           
          $score = $porcentagem * 100 / 200;
        }

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
    'hist_notificacao.status', 
    'dbo.users.*')  
    ->limit(3)
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.status','=','A')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->orderBy('dbo.Hist_Notificacao.data', 'desc')
    ->get();

    $status = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PesquisaPatrimonial_Clientes.id_referencia')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
      'dbo.PesquisaPatrimonial_Clientes.descricao as Cliente',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as Classificacao',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
    ->get();

    $imoveis =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
      'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'), 
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
      ->get();

      $veiculos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor  as valor', 
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
       ->get();

       $empresas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
        ->get();

        $infojuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
        ->join('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.user_id', '=', 'dbo.users.id')
        ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix', 
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
         ->get();

         $bacenjuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
         ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
         ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
         ->select(
          'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as id',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
         ->get();

         $protestos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
         ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
         ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
         ->select(
          'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as id',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
         ->get();
   
         $redessociais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.rede_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
        ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as id',
        'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as redesocial',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.status as status',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $processosjudiciais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $pesquisascadastral =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $dossiecomercials =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $dadosprocessos =  DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
        ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
        ->leftjoin('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
                 'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                 'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
                 DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'),
                 'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
        ->where('CPF_CNPJ', '=', $codigo) 
        ->get();
    

    return view('Painel.PesquisaPatrimonial.solicitacao.capa', compact('score','somabruto','codigo', 'datas','saldodevedor','solicitacoes','totalNotificacaoAbertas', 'notificacoes', 'status', 'imoveis', 'veiculos', 'empresas', 'infojuds','bacenjuds', 'protestos', 'redessociais', 'processosjudiciais', 'pesquisascadastral','dossiecomercials', 'dadosprocessos', 'QuantidadePesquisa','QuantidadeStatus','QuantidadeImovel','QuantidadeVeiculo','QuantidadeEmpresa','QuantidadeInfojud','QuantidadeBacenjud','QuantidadeProtestos','QuantidadePesquisaCadastral','QuantidadeDossieComercial','QuantidadeRedesSociais','QuantidadeProcessosJudiciais'));
  
    }

    public function nucleo_capa($codigo, $numero) {
  
      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('dbo.PesquisaPatrimonial_Matrix.id','=', $numero)
      ->first();

      $QuantidadePesquisa = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeStatus = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeImovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeVeiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeEmpresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeInfojud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Infojud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Infojud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeBacenjud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeProtestos = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadePesquisaCadastral = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeDossieComercial = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeRedesSociais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      $QuantidadeProcessosJudiciais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
      // $QuantidadeParticipacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();


      $saldodevedor = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
      ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.NumDocOr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
      ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
      ->where('PLCFULL.dbo.Jurid_ContPrBx.Cliente', '=', $codigo)
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '=', '5')
      ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

      $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->select(
               'dbo.PesquisaPatrimonial_Matrix.id as id',
               'dbo.PesquisaPatrimonial_Matrix.codigo as codigo',
               'dbo.PesquisaPatrimonial_Matrix.data as data',
               'dbo.PesquisaPatrimonial_Status.descricao as status')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo','=', $codigo)
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id)
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

    $status = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PesquisaPatrimonial_Clientes.id_referencia')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
      'dbo.PesquisaPatrimonial_Clientes.descricao as Cliente',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as Classificacao',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
    ->get();

    $imoveis =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
      'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'), 
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
      'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
      ->get();

      $veiculos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor  as valor', 
       'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
       ->get();

       $empresas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
        'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
        ->get();

        $infojuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
        ->join('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.user_id', '=', 'dbo.users.id')
        ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix', 
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
         ->get();

         $bacenjuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
         ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
         ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
         ->select(
          'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as id',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
          'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
         ->get();

         $protestos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
         ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
         ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
         ->select(
          'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as id',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
          'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao')
         ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
         ->get();
   
         $redessociais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.rede_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
        ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as id',
        'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as redesocial',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.status as status',
        'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $processosjudiciais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $pesquisascadastral =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $dossiecomercials =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
        ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.user_id', '=', 'dbo.users.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as id',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
         'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
        ->get();

        $dadosprocessos =  DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
        ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
        ->leftjoin('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
                 'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                 'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
                 DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'),
                 'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
        ->where('CPF_CNPJ', '=', $codigo) 
        ->get();

       //Se não houver nenhuma pesquisa pra este pesquisado o score = 0
       if($QuantidadePesquisa == 0) {

        $porcentagem = 0.00;
        $score = 0.00;
        $somabruto = 0.00;

       } else {

       //Veiculo 
       $veiculo_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor))'));
       $veiculo_soma = number_format((float)$veiculo_soma, 2, '.', '');      

       $veiculo_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc))'));
       $veiculo_avaliacaoplc = number_format((float)$veiculo_avaliacaoplc, 2, '.', '');      
       
       $veiculo_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base))'));
       $veiculo_valorbase = number_format((float)$veiculo_valorbase, 2, '.', '');      
       //Fim Veiculo

       //Imovel
       $imovel_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc))'));
       $imovel_avaliacaoplc = number_format((float)$imovel_avaliacaoplc, 2, '.', ''); 

       $imovel_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor))'));
       $imovel_soma = number_format((float)$imovel_soma, 2, '.', ''); 

       $imovel_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base))'));
       $imovel_valorbase = number_format((float)$imovel_valorbase, 2, '.', ''); 
       //Fim Imovel


       //Empresa
       $empresa_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc))'));
       $empresa_avaliacaoplc = number_format((float)$empresa_avaliacaoplc, 2, '.', ''); 

       $empresa_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial))'));
       $empresa_soma = number_format((float)$empresa_soma, 2, '.', ''); 

       $empresa_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base))'));
       $empresa_valorbase = number_format((float)$empresa_valorbase, 2, '.', ''); 
       //Fim Empresa

       //Diversos
       $diversos_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc))'));
       $diversos_avaliacaoplc = number_format((float)$diversos_avaliacaoplc, 2, '.', ''); 
       
       $diversos_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor))'));
       $diversos_soma = number_format((float)$diversos_soma, 2, '.', ''); 

       $diversos_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base))'));
       $diversos_valorbase = number_format((float)$diversos_valorbase, 2, '.', ''); 
       
       //Fim Diversos


       //Moeda
       $moeda_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc))'));
       $moeda_avaliacaoplc = number_format((float)$moeda_avaliacaoplc, 2, '.', ''); 
       
       $moeda_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor))'));
       $moeda_soma = number_format((float)$moeda_soma, 2, '.', ''); 

       $moeda_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base))'));
       $moeda_valorbase = number_format((float)$moeda_valorbase, 2, '.', ''); 
       //Fim Moeda


       //Joias
       $joias_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc))'));
       $joias_avaliacaoplc = number_format((float)$joias_avaliacaoplc, 2, '.', ''); 
       
       $joias_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor))'));
       $joias_soma = number_format((float)$joias_soma, 2, '.', ''); 

       $joias_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base))'));
       $joias_valorbase = number_format((float)$joias_valorbase, 2, '.', ''); 
       //Fim Joias

       $somaavaliacaoplc = $veiculo_avaliacaoplc + $imovel_avaliacaoplc + $empresa_avaliacaoplc + $diversos_avaliacaoplc + $moeda_avaliacaoplc + $joias_avaliacaoplc;
       $somabruto = $veiculo_soma + $imovel_soma + $empresa_soma + $empresa_soma + $diversos_soma + $moeda_soma + $joias_soma;
       $somavalorbase =   $veiculo_valorbase + $imovel_valorbase + $empresa_valorbase + $diversos_valorbase + $moeda_valorbase + $joias_valorbase;    

       $valordivida = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')->select(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa)'))->where('CPF_CNPJ', $codigo)->value(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa))'));
       $valordivida = number_format((float)$valordivida, 2, '.', '');  

      //Se o valor da divida for vazio
       if($valordivida == 0.00) {
        $porcentagem = '100.00';
       }

      else {
        $porcentagem = $somaavaliacaoplc / $valordivida;

      }
        
        //Se a porcentagem for entre 0 e 50
        if($porcentagem <= 50.00) {

          $score = '0.00';

        }
        //Se a porcentagem for maior ou igual a 130
        elseif($porcentagem >= 130.00) {

          $score = '100.00';
       
        } else {
           
          $score = $porcentagem * 100 / 200;
        }

       }
            

    return view('Painel.PesquisaPatrimonial.nucleo.capa', compact('score','somabruto','codigo', 'numero', 'datas','saldodevedor','solicitacoes','totalNotificacaoAbertas', 'notificacoes', 'status', 'imoveis', 'veiculos', 'empresas', 'infojuds','bacenjuds', 'protestos', 'redessociais', 'processosjudiciais', 'pesquisascadastral','dossiecomercials', 'dadosprocessos', 'QuantidadePesquisa','QuantidadeStatus','QuantidadeImovel','QuantidadeVeiculo','QuantidadeEmpresa','QuantidadeInfojud','QuantidadeBacenjud','QuantidadeProtestos','QuantidadePesquisaCadastral','QuantidadeDossieComercial','QuantidadeRedesSociais','QuantidadeProcessosJudiciais'));

  }



    public function solicitacao_store(Request $request) {

      //Primeira coisa verifica se foi extraJudicial ou Judicial
      $codigosemformato = $request->get('cpf_cnpj');
      $codigo = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $codigosemformato)));
      $classificacao = $request->get('classificacao');
      $nome = $request->get('nome');
      $solicitante_id =  Auth::user()->id;
      $carbon= Carbon::now();
      $observacao = $request->get('observacao');
      $cobravel = $request->get('cobravel');
      $tipo = $request->get('tipo');
      $emailcliente = $request->get('email');

      //Se for Judicial
      if($classificacao == "JUDICIAL") {

       $grupocliente = $request->get('codigogrupo');
       $cliente = $request->get('cliente');
       $codigocliente = $request->get('codigocliente');

       $idcliente = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo','=',$codigocliente)->value('id_cliente');  
       $setor = $request->get('setor');
       $unidadecliente = $request->get('codigounidade');
       $tiposervico = $request->get('tiposervico');

       $dados = $request->get('dados');
       //$id_pasta = $request->get('id_pasta');

       $id_pasta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=',$dados)->orwhere('NumPrc1_Sonumeros', $dados)->value('id_pasta');  
       $setor = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Setor')->where('Codigo_Comp','=',$dados)->orwhere('NumPrc1_Sonumeros', $dados)->value('Setor');  

          //Verifica se existe o anexo
            if( $request->hasFile('select_file') ) {
            //Pega a imagem
            $image = $request->file('select_file');

                  
            //Define o nome para a imagem
            $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
                  
            $image->storeAs('pesquisapatrimonial', $nameImage);
            Storage::disk('reembolso-local')->put($nameImage, File::get($image));

              $values = array(
                'codigo' => $codigo, 
                'nome' => $nome, 
                'solicitante_id' => $solicitante_id, 
                'status_id' => '1',
                'arquivo' => $nameImage,
                'data' => $carbon,
                'classificacao' => $classificacao,
                'tipo' => $tipo,
                'cobravel' => $cobravel,
                'cliente_id' => $idcliente,
                'tiposervico_id' => $tiposervico,
                'id_pasta' => $id_pasta,
                'setor_id' => $setor,
                'observacao' => $observacao);
                DB::table('dbo.PesquisaPatrimonial_Matrix')->insert($values);
              } 
              
              //Não possui anexo
              else {
                $values = array(
                'codigo' => $codigo, 
                'nome' => $nome, 
                'solicitante_id' => $solicitante_id, 
                'status_id' => '1',
                'data' => $carbon,
                'classificacao' => $classificacao,
                'tipo' => $tipo,
                'cobravel' => $cobravel,
                'cliente_id' => $idcliente,
                'tiposervico_id' => $tiposervico,
                'id_pasta' => $id_pasta,
                'setor_id' => $setor,
                'observacao' => $observacao);
                DB::table('dbo.PesquisaPatrimonial_Matrix')->insert($values);

              }

      }
      //Fim Judicial


      //Inicio ExtraJudicial
      else {


        $grupocliente = $request->get('grupoclienteselected');
        $codigocliente = $request->get('clienteselected');
        $cliente = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo','=',$codigocliente)->value('id_cliente');  
        $setor = $request->get('setor');
        $unidadecliente = $request->get('codigounidade');
        $tiposervico = $request->get('tiposervico');

        $shortcode = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Short_code')->where('Codigo','=',$codigocliente)->value('Short_code');  
        $ultimonumero = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Numero')->where('Cliente','=', $codigocliente)->orderBy('Numero', 'desc')->value('Numero');  
        $ultimonumeroformatado= preg_replace('/[^0-9\.]+/', null, $ultimonumero);


        if($shortcode == null && $ultimonumero == null) {
          $numero = '00001';
          $shortcode = '00001';
          $codigo_comp = '00001-000001'; 
        } elseif($shortcode == null) {
          $numero = $ultimonumeroformatado + 1;
          $shortcode = '00001';
          $codigo_comp = '00001-'.$numero; 
        } else {
          $numero = $ultimonumeroformatado + 1;
          $codigo_comp = $shortcode.'-'.$numero; 
        }
       
        //Cria a Pasta 
        $values = array(
         'Codigo' => $shortcode, 
         'Numero' => $numero,
         'Codigo_Comp' => $codigo_comp,
         'Dt_Cad' => $carbon,
         'Cliente' => $codigocliente,
         'Descricao' => 'Pesquisa Patrimonial',
         'Advogado' => '',
         'Status' => 'Ativa',
         'Cad_Por' => 'portal',
         'UF' => '',
         'Unidade' => $unidadecliente,
         'StatusPrc' => 'Ativa',
         'DataPrazo' => $carbon,
         'Setor' => $setor,
         'OutraParte' => $nome,
         'Moeda' => 'R$',
         'PA_DT_INSERIDO' => $carbon,
         'PA_Workflowstatus' => '0',
         'IDForm' => '3',
         'ValorInestimavel' => '0');
        DB::table('PLCFULL.dbo.Jurid_Pastas')->insert($values);

        $id_pasta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp', '=', $codigo_comp)->value('id_pasta');  

        $image = $request->file('select_file');
        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

                
          $values = array(
           'codigo' => $codigo, 
           'nome' => $nome, 
           'solicitante_id' => $solicitante_id, 
           'status_id' => '1',
           'arquivo' => $nameImage,
           'data' => $carbon,
           'classificacao' => $classificacao,
           'tipo' => $tipo,
           'cobravel' => $cobravel,
           'cliente_id' => $cliente,
           'tiposervico_id' => $tiposervico,
           'id_pasta' => $id_pasta,
           'setor_id' => $setor,
           'observacao' => $observacao);
            DB::table('dbo.PesquisaPatrimonial_Matrix')->insert($values);
          
      
    }
    //Fim ExtraJudicial

    $id_matrix = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('id')->orderBy('id', 'desc')->where('codigo', '=', $codigo)->value('id'); 

    //Verifico se o cliente já esta cadastrado na Jurid Banco, se não da INSERT
    $cliente_id = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('cliente_id')->where('id', '=', $id_matrix)->value('cliente_id');
    $codigocliente = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Codigo')->where('id_cliente', '=', $cliente_id)->value('Codigo');
    $clienterazao = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Nome')->where('id_cliente', '=', $cliente_id)->value('Nome');
    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;
    $verifica = DB::table('PLCFULL.dbo.Jurid_Banco')->where('Codigo', '=', $descricao)->count(); 


    if($verifica == 0) {

      $values = array(
        'Codigo' => $descricao, 
        'Descricao' => $razaoeditado, 
        'DaEmpresa' => '1', 
        'Unidade' => $unidadecliente,
        'caixa' => '0',
        'moeda' => 'R$',
        'contaPertence' => '0',
        'ger_boleto' => '0',
        'cobranca_registrada' => '0',
        'juros_dias' => '0',
        'percentual_multa' => '0',
        'protestar' => '0',
        'dias_protestar' => '0',
        'protestar_dias_tipo' => '0',
        'dias_baixa' => '0',
        'seq_remessa_pagfor' => '0',
        'seq_remessa_boleto' => '0',
        'pagfor_segm_b' => '0',
        'Status' => '1',
        'cnpj_empresa' => $codigocliente,
        'CodigoMora' => '2');
      DB::table('PLCFULL.dbo.Jurid_Banco')->insert($values);

      //Cadastrar Pesquisado como Fornecedor
      $values = array(
                   'Codigo' => $descricao, 
                   'Dt_cad' => $carbon, 
                   'Nome' => $descricao, 
                   'Razao' => $descricao, 
                   'Endereco' => '', 
                   'Bairro' => '',
                   'Cidade' => '',
                   'Cep' => '',
                   'UF' => $unidadecliente,
                   'Pais' => 'Brasil',
                   'Fone1' => '',
                   'Obs' => 'Conta do cliente para o pesquisa patrimonial.',
                   'Status' => 'Ativo',
                   'Cad_por' => 'portal',
                   'E_mail' => $emailcliente,
                   'TipoCF' => 'F',
                   'banco' => $descricao,
                   'agencia' => '',
                   'conta' => '',
                   'pessoa_fisica' => '0',
                   'StatusFornecedor' => 'Ativo',
                   'Orgao_Publico' => '0');
       DB::table('PLCFULL.dbo.Jurid_CliFor')->insert($values);
    }

    //Insert na Tabela Historico
    $values = array('id_matrix' => $id_matrix, 'user_id' => $solicitante_id, 'status_id' => '1', 'data' => $carbon);
    DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values);  


    ////////////////////GRAVAR NA TABELA AUXILIAR OS TIPOS SOLICITACAO//////////////////////

    //Verifico se é completa ou parcial
    $tipo = $request->get('tipo');
    if($tipo == "PARCIAL") {

    $tipossolicitacao = $request->get('tipossolicitacao');

    $data = array();
    foreach($tipossolicitacao as $index => $tipossolicitacao) {

        $item = array('tipossolicitacao' => $tipossolicitacao);
        array_push($data,$item);

        $values = array(
          'id_matrix' => $id_matrix, 
          'id_tiposolicitacao' => $tipossolicitacao, 
          'solicitante_id' => $solicitante_id,
          'data' => $carbon);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);  
      
    }

    } else {
    //Se for completa
    $tiposervico_id = $request->get('tiposervico');

    $tipos = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')
                        ->select('id')
                        ->whereIn('id', array(2,3,4,7,8,9,10,11))
                        ->get();

    foreach($tipos as $index => $tipos) {

      $id = $tipos->id;

      $values = array(
          'id_matrix' => $id_matrix, 
          'id_tiposolicitacao' => $id,
          'solicitante_id' => $solicitante_id,
          'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values); 
        
    }

  }

    DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->where('id_matrix', $id_matrix)
    ->where('id_tiposolicitacao', '=', '0')
    ->delete();        
    ////////////////////GRAVAR NA TABELA AUXILIAR OS TIPOS SOLICITACAO////////////////////


    ////////////////////GRAVAR NA TABELA AUXILIAR AS COMARCAS POR UF//////////////////////
    $cidade= $request->get('cidade');
    $estado = $request->get('estado');

    $data = array();
    foreach($cidade as $index => $cidade) {

        // $item = array('cidade' => $cidade);
        // $item = array('estado' => $estado[$index]);

        $values = array(
          'id_matrix' => $id_matrix, 
          'uf_id' => $estado[$index],
          'comarca' => $cidade, 
          'solicitante_id' => $solicitante_id,
          'data' => $carbon);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')->insert($values);  

    }
    ////////////////////GRAVAR NA TABELA AUXILIAR AS COMARCAS////////////////////

    //Atualizo na tabela o email do Cliente
    $existe = DB::table('dbo.PesquisaPatrimonial_EmailCliente')->where('cliente_id', $cliente_id)->count();
    if($existe == 0) {
      $values = array('cliente_id' => $cliente_id, 'email' => $emailcliente, 'status' => 'A', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_EmailCliente')->insert($values);
    } else {
      DB::table('dbo.PesquisaPatrimonial_EmailCliente')
      ->where('cliente_id', $cliente_id)  
      ->limit(1) 
      ->update(array('email' => $emailcliente,'status' => 'A', 'data' => $carbon));
    }

    //Manda notificação para o Advogado solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $solicitante_id, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Solicitação de pesquisa patrimonial criada.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Verifico se a solicitação é Cobrável se não for envia notificação + email para Supervisão Pesquisa Patrimonial + Financeiro
    if($cobravel == "NAO") {

      //Supervisor pesquisa patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $solicitante_id, 'destino_id' => '225', 'tipo' => '2', 'obs' => 'Solicitação de pesquisa patrimonial não cobrável criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Financeiro pesquisa patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $solicitante_id, 'destino_id' => '213', 'tipo' => '2', 'obs' => 'Solicitação de pesquisa patrimonial não cobrável criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //envio email
      Mail::to(Auth::user()->email)
      ->cc('daniele.oliveira@plcadvogados.com.br', 'roberta.povoa@plcadvogados.com.br')
      ->send(new NovaSolicitacaoNaoCobravel($id_matrix));

    }

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',)
    ->where('dbo.PesquisaPatrimonial_Matrix.id', $id_matrix)  
    ->get();

    //Enviar e-mail e notificação a todos da equipe núcleo pesquisa patrimonial
    $equipe = DB::table('dbo.users')
    ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
    ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
    ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
    ->where('dbo.profiles.id', '=', '26')
    ->get();     
   
    foreach ($equipe as $data) {

     $equipe_id = $data->id;
     $equipe_email = $data->email;
     $equipe_nome = $data->nome;

     Mail::to($equipe_email)
     ->send(new SolicitacaoPesquisaPatrimonial($datas, $equipe_nome));

     $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $equipe_id, 'tipo' => '7', 'obs' => 'Pesquisa patrimonial: Nova solicitação de pesquisa patrimonial criada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);

    }
    //Fim

    flash('Nova solicitação de pesquisa patrimonial registrada com sucesso !')->success();  

    return redirect()->route("Painel.PesquisaPatrimonial.solicitacao.solicitacoes")->withInput();

  }


  public function nucleo_index() {

    $meuid = Auth::user()->id;

      $QuantidadeSolicitacoesAguardandoFichaFinanceira = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '1')->count();
      $QuantidadeSolicitacoesAguardandoEnvioBoleto = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(21,22))->count();
      $QuantidadeSolicitacoesAguardandoPagamentoCliente = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(5))->count();
      $QuantidadeSolicitacoesNaoCobravel = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('cobravel', 'Nao')->count();
      $QuantidadeSolicitacoesEmAndamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(4,8,11))->count();
      $QuantidadeSolicitacoesAguardandoRevisao = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '9')->count();
      $QuantidadeSolicitacoesAguardandoRevisaoFinanceiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '10')->count();
      $QuantidadeSolicitacoesCriadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->count();
      $ValorReceber = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(5,21))->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
      $ValorRecebido = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(4,7,8))->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
      $QuantidadeSolicitacoesFinalizadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(15,17))->count();
      $QuantidadeSolicitacoesAguardandoPagamentoFinanceiro  = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '19')->count();
      $QuantidadeSolicitacoesReprovadas  = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '24')->count();


      $TotalSolicitacoesJaneiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '01')->count();
      $TotalSolicitacoesFevereiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '02')->count();
      $TotalSolicitacoesMarco = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '03')->count();
      $TotalSolicitacoesAbril = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '04')->count();
      $TotalSolicitacoesMaio = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '05')->count();
      $TotalSolicitacoesJunho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '06')->count();
      $TotalSolicitacoesJulho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '07')->count();
      $TotalSolicitacoesAgosto = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '08')->count();
      $TotalSolicitacoesSetembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '09')->count();
      $TotalSolicitacoesOutubro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '10')->count();
      $TotalSolicitacoesNovembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '11')->count();
      $TotalSolicitacoesDezembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '12')->count();

      $ValorPagar = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
      ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor ) as valor'))
      ->where('PLCFULL.dbo.Jurid_Debite.numdocpag', '=', NULL)
      ->where('PLCFULL.dbo.Jurid_Debite.datapag', '=', null)
      ->where('PLCFULL.dbo.Jurid_Debite.DebPago', '=', 'N')
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '19')
      ->orWhere('dbo.PesquisaPatrimonial_Matrix.status_id', '20')
      ->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor)'));

      $usuarios =  DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
      ->select('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
      ->orderBy('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'ASC')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'dbo.pesquisaPatrimonial_Matrix.codigo')
      ->groupby('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
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

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',)
      ->get();
  
      return view('Painel.PesquisaPatrimonial.nucleo.index', compact('usuarios','ValorPagar','QuantidadeSolicitacoesAguardandoFichaFinanceira','QuantidadeSolicitacoesReprovadas','QuantidadeSolicitacoesAguardandoEnvioBoleto','QuantidadeSolicitacoesAguardandoPagamentoCliente','QuantidadeSolicitacoesEmAndamento','QuantidadeSolicitacoesNaoCobravel','QuantidadeSolicitacoesAguardandoRevisao','QuantidadeSolicitacoesAguardandoRevisaoFinanceiro','QuantidadeSolicitacoesCriadas','ValorReceber','ValorRecebido','QuantidadeSolicitacoesFinalizadas','QuantidadeSolicitacoesAguardandoPagamentoFinanceiro','totalNotificacaoAbertas', 'notificacoes', 'datas', 'TotalSolicitacoesJaneiro', 'TotalSolicitacoesFevereiro', 'TotalSolicitacoesMarco', 'TotalSolicitacoesAbril', 'TotalSolicitacoesMaio', 'TotalSolicitacoesJunho', 'TotalSolicitacoesJulho', 'TotalSolicitacoesAgosto', 'TotalSolicitacoesSetembro', 'TotalSolicitacoesOutubro','TotalSolicitacoesNovembro', 'TotalSolicitacoesDezembro'));
  }

  public function nucleo_informacoes($id_matrix) {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Matrix.status_id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Pastas.PRConta',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'dbo.PesquisaPatrimonial_Matrix.observacaoedicao as ObservacaoEdicao',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento',)
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
              DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Cidades.municipio as Cidade',
             'dbo.users.name as Correspondente',
             'dbo.Jurid_Nota_Tiposervico.descricao as Motivo',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.assertiva',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.anexo as Anexo',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.comprovante as Comprovante',
             'PLCFULL.dbo.Jurid_Debite.datapag as DataPagamento',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.observacao',
             'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
             'PLCFULL.dbo.Jurid_Debite.Hist as Hist')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Cidades', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.cidade_id', '=', 'dbo.PesquisaPatrimonial_Cidades.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.correspondente_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.motivocorrespondente_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor', '!=', '0.00')
    ->wherenull('PLCFULL.dbo.Jurid_Debite.numdocpag')
    ->orWhere('PLCFULL.dbo.Jurid_Debite.numdocpag', '=', '')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor', '!=', '0.00')
    ->get();  

    $cidades = DB::table('dbo.PesquisaPatrimonial_Cidades')
    ->get();

    $historicos = DB::table('dbo.PesquisaPatrimonial_Hist')
    ->select('dbo.users.name as nome', 'dbo.PesquisaPatrimonial_Hist.data', 'dbo.PesquisaPatrimonial_Status.Descricao as status')
    ->leftjoin('dbo.PesquisaPatrimonial_Status','dbo.PesquisaPatrimonial_Hist.status_id','=','dbo.PesquisaPatrimonial_Status.id')
    ->join('dbo.users', 'dbo.PesquisaPatrimonial_Hist.user_id', 'dbo.users.id')
    ->where('dbo.PesquisaPatrimonial_Hist.id_matrix','=',$id_matrix)
    ->get();

    $codigocliente = $datas->CodigoCliente;
    $clienterazao = $datas->ClienteFantasia;

    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;

    $saldoclienter = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipolan', '=', 'TRANSF')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
    
  
    $saldoclientep = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

    $saldocliente =  $saldoclienter - $saldoclientep;

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

    return view('Painel.PesquisaPatrimonial.Nucleo.informacoes', compact('historicos','cidades','carbon','datahoje','saldocliente','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes'));


  }

  public function nucleo_storeinformacoes(Request $request) {

    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitanteid');
    $solicitante_cpf = $request->get('solicitantecpf');
    $observacao = $request->get('observacao');
    $clientecodigo = $request->get('clientecodigo');
    $pasta = $request->get('codigopasta');
    $setor = $request->get('setor');
    $unidade = $request->get('unidade');
    $contrato = $request->get('contrato');
    $usuario_id = Auth::user()->id;
    $contrato = $request->get('contrato');
    $primeiroscodigo =  substr($clientecodigo, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;
    $status = $request->get('statusid');
    $carbon= Carbon::now();
    $cobravel = $request->get('cobravel');
    $valortotal = str_replace (',', '.', str_replace ('.', '.', $request->get('valortotal')));

    $clienterazao = $request->get('clienterazao');
    $prconta = $request->get('prconta');
    $nomepesquisado = $request->get('nomepesquisado');
    $codigopesquisado = $request->get('codigopesquisado');
    $numeroprocesso = $request->get('numeroprocesso');
    $datasolicitacao = $request->get('datasolicitacao');

    //Grava os anexos dos comprovantes e data de pagamento
    
    $tipossolicitacao = $request->get('tipossolicitacao');
    $numerodebite = $request->get('numerodebite');
    $descricaosolicitacao = $request->get('descricaosolicitacao');
    $assertiva = $request->get('assertiva');
    $observacao = $request->get('informacoesadicionais');
    $boleto = $request->file('select_file');
    $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $comarcas = $request->get('comarcas');
    $tiposolicitacao_id = $request->get('tiposolicitacao_id');

    
    $data = array();
    foreach($tipossolicitacao as $index => $tipossolicitacao) {

    //Foreach verificando as solicitações

      //Se for nova solicitação
      if(!empty($tiposolicitacao_id[$index])) {

        if(!empty($boleto[$index])) {

          $ultimonumero = DB::table('PLCFULL.dbo.Jurid_Debite')->orderBy('Numero', 'desc')->value('Numero');
          $numerodebite = $ultimonumero + 1;
  
          $new_name = 'boleto' . '_' . $id_matrix . '_' . $numerodebite . '.'  . $boleto[$index]->getClientOriginalExtension();
          $boleto[$index]->storeAs('pesquisapatrimonial', $new_name);
          Storage::disk('reembolso-local')->put($new_name, File::get($boleto[$index]));


          //Gera o debite
          $values3= array(
          'Numero' => $numerodebite,
          'Advogado' => $solicitante_cpf, 
          'Cliente' => $clientecodigo,
          'Data' => $carbon->format('Y-m-d'),
          'Tipo' => 'D',
          'Obs' => ' Pesquisa Patrimonial - Número da solicitação: ' . $id_matrix . '  PRConta: ' . $prconta  . ' Nome do pesquisado:  ' . $nomepesquisado . ' CPF/CNPJ: ' . $codigopesquisado . ' Número do processo: ' . $numeroprocesso . ' Operação: ' . $clienterazao . ' Data da solicitação: ' . $datasolicitacao,  
          'Status' => '0',
          'Hist' => 'Solicitação de pesquisa patrimonial número: ' . $id_matrix . ' . Debite criado pelo(a): ' . Auth::user()->name . ' em: ' . $carbon->format('d-m-Y H:i:s'),
          'ValorT' => $valor[$index],
          'Usuario' => 'portal.plc',
          'DebPago' => 'N',
          'TipoDeb' => '004',
          'AdvServ' => $solicitante_cpf,
          'Setor' => $setor,
          'Pasta' => $pasta,
          'Unidade' => $unidade,
          'Valor_Adv' => $valor[$index],
          'Quantidade' => '1.00',
          'ValorUnitario_Adv' => $valor[$index],
          'ValorUnitarioCliente' => $valor[$index],
          'Revisado_DB' => '1',
          'tipodocpag' => '',
          'nomebanco' => '',
          'moeda' => 'R$');
         DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values3);

        //Grava na GED do debite
        $values = array(
          'Tabela_OR' => 'Debite',
          'Codigo_OR' => $numerodebite,
          'Id_OR' => $numerodebite,
          'Descricao' => $boleto[$index]->getClientOriginalName(),
          'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\pesquisapatrimonial/'.$new_name, 
          'Data' => $carbon,
          'Nome' => $boleto[$index]->getClientOriginalName(),
          'Responsavel' => 'portal.plc',
          'Arq_tipo' => $boleto[$index]->getClientOriginalExtension(),
          'Arq_Versao' => '1',
          'Arq_Status' => 'Guardado',
          'Arq_usuario' => 'portal.plc',
          'Arq_nick' => $new_name,
          'Obs' => 'Solicitação de pesquisa patrimonial, referente ao boleto da solicitação: ' . $id_matrix,
          'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);

        $values = array(
          'id_matrix' => $id_matrix,
          'id_tiposolicitacao' => $tiposolicitacao_id[$index],
          'solicitante_id' => Auth::user()->id,
          'cidade_id' => $comarcas[$index],
          'valor' => $valor[$index], 
          'data' => $carbon,
          'numerodebite' => $numerodebite,
          'status_id' => '1',
          'anexo' => $new_name,
          'assertiva' => $assertiva[$index],
          'observacao' => $observacao[$index]);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);


        } else {

          $ultimonumero = DB::table('PLCFULL.dbo.Jurid_Debite')->orderBy('Numero', 'desc')->value('Numero');
          $numerodebite = $ultimonumero + 1;

          //Gera o debite
          $values3= array(
          'Numero' => $numerodebite,
          'Advogado' => $solicitante_cpf, 
          'Cliente' => $clientecodigo,
          'Data' => $carbon->format('Y-m-d'),
          'Tipo' => 'D',
          'Obs' => ' Pesquisa Patrimonial - Número da solicitação: ' . $id_matrix . '  PRConta: ' . $prconta  . ' Nome do pesquisado:  ' . $nomepesquisado . ' CPF/CNPJ: ' . $codigopesquisado . ' Número do processo: ' . $numeroprocesso . ' Operação: ' . $clienterazao . ' Data da solicitação: ' . $datasolicitacao,  
          'Status' => '0',
          'Hist' => 'Solicitação de pesquisa patrimonial número: ' . $id_matrix . ' . Debite criado pelo(a): ' . Auth::user()->name . ' em: ' . $carbon->format('d-m-Y H:i:s'),
          'ValorT' => $valor[$index],
          'Usuario' => 'portal.plc',
          'DebPago' => 'N',
          'TipoDeb' => '004',
          'AdvServ' => $solicitante_cpf,
          'Setor' => $setor,
          'Pasta' => $pasta,
          'Unidade' => $unidade,
          'Valor_Adv' => $valor[$index],
          'Quantidade' => '1.00',
          'ValorUnitario_Adv' => $valor[$index],
          'ValorUnitarioCliente' => $valor[$index],
          'Revisado_DB' => '1',
          'tipodocpag' => '',
          'nomebanco' => '',
          'moeda' => 'R$');
         DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values3);

        $values = array(
          'id_matrix' => $id_matrix,
          'id_tiposolicitacao' => $tiposolicitacao_id[$index],
          'solicitante_id' => Auth::user()->id,
          'cidade_id' => $comarcas[$index],
          'valor' => $valor[$index], 
          'data' => $carbon,
          'numerodebite' => $numerodebite,
          'status_id' => '1',
          'assertiva' => $assertiva[$index],
          'observacao' => $observacao[$index]);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);

        }

      } 
      //Se já foi criado
      else {


      //Se houver um boleto novo anexado ele gera Debite e update na tabela
      if(!empty($boleto[$index]) && !empty($numerodebite[$index])) {

        $new_name = 'boleto' . '_' . $id_matrix . '_' . $numerodebite[$index]  . '.'  . $boleto[$index]->getClientOriginalExtension();
        $boleto[$index]->storeAs('pesquisapatrimonial', $new_name);
        Storage::disk('reembolso-local')->put($new_name, File::get($boleto[$index]));


        
        //Grava na GED do debite
        $values = array(
          'Tabela_OR' => 'Debite',
          'Codigo_OR' => $numerodebite[$index],
          'Id_OR' => $numerodebite[$index],
          'Descricao' => $boleto[$index]->getClientOriginalName(),
          'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\pesquisapatrimonial/'.$new_name, 
          'Data' => $carbon,
          'Nome' => $boleto[$index]->getClientOriginalName(),
          'Responsavel' => 'portal.plc',
          'Arq_tipo' => $boleto[$index]->getClientOriginalExtension(),
          'Arq_Versao' => '1',
          'Arq_Status' => 'Guardado',
          'Arq_usuario' => 'portal.plc',
          'Arq_nick' => $new_name,
          'Obs' => 'Solicitação de pesquisa patrimonial, referente ao boleto da solicitação: ' . $id_matrix,
          'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
          DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);


         //Update 
         DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
         ->where('id', $tipossolicitacao)
         ->update(array('valor' => $valor[$index],'anexo' => $new_name, 'assertiva' => $assertiva[$index], 'observacao' => $observacao[$index]));

        //Update 
         DB::table('PLCFULL.dbo.Jurid_Debite')
        ->where('Numero', $numerodebite[$index])
        ->update(array('ValorT' => $valor[$index],
                        'Valor_Adv' => $valor[$index],
                        'ValorUnitario_Adv' => $valor[$index],
                        'ValorUnitarioCliente' => $valor[$index]));

      } 
      //Gera um novo debite
       elseif(!empty($boleto[$index]) && empty($numerodebite[$index])) {


        $ultimonumero = DB::table('PLCFULL.dbo.Jurid_Debite')->orderBy('Numero', 'desc')->value('Numero');
        $numerodebite = $ultimonumero + 1;

        $new_name = 'boleto' . '_' . $id_matrix . '_' . $numerodebite . '.'  . $boleto[$index]->getClientOriginalExtension();
        $boleto[$index]->storeAs('pesquisapatrimonial', $new_name);
        Storage::disk('reembolso-local')->put($new_name, File::get($boleto[$index]));


        //Gera o debite
        $values3= array(
         'Numero' => $numerodebite,
         'Advogado' => $solicitante_cpf, 
         'Cliente' => $clientecodigo,
         'Data' => $carbon->format('Y-m-d'),
         'Tipo' => 'D',
         'Obs' => ' Pesquisa Patrimonial - Número da solicitação: ' . $id_matrix . '  PRConta: ' . $prconta  . ' Nome do pesquisado:  ' . $nomepesquisado . ' CPF/CNPJ: ' . $codigopesquisado . ' Número do processo: ' . $numeroprocesso . ' Operação: ' . $clienterazao . ' Data da solicitação: ' . $datasolicitacao,  
         'Status' => '0',
         'Hist' => 'Solicitação de pesquisa patrimonial número: ' . $id_matrix . ' . Debite criado pelo(a): ' . Auth::user()->name . ' em: ' . $carbon->format('d-m-Y H:i:s'),
         'ValorT' => $valor[$index],
         'Usuario' => 'portal.plc',
         'DebPago' => 'N',
         'TipoDeb' => '004',
         'AdvServ' => $solicitante_cpf,
         'Setor' => $setor,
         'Pasta' => $pasta,
         'Unidade' => $unidade,
         'Valor_Adv' => $valor[$index],
         'Quantidade' => '1.00',
         'ValorUnitario_Adv' => $valor[$index],
         'ValorUnitarioCliente' => $valor[$index],
         'Revisado_DB' => '1',
         'tipodocpag' => '',
         'nomebanco' => '',
         'moeda' => 'R$');
        DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values3);

        //Grava na GED do debite
        $values = array(
          'Tabela_OR' => 'Debite',
          'Codigo_OR' => $numerodebite,
          'Id_OR' => $numerodebite,
          'Descricao' => $boleto[$index]->getClientOriginalName(),
          'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\pesquisapatrimonial/'.$new_name, 
          'Data' => $carbon,
          'Nome' => $boleto[$index]->getClientOriginalName(),
          'Responsavel' => 'portal.plc',
          'Arq_tipo' => $boleto[$index]->getClientOriginalExtension(),
          'Arq_Versao' => '1',
          'Arq_Status' => 'Guardado',
          'Arq_usuario' => 'portal.plc',
          'Arq_nick' => $new_name,
          'Obs' => 'Solicitação de pesquisa patrimonial, referente ao boleto da solicitação: ' . $id_matrix,
          'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);

        //Update 
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
        ->where('id', $tipossolicitacao)
        ->update(array('valor' => $valor[$index],'numerodebite' => $numerodebite,'anexo' => $new_name, 'assertiva' => $assertiva[$index], 'observacao' => $observacao[$index]));

      } else {

          //Update 
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
          ->where('id', $tipossolicitacao)
          ->update(array('valor' => $valor[$index],'numerodebite' => $numerodebite[$index],'observacao' => $observacao[$index], 'assertiva' => $assertiva[$index]));

          DB::table('PLCFULL.dbo.Jurid_Debite')
          ->where('Numero', $numerodebite[$index])
          ->update(array('ValorT' => $valor[$index],
                          'Valor_Adv' => $valor[$index],
                          'ValorUnitario_Adv' => $valor[$index],
                          'ValorUnitarioCliente' => $valor[$index]));
      }

    }

    }
    //Fim Foreach

    //Se estiver pendente ele volta para o status aguardando pagamento dos boletos pelo financeiro
    $status_id = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('status_id')->where('dbo.PesquisaPatrimonial_Matrix.id','=', $id_matrix)->value('status_id');
    $anexoadiantamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('anexoadiantamento')->where('dbo.PesquisaPatrimonial_Matrix.id','=', $id_matrix)->value('anexoadiantamento');

    if($anexoadiantamento != null) {

      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)
      ->limit(1)
      ->update(array('status_id' => '20', 'valor' => $valortotal));

    }elseif($anexoadiantamento == null) {

      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)
      ->limit(1)
      ->update(array('status_id' => '19', 'valor' => $valortotal));
    }
       
    //Envia notificação para a Vanessa
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '284', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Solicitação atualizada pelo núcleo da pesquisa patrimonial','status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação para o Felipe
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '885', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Solicitação atualizada pelo núcleo da pesquisa patrimonial','status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação para a Roberta
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '242', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Solicitação atualizada pelo núcleo da pesquisa patrimonial','status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Manda e-mail pro financeiro copiando gumercindo e equipe do núcleo, informando que teve alterações

    Mail::to('roberta.povoa@plcadvogados.com.br')
    ->cc('felipe.rocha@especialistaresultados.com.br','vanessa.ferreira@plcadvogados.com.br' ,'gumercindo.ribeiro@plcadvogados.com.br')
    ->send(new SolicitacaoEditadaNucleo($id_matrix));


    return redirect()->route('Painel.PesquisaPatrimonial.nucleo.index');

  

  }

  public function nucleo_create() {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    $estados = DB::table('dbo.PesquisaPatrimonial_Estados')
    ->where('Status', '=', 'A')
    ->get();

    $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
    ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
    ->where('Ativo', '=', '1')
    ->where('setor_custo_user.user_id', '=', Auth::user()->id)
    ->orderBy('Codigo', 'asc')
    ->get();

    $gruposcliente = DB::table('dbo.PesquisaPatrimonial_Grupos')
    ->orderby('descricao', 'asc')
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
     
  return view('Painel.PesquisaPatrimonial.Nucleo.novasolicitacao', compact('estados','gruposcliente','setores','datahoje', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function nucleo_store(Request $request) {

      $codigosemformato = $request->get('cpf_cnpj');
      $codigo = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $codigosemformato)));
      $nome = $request->get('nome');
      $solicitante_id =  Auth::user()->id;
      $carbon= Carbon::now();
      $observacao = $request->get('observacao');
      $cobravel = $request->get('cobravel');
      $tipo = $request->get('tipo');

 
      $grupocliente = $request->get('grupoclienteselected');
      $cliente = $request->get('clienteselected');
      $codigocliente = $request->get('codigocliente');
      $setor = $request->get('setor');
      $unidadecliente = $request->get('codigounidade');
      $tiposervico = $request->get('tiposervico');

      $shortcode = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Short_code')->where('id_cliente','=',$cliente)->value('Short_code');  
      $ultimonumero = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Numero')->where('Cliente','=', $codigocliente)->orderBy('Numero', 'desc')->value('Numero');  
      $ultimonumeroformatado= preg_replace('/[^0-9\.]+/', null, $ultimonumero);


        if($shortcode == null && $ultimonumero == null) {
          $codigo_comp = '00001-000001'; 
        } elseif($shortcode == null) {
          $numero = $ultimonumeroformatado + 1;
          $codigo_comp = '00001-'.$numero; 
        } else {
          $numero = $ultimonumeroformatado + 1;
          $codigo_comp = $shortcode.'-'.$numero; 
        }
       
        //Cria a Pasta 
        $values = array(
         'Codigo' => $shortcode, 
         'Numero' => $numero,
         'Codigo_Comp' => $codigo_comp,
         'Dt_Cad' => $carbon,
         'Cliente' => $codigocliente,
         'Descricao' => 'Pesquisa Patrimonial',
         'Advogado' => '',
         'Status' => 'Ativa',
         'Cad_Por' => 'portal',
         'UF' => '',
         'Unidade' => $unidadecliente,
         'StatusPrc' => 'Ativa',
         'DataPrazo' => $carbon,
         'Setor' => $setor,
         'OutraParte' => $nome,
         'Moeda' => 'R$',
         'PA_DT_INSERIDO' => $carbon,
         'PA_Workflowstatus' => '0',
         'IDForm' => '3',
         'ValorInestimavel' => '0');
        DB::table('PLCFULL.dbo.Jurid_Pastas')->insert($values);

        $id_pasta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp', '=', $codigo_comp)->value('id_pasta');  

        $image = $request->file('select_file');
        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

                
          $values = array(
           'codigo' => $codigo, 
           'nome' => $nome, 
           'solicitante_id' => $solicitante_id, 
           'status_id' => '1',
           'arquivo' => $nameImage,
           'data' => $carbon,
           'classificacao' => 'EXTRAJUDICIAL',
           'tipo' => $tipo,
           'cobravel' => $cobravel,
           'cliente_id' => $cliente,
           'tiposervico_id' => $tiposervico,
           'id_pasta' => $id_pasta,
           'setor_id' => $setor,
           'observacao' => $observacao);
            DB::table('dbo.PesquisaPatrimonial_Matrix')->insert($values);
          
    $id_matrix = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('id')->orderBy('id', 'desc')->where('codigo', '=', $codigo)->value('id'); 

    //Verifico se o cliente já esta cadastrado na Jurid Banco, se não da INSERT
    $cliente_id = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('cliente_id')->where('id', '=', $id_matrix)->value('cliente_id');
    $codigocliente = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Codigo')->where('id_cliente', '=', $cliente_id)->value('Codigo');
    $clienterazao = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Nome')->where('id_cliente', '=', $cliente_id)->value('Nome');
    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;
    $verifica = DB::table('PLCFULL.dbo.Jurid_Banco')->where('Codigo', '=', $descricao)->count(); 


    if($verifica == 0) {

      $values = array(
        'Codigo' => $descricao, 
        'Descricao' => $razaoeditado, 
        'DaEmpresa' => '1', 
        'Unidade' => $unidadecliente,
        'caixa' => '0',
        'moeda' => 'R$',
        'contaPertence' => '0',
        'ger_boleto' => '0',
        'cobranca_registrada' => '0',
        'juros_dias' => '0',
        'percentual_multa' => '0',
        'protestar' => '0',
        'dias_protestar' => '0',
        'protestar_dias_tipo' => '0',
        'dias_baixa' => '0',
        'seq_remessa_pagfor' => '0',
        'seq_remessa_boleto' => '0',
        'pagfor_segm_b' => '0',
        'Status' => '1',
        'cnpj_empresa' => $codigocliente,
        'CodigoMora' => '2');
      DB::table('PLCFULL.dbo.Jurid_Banco')->insert($values);
    }

    //Insert na Tabela Historico
    $values = array('id_matrix' => $id_matrix, 'user_id' => $solicitante_id, 'status_id' => '1', 'data' => $carbon);
    DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values);  


    ////////////////////GRAVAR NA TABELA AUXILIAR OS TIPOS SOLICITACAO//////////////////////

    //Verifico se é completa ou parcial
    $tipo = $request->get('tipo');
    if($tipo == "PARCIAL") {

    $tipossolicitacao = $request->get('tipossolicitacao');

    $data = array();
    foreach($tipossolicitacao as $index => $tipossolicitacao) {

        $item = array('tipossolicitacao' => $tipossolicitacao);
        array_push($data,$item);

        $values = array(
          'id_matrix' => $id_matrix, 
          'id_tiposolicitacao' => $tipossolicitacao, 
          'solicitante_id' => $solicitante_id,
          'data' => $carbon);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);  
      
    }

    } else {
    //Se for completa
    $tiposervico_id = $request->get('tiposervico');

    $tipos = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')
                        ->select('id')
                        ->whereIn('id', array(2,3,4,7,8,9,10,11))
                        ->get();

    foreach($tipos as $index => $tipos) {

      $id = $tipos->id;

      $values = array(
          'id_matrix' => $id_matrix, 
          'id_tiposolicitacao' => $id,
          'solicitante_id' => $solicitante_id,
          'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values); 
        
    }

  }

    DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->where('id_matrix', $id_matrix)
    ->where('id_tiposolicitacao', '=', '0')
    ->delete();        
    ////////////////////GRAVAR NA TABELA AUXILIAR OS TIPOS SOLICITACAO////////////////////


    ////////////////////GRAVAR NA TABELA AUXILIAR AS COMARCAS POR UF//////////////////////
    $cidade= $request->get('cidade');
    $estado = $request->get('estado');

    $data = array();
    foreach($cidade as $index => $cidade) {

        // $item = array('cidade' => $cidade);
        // $item = array('estado' => $estado[$index]);

        $values = array(
          'id_matrix' => $id_matrix, 
          'uf_id' => $estado[$index],
          'comarca' => $cidade, 
          'solicitante_id' => $solicitante_id,
          'data' => $carbon);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')->insert($values);  

    }
    ////////////////////GRAVAR NA TABELA AUXILIAR AS COMARCAS////////////////////

    $emailcliente = $request->get('email');
    //Atualizo na tabela o email do Cliente
    $existe = DB::table('dbo.PesquisaPatrimonial_EmailCliente')->where('cliente_id', $cliente_id)->count();
    if($existe == 0) {
      $values = array('cliente_id' => $cliente_id, 'email' => $emailcliente, 'status' => 'A', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_EmailCliente')->insert($values);
    } else {
      DB::table('dbo.PesquisaPatrimonial_EmailCliente')
      ->where('cliente_id', $cliente_id)  
      ->limit(1) 
      ->update(array('email' => $emailcliente,'status' => 'A', 'data' => $carbon));
    }

    //Manda notificação para o Advogado solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $solicitante_id, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Solicitação de pesquisa patrimonial criada.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Verifico se a solicitação é Cobrável se não for envia notificação + email para Supervisão Pesquisa Patrimonial + Financeiro
    if($cobravel == "NAO") {

      //Supervisor pesquisa patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $solicitante_id, 'destino_id' => '225', 'tipo' => '2', 'obs' => 'Solicitação de pesquisa patrimonial não cobrável criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Financeiro pesquisa patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $solicitante_id, 'destino_id' => '213', 'tipo' => '2', 'obs' => 'Solicitação de pesquisa patrimonial não cobrável criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //envio email
      Mail::to(Auth::user()->email)
      ->cc('daniele.oliveira@plcadvogados.com.br', 'roberta.povoa@plcadvogados.com.br')
      ->send(new NovaSolicitacaoNaoCobravel($id_matrix));

    }

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',)
    ->where('dbo.PesquisaPatrimonial_Matrix.id', $id_matrix)  
    ->get();

    //Enviar e-mail e notificação a todos da equipe núcleo pesquisa patrimonial
    $equipe = DB::table('dbo.users')
    ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
    ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
    ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
    ->where('dbo.profiles.id', '=', '26')
    ->get();     
   
    foreach ($equipe as $data) {

     $equipe_id = $data->id;
     $equipe_email = $data->email;
     $equipe_nome = $data->nome;

     Mail::to($equipe_email)
     ->send(new SolicitacaoPesquisaPatrimonial($datas, $equipe_nome));

     $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $equipe_id, 'tipo' => '7', 'obs' => 'Pesquisa patrimonial: Nova solicitação de pesquisa patrimonial criada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);

    }
    //Fim

    flash('Nova solicitação de pesquisa patrimonial registrada com sucesso !')->success();  

    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoficha")->withInput();


  }


    public function nucleo_solicitacoesaguardandoficha() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '1')
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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


      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoficha', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoesreprovadas() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '24')
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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


      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesreprovadas', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoes() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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


      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoes', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoesaguardandocliente() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(5,12))
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandocliente', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
   
    }

    public function nucleo_solicitacoesaguardandorevisaosupervisor() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(9))
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandorevisaosupervisor', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    } 

    public function nucleo_solicitacoesaguardandorevisaofinanceiro() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(10))
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandorevisaofinanceiro', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoesaguardandoanexonucleo() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '23')
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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


      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoanexonucleo', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }


    public function nucleo_solicitacoesnaocobravel() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('dbo.PesquisaPatrimonial_Matrix.cobravel', 'Nao')
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesnaocobravel', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoesaguardandofinanceiro() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(19,20))
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandofinanceiro', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoesemandamento() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(4,8,11))
      ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesemandamento', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_solicitacoesfinalizadas() {

      
      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
        ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(15,17,18))
        ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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

      return view('Painel.PesquisaPatrimonial.nucleo.solicitacoesfinalizadas', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


    }


    public function solicitante_pesquisaprevia_visualizarpesquisa($id) {

      $carbon= Carbon::now();
      
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
  
       $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
       ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
       ->join('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
       ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as logradouro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
        'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta', 
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
        'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)  
        ->get();

       $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
       ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
       'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
       'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
       ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
       ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->get();

       $codigo = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('dbo.PesquisaPatrimonial_Matrix.codigo')->where('dbo.PesquisaPatrimonial_Matrix.id','=',$id)->value('dbo.PesquisaPatrimonial_Matrix.codigo');

    
      return view('Painel.PesquisaPatrimonial.solicitacao.previa.abas', compact('id','codigo','datas','abas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function solicitante_pesquisaprevia_aba($id, $tiposervico_id) {

      $carbon= Carbon::now();
      
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

       $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
       ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
       'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
       'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
       ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
       ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->get();


       $codigo = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('dbo.PesquisaPatrimonial_Matrix.codigo')->where('dbo.PesquisaPatrimonial_Matrix.id','=',$id)->value('dbo.PesquisaPatrimonial_Matrix.codigo');
  
       //Se for imovel
       if($tiposervico_id == 2 ) {
       $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
       ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
       ->join('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
       ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as logradouro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
        'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta', 
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
        'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)  
        ->get();

        return view('Painel.PesquisaPatrimonial.solicitacao.previa.tabs.imovel', compact('id','datas','abas','codigo','totalNotificacaoAbertas', 'notificacoes'));

       } 
       //Se for veiculo
       elseif($tiposervico_id == 3) {

        $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
        ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.fabricante_id as fabricante',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.combustivel as combustivel',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
         'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
         'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor', 
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_alienacao', 
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel', 
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacaopenhora as penhora',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.dataaverbacao',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.carta',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.datacarta',
         'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
         ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)  
         ->get();

         return view('Painel.PesquisaPatrimonial.solicitacao.previa.tabs.veiculo', compact('id','datas','abas','codigo','totalNotificacaoAbertas', 'notificacoes'));


       }
       //Se for empresa
       elseif($tiposervico_id == 4) {

        $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.nomefantasia as nomefantasia',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.objetosocial',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.situacao',
         DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
         DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'), 
         DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'), 
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.penhoracotas as penhora',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.quantidadecotas',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datapenhoracotas',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cep',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.logradouro',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.numero as complemento',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.bairro',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.municipio as cidade',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.uf',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
         ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)
         ->get();
   
         return view('Painel.PesquisaPatrimonial.solicitacao.previa.tabs.empresa', compact('id','datas','abas','codigo','totalNotificacaoAbertas', 'notificacoes'));

       }

    }

    public function nucleo_pesquisaprevia_abas($id) {

      $carbon= Carbon::now();
      
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
  
       $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
       ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
       ->join('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
       ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as logradouro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
        'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta', 
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
        'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)  
        ->get();

        $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
        ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
        'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
        'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
        ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
        ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
        ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 
                  'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
                  'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
        ->get();

        $codigo = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('dbo.PesquisaPatrimonial_Matrix.codigo')->where('dbo.PesquisaPatrimonial_Matrix.id','=',$id)->value('dbo.PesquisaPatrimonial_Matrix.codigo');


    
        return view('Painel.PesquisaPatrimonial.nucleo.previa.abas', compact('id','codigo','datas','abas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_pesquisaprevia_aba($id, $tiposervico_id) {

      $carbon= Carbon::now();
      
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

       $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
       ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
       'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
       'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
       ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
       ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->get();

       $codigo = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('dbo.PesquisaPatrimonial_Matrix.codigo')->where('dbo.PesquisaPatrimonial_Matrix.id','=',$id)->value('dbo.PesquisaPatrimonial_Matrix.codigo');
  
       //Se for imovel
       if($tiposervico_id == 2 ) {
       $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
       ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
       ->join('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
       ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as logradouro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
        'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta', 
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
        'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)  
        ->get();

        return view('Painel.PesquisaPatrimonial.nucleo.previa.tabs.imovel', compact('id','datas','abas','codigo','totalNotificacaoAbertas', 'notificacoes'));

       } 
       //Se for veiculo
       elseif($tiposervico_id == 3) {

        $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
        ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.fabricante_id as fabricante',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.combustivel as combustivel',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
         'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
         'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor', 
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_alienacao', 
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel', 
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacaopenhora as penhora',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.dataaverbacao',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.carta',
         'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.datacarta',
         'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
         ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)  
         ->get();

         return view('Painel.PesquisaPatrimonial.nucleo.previa.tabs.veiculo', compact('id','datas','abas','codigo','totalNotificacaoAbertas', 'notificacoes'));


       }
       //Se for empresa
       elseif($tiposervico_id == 4) {

        $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
        ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
        ->select(
         'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.nomefantasia as nomefantasia',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.objetosocial',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.situacao',
         DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
         DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'), 
         DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'), 
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.penhoracotas as penhora',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.quantidadecotas',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datapenhoracotas',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cep',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.logradouro',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.numero as complemento',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.bairro',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.municipio as cidade',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.uf',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
         'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
         ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id)
         ->get();
   
         return view('Painel.PesquisaPatrimonial.nucleo.previa.tabs.empresa', compact('id','datas','abas','codigo','totalNotificacaoAbertas', 'notificacoes'));

       }

    
    }

    public function nucleo_pesquisaprevia_cadastrarimovel($id) {

      $datahoje= Carbon::now();

      $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
      ->select(
       'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
      ->get();
  
      $tipos =  DB::table('dbo.PesquisaPatrimonial_Imovel_Tipos')
      ->select(
       'dbo.PesquisaPatrimonial_Imovel_Tipos.id as Id',
       'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as Descricao')
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

      $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
      ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
      'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
      'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
      ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
      ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
      ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
      ->get();
  
      return view('Painel.PesquisaPatrimonial.nucleo.previa.cadastrar.imovel', compact('abas','id', 'datahoje', 'status', 'tipos', 'totalNotificacaoAbertas','notificacoes'));

    }

    public function nucleo_pesquisaprevia_cadastrarimovel_store(Request $request) {

      $id = $request->get('id');
      $data = Carbon::now();
      $usuario_id = Auth::user()->id;
      $numeromatricula = $request->get('numeromatricula');
      $tipoimovel_id = $request->get('tipoimovel');
      $datamatricula = $request->get('datamatricula');
      $cep = $request->get('cep');
      $rua = $request->get('rua');
      $bairro = $request->get('bairro');
      $cidade = $request->get('cidade');
      $uf = $request->get('uf');
      $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
      $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
      $valor_disponivel =  $request->get('valor_disponivel');
      $datarequerimento = $request->get('datarequerimento');
      $aberbacao828 = $request->get('aberbacao828');
      $averbacaopenhora = $request->get('averbacaopenhora');
      $carta = $request->get('carta');
      $datacarta = $request->get('datacarta');
      $id_status = $request->get('status');
      $impedimento = $request->get('impedimento');
  
      $values = array(
        'id_matrix' => $id, 
        'user_id' => $usuario_id,
        'data' => $data,
        'matricula' => $numeromatricula,
        'tipoimovel_id' => $tipoimovel_id,
        'datamatricula' => $datamatricula,
        'cep' => $cep,
        'rua' => $rua,
        'bairro' => $bairro,
        'cidade' => $cidade,
        'uf' => $uf,
        'valor' => $valor,
        'valor_alienacao' => $valor_alienacao,
        'valor_disponivel' => $valor_disponivel,
        'valor_base' => $valor,
        'valor_avaliacaoplc' => $valor_disponivel,
        'datarequerimento' => $datarequerimento,
        'aberbacao828' => $aberbacao828,
        'averbacaopenhora' => $averbacaopenhora,
        'carta' => $carta,
        'datacarta' => $datacarta,
        'status_id' => $id_status,
        'impedimento' => $impedimento);
  
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->insert($values);  
  
        $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->select('id')->orderby('id', 'desc')->where('id_matrix', $id)->value('id'); 
  
  
  
      //Loop anexar arquivos 
      $image = $request->file('select_file');

      if($image != null) {
  
      foreach($image as $index => $image) {
  
         $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
  
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

         $values = array(
          'id_matrix' => $id, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '2',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
  
     }     
    }
  
      flash('Cadastro realizado com sucesso!')->success();   
      return redirect()->route("Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas.aba", ["id" => $id, "tiposervico" => 2]);

    }

    public function nucleo_pesquisaprevia_cadastrarveiculo($id) {

      $tipos =  DB::table('dbo.PesquisaPatrimonial_Veiculos_Tipos')
      ->select(
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.id as Id',
       'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as Descricao')
      ->get();
  
      $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
      ->select(
       'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
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

      $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
      ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
      'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
      'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
      ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
      ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
      ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
      ->get();

      $datahoje= Carbon::now();
      return view('Painel.PesquisaPatrimonial.nucleo.previa.cadastrar.veiculo', compact('abas','id', 'datahoje', 'tipos', 'status','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_pesquisaprevia_cadastrarveiculo_store(Request $request) {

      $id = $request->get('id');
      $data = Carbon::now();
      $usuario_id = Auth::user()->id;
      $placa = $request->get('placa');
      $modelo = $request->get('modelos');
      $descricaoveiculo = $request->get('descricaoveiculo');
      $combustivel = $request->get('combustivel');
      $fabricante_id = $request->get('fabricantes');
      $tipoveiculo_id = $request->get('tipoveiculo');
      $anomodelo = $request->get('anomodelos');
      $anofabricacao = $request->get('anofabricacao');
      $impedimento = $request->get('impedimento');
      $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
      $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
      $valor_disponivel =  $request->get('valor_disponivel');
      $aberbacao828 = $request->get('aberbacao828');
      $averbacaopenhora = $request->get('averbacaopenhora');
      $dataaverbacao = $request->get('dataaverbacao');
      $carta = $request->get('carta');
      $datacarta = $request->get('datacarta');
      $status_id = $request->get('status');
  
      //Calcula valores para incluir no banco e posteriormente montar score
      $valor_base = $valor - $valor_alienacao;
      $pctmbase = -50.00;
      $valor_base = $valor * (1+($pctmbase/100));
      $gravame = -80.00;
      $valor_avaliacaoplc = $valor_base * (1+($gravame/100));
  
  
      $values = array(
        'id_matrix' => $id, 
        'user_id' => $usuario_id,
        'data' => $data,
        'placa' => $placa,
        'modelo' => $modelo,
        'descricaoveiculo' => $descricaoveiculo,
        'combustivel' => $combustivel,
        'fabricante_id' => $fabricante_id,
        'tipoveiculo_id' => $tipoveiculo_id,
        'anomodelo' => $anomodelo,
        'anofabricacao' => $anofabricacao,
        'impedimento' => $impedimento,
        'valor' => $valor,
        'valor_alienacao' => $valor_alienacao,
        'valor_disponivel' => $valor_disponivel,
        'valor_base' => $valor_base,
        'valor_avaliacaoplc' => $valor_avaliacaoplc,
        'averbacao828' => $aberbacao828,
        'averbacaopenhora' => $averbacaopenhora,
        'dataaverbacao' => $dataaverbacao,
        'carta' => $carta,
        'datacarta' => $datacarta,
        'status_id' => $status_id);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->insert($values);
  
      $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->select('id')->orderby('id', 'desc')->where('id_matrix', $id)->value('id'); 
  
  
      //Loop anexar arquivos 
      $image = $request->file('select_file');
  
      foreach($image as $index => $image) {
  
         $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
  
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

         $values = array(
          'id_matrix' => $id, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '3',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
  
     } 
  
      flash('Cadastro realizado com sucesso!')->success();   
      return redirect()->route("Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas.aba", ["id" => $id, "tiposervico" => 4]);

    }

    public function nucleo_pesquisaprevia_cadastrarempresa($id) {

      $datahoje= Carbon::now();

      $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
      ->select(
       'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
      ->get();
  
      $tipos =  DB::table('dbo.PesquisaPatrimonial_Imovel_Tipos')
      ->select(
       'dbo.PesquisaPatrimonial_Imovel_Tipos.id as Id',
       'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as Descricao')
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

       $abas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
       ->select('dbo.PesquisaPatrimonial_Servicos_UF.id',
       'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 
       'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->join('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
       ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=',$id)
       ->groupby('dbo.PesquisaPatrimonial_Servicos_UF.id', 'dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')
       ->get();
  
      return view('Painel.PesquisaPatrimonial.nucleo.previa.cadastrar.empresa', compact('id','abas','datahoje', 'status', 'tipos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function nucleo_pesquisaprevia_cadastrarempresa_store(Request $request) {

      $id = $request->get('id');
      $data = Carbon::now();
      $usuario_id = Auth::user()->id;
      $cnpj = $request->get('cnpj');
      $razaosocial = $request->get('razaosocial');
      $nomefantasia = $request->get('nomefantasia');
      $objetosocial = $request->get('objetosocial');
      $logradouro = $request->get('logradouro');
      $numero = $request->get('numero');
      $bairro = $request->get('bairro');
      $municipio = $request->get('municipio');
      $cep = $request->get('cep');
      $uf = $request->get('uf');
      $valor = $request->get('capitalsocial');
      $capitalsocial =  str_replace (',', '.', str_replace ('.', '.', $valor));
      $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
      $valor_disponivel =  $request->get('valor_disponivel');
      $datafundacao = $request->get('datafundacao');
      $participacaosocietaria = $request->get('participacaosocietaria');
      $penhoracotas = $request->get('penhoracotas');
      $datapenhoracotas = $request->get('datapenhoracotas');
      $quantidadecotas = $request->get('quantidadecotas');
      $penhoradofaturamento = $request->get('penhoradofaturamento');
      $datapenhorafaturamento = $request->get('datapenhorafaturamento');
      $situacao = $request->get('situacao');
      $recuperacaojudicial = $request->get('recuperacaojudicial');
      $falencia = $request->get('falencia');
      $recuperacao = $request->get('recuperacao');
      $impedimento = $request->get('impedimento');
 
     //Calcula valores para incluir no banco e posteriormente montar score
     $pctmbase = -80.00;
     $valor_base = $valor_disponivel * (1+($pctmbase/100));
     $gravame = -80.00;
     $valor_avaliacaoplc = $valor_base * (1+($gravame/100));
 
     $values = array(
         'id_matrix' => $id, 
         'user_id' => $usuario_id,
         'data' => $data,
         'cnpj' => $cnpj,
         'razaosocial' => $razaosocial,
         'nomefantasia' => $nomefantasia,
         'objetosocial' => $objetosocial,
         'logradouro' => $logradouro,
         'numero' => $numero,
         'bairro' => $bairro,
         'municipio' => $municipio,
         'cep' => $cep,
         'uf' => $uf,
         'capitalsocial' => $valor_disponivel,
         'valor_alienacao' => $valor_alienacao,
         'valor_disponivel' => $valor_disponivel,
         'valor_base' => $valor_base,
         'valor_avaliacaoplc' => $valor_avaliacaoplc,
         'datafundacao' => $datafundacao,
         'participacaosocietaria' => $participacaosocietaria,
         'penhoracotas' => $penhoracotas,
         'datapenhoracotas' => $datapenhoracotas,
         'quantidadecotas' => $quantidadecotas,
         'penhoradofaturamento' => $penhoradofaturamento,
         'datapenhorafaturamento' => $datapenhorafaturamento,
         'situacao' => $situacao,
         'recuperacaojudicial' => $recuperacaojudicial,
         'falencia' => $falencia,
         'recuperacao' => $recuperacao,
         'impedimento' => $impedimento);
 
         DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->insert($values);  
 
         $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->select('id')->where('id_matrix', $id)->value('id'); 
 
     //Loop anexar arquivos 
     $image = $request->file('select_file');
 
     foreach($image as $index => $image) {
 
       $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
      
      $image->storeAs('pesquisapatrimonial', $nameImage);
      Storage::disk('reembolso-local')->put($nameImage, File::get($image));

       $values = array(
        'id_matrix' => $id, 
        'id_solicitacao' => $id_solicitacao,
        'tiposervico_id' => '4',
        'user_id' => $usuario_id,
        'anexo' => $nameImage,
        'formato' => $image->getClientOriginalExtension(),
        'data' => $data);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
 
 
     }     
 
     //Grava na tabela socios
     $socios = $request->get('socios');
 
     foreach($socios as $index => $socios) {
 
 
         $values = array(
         'empresa_id' => $id_solicitacao, 
         'socio' => $socios, 
         'data' => $data);
         DB::table('dbo.PesquisaPatrimonial_Empresa_Socios')->insert($values);
 
     }
  
      flash('Cadastro realizado com sucesso!')->success();   
      return redirect()->route("Painel.PesquisaPatrimonial.nucleo.pesquisaprevia.abas.aba", ["id" => $id, "tiposervico" => 4]);

    }

    public function nucleo_pesquisaprevia_finalizarpesquisa(Request $request) {


      $id = $request->get('id');
      $carbon= Carbon::now();

      //Update na Matrix
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id)
      ->update(array('status_id' => '17'));

      //Grava na Hist
      $values = array(
      'id_matrix' => $id,
      'user_id' => Auth::user()->id, 
      'status_id' => '17', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values);

      //Update nos tipos de solicitação selecionados
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
      ->where('id_matrix', $id)
      ->update(array('status_id' => '4'));


      //Retorna para o Index
      return redirect()->route("Painel.PesquisaPatrimonial.nucleo.index");


    }


    public function anexo($anexo) {
                         
        return Storage::disk('pesquisapatrimonial-sftp')->download($anexo);
    }

    public function step1($codigo, $numero) {

    $carbon= Carbon::now();
      
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

     $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
     ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PesquisaPatrimonial_Clientes.id_referencia')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
       'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
       'dbo.PesquisaPatrimonial_Clientes.descricao as Cliente',
       'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as Classificacao',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
     ->get();

      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('status_resultadopesquisa')->where('codigo','=', $codigo)->value('status_resultadopesquisa'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('status_status')->where('codigo','=', $codigo)->value('status_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.abas', compact('codigo','numero','resultadopesquisa','statuspesquisa','datas','totalNotificacaoAbertas', 'notificacoes'));
  
    }

   public function outraparte($codigo, $numero) {

    $datahoje= Carbon::now();
    $clientes = DB::table('dbo.PesquisaPatrimonial_Clientes')
               ->select(
                'dbo.PesquisaPatrimonial_Clientes.id_referencia as Codigo',
                'dbo.PesquisaPatrimonial_Clientes.descricao as Descricao')
              ->orderBy('dbo.PesquisaPatrimonial_Clientes.descricao', 'asc') 
              ->get();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
               ->select(
                'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
                'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
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
          
               
    return view('Painel.PesquisaPatrimonial.nucleo.abas.outraparte', compact('codigo','numero','datahoje', 'clientes', 'status', 'notificacoes', 'totalNotificacaoAbertas'));
   }



  public function storeoutraparte(Request $request) {
   
    //Recupera os dados

    $numero = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = $request->get('data');
    $grupocliente = $request->get('cshopping');
    $classificacao = $request->get('classificacao');
    $id_status = $request->get('status');
    $usuario_id = Auth::user()->id;
    $datahoje= Carbon::now();


    //Grava tabela
    $values = array(
      'id_matrix' => $numero, 
      'user_id' => $usuario_id,
      'grupocliente' => $grupocliente, 
      'classificacao' => $classificacao,
      'id_status' => $id_status,
      'data' => $data);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')->insert($values); 
      
    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $datahoje);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     


    flash('Cadastro realizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.step1", ["codigo"=> $codigo, "numero" => $numero]);


    }

  public function imovel($codigo, $numero) {

    $datahoje= Carbon::now();
    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
    ->get();

    $tipos =  DB::table('dbo.PesquisaPatrimonial_Imovel_Tipos')
    ->select(
     'dbo.PesquisaPatrimonial_Imovel_Tipos.id as Id',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as Descricao')
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

    return view('Painel.PesquisaPatrimonial.nucleo.abas.imovel', compact('codigo','numero', 'datahoje', 'status', 'tipos', 'totalNotificacaoAbertas','notificacoes'));

  }
  

  public function nucleo_editarimovel($codigo, $numero, $id) {

    $datahoje= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
    ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.id as tipoimovelid',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipoimoveldescricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as rua',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as statusid',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as statusdescricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
     DB::raw('CAST(valor AS NUMERIC(15,2)) as valor'),
     DB::raw('CAST(valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
     DB::raw('CAST(valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta as datacarta')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Imovel.id', '=', $id) 
    ->first();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
    ->get();

    $tipos =  DB::table('dbo.PesquisaPatrimonial_Imovel_Tipos')
    ->select(
     'dbo.PesquisaPatrimonial_Imovel_Tipos.id as Id',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as Descricao')
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

    return view('Painel.PesquisaPatrimonial.nucleo.edit.imovel', compact('datas','codigo', 'numero', 'id', 'datahoje', 'status', 'tipos', 'totalNotificacaoAbertas', 'notificacoes'));

  }


  public function updateimovel(Request $request) {

     //Busca os dados 
     $id = $request->get('id');
     $data = Carbon::now();
     $usuario_id = Auth::user()->id;
     $numeromatricula = $request->get('numeromatricula');
     $tipoimovel_id = $request->get('tipoimovel');
     $datamatricula = $request->get('datamatricula');
     $cep = $request->get('cep');
     $rua = $request->get('rua');
     $bairro = $request->get('bairro');
     $cidade = $request->get('cidade');
     $uf = $request->get('uf');
     $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
     $valor = number_format($valor,2,".",",");
     $valor = str_replace (',', '', $valor);
     $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
     $valor_alienacao = number_format($valor_alienacao,2,".",",");
     $valor_alienacao = str_replace (',', '', $valor_alienacao);
     $valor_disponivel =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_disponivel')));
     $valor_disponivel = number_format($valor_disponivel,2,".",",");
     $valor_disponivel = str_replace (',', '', $valor_disponivel);
     $datarequerimento = $request->get('datarequerimento');
     $aberbacao828 = $request->get('aberbacao828');
     $averbacaopenhora = $request->get('averbacaopenhora');
     $carta = $request->get('carta');
     $datacarta = $request->get('datacarta');
     $id_status = $request->get('status');
     $impedimento = $request->get('impedimento');
     $codigo = $request->get('codigo');
     $numero = $request->get('id_matrix');

     //Update na tabela
     DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
     ->where('id', $id)  
     ->update(array(
      'data' => $data,
      'matricula' => $numeromatricula,
      'tipoimovel_id' => $tipoimovel_id,
      'datamatricula' => $datamatricula,
      'cep' => $cep,
      'rua' => $rua,
      'bairro' => $bairro,
      'cidade' => $cidade,
      'uf' => $uf,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor,
      'valor_avaliacaoplc' => $valor_disponivel,
      'datarequerimento' => $datarequerimento,
      'aberbacao828' => $aberbacao828,
      'averbacaopenhora' => $averbacaopenhora,
      'carta' => $carta,
      'datacarta' => $datacarta,
      'status_id' => $id_status,
      'impedimento' => $impedimento
      ));


    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

      $nameImage = 'matricula_'.$numeromatricula.'_'.uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
      $image->storeAs('pesquisapatrimonial', $nameImage);
      Storage::disk('reembolso-local')->put($nameImage, File::get($image));


      $values = array(
        'id_matrix' => $numero, 
        'id_solicitacao' => $id,
        'tiposervico_id' => '2',
        'user_id' => $usuario_id,
        'anexo' => $nameImage,
        'formato' => $image->getClientOriginalExtension(),
        'data' => $data);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values);
 
    }     

 

     flash('Atualização realizada com sucesso!')->success();   
     return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabimovel", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function storeimovel(Request $request) {

    //Busca os dados 
    $numero = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $id_matrix = $request->get('id_matrix');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $numeromatricula = $request->get('numeromatricula');
    $tipoimovel_id = $request->get('tipoimovel');
    $datamatricula = $request->get('datamatricula');
    $cep = $request->get('cep');
    $rua = $request->get('rua');
    $bairro = $request->get('bairro');
    $cidade = $request->get('cidade');
    $uf = $request->get('uf');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');
    $datarequerimento = $request->get('datarequerimento');
    $aberbacao828 = $request->get('aberbacao828');
    $averbacaopenhora = $request->get('averbacaopenhora');
    $carta = $request->get('carta');
    $datacarta = $request->get('datacarta');
    $id_status = $request->get('status');
    $impedimento = $request->get('impedimento');

    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'data' => $data,
      'matricula' => $numeromatricula,
      'tipoimovel_id' => $tipoimovel_id,
      'datamatricula' => $datamatricula,
      'cep' => $cep,
      'rua' => $rua,
      'bairro' => $bairro,
      'cidade' => $cidade,
      'uf' => $uf,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor,
      'valor_avaliacaoplc' => $valor_disponivel,
      'datarequerimento' => $datarequerimento,
      'aberbacao828' => $aberbacao828,
      'averbacaopenhora' => $averbacaopenhora,
      'carta' => $carta,
      'datacarta' => $datacarta,
      'status_id' => $id_status,
      'impedimento' => $impedimento);

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->insert($values);  

      $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->select('id')->where('id_matrix', $numero)->value('id'); 



    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

      $nameImage = 'matricula_'.$numeromatricula.'_'.uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
       $image->storeAs('pesquisapatrimonial', $nameImage);
       Storage::disk('reembolso-local')->put($nameImage, File::get($image));

       $values = array(
        'id_matrix' => $numero, 
        'id_solicitacao' => $id_solicitacao,
        'tiposervico_id' => '2',
        'user_id' => $usuario_id,
        'anexo' => $nameImage,
        'formato' => $image->getClientOriginalExtension(),
        'data' => $data);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     

    flash('Cadastro realizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabimovel", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function veiculo($codigo,$numero) {

    $tipos =  DB::table('dbo.PesquisaPatrimonial_Veiculos_Tipos')
    ->select(
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.id as Id',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as Descricao')
    ->get();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.veiculo', compact('codigo','numero', 'datahoje', 'tipos', 'status','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function nucleo_editarveiculo($codigo, $numero, $id) {

    $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
    ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.id as tipoveiculoid',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculodescricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.fabricante_id as fabricante',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.combustivel as combustivel',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.dataaverbacao as dataaverbacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.datacarta as datacarta',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor as valor',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_alienacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel',
     )
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id', '=', $id)
    ->first();

    $tipos =  DB::table('dbo.PesquisaPatrimonial_Veiculos_Tipos')
    ->select(
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.id as Id',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as Descricao')
    ->get();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
    ->get();

    $datahoje= Carbon::now();

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

    
    return view('Painel.PesquisaPatrimonial.nucleo.edit.veiculo', compact('codigo', 'numero','datas','id', 'datahoje', 'tipos', 'status', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function updateveiculo(Request $request) {

    $id = $request->get('id');
    $numero = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $placa = $request->get('placa');
    $modelo = $request->get('modelos');
    $descricaoveiculo = $request->get('descricaoveiculo');
    $combustivel = $request->get('combustivel');
    $fabricante_id = $request->get('fabricantes');
    $tipoveiculo_id = $request->get('tipoveiculo');
    $anomodelo = $request->get('anomodelos');
    $anofabricacao = $request->get('anofabricacao');
    $impedimento = $request->get('impedimento');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');
    $aberbacao828 = $request->get('aberbacao828');
    $averbacaopenhora = $request->get('averbacaopenhora');
    $dataaverbacao = $request->get('dataaverbacao');
    $carta = $request->get('carta');
    $datacarta = $request->get('datacarta');
    $status_id = $request->get('status');

    //Calcula valores para incluir no banco e posteriormente montar score
    $valor_base = $valor - $valor_alienacao;
    $pctmbase = -50.00;
    $valor_base = $valor * (1+($pctmbase/100));
    $gravame = -80.00;
    $valor_avaliacaoplc = $valor_base * (1+($gravame/100));

    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
    ->where('id', $id)  
    ->limit(1) 
    ->update(array(
      'user_id'=> $usuario_id,
      'data' => $data,
      'placa' => $placa,
      'modelo' => $modelo,
      'descricaoveiculo' => $descricaoveiculo,
      'combustivel' => $combustivel,
      'fabricante_id' => $fabricante_id,
      'tipoveiculo_id' => $tipoveiculo_id,
      'anomodelo' => $anomodelo,
      'anofabricacao' => $anofabricacao,
      'impedimento' => $impedimento,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor_base,
      'valor_avaliacaoplc' => $valor_avaliacaoplc,
      'averbacao828' => $aberbacao828,
      'averbacaopenhora' => $averbacaopenhora,
      'dataaverbacao' => $data,
      'carta' => $carta,
      'datacarta' => $data,
      'status_id' => $status_id
    ));

        //Loop anexar arquivos 
        $image = $request->file('select_file');

        foreach($image as $index => $image) {
    
           $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
          $image->storeAs('pesquisapatrimonial', $nameImage);
          Storage::disk('reembolso-local')->put($nameImage, File::get($image));

           $values = array(
            'id_matrix' => $numero, 
            'id_solicitacao' => $id_solicitacao,
            'tiposervico_id' => '3',
            'user_id' => $usuario_id,
            'anexo' => $nameImage,
            'formato' => $image->getClientOriginalExtension(),
            'data' => $data);
            DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
    
       } 


    flash('Registro atualizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabveiculo", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function storeveiculo(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $placa = $request->get('placa');
    $modelo = $request->get('modelos');
    $descricaoveiculo = $request->get('descricaoveiculo');
    $combustivel = $request->get('combustivel');
    $fabricante_id = $request->get('fabricantes');
    $tipoveiculo_id = $request->get('tipoveiculo');
    $anomodelo = $request->get('anomodelos');
    $anofabricacao = $request->get('anofabricacao');
    $impedimento = $request->get('impedimento');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');
    $aberbacao828 = $request->get('aberbacao828');
    $averbacaopenhora = $request->get('averbacaopenhora');
    $dataaverbacao = $request->get('dataaverbacao');
    $carta = $request->get('carta');
    $datacarta = $request->get('datacarta');
    $status_id = $request->get('status');

    //Calcula valores para incluir no banco e posteriormente montar score
    $valor_base = $valor - $valor_alienacao;
    $pctmbase = -50.00;
    $valor_base = $valor * (1+($pctmbase/100));
    $gravame = -80.00;
    $valor_avaliacaoplc = $valor_base * (1+($gravame/100));


    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'data' => $data,
      'placa' => $placa,
      'modelo' => $modelo,
      'descricaoveiculo' => $descricaoveiculo,
      'combustivel' => $combustivel,
      'fabricante_id' => $fabricante_id,
      'tipoveiculo_id' => $tipoveiculo_id,
      'anomodelo' => $anomodelo,
      'anofabricacao' => $anofabricacao,
      'impedimento' => $impedimento,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor_base,
      'valor_avaliacaoplc' => $valor_avaliacaoplc,
      'averbacao828' => $aberbacao828,
      'averbacaopenhora' => $averbacaopenhora,
      'dataaverbacao' => $dataaverbacao,
      'carta' => $carta,
      'datacarta' => $datacarta,
      'status_id' => $status_id);
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->insert($values);

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->select('id')->where('id_matrix', $numero)->value('id'); 


    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

       $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
      $image->storeAs('pesquisapatrimonial', $nameImage);
      Storage::disk('reembolso-local')->put($nameImage, File::get($image));

       $values = array(
        'id_matrix' => $numero, 
        'id_solicitacao' => $id_solicitacao,
        'tiposervico_id' => '3',
        'user_id' => $usuario_id,
        'anexo' => $nameImage,
        'formato' => $image->getClientOriginalExtension(),
        'data' => $data);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

   } 

    flash('Cadastro realizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabveiculo", ["codigo" => $codigo, "numero" => $numero]);
      

  }

  public function empresa($codigo, $numero) {
    $datahoje= Carbon::now();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
    ->get();

    $tipos =  DB::table('dbo.PesquisaPatrimonial_Imovel_Tipos')
    ->select(
     'dbo.PesquisaPatrimonial_Imovel_Tipos.id as Id',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as Descricao')
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


    return view('Painel.PesquisaPatrimonial.nucleo.abas.empresa', compact('codigo','numero', 'datahoje', 'status', 'tipos','totalNotificacaoAbertas', 'notificacoes'));
  }

  public function nucleo_editarempresa($codigo, $numero, $id) {

    $datahoje= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as cnpj',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.nomefantasia as nomefantasia',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.situacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.objetosocial as objetosocial',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.logradouro as logradouro',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.numero as numero',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.bairro as bairro',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.municipio as municipio',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cep as cep',
     'dbo.PesquisaPatrimonial_Solicitacao_Empresa.uf as uf',
     DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
     DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'), 
     DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'), 
     )
    ->where('id', '=', $id) 
    ->first();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
    ->get();
    $tipos =  DB::table('dbo.PesquisaPatrimonial_Imovel_Tipos')
    ->select(
     'dbo.PesquisaPatrimonial_Imovel_Tipos.id as Id',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as Descricao')
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
    

    return view('Painel.PesquisaPatrimonial.nucleo.edit.empresa', compact('id', 'numero', 'codigo', 'datahoje', 'status', 'tipos', 'datas', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function updateempresa(Request $request) {

     //Busca os dados 
     $id = $request->get('id_matrix');
     $numero = $request->get('numero');
     $codigo = $request->get('codigo');
     $data = Carbon::now();
     $usuario_id = Auth::user()->id;
     $cnpj = $request->get('cnpj');
     $razaosocial = $request->get('razaosocial');
     $nomefantasia = $request->get('nomefantasia');
     $objetosocial = $request->get('objetosocial');
     $logradouro = $request->get('logradouro');
     $numeroendereco = $request->get('numero');
     $bairro = $request->get('bairro');
     $municipio = $request->get('municipio');
     $cep = $request->get('cep');
     $uf = $request->get('uf');
     $valor = $request->get('capitalsocial');
     $capitalsocial =  str_replace (',', '.', str_replace ('.', '.', $valor));
     $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
     $valor_disponivel =  $request->get('valor_disponivel');
     $datafundacao = $request->get('datafundacao');
     $participacaosocietaria = $request->get('participacaosocietaria');
     $penhoracotas = $request->get('penhoracotas');
     $datapenhoracotas = $request->get('datapenhoracotas');
     $penhoradofaturamento = $request->get('penhoradofaturamento');
     $datapenhorafaturamento = $request->get('datapenhorafaturamento');
     $situacao = $request->get('situacao');
     $recuperacaojudicial = $request->get('recuperacaojudicial');
     $falencia = $request->get('falencia');
     $recuperacao = $request->get('recuperacao');
     $impedimento = $request->get('impedimento');

    //Calcula valores para incluir no banco e posteriormente montar score
    $pctmbase = -80.00;
    $valor_base = $valor * (1+($pctmbase/100));
    $gravame = -80.00;
    $valor_avaliacaoplc = $valor_base * (1+($gravame/100));


  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
   ->where('id', '=' ,$id) 
   ->limit(1) 
   ->update(array(
  'user_id' => $usuario_id,
  'data' => $data,
  'cnpj' => $cnpj,
  'razaosocial' => $razaosocial,
  'nomefantasia' => $nomefantasia,
  'objetosocial' => $objetosocial,
  'logradouro' => $logradouro,
  'numero' => $numeroendereco,
  'bairro' => $bairro,
  'municipio' => $municipio,
  'cep' => $cep,
  'uf' => $uf,
  'capitalsocial' => $capitalsocial,
  'valor_alienacao' => $valor_alienacao,
  'valor_disponivel' => $valor_disponivel,
  'valor_base' => $valor_base,
  'valor_avaliacaoplc' => $valor_avaliacaoplc,
  'datafundacao' => $datafundacao,
  'participacaosocietaria' => $participacaosocietaria,
  'penhoracotas' => $penhoracotas,
  'datapenhoracotas' => $datapenhoracotas,
  'penhoradofaturamento' => $penhoradofaturamento,
  'datapenhorafaturamento' => $datapenhorafaturamento,
  'situacao' => $situacao,
  'recuperacaojudicial' => $recuperacaojudicial,
  'falencia' => $falencia,
  'recuperacao' => $recuperacao,
  'impedimento' => $impedimento));

      //Loop anexar arquivos 
      $image = $request->file('select_file');

      foreach($image as $index => $image) {
  
         $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

         $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '4',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
  
     } 



flash('Registro atualizado com sucesso!')->success();   
return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabempresa", ["codigo" => $codigo, "numero" => $numero]);



  }

  public function storeempresa(Request $request) {



     //Busca os dados 
     $numero = $request->get('id_matrix');
     $id_matrix = $request->get('id_matrix');
     $codigo = $request->get('codigo');
     $data = Carbon::now();
     $usuario_id = Auth::user()->id;
     $cnpj = $request->get('cnpj');
     $razaosocial = $request->get('razaosocial');
     $nomefantasia = $request->get('nomefantasia');
     $objetosocial = $request->get('objetosocial');
     $logradouro = $request->get('logradouro');
     $numero = $request->get('numero');
     $bairro = $request->get('bairro');
     $municipio = $request->get('municipio');
     $cep = $request->get('cep');
     $uf = $request->get('uf');
     $valor = $request->get('capitalsocial');
     $capitalsocial =  str_replace (',', '.', str_replace ('.', '.', $valor));
     $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
     $valor_disponivel =  $request->get('valor_disponivel');
     $datafundacao = $request->get('datafundacao');
     $participacaosocietaria = $request->get('participacaosocietaria');
     $penhoracotas = $request->get('penhoracotas');
     $datapenhoracotas = $request->get('datapenhoracotas');
     $quantidadecotas = $request->get('quantidadecotas');
     $penhoradofaturamento = $request->get('penhoradofaturamento');
     $datapenhorafaturamento = $request->get('datapenhorafaturamento');
     $situacao = $request->get('situacao');
     $recuperacaojudicial = $request->get('recuperacaojudicial');
     $falencia = $request->get('falencia');
     $recuperacao = $request->get('recuperacao');
     $impedimento = $request->get('impedimento');

    //Calcula valores para incluir no banco e posteriormente montar score
 
    //$pctmbase = -80.00;
    //$valor_base = $valor_disponivel * (1+($pctmbase/100));
    //$gravame = -80.00;
    //$valor_avaliacaoplc = $valor_base * (1+($gravame/100));

    $values = array(
        'id_matrix' => $id_matrix, 
        'user_id' => $usuario_id,
        'data' => $data,
        'cnpj' => $cnpj,
        'razaosocial' => $razaosocial,
        'nomefantasia' => $nomefantasia,
        'objetosocial' => $objetosocial,
        'logradouro' => $logradouro,
        'numero' => $numero,
        'bairro' => $bairro,
        'municipio' => $municipio,
        'cep' => $cep,
        'uf' => $uf,
        'capitalsocial' => $valor_disponivel,
        'valor_alienacao' => $valor_alienacao,
        'valor_disponivel' => $valor_disponivel,
        //'valor_base' => $valor_base,
        //'valor_avaliacaoplc' => $valor_avaliacaoplc,
        'datafundacao' => $datafundacao,
        'participacaosocietaria' => $participacaosocietaria,
        'penhoracotas' => $penhoracotas,
        'datapenhoracotas' => $datapenhoracotas,
        'quantidadecotas' => $quantidadecotas,
        'penhoradofaturamento' => $penhoradofaturamento,
        'datapenhorafaturamento' => $datapenhorafaturamento,
        'situacao' => $situacao,
        'recuperacaojudicial' => $recuperacaojudicial,
        'falencia' => $falencia,
        'recuperacao' => $recuperacao,
        'impedimento' => $impedimento);

        DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->insert($values);  

        $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

      $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
      $image->storeAs('pesquisapatrimonial', $nameImage);
      Storage::disk('reembolso-local')->put($nameImage, File::get($image));

      $values = array(
       'id_matrix' => $numero, 
       'id_solicitacao' => $id_solicitacao,
       'tiposervico_id' => '4',
       'user_id' => $usuario_id,
       'anexo' => $nameImage,
       'formato' => $image->getClientOriginalExtension(),
       'data' => $data);
       DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 


    }     

    //Grava na tabela socios
    $socios = $request->get('socios');

    //Verificar se selecionou um sócio
    if($socios != null) {

    foreach($socios as $index => $socios) {


        $values = array(
        'empresa_id' => $id_solicitacao, 
        'socio' => $socios, 
        'data' => $data);
        DB::table('dbo.PesquisaPatrimonial_Empresa_Socios')->insert($values);

    }

    }

    
    flash('Cadastro realizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabempresa", ["codigo" => $codigo, "numero" => $id_matrix]);
        

    }



  public function storeinfojud(Request $request) {

     //Busca os dados
     $numero = $request->get('id_matrix');
     $codigo = $request->get('codigo');
     $data = Carbon::now();
     $usuario_id = Auth::user()->id;
     $descricao = $request->get('descricao');


    $values = array(
        'id_matrix' => $numero, 
        'user_id' => $usuario_id,
        'date' => $data,
        'descricao' => $descricao);
  
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')->insert($values);  

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Infojud')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '5',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     

    flash('Cadastro realizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabinfojud", ["codigo" => $codigo, "numero" => $numero]);


  }

  public function storebacenjud(Request $request) {

  $numero = $request->get('id_matrix');
  $id_matrix = $request->get('id_matrix');
  $codigo = $request->get('codigo');
  $data = Carbon::now();
  $usuario_id = Auth::user()->id;
  $descricao = $request->get('descricao');

  $values = array(
    'id_matrix' => $id_matrix, 
    'user_id' => $usuario_id,
    'date' => $data,
    'descricao' => $descricao);
 
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')->insert($values); 

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '6',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     



 flash('Cadastro realizado com sucesso!')->success();   
 return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabbacenjud", ["codigo" => $codigo, "numero" => $numero]);


  }

  
  public function protestos($codigo, $numero) {

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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.notas', compact('datahoje', 'codigo','numero', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function storenotas(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    // $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');
  
     $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'date' => $data,
      'descricao' => $descricao,
      'valor' => $valor,
      // 'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel);
  
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->insert($values);  

      $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->select('id')->where('id_matrix', $numero)->value('id'); 

      //Loop anexar arquivos 
      $image = $request->file('select_file');
  
      foreach($image as $index => $image) {
  
          $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
          $image->storeAs('pesquisapatrimonial', $nameImage);
          Storage::disk('reembolso-local')->put($nameImage, File::get($image));

          $values = array(
            'id_matrix' => $numero, 
            'id_solicitacao' => $id_solicitacao,
            'tiposervico_id' => '7',
            'user_id' => $usuario_id,
            'anexo' => $nameImage,
            'formato' => $image->getClientOriginalExtension(),
            'data' => $data);
            DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
  
      }     

  
   flash('Cadastro realizado com sucesso!')->success();   
   return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabprotestos", ["codigo" => $codigo, "numero" => $numero]);

     

    }

  public function redessociais($codigo, $numero) {

    $redesocials =  DB::table('dbo.PesquisaPatrimonial_RedesSociais_Tipos')
    ->select('id as Id','nome as Nome')
    ->where('status', '=', 'A')
    ->orderby('nome', 'asc')
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


    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.redessociais', compact('codigo','redesocials','datahoje', 'numero', 'totalNotificacaoAbertas', 'notificacoes'));
  }

  public function storeredessocias(Request $request) {



    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;


    $redesocial = $request->get('redesocial');
    $status = $request->get('encontrado');
    $observacao = $request->get('observacao');


    foreach($redesocial as $index => $redesocial) {

     $values = array(
       'id_matrix' => $id_matrix, 
       'user_id' => $usuario_id,
       'date' => $data,
       'rede_id' => $redesocial, 
       'status' => $status[$index],
       'descricao' => $observacao[$index]);
       DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')->insert($values);  
    }

    flash('Cadastro realizado com sucesso!')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabredessociais", ["codigo"=> $codigo, "numero" => $numero]);

  


    }


  public function tribunal($codigo, $numero) {

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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.tribunal', compact('totalNotificacaoAbertas','notificacoes','datahoje', 'numero', 'codigo'));

  }

  public function storetribunal(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    // $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');

    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'date' => $data,
      'descricao' => $descricao,
      'valor' => $valor,
      // 'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel);
   
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->insert($values);

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '9',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     


   flash('Cadastro realizado com sucesso!')->success();   
   return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabprocessosjudiciais", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function comercial($codigo, $numero) {

          
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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.comercial', compact('datahoje', 'numero', 'codigo', 'totalNotificacaoAbertas', 'notificacoes'));
  }

  public function storecomercial(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');

     $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'date' => $data,
      'descricao' => $descricao);
   
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')->insert($values);

      $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')->select('id')->where('id_matrix', $numero)->value('id'); 

      //Loop anexar arquivos 
      $image = $request->file('select_file');
  
      foreach($image as $index => $image) {
  
          $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
          $image->storeAs('pesquisapatrimonial', $nameImage);
          Storage::disk('reembolso-local')->put($nameImage, File::get($image));

          $values = array(
            'id_matrix' => $numero, 
            'id_solicitacao' => $id_solicitacao,
            'tiposervico_id' => '11',
            'user_id' => $usuario_id,
            'anexo' => $nameImage,
            'formato' => $image->getClientOriginalExtension(),
            'data' => $data);
            DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
  
      }     

  
   flash('Cadastro realizado com sucesso!')->success();   
   return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabdossiecomercial", ["codigo" => $codigo, "numero" => $numero]);
  
  }

  public function diversos($codigo, $numero) {

          
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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.diversos', compact('datahoje', 'numero', 'codigo', 'totalNotificacaoAbertas', 'notificacoes'));
  }

  public function storediversos(Request $request) {

    
    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');

    //Calcula valores para incluir no banco e posteriormente montar score
    $gravame = -80.00;
    $valor_avaliacaoplc = $valor_disponivel * (1+($gravame/100));

  
    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'data' => $data,
      'descricao' => $descricao,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor_avaliacaoplc,
      'valor_avaliacaoplc' => $valor_avaliacaoplc);
   
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->insert($values);

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '14',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     

   
  flash('Cadastro realizado com sucesso!')->success();   
  return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabdiversos", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function moeda($codigo, $numero) {

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

   $datahoje= Carbon::now();
   return view('Painel.PesquisaPatrimonial.nucleo.abas.moeda', compact('datahoje', 'numero', 'codigo', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function storemoeda(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');
    $tipo = $request->get('tipo');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');

    //Calcula valores para incluir no banco e posteriormente montar score
    $gravame = -100.00;
    $valor_avaliacaoplc = $valor_disponivel * (1+($gravame/100));
    
    
    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'data' => $data,
      'tipo' => $tipo,
      'descricao' => $descricao,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor_avaliacaoplc,
      'valor_avaliacaoplc' => $valor_avaliacaoplc);
   
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->insert($values);

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '15',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     

   
  flash('Cadastro realizado com sucesso!')->success();   
  return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabmoeda", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function joias($codigo, $numero) {

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

   $datahoje= Carbon::now();
   return view('Painel.PesquisaPatrimonial.nucleo.abas.joias', compact('datahoje', 'numero', 'codigo', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function storejoias(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');
    $tipo = $request->get('tipo');
    $valor =  str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $valor_alienacao =  str_replace (',', '.', str_replace ('.', '', $request->get('valor_alienacao')));
    $valor_disponivel =  $request->get('valor_disponivel');

    //Calcula valores para incluir no banco e posteriormente montar score
    $gravame = -50.00;
    $valor_avaliacaoplc = $valor_disponivel * (1+($gravame/100));
  
    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'data' => $data,
      'tipo' => $tipo,
      'descricao' => $descricao,
      'valor' => $valor,
      'valor_alienacao' => $valor_alienacao,
      'valor_disponivel' => $valor_disponivel,
      'valor_base' => $valor_avaliacaoplc,
      'valor_avaliacaoplc' => $valor_avaliacaoplc);
   
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->insert($values);

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '16',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     

   
  flash('Cadastro realizado com sucesso!')->success();   
  return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabjoias", ["codigo" => $codigo, "numero" => $numero]);

  }

  public function pesquisa($codigo, $numero) {

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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.pesquisa', compact('datahoje', 'numero', 'codigo', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function storepesquisa(Request $request) {

    $numero = $request->get('id_matrix');
    $id_matrix = $request->get('id_matrix');
    $codigo = $request->get('codigo');
    $data = Carbon::now();
    $usuario_id = Auth::user()->id;
    $descricao = $request->get('descricao');
  
    $values = array(
      'id_matrix' => $id_matrix, 
      'user_id' => $usuario_id,
      'date' => $data,
      'descricao' => $descricao);
   
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')->insert($values);

    $id_solicitacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')->select('id')->where('id_matrix', $numero)->value('id'); 

    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

        $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
        $image->storeAs('pesquisapatrimonial', $nameImage);
        Storage::disk('reembolso-local')->put($nameImage, File::get($image));

        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id_solicitacao,
          'tiposervico_id' => '10',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'formato' => $image->getClientOriginalExtension(),
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 

    }     

   
  flash('Cadastro realizado com sucesso!')->success();   
  return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabpesquisa", ["codigo" => $codigo, "numero" => $numero]);
  
  }


  function fetch2(Request $request) {

    $dado = $request->get('dado');

    $response = DB::table('PLCFULL.dbo.Jurid_Pastas')
     ->where('Codigo_Comp', 'LIKE',  $dado . '%')
     ->orWhere('NumPrc1_Sonumeros', 'LIKE',  $dado . '%')
     ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
     ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'PLCFULL.dbo.Jurid_Pastas.UF', '=', 'dbo.PesquisaPatrimonial_Estados.uf')
     ->leftjoin('PLCFULL.dbo.Jurid_Capitais', 'PLCFULL.dbo.Jurid_Pastas.Comarca', '=', 'PLCFULL.dbo.Jurid_Capitais.Cidade')
     ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCLiente.Codigo')
     ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
     ->select(
       'PLCFULL.dbo.Jurid_Pastas.id_pasta as id_pasta',
       'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as pasta',
       'PLCFULL.dbo.Jurid_Pastas.Descricao as pastadescricao',
       'PLCFULL.dbo.Jurid_Pastas.Cliente as cliente',
       'PLCFULL.dbo.Jurid_CliFor.Codigo as clientecodigo',
       'PLCFULL.dbo.Jurid_CliFor.Nome as nome',
       'dbo.PesquisaPatrimonial_Estados.id as estadoid',
       'dbo.PesquisaPatrimonial_Estados.descricao as estado',
       'PLCFULL.dbo.Jurid_Capitais.id as comarcaid',
       'PLCFULL.dbo.Jurid_Capitais.Cidade as comarca',
       'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as codigogrupo',
       'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as grupo',
       'PLCFULL.dbo.Jurid_CliFor.Razao as razao',
       'PLCFULL.dbo.Jurid_Unidade.Codigo as codigounidade',
       'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
       )
     ->get();

     echo $response;
    
  }

  // function fetch6(Request $request) {

  //   $codigo = $request->get('codigo');

  //   $response = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')->select('Descricao')->where('Codigo', '=',  $codigo)->value('Descricao');

  //   echo $response;

  // }

  function fetch5(Request $request) {

    $grupocliente_id = $request->get('grupocliente_id');

    $clientes =  DB::table('dbo.PesquisaPatrimonial_Clientes')
    ->where('grupo_id', '=', $grupocliente_id)
    ->where('status', '=', 'A')
    ->select('descricao','id_referencia')
    ->orderby('descricao', 'asc')
    ->get();

    foreach($clientes as $index) {  
    $response = '<option selected="selected" value="'.$index->id_referencia.'">'.$index->descricao.'</option>';
    echo $response;
    }
  }

  function fetch6(Request $request) {

    $codigo = $request->get('codigo');

    $data = DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->where('codigo', '=',  $codigo)
     ->count();
     
     if($data > 1) {
        $response = '<option value="4">Nova pesquisa</option>';
        $response = '<option value="3">Atualização</option>';
     } else {
      $response = '<option value="4">Nova pesquisa</option>';;
     }
     echo $response;

  }

  function fetch7(Request $request) {

    $cliente_id = $request->get('cliente_id');


    $response = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->where('cliente_id', '=',  $cliente_id)
    // ->select(
      // DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.Valor ) as Valor'),
      // DB::raw('COUNT(dbo.PesquisaPatrimonial_Matrix.id) as QuantidadePesquisas'))
    ->count();
     
     echo $response;

  }

  function fetch8(Request $request) {

    $cliente_id = $request->get('cliente_id');

    $data = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->where('cliente_id', '=',  $cliente_id)
    ->select('dbo.PesquisaPatrimonial_Matrix.data')
    ->value('dbo.PesquisaPatrimonial_Matrix.data');

    if($data == null) {
      $response = "Nenhuma pesquisa encontrada para este cliente.";
    } else {
      $response = date('d-m-Y H:i:s', strtotime($data));
    }



    echo $response;

  }

  function fetch9(Request $request) {

    $cliente_id = $request->get('cliente_id');
    $carbon= Carbon::now();

    $codigocliente = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Codigo')->where('id_cliente', '=', $cliente_id)->value('Codigo');
    $clienterazao = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Nome')->where('id_cliente', '=', $cliente_id)->value('Nome');
    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;

    $saldoempresar = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
    
  
    $saldoempresap = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
  
     $response = $saldoempresar - $saldoempresap;
     
     echo 'R$ ' . $response , ' ,00';

  }

  function fecthtiposservico(Request $request) {

    $uf_id = $request->get('escolha_id');

    if($uf_id != null) {
     
      $uf_sigla = DB::table('dbo.PesquisaPatrimonial_Estados')
      ->select('uf')
      ->where('id','=',$uf_id)
      ->value('uf');
  
      $datas = DB::table('dbo.PesquisaPatrimonial_Servicos')
       ->where('uf_id', '=', $uf_id)
       ->select('id','descricao')
       ->orderby('descricao', 'desc')
       ->get();
  
    } else {
      $ufid = $request->get('escolha');

      $uf_sigla = DB::table('dbo.PesquisaPatrimonial_Estados')
      ->select('uf')
      ->where('id','=',$ufid)
      ->value('uf');
  
      $datas = DB::table('dbo.PesquisaPatrimonial_Servicos')
       ->where('uf_id', '=', $ufid)
       ->select('id','descricao')
       ->orderby('descricao', 'desc')
       ->get();
    }

    foreach($datas as $index) {  
      $response = '<option value="'.$index->id.'">'.$index->descricao.'</option>';
      echo $response;
    }

  }


  function buscavalorestotal(Request $request) {


    $selecionados = $request->get('selecionados');
    $valortotal = 0;

    $data = array();
    foreach($selecionados as $index => $selecionados)
    {
        $item = array('selecionados' => $selecionados);
        array_push($data,$item);

        //Pego o valor de cada tiposolicitacao selecionado

        $valor = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')
                ->select(DB::raw('CAST(valor AS NUMERIC(15,2)) as valor'))
                ->where('id','=', $selecionados)
                ->value(DB::raw('CAST(valor AS NUMERIC(15,2)) as valor'));

       $valortotal += $valor;
       $response = number_format($valortotal, 2, '.', '');


   // $total = collect($request->selecionados)->sum();
   // $response = number_format($total, 2, '.', '');
    }
    echo($response);


    // $size = count(collect($request)->get('selecionados'));
    // $valortotal = 0;
    
    // $data = array();
    // foreach($selecionados as $index => $selecionados) {
    //     $item = array('selecionados' => $selecionados);

    //     $valor= DB::table('dbo.PesquisaPatrimonial_Servicos_Tipos')
    //                   ->select(DB::raw('CAST(valor AS NUMERIC(15,2)) as valor'))
    //                   ->where('id','=',$selecionados)
    //                   ->value(DB::raw('CAST(valor AS NUMERIC(15,2)) as valor'));
    // }
  }

  function fecthtaxaservico(Request $request) {
    $tiposervico_id = $request->get('tiposervico_id');

    $response = DB::table('dbo.PesquisaPatrimonial_Servicos')
    ->select(DB::raw('CAST(taxa_servico AS NUMERIC(15,2)) as valor'))
    ->where('id','=',$tiposervico_id)
    ->value(DB::raw('CAST(taxa_servico AS NUMERIC(15,2)) as valor'));

    echo($response);
  }



  function buscaemailcliente(Request $request) {

    $cliente_id = $request->get('cliente_id');

    $emailcliente =  DB::table('dbo.PesquisaPatrimonial_EmailCliente')
    ->select('email')
    ->where('cliente_id','=',$cliente_id)
    ->value('email'); 

    echo $emailcliente;
  }

  function buscaunidadecliente(Request $request) {

    $cliente_id = $request->get('cliente_id');

    $response =  DB::table('PLCFULL.dbo.Jurid_CliFor')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigounidade', 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade')
                ->where('id_cliente','=',$cliente_id)
                ->get(); 

    echo $response;
  }




  function cadastrooutraparte($codigo) {

    $datahoje= Carbon::now();

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

    return view('Painel.PesquisaPatrimonial.nucleo.cadastrooutraparte', compact('datahoje','codigo', 'totalNotificacaoAbertas', 'notificacoes'));
  }

  function storedadosoutraparte(Request $request) {

    $usuario_id = Auth::user()->id;
    $datahoje= Carbon::now();


    $codigo = $request->get('codigo');
    $nome = $request->get('nome');
    $sexo = $request->get('sexo');
    $datanascimento = $request->get('datanascimento');
    $identidade = $request->get('identidade');
    $orgaoemissor = $request->get('orgaoemissor');
    $dataexpedicao = $request->get('dataexpedicao');
    $cep = $request->get('cep');
    $rua = $request->get('rua');
    $bairro = $request->get('bairro');
    $cidade = $request->get('cidade');
    $uf = $request->get('uf');
    $estadocivil = $request->get('estadocivil');
    $profissao =  $request->get('profissao');
    $valorsemformato = $request->get('valor');
    $valor =  str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
    $telefone = $request->get('telefone');
    $tiporesidencia = $request->get('tiporesidencia');
    $email = $request->get('email');
    $pai = $request->get('pai');
    $mae = $request->get('mae');

    $values = array(
    'Codigo' => $codigo, 
    'Descricao' => $nome,
    'DataNasc' => $datanascimento,
    'NomePai' => $pai,
    'NomeMae' => $mae,
    'NumCI'=> $identidade,
    'DataExp' => $dataexpedicao,
    'OrgExp' => $orgaoemissor,
    'Endereco' => $rua,
    'Bairro' => $bairro,
    'Cidade' => $cidade,
    'UF' => $uf,
    'Cep' => $cep,
    'Fone' => $telefone,
    'TipoOutraParte' => '1',
    'E_Mail' => $email,
    'Celular' => $telefone,
    'Atuante' => '1',
   // 'sexo' => $sexo,
   // 'Naturalidade' => 'Brasileiro(a)',
  //  'estado_civil'  => $estadocivil,
  //  'profissao' => $profissao,
  //  'rendimento_bruto' => $valor,
  //  'tipo_residencia' => $tiporesidencia
  );
    DB::table('PLCFULL.dbo.Jurid_Outra_Parte')->insert($values);

    //Pega id matrix
    $id_matrix = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('id')->where('codigo','=', $codigo)->value('id'); 

    //Grava na Hist
    $values = array('id_matrix' => $id_matrix, 'user_id' => $usuario_id, 'status_id' => '10', 'data' => $datahoje);
    DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values);  


    flash('Cadastro da outra parte realizado com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.index")->withInput();

  }

  public function nucleo_editaroutraparte($codigo, $numero, $id) {

    $datahoje= Carbon::now();

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

    $clientes = DB::table('dbo.PesquisaPatrimonial_Clientes')
    ->select(
     'dbo.PesquisaPatrimonial_Clientes.id_referencia as Codigo',
     'dbo.PesquisaPatrimonial_Clientes.descricao as Descricao')
    ->orderBy('dbo.PesquisaPatrimonial_Clientes.descricao', 'asc')
    ->get();

    $status =  DB::table('dbo.PesquisaPatrimonial_StatusSolicitacao')
    ->select(
     'dbo.PesquisaPatrimonial_StatusSolicitacao.id as Id',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Descricao')
    ->get();  
    
    $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as data',
      'dbo.PesquisaPatrimonial_Clientes.id_referencia as clienteid',
      'dbo.PesquisaPatrimonial_Clientes.descricao as clientedescricao',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as classificacao',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.id as statusid',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as statusdescricao')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id', '=', $id)  
    ->first();


    return view('Painel.PesquisaPatrimonial.nucleo.edit.outraparte', compact('datas','datahoje','clientes','status','codigo', 'numero', 'id','totalNotificacaoAbertas','notificacoes'));

  }

  public function updateoutraparte(Request $request) {

       //Recupera os dados

       $numero = $request->get('id_matrix');
       $data = $request->get('data');
       $grupocliente = $request->get('cshopping');
       $codigo = $request->get('codigo');
       $id_status = $request->get('status');
       $id = $request->get('id');
       $usuario_id = Auth::user()->id;


       DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
       ->where('id', '=' ,$id) 
       ->limit(1) 
       ->update(array('id_matrix' => $numero, 'user_id' => $usuario_id, 'grupocliente' => $grupocliente,
       'id_status' => $id_status));



    //Loop anexar arquivos 
    $image = $request->file('select_file');

    foreach($image as $index => $image) {

      $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
      $image->storeAs('pesquisapatrimonial', $nameImage);
      Storage::disk('reembolso-local')->put($nameImage, File::get($image));


        $values = array(
          'id_matrix' => $numero, 
          'id_solicitacao' => $id,
          'tiposervico_id' => '1',
          'user_id' => $usuario_id,
          'anexo' => $nameImage,
          'data' => $data);
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')->insert($values); 
 
    }     

   
       flash('Registro atualizado com sucesso!')->success();   
       return redirect()->route("Painel.PesquisaPatrimonial.step1", ["codigo"=> $codigo, "numero" => $numero]);
  }



  function exportarsolicitacoes() {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin')
       ->get()
       ->toArray();
     $customer_array[] = array('Codigo',  'OutraParte', 'Solicitante' ,'Status', 'DataSolicitacao');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Codigo'  => $customer->Codigo,
       'OutraParte'  => $customer->OutraParte,
       'Solicitante' => $customer->Solicitante,
       'Status' => $customer->Status,
       'DataSolicitacao'=> date('d/m/Y', strtotime($customer->DataSolicitacao)),
      );
     }
     Excel::create('Solicitações pesquisa', function($excel) use ($customer_array){
      $excel->setTitle('Solicitações pesquisa');
      $excel->sheet('Solicitações pesquisa', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');

  }



  public function anexararquivos($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as Classificacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.PRConta',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
      'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
      'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
      'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Razao as UnidadeRazao',
      'PLCFULL.dbo.Jurid_Unidade.CNPJ as UnidadeCNPJ',
      'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
      'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
      'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
      'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
      'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
      'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $motivos = DB::table('dbo.Jurid_Nota_Tiposervico')
    ->get();

    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao',
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->get();

    $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                ->select('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id','dbo.PesquisaPatrimonial_Cidades.uf_sigla as descricao', 'dbo.PesquisaPatrimonial_Cidades.municipio as comarca', 'dbo.PesquisaPatrimonial_Cidades.regiao', 'dbo.PesquisaPatrimonial_Cidades.porte', 'dbo.PesquisaPatrimonial_Cidades.capital')
                ->leftjoin('dbo.PesquisaPatrimonial_Cidades', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.comarca', 'dbo.PesquisaPatrimonial_Cidades.municipio')
                ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
                ->get();

    $cidades = DB::table('dbo.PesquisaPatrimonial_Cidades')
              ->get();

    $correspondentes = DB::table('dbo.users')
                       ->select('dbo.users.id', 'dbo.users.name')
                       ->join('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
                       ->where('dbo.profile_user.profile_id', '1')
                       ->orderby('dbo.users.name', 'asc')
                       ->groupby('dbo.users.id', 'dbo.users.name')
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

     $codigocliente = $datas->CodigoCliente;
     $diasvencimento = $datas->DiasVencimento;
     $clienterazao = $datas->ClienteFantasia;
  
     $datavencimento = date('Y-m-d', strtotime('+ 60 days'));

     $razaoeditado = substr($clienterazao, 0, 50);
     $primeiroscodigo =  substr($codigocliente, 0, 5);  
     $descricao = "PESQUISA" . $primeiroscodigo;


     $saldoclienter = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
     ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
     ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
     ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
     ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $descricao)
     ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipolan', '=', 'TRANSF')
     ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
     
   
     $saldoclientep = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
     ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
     ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
     ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
     ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
     ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
 
     $saldototal =  $saldoclienter - $saldoclientep;
 

    $saldoassertivar = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', 'ASSERTIVA')
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

    $saldoassertivap = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', 'ASSERTIVA')
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

    $saldototalassertiva = $saldoassertivar - $saldoassertivap;

    // if(Auth::user()->id == 25) {

    //   return view('Painel.PesquisaPatrimonial.nucleo.fichafinanceira2', compact('carbon','solicitacoes','comarcas','cidades','correspondentes','datas','totalNotificacaoAbertas', 'notificacoes', 'saldototal', 'datavencimento', 'id_matrix'));

    // }


    // return view('Painel.PesquisaPatrimonial.nucleo.fichafinanceira', compact('carbon','solicitacoes','comarcas','datas','totalNotificacaoAbertas', 'notificacoes', 'saldototal', 'datavencimento', 'id_matrix'));
    return view('Painel.PesquisaPatrimonial.nucleo.fichafinanceira2', compact('carbon','motivos','saldototalassertiva','solicitacoes','comarcas','cidades','correspondentes','datas','totalNotificacaoAbertas', 'notificacoes', 'saldototal', 'datavencimento', 'id_matrix'));

   }

   public function nucleo_removersolicitacao(Request $request) {

      $id_solicitacao = $request->get('id_solicitacao');
      $id_matrix = $request->get('id_matrix');


      //Deleta na base 
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->where('id_matrix', $id_matrix)->where('id_tiposolicitacao', $id_solicitacao)->delete();       

      //Envia notificação ao Gumercindo
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '1', 'tipo' => '4', 'obs' => 'Pesquisa patrimonial: Foi excluido um tipo de solicitação pela equipe do núcleo de pesquisa patrimonial.', 'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '186', 'tipo' => '4', 'obs' => 'Pesquisa patrimonial: Foi excluido um tipo de solicitação pela equipe do núcleo de pesquisa patrimonial.', 'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

   }

   public function nucleo_buscaboletogerado(Request $request) {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');

      $clientecodigo = $request->get('clientecodigo');

      //Verifica se encontra 
      $response = DB::table('PLCFULL.dbo.Jurid_Boleto')
      ->where('PLCFULL.dbo.Jurid_Boleto.codigo_cliente', $clientecodigo)
      ->where('PLCFULL.dbo.Jurid_Boleto.situacao', '=','0')
      ->whereDate('PLCFULL.dbo.Jurid_Boleto.data_emissao', $datahoje)
      ->count();

      echo $response;

   }

   public function storeanexararquivos2(Request  $request) {
     

    
    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitanteid');
    $solicitante_email = $request->get('solicitanteemail');
    $solicitante_cpf = $request->get('solicitantecpf');
    $solicitante_nome = $request->get('solicitante');
    $cliente = $request->get('cliente');
    $clientecodigo = $request->get('clientecodigo');
    $clientecep = $request->get('clientecep');
    $clienteendereco = $request->get('clienteendereco');
    $clientebairro = $request->get('clientebairro');
    $clienteuf = $request->get('clienteuf');
    $clientecidade = $request->get('clientecidade');
    $unidade = $request->get('unidade');
    $unidadedescricao = $request->get('unidadedescricao');
    $unidaderazao = $request->get('unidaderazao');
    $unidadecnpj = $request->get('unidadecnpj');
    $unidadeendereco = $request->get('unidadeendereco');
    $unidadebairro = $request->get('unidadebairro');
    $unidadecidade = $request->get('unidadecidade');
    $unidadeuf = $request->get('unidadeuf');
    $unidadecep = $request->get('unidadecep');
    $unidadetelefone = $request->get('unidadetelefone');
    $grupocliente = $request->get('grupocliente');
    $grupocliente_codigo = $request->get('grupocliente_codigo');
    $numeroprocesso = $request->get('numeroprocesso');
    $outraparte = $request->get('outraparte');
    $outraparte_codigo = $request->get('codigo');
    $emailcliente = $request->get('emailcliente');
    $tipo = $request->get('tipo');
    $tiposolicitacao = $request->get('tiposolicitacao');
    $tiposervico = $request->get('tiposervico');
    $pasta = $request->get('codigopasta');
    $setor = $request->get('setor');
    $contrato = $request->get('contrato');
    $observacao = $request->get('observacao');
    $observacaocancelamento = $request->get('observacacancelamento');


    $unidadecodigo = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Codigo')->where('Descricao', $unidade)->value('Codigo');

    $cobravel = $request->get('cobravel');
    $adiantamento = $request->get('adiantamento');
    $formapagamento = $request->get('formapagamento');
    $valorsemformato = $request->get('valortotal');
    $valor =  str_replace (',', '.', str_replace ('.', ',', $valorsemformato));

    $razaoeditado = substr($cliente, 0, 50);
    $primeiroscodigo =  substr($clientecodigo, 0, 5);  
    $codigoportador = "PESQUISA" . $primeiroscodigo;

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    $statuescolhido = $request->get('statusescolhido');
    $usuarioid = Auth::user()->id;
    //gerarcobranca
    //solicitaradiantamento
    //naocobravel
    //cancelar


    //Se for para cancelar está solicitação
    if($statuescolhido == "cancelar") {


      //Update Matrix
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id'=> '18', 'observacaocancelamento' => $observacaocancelamento));

      //Inativa a Pasta
      DB::table('PLCFULL.dbo.Jurid_Pastas')
      ->where('Codigo_Comp', $pasta)  
      ->limit(1) 
      ->update(array('Status'=> 'Inativa'));

      //Grava na Hist
      $values = array('id_matrix' => $id_matrix, 
      'user_id' => $usuarioid, 'status_id' => '18', 'data' => $carbon);
       DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

      //Manda notificação para o solicitante
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Solicitação cancelada pelo núcleo.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Envia e-mail
      Mail::to('vanessa.ferreira@plcadvogados.com.br')
      ->cc('felipe.rocha@especialistaresultados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br')
      ->send(new SolicitacaoCancelada($id_matrix));

    }

    //Se o valor da pesquisa for Zerado vai direto para pesquisa em andamento
    elseif($valor == 0.00) {

      //Update Matrix colocando a pesquisa em andamento
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('equipe_id' => $usuarioid,'valor' => $valor,'status_id'=> '4'));

      //Grava na Hist 
      $values = array('id_matrix' => $id_matrix, 'user_id' => $usuarioid, 'status_id' => '8', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

      //Manda notificação para o solicitante
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Montagem da ficha financeira realizada com sucesso.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Foreach 
      $tipossolicitacao =  $request->get('tipossolicitacao');
      $comarcas = $request->get('comarcas');
      $valor = str_replace (',', '.', str_replace ('.', ',', $request->get('valor')));
      $anexoboleto = $request->file('anexoboleto');
      $assertiva = $request->get('assertiva');
      $motivo = $request->get('motivo');
      $observacao = $request->get('informacoesadicionais');

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->where('id_matrix', $id_matrix)->delete();        

      foreach($comarcas as $index => $comarcas) {

        $values = array(
              'id_matrix' => $id_matrix, 
              'id_tiposolicitacao' => $tipossolicitacao[$index],
              'solicitante_id' => $solicitante_id,
              'cidade_id' => $comarcas,
              'valor' => $valor[$index],
              'data' => $carbon,
              'status_id' => '1',
              'assertiva' => $assertiva[$index],
              'observacao' => $observacao[$index]);
        DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);

      } 

    } 


    //Se for uma solicitação não cobravel
    elseif($statuescolhido == "naocobravel") {

      //Update status 
       DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('equipe_id' => $usuarioid,'valor' => $valor,'status_id'=> '23'));

      //Grava na Hist
      $values = array('id_matrix' => $id_matrix, 'user_id' => $usuarioid, 'status_id' => '22', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 
      
      //Manda notificação para o Adv Solicitante
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Montagem da ficha financeira realizada com sucesso.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);
          
      //Manda notificação para o Supervisor Pesquisa Patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '709', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Montagem da ficha financeira realizada com sucesso.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);
      
      //Manda notificação para o financeiro pesquisa patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '235', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Montagem da ficha financeira realizada com sucesso.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '242', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Montagem da ficha financeira realizada com sucesso.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Cria os debites
      $tipossolicitacao =  $request->get('tipossolicitacao');
      $comarcas = $request->get('comarcas');
      $valor = str_replace (',', '.', str_replace ('.', ',', $request->get('valor')));
      $anexoboleto = $request->file('anexoboleto');
      $assertiva = $request->get('assertiva');
      $possuicorrespondente = $request->get('possuicorrespondente');
      $correspondente_id = $request->get('correspondente');
      $motivo = $request->get('motivo');
      $correspondente = $request->get('correspondente');
      $valorcorrespondente = str_replace (',', '.', str_replace ('.', ',', $request->get('valorcorrespondente')));
      $observacao = $request->get('informacoesadicionais');
      
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->where('id_matrix', $id_matrix)->delete();        

      foreach($comarcas as $index => $comarcas) {

            //Verifico se tem Correspondente
            if($possuicorrespondente[$index] == "Sim") {

              $correspondente_cpf = DB::table('dbo.users')->select('dbo.users.cpf')->where('dbo.users.id', '=', $correspondente_id[$index])->value('cpf');
              $correspondente_email = DB::table('dbo.users')->select('dbo.users.email')->where('dbo.users.id', '=', $correspondente_id[$index])->value('email');

              //Envia Notificação para o Correspondente
              $values3= array('data' => $carbon, 'id_ref' => $numdebitecorrespondente, 'user_id' => $usuarioid, 'destino_id' => $correspondente_id[$index], 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Criado um debite para o correspondente referente a pesquisa patrimonial.' ,'status' => 'A');
              DB::table('dbo.Hist_Notificacao')->insert($values3);

              //Envia e-mail para o Correspondente com as informações copiando equipe do nucleo
              $values = array(
                'id_matrix' => $id_matrix, 
                'id_tiposolicitacao' => $tipossolicitacao[$index],
                'solicitante_id' => $solicitante_id,
                'cidade_id' => $comarcas,
                'valor' => $valor[$index],
                'data' => $carbon,
                'correspondente_id' => $correspondente[$index],
                'motivocorrespondente_id' => $motivo[$index],
                'status_id' => '1',
                'assertiva' => $assertiva[$index],
                'observacao' => $observacao[$index]);
                DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);
              } 
              //Se não possuir correspondente
              else {
                $values = array(
                  'id_matrix' => $id_matrix, 
                  'id_tiposolicitacao' => $tipossolicitacao[$index],
                  'solicitante_id' => $solicitante_id,
                  'cidade_id' => $comarcas,
                  'valor' => $valor[$index],
                  'data' => $carbon,
                  'status_id' => '1',
                  'assertiva' => $assertiva[$index],
                  'observacao' => $observacao[$index]);
                  DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);

              }
      
    } 
  }
  //Fim se não for cobravel


    //Se for uma solicitação de adiantamento 
    elseif($statuescolhido == "solicitaradiantamento") {

      $observacaoadiantamento = $request->get('observacaoadiantamento');
      $anexoadiantamento = $request->file('anexoadiantamento');

      $new_name = 'anexoadiantamento_' . $id_matrix . '_' . $carbon->format('dmY') . '.'  . $anexoadiantamento->getClientOriginalExtension();
      $anexoadiantamento->storeAs('pesquisapatrimonial', $new_name);

      //Update status 
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('equipe_id' => $usuarioid,'valor' => $valor,'status_id'=> '9', 'observacaoadiantamento' => $observacaoadiantamento, 'anexoadiantamento' => $new_name));

      //Grava na tabela hist informando que a solicitação esta aguardando revisão supervisão    
      $values = array('id_matrix' => $id_matrix, 'user_id' => $usuarioid, 'status_id' => '9', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 
            
      //Envia Notificação para o Supervisor da Pesquisa Patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '709', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Nova revisão de pesquisa patrimonial.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);
            
      //Envia Email para o Supervisor da Pesquisa Patrimonial
      Mail::to('ronaldo.ferreira@plcadvogados.com.br')
      ->cc('gumercindo.ribeiro@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br', 'vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
      ->send(new RevisarSolicitacaoSupervisao($id_matrix));

      $tipossolicitacao =  $request->get('tipossolicitacao');
      $comarcas = $request->get('comarcas');
      $valor = str_replace (',', '.', str_replace ('.', ',', $request->get('valor')));
      $anexoboleto = $request->file('anexoboleto');
      $assertiva = $request->get('assertiva');
      $possuicorrespondente = $request->get('possuicorrespondente');
      $motivo = $request->get('motivo');
      $correspondente = $request->get('correspondente');
      $valorcorrespondente = str_replace (',', '.', str_replace ('.', ',', $request->get('valorcorrespondente')));
      $observacao = $request->get('informacoesadicionais');
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->where('id_matrix', $id_matrix)->delete();        

      foreach($comarcas as $index => $comarcas) {

            //Verifico se tem Correspondente
            if($possuicorrespondente[$index] == "Sim") {

              $correspondente_cpf = DB::table('dbo.users')->select('dbo.users.cpf')->where('dbo.users.id', '=', $correspondente_id[$index])->value('cpf');
              $correspondente_email = DB::table('dbo.users')->select('dbo.users.email')->where('dbo.users.id', '=', $correspondente_id[$index])->value('email');

            //Envia Notificação para o Correspondente
            $values3= array('data' => $carbon, 'id_ref' => $numdebitecorrespondente, 'user_id' => $usuarioid, 'destino_id' => $correspondente_id[$index], 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Criado um debite para o correspondente referente a pesquisa patrimonial.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values3);

            $values = array(
              'id_matrix' => $id_matrix, 
              'id_tiposolicitacao' => $tipossolicitacao[$index],
              'solicitante_id' => $solicitante_id,
              'cidade_id' => $comarcas,
              'valor' => $valor[$index],
              'data' => $carbon,
              'correspondente_id' => $correspondente[$index],
              'motivocorrespondente_id' => $motivo[$index],
              'status_id' => '1',
              'assertiva' => $assertiva[$index],
              'observacao' => $observacao[$index]);
              DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);
            } 

            //Se não possuir correspondente
            else {

                $values = array(
                  'id_matrix' => $id_matrix, 
                  'id_tiposolicitacao' => $tipossolicitacao[$index],
                  'solicitante_id' => $solicitante_id,
                  'cidade_id' => $comarcas,
                  'valor' => $valor[$index],
                  'data' => $carbon,
                  'status_id' => '1',
                  'assertiva' => $assertiva[$index],
                  'observacao' => $observacao[$index]);
                  DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);
              }

    }
  }
   //Fim Adiantamento 


    //Se for gerar boleto 
    elseif($statuescolhido == "gerarcobranca") {


      //Verifico se o saldo do cliente é > ou = o valor da solicitação, dai não precisa gerar o boleto
      $saldocliente =  $request->get('saldocliente');
       
      if($saldocliente >= $valor) {


      // $ultimonumprc2 = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
      // $numcpr = $ultimonumprc2 + 1;

      //  $values2= array(
      //   'Tipodoc' => 'TRANSF', 
      //   'Numdoc' => $numcpr,
      //   'Cliente' => '004',
      //   'Tipo' => 'P',
      //   'TipodocOR' => 'TRANSF',
      //   'NumDocOr' => $numcpr,
      //   'Tipolan' => '16.09',
      //   'Valor' => $valor,
      //   'Centro' => $setor,
      //   'Dt_baixa' => $carbon->format('Y-m-d'),
      //   'Portador' => $codigoportador,
      //   'Obs' => 'Uso do saldo atual do cliente para pagamento da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal. ' . $observacao,
      //   'Juros' => '0,00',
      //   'Dt_Compet' => $carbon->format('Y-m-d'),
      //   'DT_Concil' => $carbon->format('Y-m-d'),
      //   'Codigo_Comp' => $pasta,
      //   'Unidade' => $unidadecodigo,
      //   'origem_cpr' => $id_matrix);
      //  DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values2);

      //  DB::table('PLCFULL.dbo.Jurid_Default_')
      //  ->limit(1) 
      //  ->update(array('Numcpr' => $numcpr));

      //Grava na tabela hist informando que a solicitação esta iniciada
      $values = array('id_matrix' => $id_matrix, 'user_id' => $usuarioid, 'status_id' => '23', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 
  
      //Update status 
       DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('equipe_id' => $usuarioid,'valor' => $valor,'status_id'=> '23'));

      } 
      //Se o cliente não tiver saldo, gerar boleto de cobrança
      else {

      $agencia = '8809';
      $conta = '286019';
      $carteira = '109';

      $datavencimento = date('Y-m-d', strtotime('+ 60 days'));


      // //Se o saldo do cliente for > 0
      // if($saldocliente > 0 && $saldocliente < $valor) {

      // $valorrestante = $saldocliente - $valor;

      // $ultimonumprc2 = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
      // $numcpr = $ultimonumprc2 + 1;

      // $values2= array(
      //   'Tipodoc' => 'TRANSF', 
      //   'Numdoc' => $numcpr,
      //   'Cliente' => '004',
      //   'Tipo' => 'P',
      //   'TipodocOR' => 'TRANSF',
      //   'NumDocOr' => $numcpr,
      //   'Tipolan' => '16.09',
      //   'Valor' => $valorrestante,
      //   'Centro' => $setor,
      //   'Dt_baixa' => $carbon->format('Y-m-d'),
      //   'Portador' => $codigoportador,
      //   'Obs' => 'Uso do saldo atual do cliente para pagamento da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal. ' . $observacao,
      //   'Juros' => '0,00',
      //   'Dt_Compet' => $carbon->format('Y-m-d'),
      //   'DT_Concil' => $carbon->format('Y-m-d'),
      //   'Codigo_Comp' => $pasta,
      //   'Unidade' => $unidadecodigo,
      //   'origem_cpr' => $id_matrix);
      // DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values2);

      // DB::table('PLCFULL.dbo.Jurid_Default_')
      // ->limit(1) 
      // ->update(array('Numcpr' => $numcpr));

      // }

      $verifica_boletogerado = DB::table('PLCFULL.dbo.Jurid_Boleto')
      ->where('PLCFULL.dbo.Jurid_Boleto.codigo_cliente', $clientecodigo)
      ->where('PLCFULL.dbo.Jurid_Boleto.situacao', '=','0')
      ->whereDate('PLCFULL.dbo.Jurid_Boleto.data_emissao', $datahoje)
      ->count();

      //Grava na tabela hist informando que a solicitação esta aguardando pagamento boleto
      $values = array('id_matrix' => $id_matrix, 'user_id' => $usuarioid, 'status_id' => '4', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 
        
      //Update status 
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('equipe_id' => $usuarioid,'valor' => $valor,'status_id'=> '21'));

      $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
      $numdoc = $ultimonumprc + 1;
      $cliente_id = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo', '=', $clientecodigo)->value('id_cliente');
      $vencimentocliente = DB::table('dbo.PesquisaPatrimonial_Clientes')->select('diasvencimento')->where('id_referencia', '=', $cliente_id)->value('diasvencimento');

      DB::table('PLCFULL.dbo.Jurid_Default_')
      ->limit(1) 
      ->update(array('Numcpr' => $numdoc));

      //Cria a CPR
      $values= array(
        'Tipodoc' => 'BOL',
        'Numdoc' => $numdoc,
        'Cliente' => $clientecodigo,
        'Tipo' => 'R',
        'Centro' => $setor,
        'Valor' => $valor,
        'Dt_aceite' => $carbon->format('Y-m-d'),
        'Dt_Vencim' => $datavencimento,
        'Dt_Progr' => $carbon->format('Y-m-d'),
        'Multa' => '0',
        'Juros' => '0',
        'Tipolan' => '16.04',
        'Desconto' => '0',
        'Baixado' => '0',
        'Portador' => $clientecodigo,
        'Status' => '0',
        'Historico' => 'Solicitação Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal utilizando o saldo atual do cliente.' . $observacao,
        'Obs' => $observacao,
        'Valor_Or' => $valor,
        'Dt_Digit' => $carbon->format('Y-m-d'),
        'Codigo_Comp' => $pasta,
        'Unidade' => $unidadecodigo,
        'Moeda' => 'R$',
        'CSLL' => '0.00',
        'COFINS' => '0.00',
        'PIS' => '0.00',
        'ISS' => '0.00',
        'INSS' => '0.00',
        'Contrato' => $contrato,
        'Origem_cpr' => $id_matrix,
        'numprocesso' => $numeroprocesso,
        'cod_pasta' => $pasta);
      DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);


      //Se existir boleto já gerado para este cliente na data de hoje 
      if($verifica_boletogerado != 0) {

        $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')
        ->select('codigo_boleto')
        ->where('PLCFULL.dbo.Jurid_Boleto.codigo_cliente', $clientecodigo)
        ->where('PLCFULL.dbo.Jurid_Boleto.situacao', '=','0')
        ->where('PLCFULL.dbo.Jurid_Boleto.usuario', '=', 'portal.plc')
        ->whereDate('PLCFULL.dbo.Jurid_Boleto.data_emissao', $datahoje)
        ->value('codigo_boleto');

        $numdocantigo = DB::table('PLCFULL.dbo.Jurid_Boleto')
        ->select('PLCFULL.dbo.Jurid_Boleto.num_doc')
        ->where('PLCFULL.dbo.Jurid_Boleto.codigo_boleto', $codigoboleto)
        ->where('PLCFULL.dbo.Jurid_Boleto.usuario', '=', 'portal.plc')
        ->value('PLCFULL.dbo.Jurid_Boleto.num_doc');

        //Update Matrix
        DB::table('dbo.PesquisaPatrimonial_Matrix')
        ->where('id', $id_matrix)  
        ->limit(1) 
        ->update(array('codigo_boleto'=> $codigoboleto));

        $valorboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')
        ->select('valor_boleto')
        ->where('codigo_boleto', '=', $codigoboleto)
        ->where('PLCFULL.dbo.Jurid_Boleto.usuario', '=', 'portal.plc')
        ->value(DB::raw('CAST(valor_boleto AS NUMERIC(15,2))'));

        $novonumero = DB::table('PLCFULL.dbo.Jurid_Boleto')
        ->select('nosso_numero')
        ->where('codigo_boleto', '=', $codigoboleto)
        ->where('PLCFULL.dbo.Jurid_Boleto.usuario', '=', 'portal.plc')
        ->value('nosso_numero');

        //Soma o valor do boleto já gerado com a nova solicitação
         $valor = $valorboleto + $valor;

        //Update no boleto existente
        DB::table('PLCFULL.dbo.Jurid_Boleto')
        ->where('codigo_boleto', '=', $codigoboleto)  
        ->where('PLCFULL.dbo.Jurid_Boleto.usuario', '=', 'portal.plc')
        ->limit(1) 
        ->update(array('valor_boleto' => $valor, 'valor_liquido' => $valor));

        //Update Jurid_ContaPR
        DB::table('PLCFULL.dbo.Jurid_ContaPr')
        ->where('Origem_cpr', $id_matrix)  
        // ->where('Numdoc', $numdoc)
        ->limit(1) 
        ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));

        //Insert na Jurid_Boleto_Assoc
        $values = array(
            'codigo_boleto' => $codigoboleto,
            'codigo_cpr' => $numdoc);
        DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 

        //Dados do beneficiario
        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
        $beneficiario->setDocumento($unidadecnpj)
                     ->setNome($unidaderazao)
                     ->setCep($unidadecep)
                     ->setEndereco($unidadeendereco)
                     ->setBairro($unidadebairro)
                     ->setUf($unidadeuf)
                     ->setCidade($unidadecidade);
          
        //Dados do pagador
        $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
        $pagador->setDocumento($clientecodigo)
                ->setNome($cliente)
                ->setCep($clientecep)
                ->setEndereco($clienteendereco)
                ->setBairro($clientebairro)
                ->setUf($clienteuf)
                ->setCidade($clientecidade);   

          
        //Gera o boleto
        $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
          [
                  'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
                  'dataVencimento'         => Carbon::now()->addDay(60),
                  'valor'                  => $valor,
                  'multa'                  => false,
                  'juros'                  => false,
                  'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
                  'numero'                 => $novonumero,
                  'numeroDocumento'        => $numdoc,
                  'pagador'                => $pagador,
                  'beneficiario'           => $beneficiario,
                  'diasBaixaAutomatica'    => 0,
                  'carteira'               => $carteira,
                  'agencia'                => $agencia,
                  'conta'                  => '28601',
                  'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
                  'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
                  'aceite'                 => 'N',
                  'especieDoc'             => 'DS',
                  ]
            );   
        
        //Deleta o boleto atual
        // unlink(storage_path('app/public/boletos/'.$numdocantigo.'.pdf'));
        // unlink(storage_path('app/public/boletos/'.$numdocantigo.'notadebito.pdf'));

        //Montar arquivo PDF e salvar 
        $datas = DB::table("dbo.PesquisaPatrimonial_Matrix")
        ->select('dbo.PesquisaPatrimonial_Matrix.id',
                 'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
                 'dbo.PesquisaPatrimonial_Matrix.nome as PesquisadoNome',
                 'dbo.users.name as SolicitanteNome',
                 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                 'dbo.PesquisaPatrimonial_Matrix.data as Data',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                 'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
                 'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico')
        ->join('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', 'PLCFULL.dbo.Jurid_ContaPr.Origem_Cpr')
        ->join('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
        ->join('dbo.users', 'dbo.PesquisaPatrimonial_Matrix.solicitante_id', 'dbo.users.id')
        ->join('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
        ->join('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', 'dbo.PesquisaPatrimonial_Servicos.id')
        ->where('PLCFULL.dbo.Jurid_CliFor.Codigo', $clientecodigo)
        ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(21,22))
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo_boleto', $codigoboleto)
        ->get();

        $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
        $pdf->addBoleto($boleto);
        $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$numdocantigo.'.pdf');

        $extenso = new NumeroPorExtenso;
        $extenso = $extenso->converter($valor);

        $datavencimento_string = Carbon::now()->addDay($vencimentocliente);

        $notadebitopdf = \PDF::loadView('Painel.PesquisaPatrimonial.Nucleo.notadebito', compact('numdoc','carbon', 'cliente', 'clientecodigo','valor','extenso','datas', 'datavencimento_string'));
        $content = $notadebitopdf->download()->getOriginalContent();
        Storage::disk('pesquisapatrimonial-boleto')->put('notadebito/'.$numdocantigo.'notadebito.pdf',$notadebitopdf->output());

      }
      //Se não existir boleto gerado para este cliente na data de hoje
      else {

        $nossonumero = DB::table('PLCFULL.dbo.Jurid_Banco')->select('NossoNumeroGer')->where('Codigo', '=', '004')->value('NossoNumeroGer');
        $novonumero = '00000'.$nossonumero + 1;
  
        DB::table('PLCFULL.dbo.Jurid_Banco')
        ->where('Codigo', '=', '004')  
        ->limit(1) 
        ->update(array('NossoNumeroGer' => $novonumero));

        $values = array(
          'codigo_conta_bancaria' => '004',
          'nosso_numero' => $novonumero,
          'especie_doc' => 'DS',
          'especie_moeda' => 'R$',
          'aceite' => 'NAO',
          'carteira' => $carteira,
          'num_doc' => $numdoc,
          'valor_boleto' => $valor,
          'valor_juros' => '0.00',
          'valor_multa' => '0.00',
          'valor_abatimento' => '0.00',
          'valor_desconto' => '0.00',
          'valor_liquido' => $valor,
          'data_emissao' => $carbon,
          'data_vencimento' => date('Y-m-d', strtotime('+ 60 days')),
          'codigo_cliente' => $clientecodigo,
          'nome_sacado' => $cliente,
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
          'unidade' => $unidadecodigo);
        DB::table('PLCFULL.dbo.Jurid_Boleto')->insert($values);  

        $codigoboleto = 
        DB::table('PLCFULL.dbo.Jurid_Boleto')
        ->where('num_doc', $numdoc)
        ->where('PLCFULL.dbo.Jurid_Boleto.usuario', '=', 'portal.plc')
        ->orderBy('codigo_boleto', 'desc')
        ->value('codigo_boleto');
  
        //Update Matrix
        DB::table('dbo.PesquisaPatrimonial_Matrix')
        ->where('id', $id_matrix)  
        ->limit(1) 
        ->update(array('codigo_boleto'=> $codigoboleto));

        //Update Jurid_ContaPr
        DB::table('PLCFULL.dbo.Jurid_ContaPr')
        ->where('Origem_cpr', $id_matrix)  
        // ->where('Numdoc', $numdoc)
        ->limit(1) 
        ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));

        //Insert na Jurid_Boleto_Assoc
        $values = array(
          'codigo_boleto' => $codigoboleto,
          'codigo_cpr' => $numdoc);
        DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 

        //Dados do beneficiario
        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
        $beneficiario->setDocumento($unidadecnpj)
                     ->setNome($unidaderazao)
                     ->setCep($unidadecep)
                     ->setEndereco($unidadeendereco)
                     ->setBairro($unidadebairro)
                     ->setUf($unidadeuf)
                     ->setCidade($unidadecidade);
          
        //Dados do pagador
        $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
        $pagador->setDocumento($clientecodigo)
                ->setNome($cliente)
                ->setCep($clientecep)
                ->setEndereco($clienteendereco)
                ->setBairro($clientebairro)
                ->setUf($clienteuf)
                ->setCidade($clientecidade);   
          
        //Gera o boleto
        $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
                  [
                  'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
                  'dataVencimento'         => Carbon::now()->addDay(60),
                  'valor'                  => $valor,
                  'multa'                  => false,
                  'juros'                  => false,
                  'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
                  'numero'                 => $novonumero,
                  'numeroDocumento'        => $numdoc,
                  'pagador'                => $pagador,
                  'beneficiario'           => $beneficiario,
                  'diasBaixaAutomatica'    => false,
                  'carteira'               => $carteira,
                  'agencia'                => $agencia,
                  'conta'                  => '28601',
                  'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
                  'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
                  'aceite'                 => 'N',
                  'especieDoc'             => 'DS',
                  ]
              );   
                
        $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
        $pdf->addBoleto($boleto);
        $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$numdoc.'.pdf');

        //Montar arquivo PDF e salvar 
        $datas = DB::table("dbo.PesquisaPatrimonial_Matrix")
        ->select('dbo.PesquisaPatrimonial_Matrix.id',
                 'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
                 'dbo.PesquisaPatrimonial_Matrix.nome as PesquisadoNome',
                 'dbo.users.name as SolicitanteNome',
                 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                 'dbo.PesquisaPatrimonial_Matrix.data as Data',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
                 'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
                 'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico')
        ->join('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', 'PLCFULL.dbo.Jurid_ContaPr.Origem_Cpr')
        ->join('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
        ->join('dbo.users', 'dbo.PesquisaPatrimonial_Matrix.solicitante_id', 'dbo.users.id')
        ->join('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
        ->join('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', 'dbo.PesquisaPatrimonial_Servicos.id')
        ->where('PLCFULL.dbo.Jurid_CliFor.Codigo', $clientecodigo)
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo_boleto', $codigoboleto)
        ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(21,22))
        ->get();

        $extenso = new NumeroPorExtenso;
        $extenso = $extenso->converter($valor);

        $datavencimento_string = Carbon::now()->addDay($vencimentocliente);

        $notadebitopdf = \PDF::loadView('Painel.PesquisaPatrimonial.Nucleo.notadebito', compact('numdoc','carbon', 'cliente', 'clientecodigo','valor','extenso','datas', 'datavencimento_string'));
        $content = $notadebitopdf->download()->getOriginalContent();
        Storage::disk('pesquisapatrimonial-boleto')->put('notadebito/'.$numdoc.'notadebito.pdf',$notadebitopdf->output());

      }
      //Fim 1 boleto para este cliente no dia

      }
      //Fim se o cliente não tiver saldo, gerar boleto de cobrança

      $tipossolicitacao =  $request->get('tipossolicitacao');
      $comarcas = $request->get('comarcas');
      $valor = str_replace (',', '.', str_replace ('.', ',', $request->get('valor')));
      $anexoboleto = $request->file('anexoboleto');
      $assertiva = $request->get('assertiva');
      $possuicorrespondente = $request->get('possuicorrespondente');
      $motivo = $request->get('motivo');
      $correspondente = $request->get('correspondente');
      $valorcorrespondente = str_replace (',', '.', str_replace ('.', ',', $request->get('valorcorrespondente')));
      $observacao = $request->get('informacoesadicionais');
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->where('id_matrix', $id_matrix)->delete();        


      foreach($comarcas as $index => $comarcas) {


            //Verifico se tem Correspondente
            if($possuicorrespondente[$index] == "Sim") {

              $correspondente_cpf = DB::table('dbo.users')->select('dbo.users.cpf')->where('dbo.users.id', '=', $correspondente_id[$index])->value('cpf');
              $correspondente_email = DB::table('dbo.users')->select('dbo.users.email')->where('dbo.users.id', '=', $correspondente_id[$index])->value('email');

            //Envia Notificação para o Correspondente
            $values3= array('data' => $carbon, 'id_ref' => $numdebitecorrespondente, 'user_id' => $usuarioid, 'destino_id' => $correspondente_id[$index], 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Criado um debite para o correspondente referente a pesquisa patrimonial.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values3);

            DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->where('id_matrix', $id_matrix)->delete();        

            $values = array(
              'id_matrix' => $id_matrix, 
              'id_tiposolicitacao' => $tipossolicitacao[$index],
              'solicitante_id' => $solicitante_id,
              'cidade_id' => $comarcas,
              'valor' => $valor[$index],
              'data' => $carbon,
              'correspondente_id' => $correspondente[$index],
              'motivocorrespondente_id' => $motivo[$index],
              'status_id' => '1',
              'assertiva' => $assertiva[$index],
              'observacao' => $observacao[$index]);
              DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);
            } 

            //Se não possuir correspondente
            else {

              $values = array(
                'id_matrix' => $id_matrix, 
                'id_tiposolicitacao' => $tipossolicitacao[$index],
                'solicitante_id' => $solicitante_id,
                'cidade_id' => $comarcas,
                'valor' => $valor[$index],
                'data' => $carbon,
                'status_id' => '1',
                'assertiva' => $assertiva[$index],
                'observacao' => $observacao[$index]);
                DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')->insert($values);
              }
        } 

      //Leva para a View 
      \Session::flash('message', ['msg'=>'Novo boleto de cobrança gerado.', 'class'=>'green']);
      return redirect()->route('Painel.PesquisaPatrimonial.nucleo.boleto.index');


    } 


    return redirect()->route('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoficha');


   }

   public function nucleo_boleto_index() {

    $datas = DB::table('PLCFULL.dbo.Jurid_Boleto')
    ->select('PLCFULL.dbo.Jurid_Boleto.nosso_numero as NossoNumero',
             'PLCFULL.dbo.Jurid_Boleto.codigo_boleto as NumeroDocumento',
             'PLCFULL.dbo.Jurid_Boleto.num_doc as CPR',
             'PLCFULL.dbo.Jurid_Boleto.data_vencimento as DataVencimento',
             'PLCFULL.dbo.Jurid_Boleto.valor_boleto as Valor',
             'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto as Remessa',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao', 
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
             )
    ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Boleto.codigo_cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', '=','dbo.PesquisaPatrimonial_Matrix.codigo_boleto')
    ->leftjoin('PLCFULL.dbo.Jurid_Boleto_Remessa_Lista', 'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', 'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(21))
    ->where('PLCFULL.dbo.Jurid_Boleto.usuario', 'portal.plc')
    ->orderBy('PLCFULL.dbo.Jurid_CliFor.Razao', 'asc')  
    ->groupby('PLCFULL.dbo.Jurid_Boleto.nosso_numero', 
              'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', 
              'PLCFULL.dbo.Jurid_Boleto.num_doc',
              'PLCFULL.dbo.Jurid_Boleto.data_vencimento',
              'PLCFULL.dbo.Jurid_Boleto.valor_boleto',
              'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto',
              'PLCFULL.dbo.Jurid_CliFor.Razao',
              'PLCFULL.dbo.Jurid_CliFor.Codigo')
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

        
    return view('Painel.PesquisaPatrimonial.Nucleo.Boleto.index', compact('datas','totalNotificacaoAbertas', 'notificacoes')); 


   }



   public function nucleo_boleto_programado(Request $request) {


     $carbon= Carbon::now();
     $codigo_boleto = $request->get('codigo_boleto');

     foreach($codigo_boleto as $index => $codigo_boleto) {

     //Programa o boleto para envio no CRON ás 23:00 para o status 22 (Aguardando envio ao cliente)
     DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->limit(1)
     ->where('codigo_boleto', '=', $codigo_boleto)     
     ->update(array('status_id' => '22'));

     

     }

     return redirect()->route('Painel.PesquisaPatrimonial.nucleo.boleto.index');

   }

   public function nucleo_boleto_informacoes($codigo_boleto) {


    //Dados do cliente 
    $datas = DB::table('PLCFULL.dbo.Jurid_Boleto')
    ->select('PLCFULL.dbo.Jurid_Boleto.nosso_numero as NossoNumero',
             'PLCFULL.dbo.Jurid_Boleto.codigo_boleto as NumeroDocumento',
             'PLCFULL.dbo.Jurid_Boleto.num_doc as CPR',
             'PLCFULL.dbo.Jurid_Boleto.data_vencimento as DataVencimento',
             'PLCFULL.dbo.Jurid_Boleto.valor_boleto as Valor',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao', 
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
             'PLCFULL.dbo.Jurid_CliFor.id_cliente as ClienteID',
             'dbo.PesquisaPatrimonial_EmailCliente.email as ClienteEmail',
             )
    ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Boleto.codigo_cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->join('dbo.PesquisaPatrimonial_EmailCliente', 'PLCFULL.dbo.Jurid_CliFor.id_cliente', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->where('PLCFULL.dbo.Jurid_Boleto.codigo_boleto', $codigo_boleto)
    ->first();  


    //Solicitações que estão vinculadas a este boleto
    $solicitacoes = DB::table("dbo.PesquisaPatrimonial_Matrix")
    ->select('dbo.PesquisaPatrimonial_Matrix.id',
             'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
             'dbo.PesquisaPatrimonial_Matrix.nome as PesquisadoNome',
             'dbo.users.name as SolicitanteNome',
             'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
             'dbo.PesquisaPatrimonial_Matrix.data as Data',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
             'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
             'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico')
    ->join('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', 'PLCFULL.dbo.Jurid_ContaPr.Origem_Cpr')
    ->join('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->join('dbo.users', 'dbo.PesquisaPatrimonial_Matrix.solicitante_id', 'dbo.users.id')
    ->join('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->join('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(21,22))
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo_boleto', $codigo_boleto)
    ->get();


    //E-mail do advogado solicitante
    $EmailSolicitante = DB::table("dbo.PesquisaPatrimonial_Matrix")
    ->select('dbo.users.email')
    ->join('dbo.users', 'dbo.PesquisaPatrimonial_Matrix.solicitante_id', 'dbo.users.id')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo_boleto', $codigo_boleto)
    ->value('dbo.users.email');



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


    return view('Painel.PesquisaPatrimonial.Nucleo.Boleto.informacoes', compact('datas','EmailSolicitante','solicitacoes','totalNotificacaoAbertas', 'notificacoes')); 


   }

   public function nucleo_boleto_storeinformacoes(Request $request) {

      $carbon= Carbon::now();
      $clientecodigo = $request->get('clientecodigo');
      $clienteid = $request->get('clienteid');
      $emailcliente = $request->get('emailcliente');
      $emailsolicitante = $request->get('emailsolicitante');

       //Atualiza a tabela de clientes
       DB::table('dbo.PesquisaPatrimonial_EmailCliente')
       ->where('cliente_id', $clienteid)
       ->limit(1) 
       ->update(array('email' => $emailcliente, 'status' => 'A', 'data' => $carbon)); 


       return redirect()->route('Painel.PesquisaPatrimonial.nucleo.boleto.index');


   }

   public function nucleo_boleto_boletosemandamento() {

    $datas = DB::table('PLCFULL.dbo.Jurid_Boleto')
    ->select('PLCFULL.dbo.Jurid_Boleto.nosso_numero as NossoNumero',
             'PLCFULL.dbo.Jurid_Boleto.codigo_boleto as NumeroDocumento',
             'PLCFULL.dbo.Jurid_Boleto.num_doc as CPR',
             'PLCFULL.dbo.Jurid_Boleto.data_vencimento as DataVencimento',
             'PLCFULL.dbo.Jurid_Boleto.data_quitacao as DataBaixa',
             'PLCFULL.dbo.Jurid_Boleto.valor_boleto as Valor',
             'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto as Remessa',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao', 
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
             )
    ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Boleto.codigo_cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', '=','dbo.PesquisaPatrimonial_Matrix.codigo_boleto')
    ->leftjoin('PLCFULL.dbo.Jurid_Boleto_Remessa_Lista', 'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', 'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(22,5))
    ->orderBy('PLCFULL.dbo.Jurid_CliFor.Razao', 'asc')  
    ->groupby('PLCFULL.dbo.Jurid_Boleto.nosso_numero', 
              'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', 
              'PLCFULL.dbo.Jurid_Boleto.num_doc',
              'PLCFULL.dbo.Jurid_Boleto.data_vencimento',
              'PLCFULL.dbo.Jurid_Boleto.data_quitacao',
              'PLCFULL.dbo.Jurid_Boleto.valor_boleto',
              'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto',
              'PLCFULL.dbo.Jurid_CliFor.Razao',
              'PLCFULL.dbo.Jurid_CliFor.Codigo')
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

        
    return view('Painel.PesquisaPatrimonial.Nucleo.Boleto.boletosemandamento', compact('datas','totalNotificacaoAbertas', 'notificacoes')); 


   }

   public function nucleo_boleto_baixarboleto($cpr) {

    $caminho = $cpr.'.pdf';
    return Storage::disk('pesquisapatrimonial-boleto')->download($caminho);

   }

   public function nucleo_boleto_baixarnotadebito($cpr) {

    $caminho = $cpr.'notadebito.pdf';
    return Storage::disk('pesquisapatrimonial-notadebito')->download($caminho);

   }

   public function gerarexcelminhassolicitacoes() {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Matrix.estado_id', '=', 'dbo.PesquisaPatrimonial_Estados.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        'dbo.PesquisaPatrimonial_Estados.uf as Estado',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Servicos.valor AS NUMERIC(15,2)) as Valor'),
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin')
      ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id) 
      ->get()
      ->toArray();

      $customer_array[] = array(
        'Id', 
        'CPF',
        'OutraParte',
        'Status',
        'TipoSolicitacao',
        'TipoServico',
        'GrupoCliente',
        'Pasta',
        'Estado',
        'Valor',
        'DataSolicitacao',);
    foreach($customer_data as $customer)
    {
     $customer_array[] = array(
      'Id'  => $customer->Id,
      'CPF'  => $customer->CPF,
      'OutraParte'  => $customer->OutraParte,
      'Status' => $customer->Status,
      'TipoSolicitacao' => $customer->TipoSolicitacao,
      'TipoServico' => $customer->TipoServico,
      'GrupoCliente' => $customer->GrupoCliente,
      'Pasta' => $customer->Pasta,
      'Estado' => $customer->Estado,
      'Valor' => $customer->Valor,
      'DataSolicitacao'=> date('d/m/Y H:m:s', strtotime($customer->DataSolicitacao)),
     );
    }
    Excel::create('Minhas solicitacoes', function($excel) use ($customer_array){
     $excel->setTitle('Minhas solicitacoes');
     $excel->sheet('Minhas solicitacoes', function($sheet) use ($customer_array){
      $sheet->fromArray($customer_array, null, 'A1', false, false);
     });
    })->download('xlsx');

   }

   public function gerarexceloutraparte($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Cliente',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'Data',
      'Cliente',
      'Status',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'Data'  => date('d/m/Y H:m:s', strtotime($customer->Data)),
    'Cliente'  => $customer->Cliente,
    'Status' => $customer->Status,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte
   );
  }
  Excel::create('OutraParte', function($excel) use ($customer_array){
   $excel->setTitle('OutraParte');
   $excel->sheet('OutraParte', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexcelimovel($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as rua',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
     'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
     DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'), 
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta as carta',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta as datacarta',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
     'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao',
     'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
     'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'matricula',
      'datamatricula',
      'uf',
      'rua',
      'bairro',
      'cidade',
      'tipodescricao',
      'valor',
      'penhora',
      'datarequerimento',
      'averbacao828',
      'carta',
      'datacarta',
      'status',
      'restricao',
      'Codigo',
      'OutraParte',
     );
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'matricula' => $customer->matricula,
    'datamatricula'  => date('d/m/Y H:m:s', strtotime($customer->datamatricula)),
    'uf' => $customer->uf,
    'rua' => $customer->rua,
    'bairro'  => $customer->bairro,
    'cidade' => $customer->cidade,
    'tipodescricao' => $customer->tipodescricao,
    'valor' => $customer->valor,
    'penhora' => $customer->penhora,
    'datarequerimento'=> date('d/m/Y H:m:s', strtotime($customer->datarequerimento)),
    'averbacao828' => $customer->averbacao828,
    'carta' => $customer->carta,
    'datacarta' => date('d/m/Y H:m:s', strtotime($customer->datacarta)),
    'status' => $customer->status,
    'restricao' => $customer->restricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte);
  }
  Excel::create('Imovel', function($excel) use ($customer_array){
   $excel->setTitle('Imovel');
   $excel->sheet('Imovel', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

  
   }

   public function gerarexcelveiculo($id_matrix) {

   }

   public function gerarexcelempresa($id_matrix) {

   }

   public function gerarexcelinfojud($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
     'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao',

     'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
     'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte
   );
  }
  Excel::create('Infojud', function($excel) use ($customer_array){
   $excel->setTitle('Infojud');
   $excel->sheet('Infojud', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexcelbacenjud($id_matrix) {


    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
     'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao',
     'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
     'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte
   );
  }
  Excel::create('Bacenjud', function($excel) use ($customer_array){
   $excel->setTitle('Bacenjud');
   $excel->sheet('Bacenjud', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexcelnota($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
             'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as Id',
             'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
             'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao',
             'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
             'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte
   );
  }
  Excel::create('Notas', function($excel) use ($customer_array){
   $excel->setTitle('Notas');
   $excel->sheet('Notas', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexcelredessocial($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.tipo_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
    ->select(
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao',
      'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as Nome',
      'dbo.PesquisaPatrimonial_RedesSociais_Tipos.descricao as DescricaoRede',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Nome',
      'DescricaoRede',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Nome' => $customer->Nome,
    'DescricaoRede' => $customer->DescricaoRede,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte,
   );
  }
  Excel::create('RedeSocial', function($excel) use ($customer_array){
   $excel->setTitle('RedeSocial');
   $excel->sheet('RedeSocial', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexceltribunal($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
     'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao',
     'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
     'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte
   );
  }
  Excel::create('Tribunal', function($excel) use ($customer_array){
   $excel->setTitle('Tribunal');
   $excel->sheet('Tribunal', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexcelcomercial($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
     'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao',
     'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
     'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte,
   );
  }
  Excel::create('Comercial', function($excel) use ($customer_array){
   $excel->setTitle('Comercial');
   $excel->sheet('Comercial', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexcelpesquisa($id_matrix) {

    $customer_data = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
     'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao',
     'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
     'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'Codigo',
      'OutraParte');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'Codigo' => $customer->Codigo,
    'OutraParte' => $customer->OutraParte
   );
  }
  Excel::create('Pesquisa', function($excel) use ($customer_array){
   $excel->setTitle('Pesquisa');
   $excel->sheet('Pesquisa', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexceldados($id_matrix) {

    //Pega primeiro o CPF/CNPJ do solicitante
    $codigo = DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->select('codigo')  
     ->where('id','=',$id_matrix)
     ->value('codigo'); 
    
    $customer_data = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
    ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
    ->join('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
    ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
            'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
            'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
            'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
    ->where('CPF_CNPJ', '=', $codigo) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Codigo', 
      'NumeroProcesso',
      'NumeroPasta',
      'TipoProjeto');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Codigo'  => $customer->Id,
    'NumeroProcesso'  => $customer->NumeroProcesso,
    'NumeroPasta'  => $customer->NumeroPasta,
    'TipoProjeto' => $customer->TipoProjeto
   );
  }
  Excel::create('Dados', function($excel) use ($customer_array){
   $excel->setTitle('Dados');
   $excel->sheet('Dados', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

   }

   public function gerarexceldiversos($codigo) {

    $customer_data =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
     'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Diversos.data',
     'dbo.PesquisaPatrimonial_Solicitacao_Diversos.descricao as descricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor as valor',
     'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_alienacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_disponivel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'valor',
      'valor_alienacao',
      'valor_disponivel');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'valor' => number_format($customer->valor, 2,',', '.'),
    'valor_alienacao' => number_format($customer->valor_alienacao, 2,',', '.'),
    'valor_disponivel' => number_format($customer->valor_disponivel, 2,',', '.'),

   );
  }
  Excel::create('Diversos', function($excel) use ($customer_array){
   $excel->setTitle('Diversos');
   $excel->sheet('Diversos', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

 

   }

   public function gerarexcelmoeda($codigo) {

    $customer_data =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.data',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.tipo',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.descricao as descricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor as valor',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_alienacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_disponivel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'valor',
      'valor_alienacao',
      'valor_disponivel');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'valor' => number_format($customer->valor, 2,',', '.'),
    'valor_alienacao' => number_format($customer->valor_alienacao, 2,',', '.'),
    'valor_disponivel' => number_format($customer->valor_disponivel, 2,',', '.'),

   );
  }
  Excel::create('Moeda', function($excel) use ($customer_array){
   $excel->setTitle('Moeda');
   $excel->sheet('Moeda', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

 

   }

   public function gerarexceljoias($codigo) {

    $customer_data =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.data',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.tipo',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.descricao as descricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor as valor',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_alienacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_disponivel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->get()
    ->toArray();

    $customer_array[] = array(
      'Id', 
      'data',
      'descricao',
      'valor',
      'valor_alienacao',
      'valor_disponivel');
  foreach($customer_data as $customer)
  {
   $customer_array[] = array(
    'Id'  => $customer->Id,
    'data'  => date('d/m/Y H:m:s', strtotime($customer->data)),
    'descricao'  => $customer->descricao,
    'valor' => number_format($customer->valor, 2,',', '.'),
    'valor_alienacao' => number_format($customer->valor_alienacao, 2,',', '.'),
    'valor_disponivel' => number_format($customer->valor_disponivel, 2,',', '.'),

   );
  }
  Excel::create('Joias', function($excel) use ($customer_array){
   $excel->setTitle('Joias');
   $excel->sheet('Joias', function($sheet) use ($customer_array){
    $sheet->fromArray($customer_array, null, 'A1', false, false);
   });
  })->download('xlsx');

 

   }



  



  public function verificaaba(Request $request) {

   $tiposolicitacao_id = $request->get('aba_id');
   $id_matrix = $request->get('id_matrix');

   $data =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
            ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix', '=', $id_matrix)
            ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', '=', $tiposolicitacao_id) 
            ->get();

   echo($data);

  }



  public function dados($id_matrix) {


    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as Pesquisado',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
      'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
              DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as valor'),
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->get();

    return view('Painel.PesquisaPatrimonial.solicitacao.dados', compact('datas', 'solicitacoes'));

  }



  public function financeiro_index() {

    $carbon= Carbon::now();

    $QuantidadeSolicitacoesAguardandoFichaFinanceira = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '1')->count();
    $QuantidadeSolicitacoesAguardandoPagamentoCliente = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(5,21))->count();
    $QuantidadeSolicitacoesNaoCobravel = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('cobravel', 'Nao')->count();
    $QuantidadeSolicitacoesEmAndamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(4,8))->count();
    $QuantidadeSolicitacoesAguardandoRevisao = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '9')->count();
    $QuantidadeSolicitacoesAguardandoRevisaoFinanceiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '10')->count();
    $QuantidadeSolicitacoesCriadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->count();
    $ValorReceber = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(5,21))->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
    $ValorRecebido = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(4,7,8))->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
    $saldoempresar = DB::table('PLCFULL.dbo.Jurid_ContPrBx')->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', '004')->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
    
    
    $saldoempresap = DB::table('PLCFULL.dbo.Jurid_ContPrBx')->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', '004')->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
  
    $SaldoAtual = $saldoempresar - $saldoempresap;

    $TotalSolicitacoesJaneiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '01')->count();
    $TotalSolicitacoesFevereiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '02')->count();
    $TotalSolicitacoesMarco = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '03')->count();
    $TotalSolicitacoesAbril = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '04')->count();
    $TotalSolicitacoesMaio = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '05')->count();
    $TotalSolicitacoesJunho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '06')->count();
    $TotalSolicitacoesJulho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '07')->count();
    $TotalSolicitacoesAgosto = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '08')->count();
    $TotalSolicitacoesSetembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '09')->count();
    $TotalSolicitacoesOutubro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '10')->count();
    $TotalSolicitacoesNovembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '11')->count();
    $TotalSolicitacoesDezembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '12')->count();

    $usuarios =  DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
    ->select('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
    ->orderBy('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'ASC')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'dbo.pesquisaPatrimonial_Matrix.codigo')
    ->groupby('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
    ->get(); 

    $QuantidadeSolicitacoesFinalizadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(15,17))->count();

    $ValorPagar = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
                  ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
                  ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
                  ->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor ) as valor'))
                  ->where('PLCFULL.dbo.Jurid_Debite.numdocpag', '=', NULL)
                  ->where('PLCFULL.dbo.Jurid_Debite.datapag', '=', null)
                  ->where('PLCFULL.dbo.Jurid_Debite.DebPago', '=', 'N')
                  ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '19')
                  ->orWhere('dbo.PesquisaPatrimonial_Matrix.status_id', '20')
                  ->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor)'));

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

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',)
    ->whereIn('dbo.pesquisaPatrimonial_Matrix.status_id', array(3,10,12,13,14,19,20))
    ->get();

    return view('Painel.PesquisaPatrimonial.financeiro.index', compact('usuarios','QuantidadeSolicitacoesAguardandoFichaFinanceira','QuantidadeSolicitacoesAguardandoPagamentoCliente','QuantidadeSolicitacoesNaoCobravel','QuantidadeSolicitacoesEmAndamento','QuantidadeSolicitacoesAguardandoRevisao','QuantidadeSolicitacoesAguardandoRevisaoFinanceiro','QuantidadeSolicitacoesCriadas','ValorReceber','ValorRecebido','SaldoAtual','QuantidadeSolicitacoesFinalizadas','ValorPagar','totalNotificacaoAbertas', 'notificacoes', 'datas', 'TotalSolicitacoesJaneiro', 'TotalSolicitacoesFevereiro', 'TotalSolicitacoesMarco', 'TotalSolicitacoesAbril', 'TotalSolicitacoesMaio', 'TotalSolicitacoesJunho', 'TotalSolicitacoesJulho', 'TotalSolicitacoesAgosto', 'TotalSolicitacoesSetembro', 'TotalSolicitacoesOutubro','TotalSolicitacoesNovembro', 'TotalSolicitacoesDezembro'));

  }

  public function financeiro_solicitacoes() {

      $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
        ->whereIn('dbo.pesquisaPatrimonial_Matrix.status_id', array(3,10,12,13,14,19,20))
        ->orderby('dbo.PesquisaPatrimonial_Matrix.id', 'asc')
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


      return view('Painel.PesquisaPatrimonial.financeiro.solicitacoes', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function financeiro_solicitacoesaguardandoficha() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
    ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '1')
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


    return view('Painel.PesquisaPatrimonial.financeiro.solicitacoesaguardandoficha', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

  }


  public function financeiro_solicitacoesaguardandocliente() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(5,12))
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

    return view('Painel.PesquisaPatrimonial.financeiro.solicitacoesaguardandocliente', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
 
  }

  public function financeiro_solicitacoesaguardandorevisaosupervisor() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(9))
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

    return view('Painel.PesquisaPatrimonial.financeiro.solicitacoesaguardandorevisaosupervisor', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

  } 


  public function financeiro_solicitacoesemandamento() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(4,8,11))
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

    return view('Painel.PesquisaPatrimonial.financeiro.solicitacoesemandamento', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function financeiro_solicitacoesfinalizadas() {

    
    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(15,17,18))
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

    return view('Painel.PesquisaPatrimonial.financeiro.solicitacoesfinalizadas', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


  }


  public function financeiro_ficha($id_matrix) {


    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Matrix.status_id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Matrix.observacaoadiantamento',
      'dbo.PesquisaPatrimonial_Matrix.anexoadiantamento',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento',)
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();
  
    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
              DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Cidades.municipio as Cidade',
             'dbo.users.name as Correspondente',
             'dbo.Jurid_Nota_Tiposervico.descricao as Motivo',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.assertiva',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.anexo as Anexo',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.comprovante as Comprovante',
             'PLCFULL.dbo.Jurid_Debite.datapag as DataPagamento',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.observacao',
             'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
             'PLCFULL.dbo.Jurid_Debite.Hist as Hist',
             'PLCFULL.dbo.Jurid_Debite.DebPago',)
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Cidades', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.cidade_id', '=', 'dbo.PesquisaPatrimonial_Cidades.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.correspondente_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.motivocorrespondente_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor', '!=', '0.00')
    ->wherenull('PLCFULL.dbo.Jurid_Debite.numdocpag')
    ->orWhere('PLCFULL.dbo.Jurid_Debite.numdocpag', '=', '')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor', '!=', '0.00')
    ->get();  

    $historicos = DB::table('dbo.PesquisaPatrimonial_Hist')
    ->select('dbo.users.name as nome', 'dbo.PesquisaPatrimonial_Hist.data', 'dbo.PesquisaPatrimonial_Status.Descricao as status')
    ->leftjoin('dbo.PesquisaPatrimonial_Status','dbo.PesquisaPatrimonial_Hist.status_id','=','dbo.PesquisaPatrimonial_Status.id')
    ->join('dbo.users', 'dbo.PesquisaPatrimonial_Hist.user_id', 'dbo.users.id')
    ->where('dbo.PesquisaPatrimonial_Hist.id_matrix','=',$id_matrix)
    ->get();

    $codigocliente = $datas->CodigoCliente;
    $clienterazao = $datas->ClienteFantasia;

    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;

    $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
    ->where('DaEmpresa', '0')
    ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
    ->where('Status', '1')
    ->orderby('Descricao', 'ASC')
    ->get();   

    $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')
    ->where('Tipo', 'P')
    ->where('Codigo', 'NOT LIKE', '%.')
    ->orderby('Codigo', 'asc')->get();  


    $saldoclienter = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipo', 'R')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
    
    $saldoclientep = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipo', 'P')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

    $saldocliente =  $saldoclienter - $saldoclientep;
  
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

    $motivos = DB::table('dbo.Jurid_Nota_Motivos')
    ->where('ativo','=','S')
    ->get();

    return view('Painel.PesquisaPatrimonial.financeiro.pagamentoboletos', compact('tiposlan','historicos','carbon','datahoje','saldocliente','bancos','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes', 'motivos'));
  

  }

  public function financeiro_storeficha(Request $request) {

    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitanteid');
    $solicitante_cpf = $request->get('solicitantecpf');
    $observacao = $request->get('observacao');
    $clientecodigo = $request->get('clientecodigo');
    $pasta = $request->get('codigopasta');
    $setor = $request->get('setor');
    $unidade = $request->get('unidade');
    $contrato = $request->get('contrato');
    $usuario_id = Auth::user()->id;
    $contrato = $request->get('contrato');
    $primeiroscodigo =  substr($clientecodigo, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;
    $status = $request->get('statusid');
    $carbon= Carbon::now();
    $cobravel = $request->get('cobravel');

    //Grava os anexos dos comprovantes e data de pagamento
    
    $tipossolicitacao = $request->get('tipossolicitacao');
    $comprovante = $request->file('comprovante');
    $datapagamento = $request->get('datapagamentodebite');
    $numerodebite = $request->get('numerodebite');
    $debpago = $request->get('debpago');
    $descricaosolicitacao = $request->get('descricaosolicitacao');
    $hist = $request->get('hist');
    $portador = $request->get('portador');
    $assertiva = $request->get('assertiva');
    $observacao = $request->get('informacoesadicionais');
    $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
    $tipolan = $request->get('tipolan');

    $motivo_id = $request->get('motivoedicao');
    $observacaoedicao = $request->get('observacaoedicao');
    $statuescolhido = $request->get('statusescolhido');

    $fatura = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Numdoc')->where('Origem_Cpr', $id_matrix)->value('Numdoc'); 
    

    //Se a solicitação foi aprovada
    if($statuescolhido == "aprovar") {

         
    $data = array();
    foreach($tipossolicitacao as $index => $tipossolicitacao) {

      //Verifico se ele colocou a data do pagamento e o comprovante
      if($datapagamento[$index] != null) {

        if($assertiva[$index] != "SIM" && !empty($comprovante[$index])) {
        $new_name = 'comprovante' . '_' . $tipossolicitacao . '.'  . $comprovante[$index]->getClientOriginalExtension();
        $comprovante[$index]->storeAs('pesquisapatrimonial', $new_name);
        Storage::disk('reembolso-local')->put($new_name, File::get($comprovante[$index]));

        }
  
        $ultimonumprc2 = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
        $numdoccontaplc = $ultimonumprc2 + 1;
        $numdoccontacliente = $numdoccontaplc + 1;
          
        //Apos Criado na tabela ContaPR, ele vai da update na Tabela_Default_
        DB::table('PLCFULL.dbo.Jurid_Default_')
           ->limit(1) 
           ->update(array('Numcpr' => $numdoccontacliente));

        if($cobravel == "NAO") {

        //Baixa no saldo da PLC  
        $values= array(
          'Tipodoc' => 'FAT', 
          'Numdoc' => $numdoccontaplc,
          'Cliente' => $descricao,
          'Tipo' => 'P',
          'TipodocOR' => 'FAT',
          'NumDocOr' => $numerodebite[$index],
          'Tipolan' => $tipolan,
          'Valor' => $valor[$index],
          'Centro' => $setor,
          'Dt_baixa' => $datapagamento[$index],
          'Portador' => $portador[$index],
          'Obs' => 'Pagamento de boleto do debite: ' . $numerodebite[$index] .' da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal não cobrável ao cliente para o tipo de serviço:   ' . $observacao[$index],
          'Juros' => '0,00',
          'Dt_Compet' => $datapagamento[$index],
          'DT_Concil' => $datapagamento[$index],
          'Codigo_Comp' => $pasta,
          'Unidade' => $unidade,
          'Contrato' => $contrato,
          'origem_cpr' => $id_matrix);
          DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

        
          //Update Debite
          DB::table('PLCFULL.dbo.Jurid_Debite')
          ->where('Numero', $numerodebite[$index])  
          ->limit(1) 
          ->update(array('Status' => '2','numdocpag' => $numdoccontaplc ,'Hist' => $hist[$index],'DebPago'=> 'S','datapag' => $datapagamento[$index],'Banco' => $portador[$index] ,'data_vencimento' => $datapagamento[$index]));

        } else {

          //Se o cliente já pagou ele vai baixar na conta da PLC e na conta do cliente
          if($status == 19) {

          //Baixa no saldo da PLC  
          $values= array(
            'Tipodoc' => 'FAT', 
            'Numdoc' => $numdoccontaplc,
            'Cliente' => $descricao,
            'Tipo' => 'P',
            'TipodocOR' => 'FAT',
            'NumDocOr' => $numerodebite[$index],
            'Tipolan' => $tipolan,
            'Valor' => $valor[$index],
            'Centro' => $setor,
            'Dt_baixa' => $datapagamento[$index],
            'Portador' => $portador[$index],
            'Obs' => 'Pagamento de boleto do debite: ' . $numerodebite[$index] .' da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao[$index],
            'Juros' => '0,00',
            'Dt_Compet' => $datapagamento[$index],
            'DT_Concil' => $datapagamento[$index],
            'Codigo_Comp' => $pasta,
            'Unidade' => $unidade,
            'Contrato' => $contrato,
            'origem_cpr' => $id_matrix);
           DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

          //Baixa no saldo do cliente  
          $values= array(
            'Tipodoc' => 'FAT', 
            'Numdoc' => $numdoccontaplc,
            'Cliente' => $descricao,
            'Tipo' => 'P',
            'TipodocOR' => 'FAT',
            'NumDocOr' => $numerodebite[$index],
            'Tipolan' => $tipolan,
            'Valor' => $valor[$index],
            'Centro' => $setor,
            'Dt_baixa' => $datapagamento[$index],
            'Portador' => $descricao,
            'Obs' => 'Pagamento de boleto do debite: ' . $numerodebite[$index] .' da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao[$index],
            'Juros' => '0,00',
            'Dt_Compet' => $datapagamento[$index],
            'DT_Concil' => $datapagamento[$index],
            'Codigo_Comp' => $pasta,
            'Unidade' => $unidade,
            'Contrato' => $contrato,
            'origem_cpr' => $id_matrix);
           DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

          //Update Debite
          DB::table('PLCFULL.dbo.Jurid_Debite')
          ->where('Numero', $numerodebite[$index])  
          ->limit(1) 
          ->update(array('Status' => '1','Fatura' => $fatura,'numdocpag' => $numdoccontaplc ,'Hist' => $hist[$index],'DebPago'=> 'S','datapag' => $datapagamento[$index],'Banco' => $portador[$index] ,'data_vencimento' => $datapagamento[$index]));

          }
          else {

          //Se foi uma solicitação de adiantamento vai baixar apenas na conta do plc 16.01
          $values= array(
            'Tipodoc' => 'FAT', 
            'Numdoc' => $numdoccontaplc,
            'Cliente' => $descricao,
            'Tipo' => 'P',
            'TipodocOR' => 'FAT',
            'NumDocOr' => $numerodebite[$index],
            'Tipolan' => $tipolan,
            'Valor' => $valor[$index],
            'Centro' => $setor,
            'Dt_baixa' => $datapagamento[$index],
            'Portador' => $portador[$index],
            'Obs' => 'Pagamento de boleto do debite: ' . $numerodebite[$index] .' da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao[$index],
            'Juros' => '0,00',
            'Dt_Compet' => $datapagamento[$index],
            'DT_Concil' => $datapagamento[$index],
            'Codigo_Comp' => $pasta,
            'Unidade' => $unidade,
            'Contrato' => $contrato,
            'origem_cpr' => $id_matrix);
           DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

          //Update Debite
          DB::table('PLCFULL.dbo.Jurid_Debite')
          ->where('Numero', $numerodebite[$index])  
          ->limit(1) 
          ->update(array('Status' => '0','numdocpag' => $numdoccontaplc ,'Hist' => $hist[$index],'DebPago'=> 'S','datapag' => $datapagamento[$index],'Banco' => $portador[$index] ,'data_vencimento' => $datapagamento[$index]));

          }

       
        }
       
  
        if($assertiva[$index] != "SIM" && !empty($comprovante[$index])) {

        //Grava no GED o comprovante
        $values = array(
        'Tabela_OR' => 'Debite',
        'Codigo_OR' => $numerodebite[$index],
        'Id_OR' => $numerodebite[$index],
        'Descricao' => $comprovante[$index]->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\pesquisapatrimonial/'.$new_name, 
        'Data' => $carbon,
        'Nome' => $comprovante[$index]->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $comprovante[$index]->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $new_name,
        'Obs' => 'Solicitação de pesquisa patrimonial, referente ao comprovante do pagamento do tipo de serviço: '. $descricaosolicitacao[$index],
        'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);
        
        }

      
        if($assertiva[$index] != "SIM" && !empty($comprovante[$index])) {
        
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
        ->where('id', $tipossolicitacao)
        ->limit(1) 
        ->update(array('status_id' => '2','comprovante' => $new_name, 'observacao' => $observacao[$index]));
        } else {
          DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
          ->where('id', $tipossolicitacao)
          ->limit(1) 
          ->update(array('status_id' => '2','observacao' => $observacao[$index]));
  
        }

      }

      

    }
    //Fim Foreach


        //Se for solicitação de adiantamento poe status 20
        if($status == 20) {
          DB::table('dbo.PesquisaPatrimonial_Matrix')
          ->where('id', $id_matrix)
          ->limit(1) 
          ->update(array('status_id' => '11'));

          //Update Hist
          $values = array(
          'id_matrix' => $id_matrix,
          'user_id' => Auth::user()->id, 
          'status_id' => '11', 
          'data' => $carbon);
          DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 
          

        } 
        //Se for solicitação do tipo 19 poe status 4
        elseif($status == 19) {
          DB::table('dbo.PesquisaPatrimonial_Matrix')
          ->where('id', $id_matrix)
          ->limit(1) 
          ->update(array('status_id' => '4'));

          //Update Hist
          $values = array(
         'id_matrix' => $id_matrix,
         'user_id' => Auth::user()->id, 
         'status_id' => '4', 
         'data' => $carbon);
          DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 


        }
      
        //Envia notificação para a Vanessa
        $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '284', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: A equipe do financeiro realizou o pagamentos da solicitação: ' . $id_matrix ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        //Envia notificação para o Felipe
        $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '885', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: A equipe do financeiro realizou o pagamentos da solicitação: ' . $id_matrix ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        Mail::to('vanessa.ferreira@plcadvogados.com.br')
        ->cc('felipe.rocha@especialistaresultados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br')
        ->send(new SolicitacaoBaixadaFinanceiro($id_matrix, $carbon));

    } else {

      //Update Matrix
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)
      ->limit(1) 
      ->update(array('status_id' => '24', 'motivo_id' => $motivo_id, 'observacaoedicao' => $observacaoedicao));

      //Update Hist
      $values = array(
     'id_matrix' => $id_matrix,
     'user_id' => Auth::user()->id, 
     'status_id' => '25', 
     'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

      //Envia notificação para a Vanessa
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '284', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: A equipe do financeiro reprovou a solicitação: ' . $id_matrix ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Envia notificação para o Felipe
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '885', 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: A equipe do financeiro reprovou a solicitação: ' . $id_matrix ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      $solicitanteemail = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email');
      $motivodescricao = DB::table('dbo.Jurid_Nota_Motivos')->select('descricao')->where('id', $motivo_id)->value('descricao');

      Mail::to('vanessa.ferreira@plcadvogados.com.br')
      ->cc($solicitanteemail,'felipe.rocha@especialistaresultados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br')
      ->send(new SolicitacaoReprovadaFinanceiro($id_matrix, $motivodescricao, $observacaoedicao));


    }

    return redirect()->route('Painel.PesquisaPatrimonial.financeiro.solicitacoes');


    
  }


  public function financeiro_filtrorelatorio() {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    
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
    

    return view('Painel.PesquisaPatrimonial.financeiro.filtrorelatorio', compact('datahoje','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function financeiro_bancoempresa(Request $request) {

    $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
    ->where('DaEmpresa', '0')
    ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
    ->where('Status', '1')
    ->orderby('Descricao', 'ASC')
    ->get();   


    foreach($bancos as $index) {  
      $response = '<option value="'.$index->Codigo.'">'.$index->Descricao.'</option>';
      echo $response;
    }
  }

  public function financeiro_banconempresa(Request $request) {

    $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
    ->where('DaEmpresa', '0')
    ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
    ->where('Status', '1')
    ->orderby('Descricao', 'ASC')
    ->get();   


    foreach($bancosNEmpresa as $index) {  
      $response = '<option value="'.$index->Codigo.'">'.$index->Descricao.'</option>';
      echo $response;
    }
  }

  public function financeiro_relatorio(Request $request) {


    $adiantamento = $request->get('adiantamento');
    $banco = $request->get('banco');
    $datainicio = $request->get('datainicio');
    $datafim = $request->get('datafim');
  

    $descricaobanco = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Descricao')->where('Codigo', '=', $banco)->value('Descricao'); 

    //Verifica se é pra mostrar os lançamentos de adiantamento

    $datas = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select('PLCFULL.dbo.Jurid_ContPrBx.Tipodoc', 
            'PLCFULL.dbo.Jurid_ContPrBx.Numdoc',
            'PLCFULL.dbo.Jurid_ContPrBx.TipodocOr',
            'PLCFULL.dbo.Jurid_CliFor.Razao',
            'PLCFULL.dbo.Jurid_ContPrBx.Dt_baixa',
            'PLCFULL.dbo.Jurid_ContPrBx.Moeda',
            'PLCFULL.dbo.Jurid_ContPrBx.Valor',
            'PLCFULL.dbo.Jurid_ContPrBx.Tipo',
            'PLCFULL.dbo.Jurid_TipoLan.Descricao as TipoLancamento',
            'PLCFULL.dbo.Jurid_ContPrBx.Obs as Observacao')
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_TipoLan', 'PLCFULL.dbo.Jurid_ContPrBx.Tipolan', '=', 'PLCFULL.dbo.Jurid_TipoLan.Codigo')
    ->where('PLCFULL.dbo.Jurid_ContPrBx.Portador','=',$banco)
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
    

    return view('Painel.PesquisaPatrimonial.financeiro.relatorio', compact('datas','descricaobanco', 'datainicio', 'datafim', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function supervisao_index() {

    $QuantidadeSolicitacoesAguardandoFichaFinanceira = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '1')->count();
    $QuantidadeSolicitacoesAguardandoPagamentoCliente = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(5,21))->count();
    $QuantidadeSolicitacoesNaoCobravel = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('cobravel', 'Nao')->count();
    $QuantidadeSolicitacoesEmAndamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(4,8))->count();
    $QuantidadeSolicitacoesAguardandoRevisao = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '9')->count();
    $QuantidadeSolicitacoesAguardandoRevisaoFinanceiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '10')->count();
    $QuantidadeSolicitacoesCriadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->count();
    $ValorReceber = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(5,21))->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
    $ValorRecebido = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(4,7,8))->where('Cobravel', 'SIM')->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
    $QuantidadeSolicitacoesFinalizadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(15,17))->count();

    $TotalSolicitacoesJaneiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '01')->count();
    $TotalSolicitacoesFevereiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '02')->count();
    $TotalSolicitacoesMarco = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '03')->count();
    $TotalSolicitacoesAbril = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '04')->count();
    $TotalSolicitacoesMaio = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '05')->count();
    $TotalSolicitacoesJunho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '06')->count();
    $TotalSolicitacoesJulho = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '07')->count();
    $TotalSolicitacoesAgosto = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '08')->count();
    $TotalSolicitacoesSetembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '09')->count();
    $TotalSolicitacoesOutubro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '10')->count();
    $TotalSolicitacoesNovembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '11')->count();
    $TotalSolicitacoesDezembro = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereMonth('data', '12')->count();

    $usuarios =  DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
    ->select('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
    ->orderBy('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', 'ASC')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', 'dbo.pesquisaPatrimonial_Matrix.codigo')
    ->groupby('PLCFULL.dbo.Jurid_Outra_Parte.I_Cod', 'PLCFULL.dbo.Jurid_Outra_Parte.Descricao')
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

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as Solicitante',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',)
    ->get();

    return view('Painel.PesquisaPatrimonial.supervisao.index', compact('usuarios','QuantidadeSolicitacoesAguardandoFichaFinanceira','QuantidadeSolicitacoesAguardandoPagamentoCliente','QuantidadeSolicitacoesEmAndamento','QuantidadeSolicitacoesNaoCobravel','QuantidadeSolicitacoesAguardandoRevisao','QuantidadeSolicitacoesAguardandoRevisaoFinanceiro','QuantidadeSolicitacoesCriadas','ValorReceber','ValorRecebido','QuantidadeSolicitacoesFinalizadas','totalNotificacaoAbertas', 'notificacoes', 'datas', 'TotalSolicitacoesJaneiro', 'TotalSolicitacoesFevereiro', 'TotalSolicitacoesMarco', 'TotalSolicitacoesAbril', 'TotalSolicitacoesMaio', 'TotalSolicitacoesJunho', 'TotalSolicitacoesJulho', 'TotalSolicitacoesAgosto', 'TotalSolicitacoesSetembro', 'TotalSolicitacoesOutubro','TotalSolicitacoesNovembro', 'TotalSolicitacoesDezembro'));

  }

  public function supervisao_solicitacoes() {


    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
      ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
      ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
      ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as Id',
        'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
        'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
        'dbo.users.name as Solicitante',
        'dbo.PesquisaPatrimonial_Status.id as StatusID',
        'dbo.PesquisaPatrimonial_Status.descricao as Status',
        'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
        'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
        'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
        'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
        'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
        'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
        'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
        'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
        'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
        'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
        'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
        'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
      ->where('status_id', '9')
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

    return view('Painel.PesquisaPatrimonial.supervisao.novosolicitacoes', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function supervisao_avaliar($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Classificacao',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Pastas.PRConta',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'dbo.PesquisaPatrimonial_Matrix.observacaoadiantamento as ObservacaoAdiantamento',
      'dbo.PesquisaPatrimonial_Matrix.anexoadiantamento as AnexoAdiantamento',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
      'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
      'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
      'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Razao as UnidadeRazao',
      'PLCFULL.dbo.Jurid_Unidade.CNPJ as UnidadeCNPJ',
      'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
      'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
      'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
      'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
      'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
      'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

     $codigocliente = $datas->CodigoCliente;
     $diasvencimento = $datas->DiasVencimento;
     $clienterazao = $datas->ClienteFantasia;
  
     $datavencimento = date('Y-m-d', strtotime('+ 60 days'));

     $razaoeditado = substr($clienterazao, 0, 50);
     $primeiroscodigo =  substr($codigocliente, 0, 5);  
     $descricao = "PESQUISA" . $primeiroscodigo;

  
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


    return view('Painel.PesquisaPatrimonial.supervisao.revisar', compact('carbon','datas','totalNotificacaoAbertas', 'notificacoes', 'datavencimento'));
  }

  public function supervisao_avaliada(Request $request) {


    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitanteid');
    $solicitante_cpf = $request->get('solicitantecpf');
    $pasta = $request->get('codigopasta');
    $setor = $request->get('setor');
    $carbon= Carbon::now();
    $clientecodigo = $request->get('clientecodigo');
    $usuarioid = Auth::user()->id;
    $valorsemformato = $request->get('valortotal');
    $valor =  str_replace (',', '.', str_replace ('.', ',', $valorsemformato));
    $observacao = $request->get('observacao');
    $contrato = $request->get('contrato');
    $numeroprocesso = $request->get('numeroprocesso');
    $observacaoadiantamento = $request->get('observacaoadiantamento');

    $adiantamento = $request->get('statusescolhido');


    //Se foi aprovado a solicitação e enviar ao Financeiro
    if($adiantamento == "aprovarsolicitacao") {

    //Update Matrix
    DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id'=> '10', 'observacaoadiantamento' => $observacaoadiantamento));

    //Update Hist
    $values = array(
      'id_matrix' => $id_matrix,
      'user_id' => $usuarioid, 
      'status_id' => '10', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

    //Envia Notificação Advogado Solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Supervisor da pesquisa patrimonial aprovou o adiantamento da solicitação.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia Notificação para o Financeiro
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '235', 'tipo' => '2', 'obs' => 'Supervisor da pesquisa patrimonial aprovou o adiantamento da solicitação.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia Email Financeiro
    Mail::to('daniele.oliveira@plcadvogados.com.br')
    ->cc('douglas.figueiredo@plcadvogados.com.br', 'roberta.povoa@plcadvogados.com.br')
    ->send(new RevisarSolicitacaoFinanceiro($id_matrix));

    flash('Solicitação de adiantamento enviada ao financeiro!')->success();   
  
    return redirect()->route('Painel.PesquisaPatrimonial.supervisao.solicitacoes');

    }
    //Se não foi aprovado, deve gerar o boleto e encaminhar ao Cliente seguindo o fluxo
    else {

    //Update Matrix
     DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id'=> '21', 'observacaoadiantamento' => $observacaoadiantamento));

    //Update Hist
    $values = array(
      'id_matrix' => $id_matrix,
      'user_id' => $solicitante_id, 
      'status_id' => '21', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Matrix')->insert($values); 
      
      $unidaderazao = $request->get('unidaderazao');
      $unidadecnpj = $request->get('unidadecnpj');
      $unidadeendereco = $request->get('unidadeendereco');
      $unidadebairro = $request->get('unidadebairro');
      $unidadecidade = $request->get('unidadecidade');
      $unidadeuf = $request->get('unidadeuf');
      $unidadecep = $request->get('unidadecep');
      $unidadetelefone = $request->get('unidadetelefone');
      $unidade = $request->get('unidade');
  
      $clienterazao = $request->get('clienterazao');
      $clientecep = $request->get('clientecep');
      $clienteendereco = $request->get('clienteendereco');
      $clientebairro = $request->get('clientebairro');
      $clienteuf = $request->get('clienteuf');
      $clientecidade = $request->get('clientecidade');
  
  
      //Grava na tabela hist informando que a solicitação esta aguardando pagamento boleto
      $values = array('id_matrix' => $id_matrix, 'user_id' => $usuarioid, 'status_id' => '5', 'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

      $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
      $numdoc = $ultimonumprc + 1;
      $cliente_id = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo', '=', $clientecodigo)->value('id_cliente');
      $vencimentocliente = DB::table('dbo.PesquisaPatrimonial_Clientes')->select('diasvencimento')->where('id_referencia', '=', $cliente_id)->value('diasvencimento');

      //Apos Criado na tabela ContaPR, ele vai da update na Tabela_Default_

      DB::table('PLCFULL.dbo.Jurid_Default_')
      ->limit(1) 
      ->update(array('Numcpr' => $numdoc));

      $values= array(
       'Tipodoc' => 'BOL',
       'Numdoc' => $numdoc,
       'Cliente' => $clientecodigo,
       'Tipo' => 'R',
       'Centro' => $setor,
       'Valor' => $valor,
       'Dt_aceite' => $carbon->format('Y-m-d'),
       'Dt_Vencim' => date('Y-m-d', strtotime('+' .$vencimentocliente .' days')),
       'Dt_Progr' => $carbon->format('Y-m-d'),
       'Multa' => '0',
       'Juros' => '0',
       'Tipolan' => '16.04',
       'Desconto' => '0',
       'Baixado' => '0',
       'Portador' => $clientecodigo,
       'Status' => '0',
       'Historico' => 'Solicitação Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao,
       'Obs' => $observacao,
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
       'Origem_cpr' => $id_matrix,
       'numprocesso' => $numeroprocesso,
       'cod_pasta' => $pasta);
     DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);
 

    $agencia = '8809';
    $conta = '286019';
    $carteira = '109';

    $nossonumero = DB::table('PLCFULL.dbo.Jurid_Banco')->select('NossoNumeroGer')->where('Codigo', '=', '004')->value('NossoNumeroGer');
    $novonumero = '00000'.$nossonumero + 1;

    //Verifico se já existe um boleto deste cliente gerado na data de hoje e que seja PesquisaPatrimonial

    $verificacliente = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->where('dbo.PesquisaPatrimonial_Matrix.cliente_id','=', $cliente_id)
    ->whereDay('dbo.PesquisaPatrimonial_Matrix.data', '=', date('d'))
    ->count();


    //Se não existir nenhum  boleto gerado pelo cliente na data de hoje
    if($verificacliente == 1) {
 
      DB::table('PLCFULL.dbo.Jurid_Banco')
      ->where('Conta', '=', $conta)  
      ->limit(1) 
      ->update(array('NossoNumeroGer' => $novonumero));
    
      //Insert na Jurid_Boleto
      $values = array(
          'codigo_conta_bancaria' => '004',
          'nosso_numero' => $novonumero,
          'especie_doc' => 'DS',
          'especie_moeda' => 'R$',
          'aceite' => 'NAO',
          'carteira' => $carteira,
          'num_doc' => $numdoc,
          'valor_boleto' => $valor,
          'valor_juros' => '0.00',
          'valor_multa' => '0.00',
          'valor_abatimento' => '0.00',
          'valor_desconto' => '0.00',
          'valor_liquido' => $valor,
          'data_emissao' => $carbon,
          'data_vencimento' => date('Y-m-d H:i:s', strtotime('+'. $vencimentocliente . ' days')),
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
          'unidade' => $unidade);
        DB::table('PLCFULL.dbo.Jurid_Boleto')->insert($values);  
    
       $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')->orderBy('codigo_boleto', 'desc')->value('codigo_boleto');
 
        //Update Matrix
      DB::table('dbo.PesquisaPatrimonial_Matrix')
     ->where('id', $id_matrix)  
     ->limit(1) 
     ->update(array('codigo_boleto'=> $codigoboleto));

       //Update Jurid_ContaPr
       DB::table('PLCFULL.dbo.Jurid_ContaPr')
       ->where('Origem_cpr', $id_matrix)  
       ->limit(1) 
       ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));
  
        //Insert na Jurid_Boleto_Assoc
        $values = array(
          'codigo_boleto' => $codigoboleto,
          'codigo_cpr' => $numdoc);
        DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 
  
  
        //Dados do beneficiario
        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
        $beneficiario->setDocumento($unidadecnpj)
          ->setNome($unidaderazao)
          ->setCep($unidadecep)
          ->setEndereco($unidadeendereco)
          ->setBairro($unidadebairro)
          ->setUf($unidadeuf)
          ->setCidade($unidadecidade);
  
        //Dados do pagador
        $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
        $pagador->setDocumento($clientecodigo)
          ->setNome($clienterazao)
          ->setCep($clientecep)
          ->setEndereco($clienteendereco)
          ->setBairro($clientebairro)
          ->setUf($clienteuf)
          ->setCidade($clientecidade);   
  
        //Gera o boleto
        $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
              [
            'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
            'dataVencimento'         => Carbon::now()->addDay($vencimentocliente),
            'valor'                  => $valor,
            'multa'                  => false,
            'juros'                  => false,
            'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
            'numero'                  => $novonumero,
            'numeroDocumento'        => $numdoc,
            'pagador'                => $pagador,
            'beneficiario'           => $beneficiario,
            'diasBaixaAutomatica'    => 20,
            'carteira'               => $carteira,
            'agencia'                => $agencia,
            'conta'                  => '28601',
            'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
            'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
            'aceite'                 => 'N',
            'especieDoc'             => 'DS',
            ]
        );   
        
       $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
       $pdf->addBoleto($boleto);
       $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$clientecodigo.'.pdf');
 
 
       flash('Boleto sera enviado ao cliente e solicitante ás 23:00. Você pode esta acompanhando todo o processo pelo Timeline.')->success();   
   
       return redirect()->route('Painel.PesquisaPatrimonial.supervisao.solicitacoes');

    }
    //Agrupar o valor do boleto e gerar um novo arquivo
    else if($verificacliente > 1){

      $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')
                     ->where('codigo_cliente', '=', $clientecodigo)
                     ->whereDay('data_emissao', '=', date('d'))
                     ->value('codigo_boleto');

      $valorboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')
                    ->where('codigo_boleto', '=', $codigoboleto)
                    ->value(DB::raw('CAST(valor_boleto AS NUMERIC(15,2))'));
      $novovalor = $valorboleto + $valor;
      
      DB::table('PLCFULL.dbo.Jurid_Boleto')
      ->where('codigo_boleto', '=', $codigoboleto)  
      ->limit(1) 
      ->update(array('valor_boleto' => $novovalor, 'valor_liquido' => $novovalor));

    
       //Update Jurid_ContaPr
       DB::table('PLCFULL.dbo.Jurid_ContaPr')
       ->where('Origem_cpr', $id_matrix)  
       ->limit(1) 
       ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));
  
        //Insert na Jurid_Boleto_Assoc
        $values = array(
          'codigo_boleto' => $codigoboleto,
          'codigo_cpr' => $numdoc);
        DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 

        //Update Matrix
      DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('codigo_boleto'=> $codigoboleto));
  
  
        //Dados do beneficiario
        $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
        $beneficiario->setDocumento($unidadecnpj)
          ->setNome($unidaderazao)
          ->setCep($unidadecep)
          ->setEndereco($unidadeendereco)
          ->setBairro($unidadebairro)
          ->setUf($unidadeuf)
          ->setCidade($unidadecidade);
  
        //Dados do pagador
        $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
        $pagador->setDocumento($clientecodigo)
          ->setNome($clienterazao)
          ->setCep($clientecep)
          ->setEndereco($clienteendereco)
          ->setBairro($clientebairro)
          ->setUf($clienteuf)
          ->setCidade($clientecidade);   
  
        //Gera o boleto
        $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
              [
            'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
            'dataVencimento'         => Carbon::now()->addDay($vencimentocliente),
            'valor'                  => $novovalor,
            'multa'                  => false,
            'juros'                  => false,
            'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
            'numero'                 => $novonumero,
            'numeroDocumento'        => $numdoc,
            'pagador'                => $pagador,
            'beneficiario'           => $beneficiario,
            'diasBaixaAutomatica'    => '',
            'carteira'               => $carteira,
            'agencia'                => $agencia,
            'conta'                  => '28601',
            'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
            'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
            'aceite'                 => 'N',
            'especieDoc'             => 'DS',
            ]
        );   

      //Deleta o boleto atual
      unlink(storage_path('app/public/boletos/'.$numdoc.'.pdf'));

       $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
       $pdf->addBoleto($boleto);
       $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$numdoc.'.pdf');
 
       flash('Verificamos e já existe um boleto gerado para este cliente na data de hoje. Estaremos juntando o valor e enviando automaticamente ás 23 horas para o cliente e o Advogado solicitante.')->success();   
   
       return redirect()->route('Painel.PesquisaPatrimonial.supervisao.solicitacoes');

    }
    //Fim

    }

  }

  public function financeiro_avaliar($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.anexoadiantamento',
      'dbo.PesquisaPatrimonial_Matrix.observacaoadiantamento',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as Classificacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'dbo.PesquisaPatrimonial_Matrix.observacaoadiantamento as ObservacaoAdiantamento',
      'dbo.PesquisaPatrimonial_Matrix.anexoadiantamento as AnexoAdiantamento',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
      'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
      'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
      'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Razao as UnidadeRazao',
      'PLCFULL.dbo.Jurid_Unidade.CNPJ as UnidadeCNPJ',
      'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
      'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
      'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
      'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
      'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento',
      'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',)
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $codigocliente = $datas->CodigoCliente;
    $diasvencimento = $datas->DiasVencimento;
    $clienterazao = $datas->ClienteFantasia;
 
    $datavencimento = date('Y-m-d', strtotime('+ 60 days'));

    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;

    $historicos = DB::table('dbo.PesquisaPatrimonial_Hist')
    ->select('dbo.users.name as nome', 'dbo.PesquisaPatrimonial_Hist.data', 'dbo.PesquisaPatrimonial_Status.Descricao as status')
    ->leftjoin('dbo.PesquisaPatrimonial_Status','dbo.PesquisaPatrimonial_Hist.status_id','=','dbo.PesquisaPatrimonial_Status.id')
    ->join('dbo.users', 'dbo.PesquisaPatrimonial_Hist.user_id', 'dbo.users.id')
    ->where('dbo.PesquisaPatrimonial_Hist.id_matrix','=',$id_matrix)
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
  
    return view('Painel.PesquisaPatrimonial.financeiro.revisar', compact('carbon','datas','historicos','datavencimento','totalNotificacaoAbertas', 'notificacoes'));
  
  }

  public function financeiro_avaliado(Request $request) {

    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitanteid');
    $solicitante_cpf = $request->get('solicitantecpf');
    $observacao = $request->get('observacao');
    $clientecodigo = $request->get('clientecodigo');
    $pasta = $request->get('codigopasta');
    $setor = $request->get('setor');
    $unidade = $request->get('unidade');
    $valorsemformato = $request->get('valortotal');
    $valor =  str_replace (',', '.', str_replace ('.', ',', $valorsemformato));
    $valornegativo = $valor * -1;
    $contrato = $request->get('contrato');
    $numerodebite = $request->get('numerodebite');
    $usuario_id = Auth::user()->id;
    $contrato = $request->get('contrato');
    $primeiroscodigo =  substr($clientecodigo, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;
    $numeroprocesso = $request->get('numeroprocesso');
    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');
    $observacaoadiantamento = $request->get('observacaoadiantamento');
    $adiantamento = $request->get('statusescolhido');


    if($adiantamento == "aprovarsolicitacao") {


    //Baixa na ContaPr
    // $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
    // $cpr = $ultimonumprc + 1;
    $cliente_id = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo', '=', $clientecodigo)->value('id_cliente');
    $vencimentocliente = DB::table('dbo.PesquisaPatrimonial_Clientes')->select('diasvencimento')->where('id_referencia', '=', $cliente_id)->value('diasvencimento');
  
    // $ultimonumprc2 = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
    // $numdoccontaplc = $ultimonumprc2 + 1;
    // $numdoccontacliente = $numdoccontaplc + 1;
      
    // //Apos Criado na tabela ContaPR, ele vai da update na Tabela_Default_
    // DB::table('PLCFULL.dbo.Jurid_Default_')
    //    ->limit(1) 
    //    ->update(array('Numcpr' => $numdoccontacliente));


    // //Insert na Conta Cliente com saldo negativo
    // $values2= array(
    //   'Tipodoc' => 'TRANSF', 
    //   'Numdoc' => $numdoccontacliente,
    //   'Cliente' => $descricao,
    //   'Tipo' => 'P',
    //   'TipodocOR' => 'TRANSF',
    //   'NumDocOr' => $cpr,
    //   'Tipolan' => '16.01',
    //   'Valor' => $valornegativo,
    //   'Centro' => $setor,
    //   'Dt_baixa' => $datahoje,
    //   'Portador' => $descricao,
    //   'Obs' => 'Adiantamento da solicitação  Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao,
    //   'Juros' => '0,00',
    //   'Dt_Compet' => $datahoje,
    //   'DT_Concil' => $datahoje,
    //   'Codigo_Comp' => $pasta,
    //   'Unidade' => $unidade,
    //   'Ident_Cpr' => $id_matrix,
    //   'origem_cpr' => $id_matrix);
    // DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values2);


    //Update Matrix
    DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id'=> '23'));
 
     //Grava na Hist
     $values = array(
      'id_matrix' => $id_matrix,
      'user_id' => $solicitante_id, 
      'status_id' => '24', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

      //Manda Notificação para o Advogado Solicitante
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuario_id, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'O financeiro aprovou o adiantamento da solicitação.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);
    
      //Manda Notificação para o Supervisor da Pesquisa Patrimonial
      $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuario_id, 'destino_id' => '225', 'tipo' => '2', 'obs' => 'O financeiro aprovou o adiantamento da solicitação.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      // //Manda email para o núcleo Pesquisa Patrimonial com CC Supervisor Pesquisa Patrimonial
      // Mail::to('ronaldo.amaral@plcadvogados.com.br')
      // ->send(new RevisarSolicitacaoFinanceiro($id_matrix));

      flash('Solicitação de adiantamento foi aprovada com sucesso!')->success();   
  
      return redirect()->route('Painel.PesquisaPatrimonial.financeiro.solicitacoes');

     }
     //Solicitação reprovada, vai gerar o boleto e seguir o fluxo normal
     else {

    //Update Matrix
    DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->where('id', $id_matrix)  
    ->limit(1) 
    ->update(array('status_id'=> '21', 'observacaoadiantamento' => $observacaoadiantamento));

    //Update Hist
    $values = array(
     'id_matrix' => $id_matrix,
     'user_id' => $solicitante_id, 
     'status_id' => '21', 
     'data' => $carbon);
     DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 
     
     $unidaderazao = $request->get('unidaderazao');
     $unidadecnpj = $request->get('unidadecnpj');
     $unidadeendereco = $request->get('unidadeendereco');
     $unidadebairro = $request->get('unidadebairro');
     $unidadecidade = $request->get('unidadecidade');
     $unidadeuf = $request->get('unidadeuf');
     $unidadecep = $request->get('unidadecep');
     $unidadetelefone = $request->get('unidadetelefone');
     $unidade = $request->get('unidade');
 
     $clienterazao = $request->get('clienterazao');
     $clientecep = $request->get('clientecep');
     $clienteendereco = $request->get('clienteendereco');
     $clientebairro = $request->get('clientebairro');
     $clienteuf = $request->get('clienteuf');
     $clientecidade = $request->get('clientecidade');
 
 
     //Grava na tabela hist informando que a solicitação esta aguardando pagamento boleto
     $values = array('id_matrix' => $id_matrix, 'user_id' => $usuario_id, 'status_id' => '4', 'data' => $carbon);
     DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

     $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
     $numdoc = $ultimonumprc + 1;
     $cliente_id = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo', '=', $clientecodigo)->value('id_cliente');
     $vencimentocliente = DB::table('dbo.PesquisaPatrimonial_Clientes')->select('diasvencimento')->where('id_referencia', '=', $cliente_id)->value('diasvencimento');

     //Apos Criado na tabela ContaPR, ele vai da update na Tabela_Default_

     DB::table('PLCFULL.dbo.Jurid_Default_')
    ->limit(1) 
    ->update(array('Numcpr' => $numdoc));

     $values= array(
      'Tipodoc' => 'BOL',
      'Numdoc' => $numdoc,
      'Cliente' => $clientecodigo,
      'Tipo' => 'R',
      'Centro' => $setor,
      'Valor' => $valor,
      'Dt_aceite' => $carbon->format('Y-m-d'),
      'Dt_Vencim' => date('Y-m-d', strtotime('+' .$vencimentocliente .' days')),
      'Dt_Progr' => $carbon->format('Y-m-d'),
      'Multa' => '0',
      'Juros' => '0',
      'Tipolan' => '16.04',
      'Desconto' => '0',
      'Baixado' => '1',
      'Portador' => $clientecodigo,
      'Status' => '0',
      'Historico' => 'Solicitação Nª: '. $id_matrix . ' referente a solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao,
      'Obs' => $observacao,
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
      'Origem_cpr' => $id_matrix,
      'numprocesso' => $numeroprocesso,
      'cod_pasta' => $pasta);
    DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);


   $agencia = '8809';
   $conta = '286019';
   $carteira = '109';
 
   $nossonumero = DB::table('PLCFULL.dbo.Jurid_Banco')->select('NossoNumeroGer')->where('Codigo', '=', '004')->value('NossoNumeroGer');
   $novonumero = $nossonumero + 1;

   //Verifico se já existe um boleto deste cliente gerado na data de hoje e que seja PesquisaPatrimonial

   $verificacliente = DB::table('dbo.PesquisaPatrimonial_Matrix')
   ->where('dbo.PesquisaPatrimonial_Matrix.cliente_id','=', $cliente_id)
   ->whereDay('dbo.PesquisaPatrimonial_Matrix.data', '=', date('d'))
   ->count();


   //Se não existir nenhum  boleto gerado pelo cliente na data de hoje
   if($verificacliente == 1) {

     DB::table('PLCFULL.dbo.Jurid_Banco')
     ->where('Conta', '=', $conta)  
     ->limit(1) 
     ->update(array('NossoNumeroGer' => $novonumero));
   
     //Insert na Jurid_Boleto
     $values = array(
         'codigo_conta_bancaria' => '004',
         'nosso_numero' => $novonumero,
         'especie_doc' => 'DS',
         'especie_moeda' => 'R$',
         'aceite' => 'NAO',
         'carteira' => $carteira,
         'num_doc' => $numdoc,
         'valor_boleto' => $valor,
         'valor_juros' => '0.00',
         'valor_multa' => '0.00',
         'valor_abatimento' => '0.00',
         'valor_desconto' => '0.00',
         'valor_liquido' => $valor,
         'data_emissao' => $carbon,
         'data_vencimento' => date('Y-m-d H:i:s', strtotime('+'. $vencimentocliente . ' days')),
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
         'unidade' => $unidade);
       DB::table('PLCFULL.dbo.Jurid_Boleto')->insert($values);  
   
      $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')->orderBy('codigo_boleto', 'desc')->value('codigo_boleto');

      //Update Jurid_ContaPr
      DB::table('PLCFULL.dbo.Jurid_ContaPr')
      ->where('Origem_cpr', $id_matrix)  
      ->limit(1) 
      ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));
 
       //Insert na Jurid_Boleto_Assoc
       $values = array(
         'codigo_boleto' => $codigoboleto,
         'codigo_cpr' => $numdoc);
       DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 
 
 
       //Dados do beneficiario
       $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
       $beneficiario->setDocumento($unidadecnpj)
         ->setNome($unidaderazao)
         ->setCep($unidadecep)
         ->setEndereco($unidadeendereco)
         ->setBairro($unidadebairro)
         ->setUf($unidadeuf)
         ->setCidade($unidadecidade);
 
       //Dados do pagador
       $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
       $pagador->setDocumento($clientecodigo)
         ->setNome($clienterazao)
         ->setCep($clientecep)
         ->setEndereco($clienteendereco)
         ->setBairro($clientebairro)
         ->setUf($clienteuf)
         ->setCidade($clientecidade);   
 
       //Gera o boleto
       $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
             [
           'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
           'dataVencimento'         => Carbon::now()->addDay($vencimentocliente),
           'valor'                  => $valor,
           'multa'                  => false,
           'juros'                  => false,
           'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
           'numero'                 => $nossonumero,
           'numeroDocumento'        => $numdoc,
           'pagador'                => $pagador,
           'beneficiario'           => $beneficiario,
           'diasBaixaAutomatica'    => 0,
           'carteira'               => $carteira,
           'agencia'                => $agencia,
           'conta'                  => '28601',
           'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
           'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
           'aceite'                 => 'N',
           'especieDoc'             => 'DS',
           ]
       );   
       
      $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
      $pdf->addBoleto($boleto);
      $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$numdoc.'.pdf');


      flash('Boleto será enviado ao cliente e solicitante ás 23:00. Você pode esta acompanhando todo o processo pelo Timeline.')->success();   
  
      return redirect()->route('Painel.PesquisaPatrimonial.financeiro.index');

   }
   //Agrupar o valor do boleto e gerar um novo arquivo
   else if($verificacliente > 1){

     $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')
                    ->where('codigo_cliente', '=', $clientecodigo)
                    ->whereDay('data_emissao', '=', date('d'))
                    ->value('codigo_boleto');

     $valorboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')
                   ->where('codigo_boleto', '=', $codigoboleto)
                   ->value(DB::raw('CAST(valor_boleto AS NUMERIC(15,2))'));
     $novovalor = $valorboleto + $valor;
     
     DB::table('PLCFULL.dbo.Jurid_Boleto')
     ->where('codigo_boleto', '=', $codigoboleto)  
     ->limit(1) 
     ->update(array('valor_boleto' => $novovalor, 'valor_liquido' => $novovalor));

   
      //Update Jurid_ContaPr
      DB::table('PLCFULL.dbo.Jurid_ContaPr')
      ->where('Origem_cpr', $id_matrix)  
      ->limit(1) 
      ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));
 
       //Insert na Jurid_Boleto_Assoc
       $values = array(
         'codigo_boleto' => $codigoboleto,
         'codigo_cpr' => $numdoc);
       DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 
 
 
       //Dados do beneficiario
       $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
       $beneficiario->setDocumento($unidadecnpj)
         ->setNome($unidaderazao)
         ->setCep($unidadecep)
         ->setEndereco($unidadeendereco)
         ->setBairro($unidadebairro)
         ->setUf($unidadeuf)
         ->setCidade($unidadecidade);
 
       //Dados do pagador
       $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
       $pagador->setDocumento($clientecodigo)
         ->setNome($clienterazao)
         ->setCep($clientecep)
         ->setEndereco($clienteendereco)
         ->setBairro($clientebairro)
         ->setUf($clienteuf)
         ->setCidade($clientecidade);   
 
       //Gera o boleto
       $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
             [
           'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
           'dataVencimento'         => Carbon::now()->addDay($vencimentocliente),
           'valor'                  => $novovalor,
           'multa'                  => false,
           'juros'                  => false,
           'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
           'numero'                 => $nossonumero,
           'numeroDocumento'        => $numdoc,
           'pagador'                => $pagador,
           'beneficiario'           => $beneficiario,
           'diasBaixaAutomatica'    => '',
           'carteira'               => $carteira,
           'agencia'                => $agencia,
           'conta'                  => $conta,
           'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
           'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
           'aceite'                 => 'N',
           'especieDoc'             => 'DS',
           ]
       );   

     //Deleta o boleto atual
     unlink(storage_path('app/public/boletos/'.$numdoc.'.pdf'));

      $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
      $pdf->addBoleto($boleto);
      $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$numdoc.'.pdf');

      flash('Verificamos e já existe um boleto gerado para este cliente na data de hoje. Estaremos juntando o valor e enviando automaticamente ás 23 horas para o cliente e o Advogado solicitante.')->success();   
  
      return redirect()->route('Painel.PesquisaPatrimonial.financeiro.index');

   }

     }

    
  }

  public function supervisao_clientes_index() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Clientes')
    ->join('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Clientes.id_referencia', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('dbo.PesquisaPatrimonial_Grupos', 'dbo.PesquisaPatrimonial_Clientes.grupo_id', '=', 'dbo.PesquisaPatrimonial_Grupos.id_referencia')
    ->select(
            'dbo.PesquisaPatrimonial_Clientes.id as id',
            'PLCFULL.dbo.Jurid_CliFor.Codigo as Codigo',
            'dbo.PesquisaPatrimonial_Clientes.descricao as Descricao',
            'dbo.PesquisaPatrimonial_Grupos.descricao as Grupo',
            'PLCFULL.dbo.Jurid_CliFor.UF as UF',
            'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento',
            'dbo.PesquisaPatrimonial_Clientes.status as Status')
    ->where('dbo.PesquisaPatrimonial_Clientes.status','=','A')
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
    

    return view('Painel.PesquisaPatrimonial.supervisao.configuracao.clientes.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
   }

   public function supervisao_clientes_create() {

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
    
    $gruposcliente = DB::table('dbo.PesquisaPatrimonial_Grupos')
    ->orderby('descricao', 'asc')
    ->get();

    return view('Painel.PesquisaPatrimonial.supervisao.configuracao.clientes.create', compact('gruposcliente','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function supervisao_clientes_store(Request $request) {

    $grupoclienteselected = $request->get('grupoclienteselected');
    $clienteselected = $request->get('clienteselected');
    $datavencimento = $request->get('datavencimento');

    $grupocliente_id = DB::table('dbo.PesquisaPatrimonial_Grupos')->select('id_referencia')->where('descricao','=',$grupoclienteselected)->value('id_referencia');
    $clienteselected = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Nome')->where('id_cliente','=',$clienteselected)->value('Nome');

    $values = array(
      'descricao' => $clienteselected, 
      'grupo_id' => $grupocliente_id, 
      'id_referencia' => $clienteselected, 
      'status' => 'A',
      'diasvencimento' => $datavencimento);
    DB::table('dbo.PesquisaPatrimonial_Clientes')->insert($values);

    
    flash('Cliente adicionado na pesquisa patrimonial!')->success();    

    return redirect()->route('Painel.PesquisaPatrimonial.supervisao.clientes.index');

   }

   public function fetch50(Request $request) {

    $grupocliente_descricao = $request->get('grupo');

    $codigogrupo = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')->select('Codigo')->where('Descricao','=',$grupocliente_descricao)->value('Codigo');

    $clientes = DB::table('PLCFULL.dbo.Jurid_CliFor')
    ->where('GrupoCli', '=', $codigogrupo)
    ->select('Nome','id_cliente')
    ->get();

    foreach($clientes as $index) {  
    $response = '<option selected="selected" value="'.$index->id_cliente.'">'.$index->Nome.'</option>';
    echo $response;
    }

   }

   public function buscapesquisado(Request $request) {

    $nome = str_replace (', ', '', $request->get('tags'));

    $codigo = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')->select('Codigo')->where('PLCFULL.dbo.Jurid_Outra_Parte.Descricao', '=', $nome)->value('Codigo');
  
    $datas = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
    ->where('PLCFULL.dbo.Jurid_Outra_Parte.Descricao','=', $nome)
    ->first();

    $QuantidadePesquisa = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeStatus = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeImovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeVeiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeEmpresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeInfojud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Infojud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Infojud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeBacenjud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeProtestos = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadePesquisaCadastral = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeDossieComercial = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeRedesSociais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    $QuantidadeProcessosJudiciais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();
    // $QuantidadeParticipacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)->count();


    $saldodevedor = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->join('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.NumDocOr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc')
    ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_ContPrBx.Cliente', '=', $codigo)
    ->where('dbo.PesquisaPatrimonial_Matrix.status_id', '=', '5')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->select(
             'dbo.PesquisaPatrimonial_Matrix.id as id',
             'dbo.PesquisaPatrimonial_Matrix.codigo as codigo',
             'dbo.PesquisaPatrimonial_Matrix.data as data',
             'dbo.PesquisaPatrimonial_Status.descricao as status')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo','=', $codigo)
    ->where('dbo.PesquisaPatrimonial_Matrix.solicitante_id', '=', Auth::user()->id)
    ->get();


     //Se não houver nenhuma pesquisa pra este pesquisado o score = 0
     if($QuantidadePesquisa == 0) {

      $porcentagem = 0.00;
      $score = 0.00;
      $somabruto = 0.00;

     } else {

            //Veiculo 
     $veiculo_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor))'));
     $veiculo_soma = number_format((float)$veiculo_soma, 2, '.', '');      

     $veiculo_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc))'));
     $veiculo_avaliacaoplc = number_format((float)$veiculo_avaliacaoplc, 2, '.', '');      
     
     $veiculo_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base))'));
     $veiculo_valorbase = number_format((float)$veiculo_valorbase, 2, '.', '');      
     //Fim Veiculo

     //Imovel
     $imovel_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc))'));
     $imovel_avaliacaoplc = number_format((float)$imovel_avaliacaoplc, 2, '.', ''); 

     $imovel_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor))'));
     $imovel_soma = number_format((float)$imovel_soma, 2, '.', ''); 

     $imovel_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base))'));
     $imovel_valorbase = number_format((float)$imovel_valorbase, 2, '.', ''); 
     //Fim Imovel


     //Empresa
     $empresa_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc))'));
     $empresa_avaliacaoplc = number_format((float)$empresa_avaliacaoplc, 2, '.', ''); 

     $empresa_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial))'));
     $empresa_soma = number_format((float)$empresa_soma, 2, '.', ''); 

     $empresa_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base))'));
     $empresa_valorbase = number_format((float)$empresa_valorbase, 2, '.', ''); 
     //Fim Empresa

     //Diversos
     $diversos_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc))'));
     $diversos_avaliacaoplc = number_format((float)$diversos_avaliacaoplc, 2, '.', ''); 
     
     $diversos_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor))'));
     $diversos_soma = number_format((float)$diversos_soma, 2, '.', ''); 

     $diversos_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base))'));
     $diversos_valorbase = number_format((float)$diversos_valorbase, 2, '.', ''); 
     
     //Fim Diversos


     //Moeda
     $moeda_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc))'));
     $moeda_avaliacaoplc = number_format((float)$moeda_avaliacaoplc, 2, '.', ''); 
     
     $moeda_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor))'));
     $moeda_soma = number_format((float)$moeda_soma, 2, '.', ''); 

     $moeda_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base))'));
     $moeda_valorbase = number_format((float)$moeda_valorbase, 2, '.', ''); 
     //Fim Moeda


     //Joias
     $joias_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc))'));
     $joias_avaliacaoplc = number_format((float)$joias_avaliacaoplc, 2, '.', ''); 
     
     $joias_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor))'));
     $joias_soma = number_format((float)$joias_soma, 2, '.', ''); 

     $joias_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base))'));
     $joias_valorbase = number_format((float)$joias_valorbase, 2, '.', ''); 
     //Fim Joias

     $somaavaliacaoplc = $veiculo_avaliacaoplc + $imovel_avaliacaoplc + $empresa_avaliacaoplc + $diversos_avaliacaoplc + $moeda_avaliacaoplc + $joias_avaliacaoplc;
     $somabruto = $veiculo_soma + $imovel_soma + $empresa_soma + $empresa_soma + $diversos_soma + $moeda_soma + $joias_soma;
     $somavalorbase =   $veiculo_valorbase + $imovel_valorbase + $empresa_valorbase + $diversos_valorbase + $moeda_valorbase + $joias_valorbase;    

     $valordivida = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')->select(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa)'))->where('CPF_CNPJ', $codigo)->value(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa))'));
     $valordivida = number_format((float)$valordivida, 2, '.', '');  

    //Se o valor da divida for vazio
     if($valordivida == 0.00) {
      $porcentagem = '100.00';
     }

    else {
      $porcentagem = $somaavaliacaoplc / $valordivida;

    }
      
      //Se a porcentagem for entre 0 e 50
      if($porcentagem <= 50.00) {

        $score = '0.00';

      }
      //Se a porcentagem for maior ou igual a 130
      elseif($porcentagem >= 130.00) {

        $score = '100.00';
     
      } else {
         
        $score = $porcentagem * 100 / 200;
      }

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
  'hist_notificacao.status', 
  'dbo.users.*')  
  ->limit(3)
  ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
  ->where('dbo.Hist_Notificacao.status','=','A')
  ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
  ->orderBy('dbo.Hist_Notificacao.data', 'desc')
  ->get();

  $status = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
  ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
  ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PesquisaPatrimonial_Clientes.id_referencia')
  ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
  ->select(
    'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
    'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
    'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
    'dbo.PesquisaPatrimonial_Clientes.descricao as Cliente',
    'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as Classificacao',
    'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status')
  ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
  ->get();

  $imoveis =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
   ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.user_id', '=', 'dbo.users.id')
   ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
   ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
   ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
   ->select(
     'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
    'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
    DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'), 
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
    'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
    'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
    ->get();

    $veiculos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor  as valor', 
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
     ->get();

     $empresas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
      ->get();

      $infojuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
      ->join('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.user_id', '=', 'dbo.users.id')
      ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix', 
       'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as id',
       'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
       'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao')
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
       ->get();

       $bacenjuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
       ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as id',
        'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
        'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao')
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
       ->get();

       $protestos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
       ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as id',
        'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
        'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao')
       ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
       ->get();
 
       $redessociais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.rede_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
      ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as id',
      'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as redesocial',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.status as status',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
      ->get();

      $processosjudiciais =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as id',
       'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
       'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
      ->get();

      $pesquisascadastral =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as id',
       'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
       'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
      ->get();

      $dossiecomercials =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
      ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.user_id', '=', 'dbo.users.id')
      ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
      ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as id',
       'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
       'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
      ->get();

      $dadosprocessos =  DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
      ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
      ->leftjoin('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
      ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
      ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
      ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
               'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
               'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
               'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
               'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
               'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
               DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'),
               'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
      ->where('CPF_CNPJ', '=', $codigo) 
      ->get();
  

  return view('Painel.PesquisaPatrimonial.solicitacao.capa', compact('score','somabruto','codigo', 'datas','saldodevedor','solicitacoes','totalNotificacaoAbertas', 'notificacoes', 'status', 'imoveis', 'veiculos', 'empresas', 'infojuds','bacenjuds', 'protestos', 'redessociais', 'processosjudiciais', 'pesquisascadastral','dossiecomercials', 'dadosprocessos', 'QuantidadePesquisa','QuantidadeStatus','QuantidadeImovel','QuantidadeVeiculo','QuantidadeEmpresa','QuantidadeInfojud','QuantidadeBacenjud','QuantidadeProtestos','QuantidadePesquisaCadastral','QuantidadeDossieComercial','QuantidadeRedesSociais','QuantidadeProcessosJudiciais'));


   }

   public function supervisao_clientes_editar($cliente_id) {

    $datas = DB::table('dbo.PesquisaPatrimonial_Clientes')
    ->join('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Clientes.id_referencia', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('dbo.PesquisaPatrimonial_Grupos', 'dbo.PesquisaPatrimonial_Clientes.grupo_id', '=', 'dbo.PesquisaPatrimonial_Grupos.id_referencia')
    ->select(
            'dbo.PesquisaPatrimonial_Clientes.id as id',
            'dbo.PesquisaPatrimonial_Clientes.descricao as Descricao',
            'dbo.PesquisaPatrimonial_Grupos.descricao as Grupo',
            'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento',
            'dbo.PesquisaPatrimonial_Clientes.status as Status')
    ->where('dbo.PesquisaPatrimonial_Clientes.id','=',$cliente_id)
    ->first();

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
    
    return view('Painel.PesquisaPatrimonial.supervisao.configuracao.clientes.edit', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function supervisao_clientes_update(Request $request) {

    $cliente_id = $request->get('cliente_id');
    $diasvencimento = $request->get('datavencimento');
    $status = $request->get('status');

    DB::table('dbo.PesquisaPatrimonial_Clientes')
    ->where('id', $cliente_id)  
    ->limit(1) 
    ->update(array('diasvencimento' => $diasvencimento,'status' => $status));

    flash('Dados do cliente atualizados com sucesso!')->success();

    return redirect()->route('Painel.PesquisaPatrimonial.supervisao.clientes.index');


   }

   public function financeiro_formapagamento($id_matrix) {


    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as Pesquisado',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
      'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
      'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
      'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Razao as UnidadeRazao',
      'PLCFULL.dbo.Jurid_Unidade.CNPJ as UnidadeCNPJ',
      'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
      'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
      'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
      'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
      'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
      'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

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
    
    return view('Painel.PesquisaPatrimonial.financeiro.formapagamento', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


   }

   public function financeiro_enviarformapagamento(Request $request) {

    $id_matrix = $request->get('id_matrix');
    $outraparte = $request->get('pesquisado');
    $pasta = $request->get('pasta');
    $datasolicitacao = $request->get('datasolicitacao');
    $valorsemformato = $request->get('valortotal');
    $valor =  str_replace (',', '.', str_replace ('.', ',', $valorsemformato));
    $unidade = $request->get('unidade');
    $unidadecnpj = $request->get('unidadecnpj');
    $unidaderazao = $request->get('unidaderazao');
    $unidadeendereco = $request->get('unidadeendereco');
    $unidadebairro = $request->get('unidadebairro');
    $unidadecidade = $request->get('unidadecidade');
    $unidadeuf = $request->get('unidadeuf');
    $setor = $request->get('setor');
    $solicitante_id = $request->get('solicitanteid');
    $solicitante_email = $request->get('solicitanteemail');
    $solicitante_cpf = $request->get('solicitantecpf');
    $solicitante_nome = $request->get('solicitante');
    $clientedescricao = $request->get('cliente');
    $clientecodigo = $request->get('clientecodigo');
    $clienterazao = $request->get('clienterazao');
    $clienteendereco = $request->get('clienteendereco');
    $clientebairro = $request->get('clientebairro');
    $clientecidade = $request->get('clientecidade');
    $clienteuf = $request->get('clienteuf');
    $clientecep = $request->get('clientecep');
    $emailcliente = $request->get('emailcliente');
    $carbon= Carbon::now();
    $usuarioid = Auth::user()->id;
    $numeroprocesso = $request->get('numeroprocesso');
    $tiposolicitacao = $request->get('tiposolicitacao');
    $tiposervico = $request->get('tiposervico');
    $observacao = $request->get('observacao');


    $formapamento = $request->get('formapagamento');

    //Se for Boleto
    if($formapamento == "BOLETO") {

    //Update Matrix
    DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id'=> '12'));

    //Update Hist
    $values = array(
      'id_matrix' => $id_matrix,
      'user_id' => $solicitante_id, 
      'status_id' => '16', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

    //Envia Notificação Advogado Solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'O financeiro escolheu para esta solicitação de pesquisa patrimonial adiantamento que a forma de pagamento é boleto.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação para o Supervisor Pesquisa Patrimonial
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '225', 'tipo' => '2', 'obs' => 'O financeiro escolheu para esta solicitação de pesquisa patrimonial adiantamento que a forma de pagamento é boleto' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Gera CPR
    $ultimonumprc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
    $numdoc = $ultimonumprc + 1;

    $cliente_id = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('id_cliente')->where('Codigo', '=', $clientecodigo)->value('id_cliente');
    $vencimentocliente = $request->get('diasvencimento');
    
     //Apos Criado na tabela ContaPR, ele vai da update na Tabela_Default_

     DB::table('PLCFULL.dbo.Jurid_Default_')
     ->limit(1) 
     ->update(array('Numcpr' => $numdoc));

     $values= array(
       'Tipodoc' => 'BOL',
       'Numdoc' => $numdoc,
       'Cliente' => $clientecodigo,
       'Tipo' => 'R',
       'Centro' => $setor,
       'Valor' => $valor,
       'Dt_aceite' => $carbon->format('Y-m-d'),
       'Dt_Vencim' => date('Y-m-d', strtotime('+' .$vencimentocliente .' days')),
       'Dt_Progr' => $carbon->format('Y-m-d'),
       'Multa' => '0',
       'Juros' => '0',
       'Tipolan' => '16.04',
       'Desconto' => '0',
       'Baixado' => '1',
       'Portador' => $clientecodigo,
       'Status' => '0',
       'Historico' => 'Solicitação Nª: '. $id_matrix . ' referente a adiantamento de solicitação de Pesquisa Patrimonial realizada no Portal.' . $observacao,
       'Obs' => $observacao,
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
       'Contrato' => '',
       'Origem_cpr' => $id_matrix,
       'numprocesso' => $numeroprocesso,
       'cod_pasta' => $pasta);
     DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);



    //Gera o boleto
    $agencia = '8809';
    $conta = '286019';
    $carteira = '109';

    $nossonumero = DB::table('PLCFULL.dbo.Jurid_Banco')->select('NossoNumeroGer')->where('Conta', '=', $conta)->value('NossoNumeroGer');
    $novonumero = $nossonumero + 1;

    DB::table('PLCFULL.dbo.Jurid_Banco')
    ->where('Conta', '=', $conta)  
    ->limit(1) 
    ->update(array('NossoNumeroGer' => $novonumero));
  
    //Insert na Jurid_Boleto
    $values = array(
        'codigo_conta_bancaria' => '004',
        'nosso_numero' => $novonumero,
        'especie_doc' => 'DS',
        'especie_moeda' => 'R$',
        'aceite' => 'NAO',
        'carteira' => $carteira,
        'num_doc' => $numdoc,
        'valor_boleto' => $valor,
        'valor_juros' => '0.00',
        'valor_multa' => '0.00',
        'valor_abatimento' => '0.00',
        'valor_desconto' => '0.00',
        'valor_liquido' => $valor,
        'data_emissao' => $carbon,
        'data_vencimento' => date('Y-m-d H:i:s', strtotime('+'. $vencimentocliente . ' days')),
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
        'unidade' => $unidade);
      DB::table('PLCFULL.dbo.Jurid_Boleto')->insert($values);  
  
     $codigoboleto = DB::table('PLCFULL.dbo.Jurid_Boleto')->orderBy('codigo_boleto', 'desc')->value('codigo_boleto');

     //Update Jurid_ContaPr
     DB::table('PLCFULL.dbo.Jurid_ContaPr')
     ->where('Origem_cpr', $id_matrix)  
     ->limit(1) 
     ->update(array('nossonumero' => $novonumero, 'codigo_boleto' => $codigoboleto));

      //Insert na Jurid_Boleto_Assoc
      $values = array(
        'codigo_boleto' => $codigoboleto,
        'codigo_cpr' => $numdoc);
      DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->insert($values); 


      //Dados do beneficiario
      $beneficiario = new \Eduardokum\LaravelBoleto\Pessoa;
      $beneficiario->setDocumento($unidadecnpj)
        ->setNome($unidaderazao)
        ->setCep($unidadecep)
        ->setEndereco($unidadeendereco)
        ->setBairro($unidadebairro)
        ->setUf($unidadeuf)
        ->setCidade($unidadecidade);

      //Dados do pagador
      $pagador = new \Eduardokum\LaravelBoleto\Pessoa;
      $pagador->setDocumento($clientecodigo)
        ->setNome($clienterazao)
        ->setCep($clientecep)
        ->setEndereco($clienteendereco)
        ->setBairro($clientebairro)
        ->setUf($clienteuf)
        ->setCidade($clientecidade);   

      //Gera o boleto
      $boleto = new \Eduardokum\LaravelBoleto\Boleto\Banco\Itau(
            [
          'logo'                   => 'https://www.grupomemorial.net/img/itau-logo.jpg',
          'dataVencimento'         => Carbon::now()->addDay($vencimentocliente),
          'valor'                  => $valor,
          'multa'                  => false,
          'juros'                  => false,
          'jurosApos'              => 0,  // quant. de dias para começar a cobrança de juros,
          'numero'                 => 1,
          'numeroDocumento'        => $numdoc,
          'pagador'                => $pagador,
          'beneficiario'           => $beneficiario,
          'diasBaixaAutomatica'    => 20,
          'carteira'               => $carteira,
          'agencia'                => $agencia,
          'conta'                  => $conta,
          'descricaoDemonstrativo' => ['Instruções de responsabilidade do BENEFICIÁRIO. Qualquer dúvida sobre este boleto contate o beneficiário'],
          'instrucoes' => ['Até o vencimento, preferencialmente no Itaú'],
          'aceite'                 => 'N',
          'especieDoc'             => 'DS',
          ]
      );   
      
     $pdf = new \Eduardokum\LaravelBoleto\Boleto\Render\Pdf();
     $pdf->addBoleto($boleto);
     $pdf->gerarBoleto($pdf::OUTPUT_SAVE, 'storage/app/public/boletos/'.$clientecodigo.'.pdf');


    }

    flash('Forma de pagamento foi escolhida com sucesso !')->success();    

    return redirect()->route('Painel.PesquisaPatrimonial.financeiro.solicitacoes');

  
   }



   public function nucleo_alterarforma($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc', '=', 'PLCFULL.dbo.Jurid_Debite.Fatura')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.PRConta',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'PLCFULL.dbo.Jurid_CliFor.Endereco as ClienteEndereco',
      'PLCFULL.dbo.Jurid_CliFor.Bairro as ClienteBairro',
      'PLCFULL.dbo.Jurid_CliFor.UF as ClienteUF',
      'PLCFULL.dbo.Jurid_CliFor.Cidade as ClienteCidade',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Razao as UnidadeRazao',
      'PLCFULL.dbo.Jurid_Unidade.CNPJ as UnidadeCNPJ',
      'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
      'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
      'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
      'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
      'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
      'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
              DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Cidades.municipio as Cidade',
             'dbo.users.name as Correspondente',
             'dbo.Jurid_Nota_Tiposervico.descricao as Motivo',
             DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.ValorT AS NUMERIC(15,2)) as CorrespondenteValor'))
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Cidades', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.cidade_id', '=', 'dbo.PesquisaPatrimonial_Cidades.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.correspondente_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.motivocorrespondente_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebitecorrespondente', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->get();

    $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
                ->get();

    $codigocliente = $datas->CodigoCliente;
    $diasvencimento = $datas->DiasVencimento;
    $clienterazao = $datas->ClienteFantasia;
 
    $datavencimento = date('Y-m-d', strtotime('+ 60 days'));

    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;

    $saldoclienter = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipolan', '=', 'TRANSF')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
    
  
    $saldoclientep = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

    $saldocliente =  $saldoclienter - $saldoclientep;
         


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
    

    return view('Painel.PesquisaPatrimonial.nucleo.alterarcobranca', compact('carbon','comarcas','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes', 'saldototal', 'datavencimento'));


   }

   public function nucleo_alteradoforma(Request $request) {


  $id_matrix = $request->get('id_matrix');
  $solicitante_id = $request->get('solicitanteid');
  $solicitante_cpf = $request->get('solicitantecpf');
  $solicitante_email = $request->get('solicitanteemail');
  $observacao = $request->get('observacao');
  $clientecodigo = $request->get('clientecodigo');
  $pasta = $request->get('codigopasta');
  $setor = $request->get('setor');
  $unidade = $request->get('unidade');
  $valorsemformato = $request->get('valortotal');
  $valor =  str_replace (',', '.', str_replace ('.', ',', $valorsemformato));
  $cpr = $request->get('cpr');
  $carbon= Carbon::now();
  $contrato = $request->get('contrato');
  $usuario_id = Auth::user()->id;
  $tiposervico_id = $request->get('tiposervico');
  $observacaoadiantamento = $request->get('observacaoadiantamento');
  $anexoadiantamento = $request->file('anexoadiantamento');

  $new_name = 'anexoadiantamento_' . $id_matrix . '_' . $carbon . '.'  . $anexoadiantamento->getClientOriginalExtension();
  $anexoadiantamento->storeAs('pesquisapatrimonial', $new_name);

  //Update status 
  DB::table('dbo.PesquisaPatrimonial_Matrix')
  ->where('id', $id_matrix)  
  ->limit(1) 
  ->update(array('valor' => $valor,'status_id'=> '9', 'observacaoadiantamento' => $observacaoadiantamento, 'anexoadiantamento' => $new_name));


  //Grava na tabela hist informando que foi gerado debite
  $values = array(
    'id_matrix' => $id_matrix,
    'user_id' => $solicitante_id, 
    'status_id' => '19', 
    'data' => $carbon);
    DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

   //Deleta Boleto
   DB::table('PLCFULL.dbo.Jurid_Boleto')->where('num_doc', $cpr)->delete();        

   //Deleta Boleto_Assoc
   DB::table('PLCFULL.dbo.Jurid_Boleto_Assoc')->where('codigo_cpr', $cpr)->delete();  

   DB::table('PLCFULL.dbo.Jurid_ContaPr')
   ->where('Numdoc', $cpr)  
  ->limit(1) 
  ->update(array('codigo_boleto' => ''));

   //Deleta CPR
   DB::table('PLCFULL.dbo.Jurid_ContaPr')->where('Numdoc', $cpr)->delete();  
          
   //Deleta o arquivo boleto
  unlink(storage_path('app/public/boletos/'.$numdoc.'.pdf')); 

 //Grava na tabela hist informando que a solicitação esta aguardando revisão supervisão    
 $values = array('id_matrix' => $id_matrix, 'user_id' => $usuario_id, 'status_id' => '13', 'data' => $carbon);
 DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

 //Envia Notificação para o Supervisor da Pesquisa Patrimonial
 $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuario_id, 'destino_id' => '225', 'tipo' => '2', 'obs' => 'Nova revisão de pesquisa patrimonial.' ,'status' => 'A');
 DB::table('dbo.Hist_Notificacao')->insert($values3);

 //Envia Email para o Supervisor da Pesquisa Patrimonial
 Mail::to('ronaldo.amaral@plcadvogados.com.br')
 ->send(new RevisarSolicitacaoSupervisao($id_matrix));


flash('Solicitação enviada ao supervisor da Pesquisa Patrimonial para avaliação.')->success();   

return redirect()->route('Painel.PesquisaPatrimonial.nucleo.solicitacoesaguardandoficha');


}


  public function buscapesquisa($numero) {

    $carbon= Carbon::now();
      
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

     $status = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '1')
     ->value('status_id');

     $imovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '2')
     ->value('status_id');

     $veiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '3')
     ->value('status_id');

     $empresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '4')
     ->value('status_id');

     $infojud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '5')
     ->value('status_id');

     $bacenjud = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '6')
     ->value('status_id');

     $protestos = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '7')
     ->value('status_id');

     $redessociais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '8')
     ->value('status_id');

     $processosjudiciais = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '9')
     ->value('status_id');

     $pesquisacadastral = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '10')
     ->value('status_id');

     $dossiecomercial = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '11')
     ->value('status_id');

     $dados = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados') 
     ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.status_id')  
     ->join('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
     ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $numero)
     ->where('dbo.PesquisaPatrimonial_Servicos_UF.id', '=', '12')
     ->value('status_id');


    return view('Painel.PesquisaPatrimonial.pesquisa', compact('status','imovel','veiculo','empresa','infojud','bacenjud','protestos','redessociais','processosjudiciais','pesquisacadastral','dossiecomercial','dados','numero', 'totalNotificacaoAbertas', 'notificacoes'));
    
  }

  public function ficha($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
      'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_Vencim as DataVencimento',
      'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();
  
  
    $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
              DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Cidades.municipio as Cidade',
             'dbo.users.name as Correspondente',
             'dbo.Jurid_Nota_Tiposervico.descricao as Motivo',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.anexo as Anexo',
             DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.ValorT AS NUMERIC(15,2)) as CorrespondenteValor'))
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Cidades', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.cidade_id', '=', 'dbo.PesquisaPatrimonial_Cidades.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.correspondente_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.motivocorrespondente_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebitecorrespondente', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
    ->get();
  
    $cpr = $datas->CPR;
    $cpr_ident = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc', '=', $cpr)->value('Cpr_ident'); 
    
     $saldoclienter = DB::table('PLCFULL.dbo.Jurid_Banco')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Portador')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
    ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
  
    $saldoclientep = DB::table('PLCFULL.dbo.Jurid_Banco')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Portador')
    ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
    ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
  
  
    $saldototal = $saldoclienter - $saldoclientep;
  
  
    $correspondentes = DB::table('dbo.users')
    ->select('dbo.users.id as id', 'dbo.users.name as name')
    ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
    ->where('dbo.profile_user.profile_id','=', '1')
    ->get();
  
  
    $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
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
  
  
  
    return view('Painel.PesquisaPatrimonial.ficha', compact('carbon','comarcas','correspondentes','saldototal','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function supervisao_equipe_index() {


    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->select(
             'dbo.users.id as userid',
             'dbo.users.name as Nome',
             'dbo.users.email as Email',
             DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.Valor ) as Valor'),
             DB::raw('COUNT(dbo.PesquisaPatrimonial_Matrix.id) as QuantidadeSolicitacoesRevisadas'))
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Matrix.equipe_id', '=', 'dbo.users.id')         
    ->groupby('dbo.users.id',
              'dbo.users.name',
              'dbo.users.email')
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

    return view('Painel.PesquisaPatrimonial.supervisao.equipe.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function supervisao_equipe_solicitacoes($user_id) {

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.equipe_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'dbo.PesquisaPatrimonial_Matrix.codigo', '=', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as Id',
      'dbo.PesquisaPatrimonial_Matrix.codigo as CPF',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.name as EquipeNome',
      'dbo.PesquisaPatrimonial_Status.id as StatusID',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.arquivo as anexo',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'PLCFULL.dbo.Jurid_CliFor.Unidade as Unidade',
      'dbo.PesquisaPatrimonial_Matrix.setor_id as Setor',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Outra_Parte.Codigo as CadastroAdvwin',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente')
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

  return view('Painel.PesquisaPatrimonial.supervisao.equipe.solicitacoes', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function supervisao_equipe_dashboard($user_id) {

    $nome = DB::table('dbo.users')->select('name')->where('id', $user_id)->value('name');

    $QuantidadeSolicitacoesRevisadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('equipe_id', $user_id)->count();
    $QuantidadeSolicitacoesFinalizadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(15,17))->count();
    $QuantidadeSolicitacoesAdiantamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('equipe_id', $user_id)->whereIn('status_id', array(9,10,11,12,13,14))->count();

    $QuantidadeSolicitacoesAguardandoPagamentoCliente = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(5,21))->count();
    $QuantidadeSolicitacoesEmAndamento = DB::table('dbo.PesquisaPatrimonial_Matrix')->whereIn('status_id', array(4,8))->count();
    $QuantidadeSolicitacoesAguardandoRevisao = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '9')->count();
    $QuantidadeSolicitacoesAguardandoRevisaoFinanceiro = DB::table('dbo.PesquisaPatrimonial_Matrix')->where('status_id', '10')->count();
    $QuantidadeSolicitacoesCriadas = DB::table('dbo.PesquisaPatrimonial_Matrix')->count();
    $QuantidadePesquisados = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')->where('TipoOutraParte', '1')->count();
    $ValorReceber = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(5,21))->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
    $ValorRecebido = DB::table('dbo.PesquisaPatrimonial_Matrix')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor ) as valor'))->whereIn('status_id', array(4,7,8))->value(DB::raw('sum(dbo.PesquisaPatrimonial_Matrix.valor)'));
  
  
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
  
    return view('Painel.PesquisaPatrimonial.supervisao.equipe.dashboard', compact('nome','QuantidadeSolicitacoesRevisadas', 'QuantidadeSolicitacoesFinalizadas','QuantidadeSolicitacoesAdiantamento','totalNotificacaoAbertas', 'notificacoes'));
  
  }

  public function supervisao_grupos_index() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Grupos')
    ->join('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.PesquisaPatrimonial_Grupos.id_referencia', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->select(
            'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as Codigo',
            'dbo.PesquisaPatrimonial_Grupos.descricao as Descricao',
            'dbo.PesquisaPatrimonial_Grupos.status as Status')
    ->where('dbo.PesquisaPatrimonial_Grupos.status','=','A')
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
    

    return view('Painel.PesquisaPatrimonial.supervisao.configuracao.grupos.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
   }

   public function supervisao_alterarcobranca($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
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

    $codigocliente = $datas->CodigoCliente;
    $diasvencimento = $datas->DiasVencimento;
    $clienterazao = $datas->ClienteFantasia;
 
    $datavencimento = date('Y-m-d', strtotime('+ 60 days'));

    $razaoeditado = substr($clienterazao, 0, 50);
    $primeiroscodigo =  substr($codigocliente, 0, 5);  
    $descricao = "PESQUISA" . $primeiroscodigo;

   $saldoclienter = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
   ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
   ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
   ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
   ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $descricao)
   ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
   ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipolan', '=', 'TRANSF')
   ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
   
 
   $saldoclientep = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
   ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
   ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
   ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
   ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
   ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
   ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

   $saldototal = $saldoclienter - $saldoclientep;           


    return view('Painel.PesquisaPatrimonial.supervisao.novoalterarcobranca', compact('carbon','comarcas','datas','totalNotificacaoAbertas', 'notificacoes', 'datavencimento', 'saldototal'));

   }

   public function supervisao_alteradostatuscobravel(Request $request) {

    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitante_id');
    $carbon= Carbon::now();
    $cobravel = $request->get('cobravel');
    $cobravelatual = $request->get('cobravelatual');
    $usuarioid = Auth::user()->id;

    //Verifico se o status é o mesmo
    if($cobravel == $cobravelatual) {

      flash('O status selecionado é o mesmo que o atual!')->error();     

      return redirect()->route('Painel.PesquisaPatrimonial.supervisao.solicitacoes');
    }


    //Update Matrix
    DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id' => '1', 'cobravel' => $cobravel));

    //Update Hist
    $values = array(
      'id_matrix' => $id_matrix,
      'user_id' => $usuarioid, 
      'status_id' => '23', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

    //Envia Notificação Advogado Solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Alterado status de cobrança desta solicitação.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia Notificação para o Financeiro
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '235', 'tipo' => '2', 'obs' => 'Alterado status de cobrança desta solicitação.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    flash('Status de cobrança alterado com sucesso!')->success();    

    return redirect()->route('Painel.PesquisaPatrimonial.supervisao.solicitacoes');
  }

  public function nucleo_finalizarpesquisa($numero) {

    $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
    $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
    $statusid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('status_id')->where('id', $numero)->value('status_id');

    $carbon= Carbon::now();

    //Update na matrix
    
       //Se for pesquisa completa
       if($statusid == 4 || $statusid == 8) {

        DB::table('dbo.PesquisaPatrimonial_Matrix')
        ->where('id', $numero)  
        ->limit(1) 
        ->update(array('status_id' => '15'));

        //Grava na Hist
        $values = array(
        'id_matrix' => $numero,
        'user_id' => Auth::user()->id, 
        'status_id' => '15', 
        'data' => $carbon);
        DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values);

       }
       //Se for pesquisa previa
       else if($statusid == 11 ) {

        DB::table('dbo.PesquisaPatrimonial_Matrix')
        ->where('id', $numero)  
        ->limit(1) 
        ->update(array('status_id' => '17'));

        //Grava na Hist
        $values = array(
        'id_matrix' => $numero,
        'user_id' => Auth::user()->id, 
        'status_id' => '17', 
        'data' => $carbon);
        DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values);

       }

    //Update nos tipos de solicitação selecionados
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->where('id_matrix', $numero)
    ->update(array('status_id' => '4'));

    Mail::to($solicitanteemail)
    ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
    ->send(new PesquisaFinalizada($numero));

    //Envia notificação
    $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: foi finalizada pelo núcleo.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.solicitacoesfinalizadas");

  }

  public function financeiro_alterarcobranca($id_matrix) {

    $carbon= Carbon::now();

    $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
    ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
    ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_EmailCliente', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_EmailCliente.cliente_id')
    ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'dbo.PesquisaPatrimonial_Clientes.id_referencia')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as ID',
      'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
      'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
      'dbo.users.id as SolicitanteID',
      'dbo.users.email as SolicitanteEmail',
      'dbo.users.cpf as SolicitanteCPF',
      'dbo.users.name as SolicitanteNome',
      'dbo.PesquisaPatrimonial_Status.descricao as Status',
      'dbo.PesquisaPatrimonial_Matrix.tipo as Tipo',
      'dbo.PesquisaPatrimonial_Matrix.cobravel as Cobravel',
      'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
      'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
      'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
      'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
      'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
      'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
      'PLCFULL.dbo.Jurid_GrupoCliente.Codigo as GrupoClienteCodigo',
      'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
      'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteFantasia',
      'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
      'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
      'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
      'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
      'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
      'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
      'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
      'PLCFULL.dbo.Jurid_CliFor.CEP as ClienteCEP',
      'dbo.PesquisaPatrimonial_EmailCliente.email as EmailCliente',
      'dbo.PesquisaPatrimonial_Clientes.diasvencimento as DiasVencimento')
    ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
    ->first();

    $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
                ->get();

     $codigocliente = $datas->CodigoCliente;
     $diasvencimento = $datas->DiasVencimento;
     $clienterazao = $datas->ClienteFantasia;
  
     $datavencimento = date('Y-m-d', strtotime('+ 60 days'));

     $razaoeditado = substr($clienterazao, 0, 50);
     $primeiroscodigo =  substr($codigocliente, 0, 5);  
     $descricao = "PESQUISA" . $primeiroscodigo;

    $saldoclienter = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
    ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipolan', '=', 'TRANSF')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
    
  
    $saldoclientep = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
    ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
    ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
    ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $descricao)
    ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
    ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
 
    $saldototal = $saldoclienter - $saldoclientep;           

  
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
    

    return view('Painel.PesquisaPatrimonial.financeiro.alterarcobranca', compact('carbon','comarcas','datas','totalNotificacaoAbertas', 'notificacoes', 'datavencimento', 'saldototal'));

   }

   public function financeiro_alteradostatuscobravel(Request $request) {

    $id_matrix = $request->get('id_matrix');
    $solicitante_id = $request->get('solicitante_id');
    $carbon= Carbon::now();
    $cobravel = $request->get('cobravel');
    $cobravelatual = $request->get('cobravelatual');
    $usuarioid = Auth::user()->id;

    //Verifico se o status é o mesmo
    if($cobravel == $cobravelatual) {

      flash('O status selecionado é o mesmo que o atual!')->error();     

      return redirect()->route('Painel.PesquisaPatrimonial.financeiro.solicitacoes');
    }


    //Update Matrix
    DB::table('dbo.PesquisaPatrimonial_Matrix')
      ->where('id', $id_matrix)  
      ->limit(1) 
      ->update(array('status_id' => '1', 'cobravel' => $cobravel));

    //Update Hist
    $values = array(
      'id_matrix' => $id_matrix,
      'user_id' => $usuarioid, 
      'status_id' => '23', 
      'data' => $carbon);
      DB::table('dbo.PesquisaPatrimonial_Hist')->insert($values); 

    //Envia Notificação Advogado Solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => $solicitante_id, 'tipo' => '2', 'obs' => 'Alterado status de cobrança desta solicitação.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia Notificação para o supervisor pesquisa patrimonial
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => $usuarioid, 'destino_id' => '225', 'tipo' => '2', 'obs' => 'Alterado status de cobrança desta solicitação.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    flash('Status de cobrança alterado com sucesso!')->success();    

    return redirect()->route('Painel.PesquisaPatrimonial.financeiro.solicitacoes');
  }

  public function nucleo_finalizaraba($id, $numero) {

    $carbon= Carbon::now();

    $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', $id)->value('descricao');
    $codigo = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('codigo')->where('numero', $numero)->value('codigo');
    $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('numero', $numero)->value('solicitante_id');
    $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');

    //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
    Mail::to($solicitanteemail)
    ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
    ->send(new AbaFinalizada($numero, $descricao));

    //Envia notificação
    $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [$descricao] foi finalizada pelo núcleo.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Update Tabela armazenando os tipos de serviços com a Matrix
    DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->where('id_matrix', $numero)  
    ->where('id_tiposolicitacao', $id)
    ->limit(1) 
    ->update(array('datafinalizada' => $carbon, 'status_id' => '3'));
      
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


  
    return view('Painel.PesquisaPatrimonial.nucleo.abas', compact('codigo','numero', 'totalNotificacaoAbertas', 'notificacoes'));   

  }

  public function ti_tipossolicitacoes() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Servicos_UF') 
    ->select('dbo.PesquisaPatrimonial_Servicos_UF.id as id',
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Servicos_UF.status as status',
             'dbo.PesquisaPatrimonial_Servicos_UF.geradebite as geradebite',
             'dbo.PesquisaPatrimonial_Servicos_UF.tipodebite as tipodebite')  
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

    return view('Painel.PesquisaPatrimonial.ti.configuracao.tipossolicitacoes.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));   

  }

  public function ti_peso_index() {

    $datas = DB::table('dbo.PesquisaPatrimonial_Servicos_UF') 
    ->select('dbo.PesquisaPatrimonial_Servicos_UF.id as id',
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Servicos_UF.status as status',
             'dbo.PesquisaPatrimonial_Servicos_UF.geradebite as geradebite',
             'PLCFULL.dbo.Jurid_TipoDebite.Descricao as tipodebite',
             'dbo.PesquisaPatrimonial_Servicos_UF.peso') 
    ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'dbo.PesquisaPatrimonial_Servicos_UF.tipodebite', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo' )          
    ->orderby('dbo.PesquisaPatrimonial_Servicos_UF.descricao', 'asc')
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

    return view('Painel.PesquisaPatrimonial.ti.configuracao.peso.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));   

  }

  public function ti_peso_editado(Request $request) {

    $id = $request->get('id');
    $tiposolicitacao = $request->get('tiposolicitacao');
    $peso = $request->get('peso');

    DB::table('dbo.PesquisaPatrimonial_Servicos_UF')
    ->where('id', $id)  
    ->limit(1) 
    ->update(array('peso' => $peso));

    flash('Registro atualizado com sucesso!')->success();    

    return redirect()->route('Painel.PesquisaPatrimonial.ti.peso.index');


  }

  public function ti_tipossolicitacoes_editar($id) {


    $datas = DB::table('dbo.PesquisaPatrimonial_Servicos_UF') 
    ->select('dbo.PesquisaPatrimonial_Servicos_UF.id as id',
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao',
             'dbo.PesquisaPatrimonial_Servicos_UF.status as status',
             'dbo.PesquisaPatrimonial_Servicos_UF.geradebite as geradebite',
             'dbo.PesquisaPatrimonial_Servicos_UF.tipodebite as tipodebite')  
    ->where('id', '=', $id)         
    ->first();

    $tiposdebite = DB::table('PLCFULL.dbo.Jurid_TipoDebite') 
    ->select('PLCFULL.dbo.Jurid_TipoDebite.Codigo as Codigo',
             'PLCFULL.dbo.Jurid_TipoDebite.Descricao as Descricao')  
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

    return view('Painel.PesquisaPatrimonial.ti.configuracao.tipossolicitacoes.editar', compact('datas','tiposdebite','totalNotificacaoAbertas', 'notificacoes'));   

  }

  public function ti_tipossolicitacoes_atualizar(Request $request) {

    $id = $request->get('id');
    $descricao = $request->get('descricao');
    $geradebite = $request->get('geradebite');
    $tipodebite = $request->get('tipodebite');
    $status = $request->get('status');

    DB::table('dbo.PesquisaPatrimonial_Servicos_UF')
    ->where('id', $id)  
    ->limit(1) 
    ->update(array('descricao' => $descricao,'geradebite' => $geradebite, 'tipodebite' => $tipodebite, 'status' => $status));

    flash('Tipo de solicitação atualizada com sucesso!')->success();    

    return redirect()->route('Painel.PesquisaPatrimonial.ti.configurar.tipossolicitacoes');


  }


  public function nucleo_tabimovel($codigo, $numero) {

      $carbon= Carbon::now();
        
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
  
       $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
       ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
       ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as logradouro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
        'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta', 
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
        'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
        ->get();

        $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('imovel_pesquisa')->where('codigo','=', $codigo)->value('imovel_pesquisa'); 
        $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('imovel_resultado')->where('codigo','=', $codigo)->value('imovel_resultado'); 
        $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('imovel_status')->where('codigo','=', $codigo)->value('imovel_status'); 
  
  
      return view('Painel.PesquisaPatrimonial.nucleo.tabs.imovel', compact('pesquisa','resultadopesquisa', 'statuspesquisa','codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes'));
  
  }

  public function nucleo_tabveiculo($codigo, $numero) {

    $carbon= Carbon::now();
      
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

     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
     'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.fabricante_id as fabricante',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.combustivel as combustivel',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
     'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor', 
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_alienacao', 
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel', 
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacaopenhora as penhora',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.dataaverbacao',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.carta',
     'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.datacarta',
     'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
     ->get();

     $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('veiculo_pesquisa')->where('codigo','=', $codigo)->value('veiculo_pesquisa'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('veiculo_resultado')->where('codigo','=', $codigo)->value('veiculo_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('veiculo_status')->where('codigo','=', $codigo)->value('veiculo_status'); 

     return view('Painel.PesquisaPatrimonial.nucleo.tabs.veiculo', compact('pesquisa', 'resultadopesquisa', 'statuspesquisa','codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function nucleo_tabempresa($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.nomefantasia as nomefantasia',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.objetosocial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.situacao',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'), 
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'), 
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.penhoracotas as penhora',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.quantidadecotas',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datapenhoracotas',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cep',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.logradouro',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.numero as complemento',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.bairro',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.municipio as cidade',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.uf',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
      ->get();


      $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('empresa_pesquisa')->where('codigo','=', $codigo)->value('empresa_pesquisa'); 
      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('empresa_resultado')->where('codigo','=', $codigo)->value('empresa_resultado'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('empresa_status')->where('codigo','=', $codigo)->value('empresa_status'); 
 
 
     return view('Painel.PesquisaPatrimonial.nucleo.tabs.empresa', compact('pesquisa', 'resultadopesquisa', 'statuspesquisa','codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function infojud($codigo, $numero) {

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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.infojud', compact('datahoje', 'numero', 'codigo','totalNotificacaoAbertas', 'notificacoes'));
  }

  public function bacenjud($codigo, $numero) {

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

    $datahoje= Carbon::now();
    return view('Painel.PesquisaPatrimonial.nucleo.abas.bacenjud', compact('datahoje', 'numero', 'codigo', 'totalNotificacaoAbertas', 'notificacoes'));

}


   public function nucleo_tabinfojud($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix', 
      'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
      ->get();

      $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select(DB::raw('CAST(infojud_data AS DateTime) as infojud_data'))->where('codigo','=', $codigo)->value('infojud_data'); 
      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('infojud_resultado')->where('codigo','=', $codigo)->value('infojud_resultado'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('infojud_status')->where('codigo','=', $codigo)->value('infojud_status'); 


    return view('Painel.PesquisaPatrimonial.nucleo.tabs.infojud', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabbacenjud($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

      $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('bacenjud_pesquisa')->where('codigo','=', $codigo)->value('bacenjud_pesquisa'); 
      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('bacenjud_resultado')->where('codigo','=', $codigo)->value('bacenjud_resultado'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('bacenjud_status')->where('codigo','=', $codigo)->value('bacenjud_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.bacenjud', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'pesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabredessociais($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.rede_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as id',
      'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as redesocial',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.status as status',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('redesocial_data')->where('codigo','=', $codigo)->value('redesocial_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('redesocial_resultado')->where('codigo','=', $codigo)->value('redesocial_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('redesocial_status')->where('codigo','=', $codigo)->value('redesocial_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.redessociais', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa','resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabprotestos($codigo, $numero) {


    $carbon= Carbon::now();
      
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


      $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.valor',
      // 'dbo.PesquisaPatrimonial_Solicitacao_Notas.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('protesto_data')->where('codigo','=', $codigo)->value('protesto_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('protesto_resultado')->where('codigo','=', $codigo)->value('protesto_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('protesto_status')->where('codigo','=', $codigo)->value('protesto_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.protestos', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabprocessosjudicias($codigo,$numero) {

    $carbon= Carbon::now();
      
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


     $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.valor',
      // 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('processojudicial_data')->where('codigo','=', $codigo)->value('processojudicial_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('processojudicial_resultado')->where('codigo','=', $codigo)->value('processojudicial_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('processojudicial_status')->where('codigo','=', $codigo)->value('processojudicial_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.processosjudiciais', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabdossiecomercial($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('dossiecomercial_data')->where('codigo','=', $codigo)->value('dossiecomercial_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('dossiecomercial_resultado')->where('codigo','=', $codigo)->value('dossiecomercial_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('dossiecomercial_status')->where('codigo','=', $codigo)->value('dossiecomercial_status'); 
  

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.dossiecomercial', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabpesquisacadastral($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('pesquisacadastral_data')->where('codigo','=', $codigo)->value('pesquisacadastral_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('pesquisacadastral_resultado')->where('codigo','=', $codigo)->value('pesquisacadastral_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('pesquisacadastral_status')->where('codigo','=', $codigo)->value('pesquisacadastral_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.pesquisacadastral', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabdados($codigo, $numero) {

    $carbon= Carbon::now();
      
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

  
   $datas =  DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
    ->leftjoin('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
             'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
             'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
             'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
             'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
             'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
             DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'),
             'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
    ->where('CPF_CNPJ', '=', $codigo) 
    ->get();

  

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.dados', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function nucleo_tabdiversos($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.data',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor as valor',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('diversos_data')->where('codigo','=', $codigo)->value('diversos_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('diversos_resultado')->where('codigo','=', $codigo)->value('diversos_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('diversos_status')->where('codigo','=', $codigo)->value('diversos_status'); 

    return view('Painel.PesquisaPatrimonial.nucleo.tabs.diversos', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabsmoeda($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.data',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.tipo',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor as valor',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('moeda_data')->where('codigo','=', $codigo)->value('moeda_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('moeda_resultado')->where('codigo','=', $codigo)->value('moeda_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('moeda_status')->where('codigo','=', $codigo)->value('moeda_status'); 

     return view('Painel.PesquisaPatrimonial.nucleo.tabs.moeda', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabsjoias($codigo, $numero) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.data',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.tipo',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor as valor',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('joias_data')->where('codigo','=', $codigo)->value('joias_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('joias_resultado')->where('codigo','=', $codigo)->value('joias_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('joias_status')->where('codigo','=', $codigo)->value('joias_status'); 

     return view('Painel.PesquisaPatrimonial.nucleo.tabs.joias', compact('codigo','numero', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function nucleo_tabscorecard($codigo, $numero) {

    $carbon= Carbon::now();
      
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


       //Veiculo 
       $veiculo_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor))'));
       $veiculo_soma = number_format((float)$veiculo_soma, 2, '.', '');      

       $veiculo_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc))'));
       $veiculo_avaliacaoplc = number_format((float)$veiculo_avaliacaoplc, 2, '.', '');      
       
       $veiculo_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base))'));
       $veiculo_valorbase = number_format((float)$veiculo_valorbase, 2, '.', '');      
       //Fim Veiculo

       //Imovel
       $imovel_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc))'));
       $imovel_avaliacaoplc = number_format((float)$imovel_avaliacaoplc, 2, '.', ''); 

       $imovel_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor))'));
       $imovel_soma = number_format((float)$imovel_soma, 2, '.', ''); 

       $imovel_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base))'));
       $imovel_valorbase = number_format((float)$imovel_valorbase, 2, '.', ''); 
       //Fim Imovel


       //Empresa
       $empresa_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc))'));
       $empresa_avaliacaoplc = number_format((float)$empresa_avaliacaoplc, 2, '.', ''); 

       $empresa_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial))'));
       $empresa_soma = number_format((float)$empresa_soma, 2, '.', ''); 

       $empresa_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base))'));
       $empresa_valorbase = number_format((float)$empresa_valorbase, 2, '.', ''); 
       //Fim Empresa

       //Diversos
       $diversos_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc))'));
       $diversos_avaliacaoplc = number_format((float)$diversos_avaliacaoplc, 2, '.', ''); 
       
       $diversos_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor))'));
       $diversos_soma = number_format((float)$diversos_soma, 2, '.', ''); 

       $diversos_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base))'));
       $diversos_valorbase = number_format((float)$diversos_valorbase, 2, '.', ''); 
       
       //Fim Diversos


       //Moeda
       $moeda_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc))'));
       $moeda_avaliacaoplc = number_format((float)$moeda_avaliacaoplc, 2, '.', ''); 
       
       $moeda_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor))'));
       $moeda_soma = number_format((float)$moeda_soma, 2, '.', ''); 

       $moeda_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base))'));
       $moeda_valorbase = number_format((float)$moeda_valorbase, 2, '.', ''); 
       //Fim Moeda


       //Joias
       $joias_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc))'));
       $joias_avaliacaoplc = number_format((float)$joias_avaliacaoplc, 2, '.', ''); 
       
       $joias_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor))'));
       $joias_soma = number_format((float)$joias_soma, 2, '.', ''); 

       $joias_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base))'));
       $joias_valorbase = number_format((float)$joias_valorbase, 2, '.', ''); 
       //Fim Joias

       $somaavaliacaoplc = $veiculo_avaliacaoplc + $imovel_avaliacaoplc + $empresa_avaliacaoplc + $diversos_avaliacaoplc + $moeda_avaliacaoplc + $joias_avaliacaoplc;
       $somabruto = $veiculo_soma + $imovel_soma + $empresa_soma + $empresa_soma + $diversos_soma + $moeda_soma + $joias_soma;
       $somavalorbase =   $veiculo_valorbase + $imovel_valorbase + $empresa_valorbase + $diversos_valorbase + $moeda_valorbase + $joias_valorbase;    

       $valordivida = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')->select(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa)'))->where('CPF_CNPJ', $codigo)->value(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa))'));
       $valordivida = number_format((float)$valordivida, 2, '.', ''); 


       //Se o valor da divida for vazio
       if($valordivida == 0.00) {
        $porcentagem = '100.00';
       }

      else {
        $porcentagem = $somaavaliacaoplc / $valordivida;

      }
        
        //Se a porcentagem for entre 0 e 50
        if($porcentagem <= 50.00) {

          $score = '0.00';

        }
        //Se a porcentagem for maior ou igual a 130
        elseif($porcentagem >= 130.00) {

          $score = '100.00';
       
        } else {
           
          $score = $porcentagem * 100 / 200;
        }


       //Pegar os valores para por no detalhamento score
       $veiculos = DB::table('dbo.PesquisaPatrimonial_Matrix')
                 ->select(
                  'dbo.PesquisaPatrimonial_Matrix.id',
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor ) as veiculo_valor'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base ) as veiculo_valorbase'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc ) as veiculo_valoravaliacaoplc'),
                   )
                 ->join('dbo.PesquisaPatrimonial_Solicitacao_Veiculo', 'dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix')
                 ->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)
                 ->groupby('dbo.PesquisaPatrimonial_Matrix.id','dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor','valor_base', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc')
                 ->get(); 

       $imovels = DB::table('dbo.PesquisaPatrimonial_Matrix')
                 ->select(
                  'dbo.PesquisaPatrimonial_Matrix.id',
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor ) as imovel_valor'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_base ) as imovel_valorbase'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc ) as imovel_valoravaliacaoplc'),
                   )
                 ->join('dbo.PesquisaPatrimonial_Solicitacao_Imovel', 'dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix')
                 ->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)
                 ->groupby('dbo.PesquisaPatrimonial_Matrix.id','dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor', 'valor_base', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc')
                 ->get(); 

      $diversos = DB::table('dbo.PesquisaPatrimonial_Matrix')
                 ->select(
                  'dbo.PesquisaPatrimonial_Matrix.id',
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor) as diverso_valor'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_base) as diverso_valorbase'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc) as diverso_valoravaliacaoplc'))
                 ->join('dbo.PesquisaPatrimonial_Solicitacao_Diversos', 'dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id_matrix')
                 ->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)
                 ->groupby('dbo.PesquisaPatrimonial_Matrix.id','dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_base', 'valor_avaliacaoplc')
                 ->get();      
                 
      $moedas = DB::table('dbo.PesquisaPatrimonial_Matrix')
                 ->select(
                  'dbo.PesquisaPatrimonial_Matrix.id',
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor) as moeda_valor'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_base) as moeda_valorbase'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc) as moeda_valoravaliacaoplc'),
                   )
                 ->leftjoin('dbo.PesquisaPatrimonial_Solicitacao_Moeda', 'dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id_matrix')
                 ->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)
                 ->groupby('dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_base','dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc')
                 ->get();        
                 
      $joias = DB::table('dbo.PesquisaPatrimonial_Matrix')
                 ->select(
                  'dbo.PesquisaPatrimonial_Matrix.id',
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Joias.valor) as joias_valor'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_base) as joias_valorbase'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc) as joias_valoravaliacaoplc'),
                   )
                 ->leftjoin('dbo.PesquisaPatrimonial_Solicitacao_Joias', 'dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.id_matrix')
                 ->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)
                 ->groupby('dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_base', 'valor_avaliacaoplc')
                 ->get();      
         
      $empresas = DB::table('dbo.PesquisaPatrimonial_Matrix')
                 ->select(
                  'dbo.PesquisaPatrimonial_Matrix.id',
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial) as empresa_valor'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_base) as empresa_valorbase'),
                  DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc) as empresa_valoravaliacaoplc'),
                   )
                 ->leftjoin('dbo.PesquisaPatrimonial_Solicitacao_Empresa', 'dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix')
                 ->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)
                 ->groupby('dbo.PesquisaPatrimonial_Matrix.id', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_base','dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc')
                 ->get(); 



    return view('Painel.PesquisaPatrimonial.nucleo.tabs.scorecard', compact('veiculos','imovels','diversos','empresas','moedas','joias','score','codigo','numero','somabruto','somavalorbase','somaavaliacaoplc','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function nucleo_finalizarstatus(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $resultadopesquisa = $request->get('resultadopesquisa');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'status_resultadopesquisa' => $request->get('resultadopesquisa'),
        'status_status' => $request->get('status'),
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'status_resultadopesquisa' => $request->get('resultadopesquisa'),
        'status_status' => $request->get('status'));
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '1')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Status] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

    
    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.step1", ["codigo" => $codigo, "numero" => $numero]);


   }

   public function nucleo_finalizarimovel(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $pesquisa = $request->get('pesquisa');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'imovel_pesquisa' => $pesquisa,
        'imovel_resultado' => $resultadopesquisa,
        'imovel_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'imovel_pesquisa' => $pesquisa,
        'imovel_resultado' => $resultadopesquisa,
        'imovel_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '2')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Imóvel] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

    
    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabimovel", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarveiculo(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $pesquisa = $request->get('pesquisa');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'veiculo_pesquisa' => $pesquisa,
        'veiculo_resultado' => $resultadopesquisa,
        'veiculo_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'veiculo_pesquisa' => $pesquisa,
        'veiculo_resultado' => $resultadopesquisa,
        'veiculo_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '3')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Veículo] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

    
    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabveiculo", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarempresa(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $pesquisa = $request->get('pesquisa');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'empresa_pesquisa' => $pesquisa,
        'empresa_resultado' => $resultadopesquisa,
        'empresa_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'empresa_pesquisa' => $pesquisa,
        'empresa_resultado' => $resultadopesquisa,
        'empresa_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '4')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Empresa] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

    
    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabempresa", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarinfojud(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'infojud_data' => $data,
        'infojud_resultado' => $resultadopesquisa,
        'infojud_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'infojud_data' => $data,
        'infojud_resultado' => $resultadopesquisa,
        'infojud_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '5')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Infojud] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

    
    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabinfojud", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarbacenjud(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $pesquisa = $request->get('pesquisa');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'bacenjud_pesquisa' => $pesquisa,
        'bacenjud_resultado' => $resultadopesquisa,
        'bacenjud_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'bacenjud_pesquisa' => $pesquisa,
        'bacenjud_resultado' => $resultadopesquisa,
        'bacenjud_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '6')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Bacenjud] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

    
    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabbacenjud", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarprotesto(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'protesto_data' => $data,
        'protesto_resultado' => $resultadopesquisa,
        'protesto_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'protesto_data' => $data,
        'protesto_resultado' => $resultadopesquisa,
        'protesto_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '7')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Protesto] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }


    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabprotestos", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarredesocial(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'redesocial_data' => $data,
        'redesocial_resultado' => $resultadopesquisa,
        'redesocial_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'redesocial_data' => $data,
        'redesocial_resultado' => $resultadopesquisa,
        'redesocial_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '8')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Rede Social] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }


    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabredessociais", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarprocessojudicial(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'processojudicial_data' => $data,
        'processojudicial_resultado' => $resultadopesquisa,
        'processojudicial_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'processojudicial_data' => $data,
        'processojudicial_resultado' => $resultadopesquisa,
        'processojudicial_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '9')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Processos Judiciais] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }


    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabprocessosjudiciais", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarpesquisacadastral(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'pesquisacadastral_data' => $data,
        'pesquisacadastral_resultado' => $resultadopesquisa,
        'pesquisacadastral_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'pesquisacadastral_data' => $data,
        'pesquisacadastral_resultado' => $resultadopesquisa,
        'pesquisacadastral_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '10')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Pesquisa Cadastral] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }


    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabpesquisa", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizardossiecomercial(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'dossiecomercial_data' => $data,
        'dossiecomercial_resultado' => $resultadopesquisa,
        'dossiecomercial_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'dossiecomercial_data' => $data,
        'dossiecomercial_resultado' => $resultadopesquisa,
        'dossiecomercial_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '11')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Dossiê Comercial] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }


    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabdossiecomercial", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizardiversos(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $data = $request->get('data');
    $resultadopesquisa = $request->get('resultado');
    $status = $request->get('status');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'diversos_data' => $data,
        'diversos_resultado' => $resultadopesquisa,
        'diversos_status' => $status
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'diversos_data' => $data,
        'diversos_resultado' => $resultadopesquisa,
        'diversos_status' => $status);
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '14')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Diversos] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }


    flash('Pesquisa finalizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabdiversos", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarmoeda(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'moeda_data' => $request->get('data'),
        'moeda_resultado' => $request->get('resultado'),
        'moeda_status' => $request->get('valor')
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'moeda_data' => $request->get('data'),
        'moeda_resultado' => $request->get('resultado'),
        'moeda_status' => $request->get('valor'));
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '16')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Moeda] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

        flash('Pesquisa atualizada com sucesso !')->success();   
        return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabmoeda", ["codigo" => $codigo, "numero" => $numero]);

   }

   public function nucleo_finalizarjoias(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'joias_data' => $request->get('data'),
        'joias_resultado' => $request->get('resultado'),
        'joias_status' => $request->get('status')
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'joias_data' => $request->get('data'),
        'joias_resultado' => $request->get('resultado'),
        'joias_status' => $request->get('status'));
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

        //Se o status for concluido
        if($status == "Conclúida") {

          $descricao = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('descricao')->where('id', '15')->value('descricao');
          $solicitanteid = DB::table('dbo.PesquisaPatrimonial_Matrix')->select('solicitante_id')->where('id', $numero)->value('solicitante_id');
          $solicitanteemail = DB::table('dbo.users')->select('email')->where('id','=', $solicitanteid)->value('email');
      
          //Envia e-mail ao solicitante informando que uma aba da pesquisa foi finalizada
          Mail::to($solicitanteemail)
          ->cc('vanessa.ferreira@plcadvogados.com.br', 'felipe.rocha@especialistaresultados.com.br')
          ->send(new AbaFinalizada($numero, $descricao));
      
          //Envia notificação
          $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '2', 'obs' => 'Pesquisa patrimonial: Aba [Joias] foi finalizada pelo núcleo.' ,'status' => 'A');
          DB::table('dbo.Hist_Notificacao')->insert($values3);

        }

        flash('Pesquisa atualizada com sucesso !')->success();   
        return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabjoias", ["codigo" => $codigo, "numero" => $numero]);
  

   }

   public function nucleo_finalizarscorecard(Request $request) {

    $codigo = $request->get('codigo');
    $numero = $request->get('numero');
    $usuarioid = Auth::user()->id;
    $carbon= Carbon::now();

    $total = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->where('codigo', '=', $codigo)->count();

    if($total != 0) {

      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')
      ->where('codigo', $codigo)  
      ->limit(1) 
      ->update(array(
        'user_id' => $usuarioid, 
        'scorecard_resultado' => $request->get('resultadopesquisa'),
        'scorecard_restricao' => $request->get('restricao'),
        'scorecard_valor' => $request->get('valor')
      ));

    }
    //Não existe da insert
    else {

      $values= array(
        'codigo' => $codigo, 
        'user_id' => $usuarioid, 
        'scorecard_resultado' => $request->get('resultadopesquisa'),
        'scorecard_restricao' => $request->get('restricao'),
        'scorecard_valor' => $request->get('valor'));
      DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->insert($values);

    }

    
    flash('Pesquisa atualizada com sucesso !')->success();   
    return redirect()->route("Painel.PesquisaPatrimonial.nucleo.tabscorecard", ["codigo" => $codigo, "numero" => $numero]);


   }


   public function relatoriopesquisa($codigo){

    $datas = DB::table('PLCFULL.dbo.Jurid_Outra_Parte')
    ->select(
             'PLCFULL.dbo.Jurid_Outra_Parte.Descricao as Nome',
             'PLCFULL.dbo.Jurid_Outra_Parte.Endereco as Endereco',
             'PLCFULL.dbo.Jurid_Outra_Parte.Bairro as Bairro',
             'PLCFULL.dbo.Jurid_Outra_Parte.Cidade as Cidade',
             'PLCFULL.dbo.Jurid_Outra_Parte.UF as Estado',
             'PLCFULL.dbo.Jurid_Outra_Parte.Cep as CEP',
             'PLCFULL.dbo.Jurid_Outra_Parte.DataNasc as DataNascimento',
             'PLCFULL.dbo.Jurid_Outra_Parte.Celular as Celular',
             'PLCFULL.dbo.Jurid_Outra_Parte.E_Mail as Email',
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo', '=', 'dbo.PesquisaPatrimonial_Matrix.codigo')  
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
    ->where('PLCFULL.dbo.Jurid_Outra_Parte.codigo','=', $codigo)
    ->first();

   $QuantidadeImovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
                                    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
                                    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
                                    ->count();

    $ValorImovel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
            DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel) as ValorTotal'))
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
    ->groupby('dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel')
    ->value('dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel');  
            
    $ValorVeiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
              DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel) as ValorTotal'))
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
    ->groupby('dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel')
    ->value('dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel');   

    $ValorEmpresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
              DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel) as ValorTotal'))
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
    ->groupby('dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel')
    ->value('dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel');   
    
                                    
    $QuantidadeVeiculo = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
                                    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
                                    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
                                    ->count();

    $QuantidadeEmpresa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
                                    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
                                    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
                                    ->count();               
                              
    $ValorTotal = $ValorImovel + $ValorEmpresa + $ValorVeiculo;
  
   
   //Veiculo 
   $veiculo_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor))'));
   $veiculo_soma = number_format((float)$veiculo_soma, 2, '.', '');      

   $veiculo_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_avaliacaoplc))'));
   $veiculo_avaliacaoplc = number_format((float)$veiculo_avaliacaoplc, 2, '.', '');      
   
   $veiculo_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_base))'));
   $veiculo_valorbase = number_format((float)$veiculo_valorbase, 2, '.', '');      
   //Fim Veiculo

   //Imovel
   $imovel_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_avaliacaoplc))'));
   $imovel_avaliacaoplc = number_format((float)$imovel_avaliacaoplc, 2, '.', ''); 

   $imovel_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor))'));
   $imovel_soma = number_format((float)$imovel_soma, 2, '.', ''); 

   $imovel_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_base))'));
   $imovel_valorbase = number_format((float)$imovel_valorbase, 2, '.', ''); 
   //Fim Imovel


   //Empresa
   $empresa_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_avaliacaoplc))'));
   $empresa_avaliacaoplc = number_format((float)$empresa_avaliacaoplc, 2, '.', ''); 

   $empresa_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial))'));
   $empresa_soma = number_format((float)$empresa_soma, 2, '.', ''); 

   $empresa_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_base))'));
   $empresa_valorbase = number_format((float)$empresa_valorbase, 2, '.', ''); 
   //Fim Empresa

   //Diversos
   $diversos_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_avaliacaoplc))'));
   $diversos_avaliacaoplc = number_format((float)$diversos_avaliacaoplc, 2, '.', ''); 
   
   $diversos_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor))'));
   $diversos_soma = number_format((float)$diversos_soma, 2, '.', ''); 

   $diversos_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_base))'));
   $diversos_valorbase = number_format((float)$diversos_valorbase, 2, '.', ''); 
   
   //Fim Diversos


   //Moeda
   $moeda_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_avaliacaoplc))'));
   $moeda_avaliacaoplc = number_format((float)$moeda_avaliacaoplc, 2, '.', ''); 
   
   $moeda_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor))'));
   $moeda_soma = number_format((float)$moeda_soma, 2, '.', ''); 

   $moeda_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Moeda.valor_base))'));
   $moeda_valorbase = number_format((float)$moeda_valorbase, 2, '.', ''); 
   //Fim Moeda


   //Joias
   $joias_avaliacaoplc = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_avaliacaoplc))'));
   $joias_avaliacaoplc = number_format((float)$joias_avaliacaoplc, 2, '.', ''); 
   
   $joias_soma = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor))'));
   $joias_soma = number_format((float)$joias_soma, 2, '.', ''); 

   $joias_valorbase = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Joias.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Joias.valor_base))'));
   $joias_valorbase = number_format((float)$joias_valorbase, 2, '.', ''); 
   //Fim Joias

   $somaavaliacaoplc = $veiculo_avaliacaoplc + $imovel_avaliacaoplc + $empresa_avaliacaoplc + $diversos_avaliacaoplc + $moeda_avaliacaoplc + $joias_avaliacaoplc;
   $somabruto = $veiculo_soma + $imovel_soma + $empresa_soma + $empresa_soma + $diversos_soma + $moeda_soma + $joias_soma;
   $somavalorbase =   $veiculo_valorbase + $imovel_valorbase + $empresa_valorbase + $diversos_valorbase + $moeda_valorbase + $joias_valorbase;    

   $valordivida = DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')->select(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa)'))->where('CPF_CNPJ', $codigo)->value(DB::raw('sum(PLCFULL.dbo.Jurid_Pastas.ValorCausa))'));
   $valordivida = number_format((float)$valordivida, 2, '.', ''); 


   //Se o valor da divida for vazio
   if($valordivida == 0.00) {
    $porcentagem = '100.00';
   }

  else {
    $porcentagem = $somaavaliacaoplc / $valordivida;

  }
    
    //Se a porcentagem for entre 0 e 50
    if($porcentagem <= 50.00) {

      $score = '0.00';

    }
    //Se a porcentagem for maior ou igual a 130
    elseif($porcentagem >= 130.00) {

      $score = '100.00';
   
    } else {
       
      $score = $porcentagem * 100 / 200;
    }

    return view('Painel.PesquisaPatrimonial.solicitacao.relatoriopesquisa', compact('codigo','ValorTotal','ValorImovel','ValorVeiculo','ValorEmpresa','datas','QuantidadeEmpresa','QuantidadeImovel', 'QuantidadeVeiculo','score'));

   }

   public function solicitacao_verpesquisastatus($codigo) {

    $carbon= Carbon::now();
      
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

     $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_OutraParte')
     ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_status', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Clientes', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.grupocliente', '=', 'PesquisaPatrimonial_Clientes.id_referencia')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
       'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
       'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.id as Id',
       'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.data as Data',
       'dbo.PesquisaPatrimonial_Clientes.descricao as Cliente',
       'dbo.PesquisaPatrimonial_Solicitacao_OutraParte.classificacao as Classificacao',
       'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as Status')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
     ->get();

      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('status_resultadopesquisa')->where('codigo','=', $codigo)->value('status_resultadopesquisa'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('status_status')->where('codigo','=', $codigo)->value('status_status'); 

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.status', compact('codigo','resultadopesquisa','statuspesquisa','datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisaimovel($codigo) {

    $carbon= Carbon::now();
        
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
  
       $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')
       ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.user_id', '=', 'dbo.users.id')
       ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
       ->leftjoin('dbo.PesquisaPatrimonial_Imovel_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.tipoimovel_id', '=', 'dbo.PesquisaPatrimonial_Imovel_Tipos.id')
       ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
       ->select(
        'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.id as Id',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.matricula as matricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datamatricula as datamatricula',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cep as cep',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.rua as logradouro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.bairro as bairro',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.cidade as cidade',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.uf as uf',
        'dbo.PesquisaPatrimonial_Imovel_Tipos.descricao as tipodescricao',
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor AS NUMERIC(15,2)) as valor'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'),
        DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'),
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.aberbacao828 as averbacao828',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.carta',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datacarta', 
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.averbacaopenhora as penhora',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.datarequerimento as datarequerimento',
        'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status',
        'dbo.PesquisaPatrimonial_Solicitacao_Imovel.impedimento as restricao')
        ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
        ->get();

        $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('imovel_pesquisa')->where('codigo','=', $codigo)->value('imovel_pesquisa'); 
        $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('imovel_resultado')->where('codigo','=', $codigo)->value('imovel_resultado'); 
        $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('imovel_status')->where('codigo','=', $codigo)->value('imovel_status'); 
  
  
      return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.imovel', compact('pesquisa','resultadopesquisa', 'statuspesquisa','codigo','datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisaveiculo($codigo) {
    $carbon= Carbon::now();

      
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

     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.user_id', '=', 'dbo.users.id')
    ->leftjoin('dbo.PesquisaPatrimonial_StatusSolicitacao', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.status_id', '=', 'dbo.PesquisaPatrimonial_StatusSolicitacao.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Veiculos_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.tipoveiculo_id', '=', 'dbo.PesquisaPatrimonial_Veiculos_Tipos.id')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.placa as placa',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.fabricante_id as fabricante',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.combustivel as combustivel',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.modelo as modelo',
      'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipoveiculo',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.descricaoveiculo as descricaoveiculo',
      'dbo.PesquisaPatrimonial_Veiculos_Tipos.descricao as tipodescricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anomodelo as anomodelo',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.anofabricacao as anofabricacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.impedimento as impedimento',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor', 
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_alienacao', 
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel', 
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacao828 as averbacao828',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.averbacaopenhora as penhora',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.dataaverbacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.carta',
      'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.datacarta',
      'dbo.PesquisaPatrimonial_StatusSolicitacao.descricao as status')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)  
     ->get();

     $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('veiculo_pesquisa')->where('codigo','=', $codigo)->value('veiculo_pesquisa'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('veiculo_resultado')->where('codigo','=', $codigo)->value('veiculo_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('veiculo_status')->where('codigo','=', $codigo)->value('veiculo_status'); 


    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.veiculo', compact('pesquisa', 'resultadopesquisa', 'statuspesquisa','codigo', 'datas','totalNotificacaoAbertas', 'notificacoes'));
   }

   public function solicitacao_verpesquisaempresa($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cnpj as codigo',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.razaosocial as razao',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.nomefantasia as nomefantasia',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.objetosocial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.situacao',
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.capitalsocial AS NUMERIC(15,2)) as capitalsocial'), 
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao AS NUMERIC(15,2)) as valor_alienacao'), 
      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel AS NUMERIC(15,2)) as valor_disponivel'), 
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datafundacao as datafundacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.penhoracotas as penhora',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.quantidadecotas',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.datapenhoracotas',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.cep',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.logradouro',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.numero as complemento',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.bairro',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.municipio as cidade',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.uf',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.participacaosocietaria as participacaosocio',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacaojudicial as recuperacaojudicial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.recuperacao as recuperacaoextrajudicial',
      'dbo.PesquisaPatrimonial_Solicitacao_Empresa.falencia as falencia')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
      ->get();


      $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('empresa_pesquisa')->where('codigo','=', $codigo)->value('empresa_pesquisa'); 
      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('empresa_resultado')->where('codigo','=', $codigo)->value('empresa_resultado'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('empresa_status')->where('codigo','=', $codigo)->value('empresa_status'); 
 
 
     return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.empresa', compact('pesquisa', 'resultadopesquisa', 'statuspesquisa','codigo','datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisainfojud($codigo) {

    
    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_InfoJud')
     ->join('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.user_id', '=', 'dbo.users.id')
     ->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix', 
      'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.id as id',
      'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_InfoJud.descricao as descricao')
      ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
      ->get();

      $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('infojud_data')->where('codigo','=', $codigo)->value('infojud_data'); 
      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('infojud_resultado')->where('codigo','=', $codigo)->value('infojud_resultado'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('infojud_status')->where('codigo','=', $codigo)->value('infojud_status'); 


    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.infojud', compact('codigo','datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function solicitacao_verpesquisabacenjud($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Bacenjud')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.id as id',
      'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Bacenjud.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

      $pesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('bacenjud_pesquisa')->where('codigo','=', $codigo)->value('bacenjud_pesquisa'); 
      $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('bacenjud_resultado')->where('codigo','=', $codigo)->value('bacenjud_resultado'); 
      $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('bacenjud_status')->where('codigo','=', $codigo)->value('bacenjud_status'); 

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.bacenjud', compact('codigo','datas','totalNotificacaoAbertas', 'notificacoes', 'pesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function solicitacao_verpesquisaprotesto($codigo) {

    $carbon= Carbon::now();
      
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


      $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Notas.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.valor',
      // 'dbo.PesquisaPatrimonial_Solicitacao_Notas.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Notas.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo)
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('protesto_data')->where('codigo','=', $codigo)->value('protesto_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('protesto_resultado')->where('codigo','=', $codigo)->value('protesto_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('protesto_status')->where('codigo','=', $codigo)->value('protesto_status'); 

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.protestos', compact('codigo', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function solicitacao_verpesquisaredessociais($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_RedesSociais')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->leftjoin('dbo.PesquisaPatrimonial_RedesSociais_Tipos', 'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.rede_id', '=', 'dbo.PesquisaPatrimonial_RedesSociais_Tipos.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.id as id',
      'dbo.PesquisaPatrimonial_RedesSociais_Tipos.nome as redesocial',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.status as status',
      'dbo.PesquisaPatrimonial_Solicitacao_RedesSociais.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('redesocial_data')->where('codigo','=', $codigo)->value('redesocial_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('redesocial_resultado')->where('codigo','=', $codigo)->value('redesocial_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('redesocial_status')->where('codigo','=', $codigo)->value('redesocial_status'); 

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.redessociais', compact('codigo', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa','resultadopesquisa', 'statuspesquisa'));

   }

   public function solicitacao_verpesquisaprocessosjudiciais($codigo) {
    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.valor',
      // 'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Tribunal.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('processojudicial_data')->where('codigo','=', $codigo)->value('processojudicial_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('processojudicial_resultado')->where('codigo','=', $codigo)->value('processojudicial_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('processojudicial_status')->where('codigo','=', $codigo)->value('processojudicial_status'); 

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.processosjudiciais', compact('codigo', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }
   
   public function solicitacao_verpesquisapesquisacadastral($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Pesquisa')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.id as id',
      'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Pesquisa.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('pesquisacadastral_data')->where('codigo','=', $codigo)->value('pesquisacadastral_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('pesquisacadastral_resultado')->where('codigo','=', $codigo)->value('pesquisacadastral_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('pesquisacadastral_status')->where('codigo','=', $codigo)->value('pesquisacadastral_status'); 

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.pesquisacadastral', compact('codigo', 'datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function solicitacao_verpesquisadossiecomercial($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comercial')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Comercial.id as id',
      'dbo.PesquisaPatrimonial_Solicitacao_Comercial.date as data',
      'dbo.PesquisaPatrimonial_Solicitacao_Comercial.descricao as descricao')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();

     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('dossiecomercial_data')->where('codigo','=', $codigo)->value('dossiecomercial_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('dossiecomercial_resultado')->where('codigo','=', $codigo)->value('dossiecomercial_data'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('dossiecomercial_status')->where('codigo','=', $codigo)->value('dossiecomercial_status'); 
  

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.dossiecomercial', compact('codigo','datas','totalNotificacaoAbertas', 'notificacoes', 'datapesquisa', 'resultadopesquisa', 'statuspesquisa'));

   }

   public function solicitacao_verpesquisadados($codigo) {

    $carbon= Carbon::now();
      
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

  
   $datas =  DB::table('PLCFULL.dbo.Jurid_Litis_NaoCliente')
    ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Litis_NaoCliente.Codigo_Comp', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
    ->leftjoin('PLCFULL.dbo.Jurid_TipoP', 'PLCFULL.dbo.Jurid_Pastas.TipoP', '=', 'PLCFULL.dbo.Jurid_TipoP.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
    ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
    ->select('PLCFULL.dbo.Jurid_Litis_NaoCliente.CPF_CNPJ as Codigo',
             'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
             'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as GrupoFinanceiro',
             'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
             'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
             'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as NumeroPasta',
             DB::raw('CAST(PLCFULL.dbo.Jurid_Pastas.ValorCausa AS NUMERIC(15,2)) as ValorCausa'),
             'PLCFULL.dbo.Jurid_TipoP.Descricao as TipoProjeto')
    ->where('CPF_CNPJ', '=', $codigo) 
    ->get();

  

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.dados', compact('codigo','datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisascore($codigo) {

    $carbon= Carbon::now();
      
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

     //Veiculo 
     $veiculo_disponivel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_disponivel))'));
     $veiculo_disponivel = number_format((float)$veiculo_disponivel, 2, '.', ''); 
     $veiculo_alienacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Veiculo')->join('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Veiculo.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.valor_alienacao)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(dbo.PesquisaPatrimonial_Solicitacao_Veiculo.alienacao))'));
     $veiculo_alienacao = number_format((float)$veiculo_alienacao, 2, '.', ''); 
     $veiculo_peso = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('peso')->where('id','=', '3')->value('peso'); 
     $veiculo_meta = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('meta')->where('id','=', '3')->value('meta'); 
     
     //1 verificação vejo se o valor da alienação for maior que bem disponivel, ele vai fazer a proporção
     if($veiculo_alienacao > $veiculo_disponivel) {

      $score_veiculo = $veiculo_peso * $veiculo_meta;
      $score_veiculo =  -1 * $veiculo_disponivel / $score_veiculo;
      $score_veiculo = number_format((float)$score_veiculo, 2, '.', ''); 
    
      
     }
     else if($veiculo_disponivel >= $veiculo_alienacao) {

      $score_veiculo = $veiculo_peso * $veiculo_meta;

     }
     //Se não tiver registro fala que o score_veiculo é 0.00
     else {

       $score_veiculo = 0.00;

     }
     //Fim Veiculo

     //Imovel
     $imovel_disponivel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_disponivel))'));
     $imovel_disponivel = number_format((float)$imovel_disponivel, 2, '.', ''); 
     $imovel_alienacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Imovel')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Imovel.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Imovel.valor_alienacao))'));
     $imovel_alienacao = number_format((float)$imovel_alienacao, 2, '.', ''); 
     $imovel_peso = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('peso')->where('id','=', '2')->value('peso'); 
     $imovel_meta = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('meta')->where('id','=', '2')->value('meta'); 

     //1 verificação vejo se o valor da alienação for maior que bem disponivel, ele vai fazer a proporção
     if($imovel_alienacao > $imovel_disponivel) {

      $score_imovel = $imovel_peso * $imovel_meta;
      $score_imovel =  -1 * $imovel_disponivel / $score_imovel;
      $score_imovel = number_format((float)$score_imovel, 2, '.', ''); 

     }
     else if($imovel_disponivel >= $imovel_alienacao) {

      $score_imovel = $imovel_peso * $imovel_meta;

     }
     //Se não tiver registro fala que o score_veiculo é 0.00
     else {

       $score_imovel = 0.00;

     }
     //Fim Imovel

     //Empresa
     $empresa_disponivel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_disponivel))'));
     $empresa_disponivel = number_format((float)$empresa_disponivel, 2, '.', ''); 
     $empresa_alienacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Empresa')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Empresa.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Empresa.valor_alienacao))'));
     $empresa_alienacao = number_format((float)$empresa_alienacao, 2, '.', ''); 
     $empresa_peso = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('peso')->where('id','=', '4')->value('peso'); 
     $empresa_meta = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('meta')->where('id','=', '4')->value('meta'); 


     //1 verificação vejo se o valor da alienação for maior que bem disponivel, ele vai fazer a proporção
     if($empresa_alienacao > $empresa_disponivel) {

      $score_empresa = $empresa_peso * $empresa_meta;
      $score_empresa =  -1 * $empresa_disponivel / $score_empresa;
      $score_empresa = number_format((float)$score_empresa, 2, '.', ''); 
     }
     else if($empresa_disponivel >= $empresa_alienacao) {

      $score_empresa = $empresa_peso * $empresa_meta;

     }
     //Se não tiver registro fala que o score_veiculo é 0.00
     else {

       $score_empresa = 0.00;

     }
     //Fim Empresa

     //Diversos
     $diversos_disponivel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_disponivel)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_disponivel))'));
     $diversos_disponivel = number_format((float)$diversos_disponivel, 2, '.', ''); 
     $diversos_alienacao = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_alienacao)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Diversos.valor_alienacao))'));
     $diversos_alienacao = number_format((float)$diversos_alienacao, 2, '.', ''); 
     $diversos_peso = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('peso')->where('id','=', '14')->value('peso'); 
     $diversos_meta = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('meta')->where('id','=', '14')->value('meta'); 


     //1 verificação vejo se o valor da alienação for maior que bem disponivel, ele vai fazer a proporção
     if($diversos_alienacao > $diversos_disponivel) {

      $score_diversos = $diversos_peso * $diversos_meta;
      $score_diversos =  -1 * $diversos_disponivel / $score_diversos;
      $score_diversos = number_format((float)$score_diversos, 2, '.', ''); 
     }
     else if($diversos_disponivel >= $diversos_alienacao) {

      $score_diversos = $diversos_peso * $diversos_meta;

     }
     //Se não tiver registro fala que o score_veiculo é 0.00
     else {

       $score_diversos = 0.00;

     }
     //Fim Diversos

     //Protestos
     $protestos_disponivel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Notas')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Notas.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Notas.valor_disponivel)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Notas.valor_disponivel))'));
     $protestos_disponivel = number_format((float)$protestos_disponivel, 2, '.', ''); 
     $protestos_peso = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('peso')->where('id','=', '7')->value('peso'); 
     $protestos_meta = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('meta')->where('id','=', '7')->value('meta'); 

    //1 verificação vejo se o valor da alienação for maior que bem disponivel, ele vai fazer a proporção
     if($protestos_disponivel != '0.00') {

      $score_protestos = $protestos_peso * $protestos_meta;

     }
     //Se não tiver registro fala que o score_veiculo é 0.00
     else {

       $score_protestos = 0.00;

     }
     //Fim Protestos

     //Processos Judiciais 
     $processos_disponivel = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Tribunal')->join('dbo.PesquisaPatrimonial_Matrix', 'PesquisaPatrimonial_Solicitacao_Tribunal.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')->select(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Tribunal.valor_disponivel)'))->where('dbo.PesquisaPatrimonial_Matrix.codigo', $codigo)->value(DB::raw('sum(PesquisaPatrimonial_Solicitacao_Tribunal.valor_disponivel))'));
     $processos_disponivel = number_format((float)$processos_disponivel, 2, '.', ''); 
     $processos_peso = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('peso')->where('id','=', '9')->value('peso'); 
     $processos_meta = DB::table('dbo.PesquisaPatrimonial_Servicos_UF')->select('meta')->where('id','=', '9')->value('meta'); 


    //1 verificação vejo se o valor da alienação for maior que bem disponivel, ele vai fazer a proporção
     if($processos_disponivel != '0.00') {

      $score_processos = $processos_peso * $processos_meta;

     }
     //Se não tiver registro fala que o score_veiculo é 0.00
     else {

       $score_processos = 0.00;

     }
     //Fim Protestos

      $score = $score_veiculo + $score_imovel + $score_empresa + $score_diversos + $score_processos + $score_protestos;
      $scorefaltante = 100 - $score ;

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.scorecard', compact('codigo', 'score','scorefaltante','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisadiversos($codigo) {


    $carbon= Carbon::now();
      
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

  
     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Diversos')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.data',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor as valor',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Diversos.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('diversos_data')->where('codigo','=', $codigo)->value('diversos_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('diversos_resultado')->where('codigo','=', $codigo)->value('diversos_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('diversos_status')->where('codigo','=', $codigo)->value('diversos_status'); 
  

    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.diversos', compact('codigo','datapesquisa','resultadopesquisa','statuspesquisa','datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisamoeda($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Moeda')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.data',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.tipo',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor as valor',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Moeda.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('moeda_data')->where('codigo','=', $codigo)->value('moeda_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('moeda_resultado')->where('codigo','=', $codigo)->value('moeda_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('moeda_status')->where('codigo','=', $codigo)->value('moeda_status'); 

     return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.moeda', compact('codigo','datapesquisa','resultadopesquisa','statuspesquisa','datas','totalNotificacaoAbertas', 'notificacoes'));

   }

   public function solicitacao_verpesquisajoias($codigo) {

    $carbon= Carbon::now();
      
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


     $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Joias')
     ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.user_id', '=', 'dbo.users.id')
     ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Joias.id_matrix', '=', 'dbo.PesquisaPatrimonial_Matrix.id')
     ->select(
      'dbo.PesquisaPatrimonial_Matrix.id as id_matrix',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.id as Id',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.data',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.tipo',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.descricao as descricao',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor as valor',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_alienacao',
      'dbo.PesquisaPatrimonial_Solicitacao_Joias.valor_disponivel')
     ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
     ->get();
  
     $datapesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('joias_data')->where('codigo','=', $codigo)->value('joias_data'); 
     $resultadopesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('joias_resultado')->where('codigo','=', $codigo)->value('joias_resultado'); 
     $statuspesquisa = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Informacoes')->select('joias_status')->where('codigo','=', $codigo)->value('joias_status'); 


    return view('Painel.PesquisaPatrimonial.solicitacao.pesquisa.joias', compact('codigo','datapesquisa','resultadopesquisa','statuspesquisa','datas','totalNotificacaoAbertas', 'notificacoes'));

   }



   public function ver_anexos($codigo) {


    $datas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->get();

    $QuantidadeAnexos = $datas->count();

    $statuss =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '1')
    ->get();
    $QuantidadeAnexosStatus = $statuss->count();

    $imovels =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '2')
    ->get();
    $QuantidadeAnexosImovel = $imovels->count();

    $veiculos =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '3')
    ->get();
    $QuantidadeAnexosVeiculo = $veiculos->count();

    $empresas =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '4')
    ->get();
    $QuantidadeAnexosEmpresa = $empresas->count();

    $infojuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '5')
    ->get();
    $QuantidadeAnexosInfojud = $infojuds->count();

    $bacenjuds =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '6')
    ->get();
    $QuantidadeAnexosBacenjud = $bacenjuds->count();

    $protestoss =  DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '7')
    ->get();
    $QuantidadeAnexosProtesto = $protestoss->count();

    $processosjudiciaiss = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '9')
    ->get();
    $QuantidadeAnexosProcessosJudiciais = $processosjudiciaiss->count();

    $pesquisacadastrals = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '10')
    ->get();
    $QuantidadeAnexosPesquisaCadastral = $pesquisacadastrals->count();

    $dossiecomercials = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '11')
    ->get();
    $QuantidadeAnexosDossieComercial = $dossiecomercials->count();

    $diversoss = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Anexos')
    ->leftjoin('dbo.PesquisaPatrimonial_Matrix', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.id_matrix', 'dbo.PesquisaPatrimonial_Matrix.id')
    ->leftjoin('dbo.users', 'dbo.PesquisaPatrimonial_Solicitacao_Anexos.user_id', 'dbo.users.id')
    ->select('anexo as nome', 
            'formato',
            'dbo.PesquisaPatrimonial_Solicitacao_Anexos.data',
            'dbo.users.name as Responsavel')
    ->where('dbo.PesquisaPatrimonial_Matrix.codigo', '=', $codigo) 
    ->where('dbo.PesquisaPatrimonial_Solicitacao_Anexos.tiposervico_id', '11')
    ->get();
    $QuantidadeAnexosDiversos = $diversoss->count();

    return view('Painel.PesquisaPatrimonial.veranexos', compact('codigo',
                                                                'datas', 
                                                                'QuantidadeAnexos',
                                                                'statuss',
                                                                'QuantidadeAnexosStatus',
                                                                'imovels',
                                                                'QuantidadeAnexosImovel',
                                                                'veiculos',
                                                                'QuantidadeAnexosVeiculo',
                                                                'empresas',
                                                                'QuantidadeAnexosEmpresa',
                                                                'infojuds',
                                                                'QuantidadeAnexosInfojud',
                                                                'bacenjuds',
                                                                'QuantidadeAnexosBacenjud',
                                                                'protestoss',
                                                                'QuantidadeAnexosProtesto',
                                                                'processosjudiciaiss',
                                                                'QuantidadeAnexosProcessosJudiciais',
                                                                'pesquisacadastrals',
                                                                'QuantidadeAnexosPesquisaCadastral',
                                                                'dossiecomercials',
                                                                'QuantidadeAnexosDossieComercial',
                                                                'diversoss',
                                                                'QuantidadeAnexosDiversos',

                                                                 ));


   }

   public function baixaranexo($caminho) {

    return Storage::disk('pesquisapatrimonial-sftp')->download($caminho);


   }
   
    public function indexprestacao(){
      
      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');    

      $gruposcliente = DB::table('dbo.PesquisaPatrimonial_Grupos')
      ->orderby('descricao', 'asc')
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

      $clientes = DB::table('PLCFULL.dbo.Jurid_CliFor')
                  ->select('Codigo', 'Razao')
                  ->where('status', '=', 'Ativo')
                  ->orderby('Razao', 'asc')
                  ->get();

      return view('Painel.PesquisaPatrimonial.financeiro.prestacaodecontas.index', compact('datahoje','gruposcliente','totalNotificacaoAbertas', 'notificacoes', 'clientes'));
    }

    public function solicitacao_buscaclientes(Request $request) {

      $grupocliente = $request->get('grupocliente');

      $response = DB::table('PLCFULL.dbo.Jurid_CliFor')
      ->select('Codigo', 'Razao', 'id_cliente')
      ->where('Razao', 'not like', "%USO EXCLUSIVO CONTROLADORIA%")
      ->where('Razao', 'not like', "%USO EXCLUSIVO DA CONTROLADORIA%")
      ->where('GrupoCli', $grupocliente)
      ->orderby('Razao', 'asc')
      ->get();

      echo $response;

    }

    public function prestacaodecontas_buscacliente(Request $request) {

      $grupocliente = $request->get('grupocliente');

      $response = DB::table('PLCFULL.dbo.Jurid_CliFor')
      ->select('Codigo', 'Razao')  
      ->where('Status', 'Ativo')
      ->where('GrupoCli', $grupocliente)
      ->orderby('Razao', 'asc')
      ->get();
      
      echo $response;
    }

   public function prestacaodecontas(Request $request){

    $imagick = new Imagick();

    $grupocliente = $request->get('grupocliente');
    $cliente = $request->get('cliente');
    $datainicio = $request->get('datainicio');
    $datafim = $request->get('datafim');
    $carbon= Carbon::now();

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
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo')
    ->first();

    $debites = DB::table('PLCFULL.dbo.Jurid_Debite')
    ->select('PLCFULL.dbo.Jurid_Debite.Numero',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao',
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
             'PLCFULL.dbo.Jurid_Debite.Data',
             'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
             'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
             'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
             'PLCFULL.dbo.Jurid_Advogado.Nome as AdvogadoNome',
             'PLCFULL.dbo.Jurid_Debite.Pasta',
             'PLCFULL.dbo.Jurid_Debite.Usuario')
    ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->join('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
    ->join('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
    ->where('PLCFULL.dbo.Jurid_Debite.Cliente', '=', $cliente)
    ->whereBetween('PLCFULL.dbo.Jurid_Debite.Data', [$datainicio, $datafim])
    ->where('PLCFULL.dbo.Jurid_Debite.Obs', 'LIKE', '%'. 'Pesquisa patrimonial' . '%')
    ->get();

    // dd($dados);

    // $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('Painel.PesquisaPatrimonial.financeiro.PrestacaoDeContas.prestacaodecontas', compact('totalNotificacaoAbertas', 'notificacoes', 'dados'));
    // return $pdf->stream(); 

    return view('Painel.PesquisaPatrimonial.financeiro.PrestacaoDeContas.relatorio', compact('carbon','totalNotificacaoAbertas', 'notificacoes','datainicio','datafim','datas', 'debites'));
   }
  
  }