<?php

namespace App\Http\Controllers\Painel\Contratacao;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contratacao\Coordenador\CoordenadorRevisar;
use App\Mail\Contratacao\Marketing\DivulgarVaga;
use App\Mail\Contratacao\CandidatoPreencherInformacoes;
use App\Mail\Contratacao\CandidatoPreencherInformacoesAdvogado;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Mail\Contratacao\RH\AprovarInformacoes;
use Illuminate\Support\Facades\Storage;
use App\Mail\NovoUsuarioCron;
use App\Mail\NovoUsuarioMarketing;
use App\Mail\Contratacao\SolicitacaoCancelada;
use App\Mail\Contratacao\Gerente\GerenteRevisar;
use App\Mail\Contratacao\SuperIntendente\SuperintendenteRevisar;
use App\Mail\Contratacao\CEO\CEORevisar;
use App\Mail\Contratacao\RH\RHEnviaDocumentacao;

class ContratacaoController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    

    public function contratacao_anexos($id) {

        //Busco os arquivos gravados
        $datas = DB::table("dbo.Contratacao_Anexos")
        ->select('dbo.Contratacao_Anexos.id', 'dbo.Contratacao_Anexos.name', 'dbo.Contratacao_Anexos.data as Data','dbo.users.name as Responsavel')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Anexos.user_id', 'dbo.users.id')
        ->where('id_matrix', $id)
        ->get();

        $QuantidadeAnexos = $datas->count();

        return view('Painel.Contratacao.anexos', compact('datas', 'QuantidadeAnexos'));

    }

    public function contratacao_baixaranexo($caminho) {

        return Storage::disk('contratacao-sftp')->download($caminho);

    }

    public function contratacao_subcoordenador_index() {

        $carbon= Carbon::now();

        $dataurgente = $carbon->modify('+2 days');
        $datafluxopadrao = $carbon->modify('+12 days');

        $dataurgente = $dataurgente->format('Y-m-d');
        $datafluxopadrao = $datafluxopadrao->format('Y-m-d');
        $datahoje = $carbon->format('Y-m-d');

        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name', 'dbo.users.cpf')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderby('dbo.users.name', 'asc')
        ->get(); 

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                         'dbo.Contratacao_Matrix.datasaida as DataSaida',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.advogado_substituido', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('dbo.Contratacao_Matrix.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(1,2,3,4,5,6,8))
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('Codigo', 'Descricao')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->where('Ativo', '=', '1')
        ->where('setor_custo_user.user_id', '=', Auth::user()->id)
        ->orderBy('Codigo', 'asc')
        ->get();

        $tiposadvogado = DB::table('PLCFULL.dbo.Jurid_GrupoAdvogado')
        ->select('Codigo', 'Descricao')  
        ->orderby('Descricao', 'asc')
        ->whereNotIn('PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo', array(1,14))
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

       
      return view('Painel.Contratacao.SubCoordenador.index', compact('datahoje','usuarios','dataurgente','datafluxopadrao','datas','setores','tiposadvogado','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_subcoordenador_historico() {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->where('dbo.Contratacao_Matrix.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(7,8))
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

       
      return view('Painel.Contratacao.SubCoordenador.historico', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_subcoordenador_solicitacaocriada(Request $request) {

        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $setorcodigo = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $tipocontratacao = $request->get('tipocontratacao');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();
        $classificacao = $request->get('classificacao');
        $dataentrada = $request->get('dataentrada');

        $usuario = $request->get('usuario');
        $datasaida = $request->get('datasaida');

        $curriculo = $request->file('curriculo');

        //Grava na Matrix
        $values3= array('user_id' => Auth::user()->id, 
                        'setor' => $setorcodigo, 
                        'salario' => $salario, 
                        'observacao' => $observacao, 
                        'tipovaga' => $tipocontratacao, 
                        'advogado_substituido' => $usuario,
                        'datasaida' => $datasaida,
                        'candidatonome' => $candidatonome,
                        'candidatoemail' => $candidatoemail,
                        'tipoadvogado' => $tipocargo,
                        'classificacao' => $classificacao,
                        'status' => '1',
                        'data_criacao' => $carbon,
                        'data_edicao' => $carbon,
                        'data_entrada' => $dataentrada);
        DB::table('dbo.Contratacao_Matrix')->insert($values3);   

        $id = DB::table('dbo.Contratacao_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '1', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3);   

        //Grava Anexos
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'data' => $carbon, 
                        'name' => $curriculo->getClientOriginalName());
        DB::table('dbo.Contratacao_Anexos')->insert($values3);   


        $curriculo->storeAs('contratacao', $curriculo->getClientOriginalName());
        Storage::disk('contratacao-local')->put($curriculo->getClientOriginalName(), fopen($curriculo, 'r+'));

        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.Contratacao_Matrix.salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.Contratacao_Matrix.tipovaga',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                 'dbo.Contratacao_Matrix.classificacao',
                 'dbo.Contratacao_Status.descricao as status',
                 'dbo.Contratacao_Matrix.data_criacao as data')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setorcodigo)->value('Id'); 

        $coordenador =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '35')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    

        $coordenador_id = $coordenador->id;
        $coordenador_email = $coordenador->email;
        $coordenador_nome = $coordenador->nome;

        Mail::to($coordenador_email)
        ->send(new CoordenadorRevisar($datas, $coordenador_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        flash('Solicitação cadastrada com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.SubCoordenador.index');

        
    }

    public function contratacao_coordenador_index() {

        $carbon= Carbon::now();

        $dataurgente = $carbon->modify('+2 days');
        $datafluxopadrao = $carbon->modify('+12 days');

        $dataurgente = $dataurgente->format('Y-m-d');
        $datafluxopadrao = $datafluxopadrao->format('Y-m-d');
        $datahoje = $carbon->format('Y-m-d');

        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name', 'dbo.users.cpf')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderby('dbo.users.name', 'asc')
        ->get(); 
          
        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                         'dbo.Contratacao_Matrix.datasaida as DataSaida',
                         'dbo.users.name as solicitantenome',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.advogado_substituido', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(1,2,3,4,5,6,8))
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('Codigo', 'Descricao')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->where('Ativo', '=', '1')
        ->where('setor_custo_user.user_id', '=', Auth::user()->id)
        ->orderBy('Codigo', 'asc')
        ->get();

        $tiposadvogado = DB::table('PLCFULL.dbo.Jurid_GrupoAdvogado')
        ->select('Codigo', 'Descricao')  
        ->orderby('Descricao', 'asc')
        ->whereNotIn('PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo', array(1,14))
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

       
      return view('Painel.Contratacao.Coordenador.index', compact('datahoje','datas','dataurgente','datafluxopadrao','usuarios','setores','tiposadvogado','totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function contratacao_coordenador_historico() {
          
        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.users.name as solicitantenome',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(7,9))
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

       
      return view('Painel.Contratacao.Coordenador.historico', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
    }

    public function contratacao_coordenador_solicitacaocriada(Request $request) {


        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $setorcodigo = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $tipocontratacao = $request->get('tipocontratacao');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();
        $classificacao = $request->get('classificacao');
        $dataentrada = $request->get('dataentrada');

        $curriculo = $request->file('curriculo');

        $usuario = $request->get('usuario');
        $datasaida = $request->get('datasaida');
        
        //Grava Anexos
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'data' => $carbon, 
                        'name' => $curriculo->getClientOriginalName());
        DB::table('dbo.Contratacao_Anexos')->insert($values3);   

        $curriculo->storeAs('contratacao', $curriculo->getClientOriginalName());
        Storage::disk('contratacao-local')->put($curriculo->getClientOriginalName(), fopen($curriculo, 'r+'));

        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.Contratacao_Matrix.salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.Contratacao_Matrix.tipovaga',
                 'dbo.users.name as solicitantenome',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                 'dbo.Contratacao_Matrix.classificacao',
                 'dbo.Contratacao_Status.descricao as status',
                 'dbo.Contratacao_Matrix.data_criacao as data')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.COntratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();


        $gerente =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '23')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();        

        //Update Matrix
        DB::table('dbo.Contratacao_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '2', 'data_edicao' => $carbon));

        //Grava hist
        $values3= array('id_matrix' => $id,
                       'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3);  

        $gerente_id = $gerente->id;
        $gerente_email = $gerente->email;
        $gerente_name = $gerente->nome;
    
        Mail::to($gerente_email)
        ->send(new GerenteRevisar($datas, $gerente_name));

        //Manda notificação portal
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);    


        flash('Solicitação cadastrada com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.Coordenador.index');

    }



    public function contratacao_coordenador_solicitacaorevisada(Request $request) {


        $id = $request->get('id');
        $setor = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $acao = $request->get('acao');
        $carbon= Carbon::now();

        //Se for aprovado
        if($acao == null) {

            $datas = DB::table('dbo.Contratacao_Matrix')
            ->select('dbo.Contratacao_Matrix.id',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'dbo.Contratacao_Matrix.salario',
                     'dbo.Contratacao_Matrix.observacao',
                     'dbo.Contratacao_Matrix.tipovaga',
                     'dbo.users.name as solicitantenome',
                     'dbo.Contratacao_Matrix.candidatonome',
                     'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                     'dbo.Contratacao_Matrix.classificacao',
                     'dbo.Contratacao_Status.descricao as status',
                     'dbo.Contratacao_Matrix.data_criacao as data')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
            ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
            ->where('dbo.Contratacao_Matrix.id', $id)
            ->get();

            $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 
        
            $gerente =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '23')
            ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
            ->first();        

            //Update Matrix
            DB::table('dbo.Contratacao_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status' => '2', 'data_edicao' => $carbon));

            //Grava hist
            $values3= array('id_matrix' => $id,
                       'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon);
            DB::table('dbo.Contratacao_Hist')->insert($values3);  

            $gerente_id = $gerente->id;
            $gerente_email = $gerente->email;
            $gerente_name = $gerente->nome;
    
            Mail::to($gerente_email)
            ->send(new GerenteRevisar($datas, $gerente_name));

            //Manda notificação portal
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);
    

        } 
        //Se for glosado
        else {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'dbo.Contratacao_Matrix.salario',
                     'dbo.Contratacao_Matrix.observacao',
                     'dbo.Contratacao_Matrix.tipovaga',
                     'dbo.users.name as solicitantenome',
                     'dbo.Contratacao_Matrix.candidatonome',
                     'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                     'dbo.Contratacao_Matrix.classificacao',
                     'dbo.Contratacao_Status.descricao as status',
                     'dbo.Contratacao_Matrix.data_criacao as data')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();    

        //Update na Matrix
        DB::table('dbo.Contratacao_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '7', 'data_edicao' => $carbon));

        //Grava Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '7', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3);  

        //Envia e-mail informando ao solicitante que foi cancelado
        $solicitante_id = DB::table('dbo.Contratacao_Matrix')->select('user_id')->where('id', $id)->value('user_id'); 
        $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
        $solicitante_name = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 

        Mail::to($solicitante_email)
        ->send(new SolicitacaoCancelada($datas, $solicitante_name));


        //Manda notificação ao solicitante informando que foi cancelado
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação glosada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        }

        flash('Solicitação revisada com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.Coordenador.index');

    }

    public function contratacao_superintendente_index() {

        $carbon= Carbon::now();

        $dataurgente = $carbon->modify('+2 weekdays');
        $datafluxopadrao = $carbon->modify('+12 weekdays');

        $datahoje = $carbon->format('Y-m-d');
        $dataurgente = $dataurgente->format('Y-m-d');
        $datafluxopadrao = $datafluxopadrao->format('Y-m-d');

        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name', 'dbo.users.cpf')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderby('dbo.users.name', 'asc')
        ->get(); 

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                         'dbo.Contratacao_Matrix.datasaida as DataSaida',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.advogado_substituido', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(3,4,5,6,8))
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('Codigo', 'Descricao')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->where('Ativo', '=', '1')
        ->where('setor_custo_user.user_id', '=', Auth::user()->id)
        ->orderBy('Codigo', 'asc')
        ->get();

        $tiposadvogado = DB::table('PLCFULL.dbo.Jurid_GrupoAdvogado')
        ->select('Codigo', 'Descricao')  
        ->orderby('Descricao', 'asc')
        ->whereNotIn('PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo', array(1,14))
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

       
      return view('Painel.Contratacao.Superintendente.index', compact('datahoje','datas','datafluxopadrao','dataurgente','usuarios','setores','tiposadvogado','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_superintendente_historico() {


        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(7,9))
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

       
      return view('Painel.Contratacao.Superintendente.historico', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function contratacao_superintendente_solicitacaocriada(Request $request) {

        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $setorcodigo = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $tipocontratacao = $request->get('tipocontratacao');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();
        $classificacao = $request->get('classificacao');
        $dataentrada = $request->get('dataentrada');

        $curriculo = $request->file('curriculo');

        $usuario = $request->get('usuario');
        $datasaida = $request->get('datasaida');


        //Grava na Matrix
        $values3= array('user_id' => Auth::user()->id, 
                        'setor' => $setorcodigo, 
                        'salario' => $salario, 
                        'observacao' => $observacao, 
                        'tipovaga' => $tipocontratacao, 
                        'advogado_substituido' => $usuario,
                        'datasaida' => $datasaida,
                        'candidatonome' => $candidatonome,
                        'candidatoemail' => $candidatoemail,
                        'tipoadvogado' => $tipocargo,
                        'classificacao' => $classificacao,
                        'status' => '3',
                        'data_criacao' => $carbon,
                        'data_edicao' => $carbon,
                        'data_entrada' => $dataentrada);
        DB::table('dbo.Contratacao_Matrix')->insert($values3);   

        $id = DB::table('dbo.Contratacao_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '3', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3);   

        //Grava Anexos
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'data' => $carbon, 
                        'name' => $curriculo->getClientOriginalName());
        DB::table('dbo.Contratacao_Anexos')->insert($values3);   

        $curriculo->storeAs('contratacao', $curriculo->getClientOriginalName());
        Storage::disk('contratacao-local')->put($curriculo->getClientOriginalName(), fopen($curriculo, 'r+'));

        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.Contratacao_Matrix.salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.Contratacao_Matrix.tipovaga',
                 'dbo.users.name as solicitantenome',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                 'dbo.Contratacao_Matrix.classificacao',
                 'dbo.Contratacao_Status.descricao as status',
                 'dbo.Contratacao_Matrix.data_criacao as data')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.COntratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setorcodigo)->value('Id'); 

        $gerente =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '23')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();        

        $gerente_id = $gerente->id;
        $gerente_email = $gerente->email;
        $gerente_name = $gerente->nome;

        Mail::to($gerente_email)
        ->send(new GerenteRevisar($datas, $gerente_name));

        //Manda notificação portal
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        
        flash('Solicitação cadastrada com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.Superintendente.index');
    }

    public function contratacao_superintendente_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $setor = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $acao = $request->get('acao');
        $carbon= Carbon::now();

         //Se for aprovado
         if($acao == null) {

            $datas = DB::table('dbo.Contratacao_Matrix')
            ->select('dbo.Contratacao_Matrix.id',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'dbo.Contratacao_Matrix.salario',
                     'dbo.Contratacao_Matrix.observacao',
                     'dbo.Contratacao_Matrix.tipovaga',
                     'dbo.users.name as solicitantenome',
                     'dbo.Contratacao_Matrix.candidatonome',
                     'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                     'dbo.Contratacao_Matrix.classificacao',
                     'dbo.Contratacao_Status.descricao as status',
                     'dbo.Contratacao_Matrix.data_criacao as data')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
            ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
            ->where('dbo.Contratacao_Matrix.id', $id)
            ->get();
    
            $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

            $gerente =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '23')
            ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
            ->first();        

            //Update Matrix
            DB::table('dbo.Contratacao_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status' => '3', 'data_edicao' => $carbon));

            //Grava hist
            $values3= array('id_matrix' => $id,
                       'user_id' => Auth::user()->id, 
                        'status' => '3', 
                        'data' => $carbon,                     
                        'observacao' => $observacao);
            DB::table('dbo.Contratacao_Hist')->insert($values3);  

            $gerente_id = $gerente->id;
            $gerente_email = $gerente->email;
            $gerente_name = $gerente->nome;
    
            Mail::to($gerente_email)
            ->send(new GerenteRevisar($datas, $gerente_name));

            //Manda notificação portal
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

            } 
            //Se for glosado 
            else {

             //Update na Matrix
             DB::table('dbo.Contratacao_Matrix')
             ->where('id', $id)  
             ->limit(1) 
             ->update(array('status' => '7', 'data_edicao' => $carbon));

             //Grava Hist
             $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '7', 
                        'data' => $carbon);
              DB::table('dbo.Contratacao_Hist')->insert($values3);  

             //Envia e-mail informando ao solicitante que foi cancelado
             $solicitante_id = DB::table('dbo.Contratacao_Matrix')->select('user_id')->where('id', $id)->value('user_id'); 
             $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
             $solicitante_email = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 

             Mail::to($solicitante_email)
             ->send(new SolicitacaoCancelada($datas, $solicitante_email));

            //Manda notificação ao solicitante informando que foi cancelado
             $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação glosada.' ,'status' => 'A');
             DB::table('dbo.Hist_Notificacao')->insert($values4);

            }

            flash('Solicitação revisada com sucesso!')->success();
            return redirect()->route('Painel.Contratacao.Superintendente.index');

        }

    public function contratacao_gerente_index() {

        $carbon= Carbon::now();
        $dataurgente = $carbon->modify('+2 days');
        $datafluxopadrao = $carbon->modify('+12 days');

        $dataurgente = $dataurgente->format('Y-m-d');
        $datafluxopadrao = $datafluxopadrao->format('Y-m-d');
        $datahoje = $carbon->format('Y-m-d');

        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name', 'dbo.users.cpf')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderby('dbo.users.name', 'asc')
        ->get(); 

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                         'dbo.Contratacao_Matrix.datasaida as DataSaida',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.advogado_substituido', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(2,3,4,5,6,8))
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('Codigo', 'Descricao')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->where('Ativo', '=', '1')
        ->where('setor_custo_user.user_id', '=', Auth::user()->id)
        ->orderBy('Codigo', 'asc')
        ->get();

        $tiposadvogado = DB::table('PLCFULL.dbo.Jurid_GrupoAdvogado')
        ->select('Codigo', 'Descricao')  
        ->orderby('Descricao', 'asc')
        ->whereNotIn('PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo', array(1,14))
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

       
      return view('Painel.Contratacao.Gerente.index', compact('datas','datahoje','datafluxopadrao','dataurgente','usuarios','setores','tiposadvogado','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_gerente_historico() {

        
        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(7,9))
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

       
      return view('Painel.Contratacao.Gerente.historico', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_gerente_solicitacaocriada(Request $request) {

        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $setorcodigo = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $tipocontratacao = $request->get('tipocontratacao');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();
        $classificacao = $request->get('classificacao');
        $dataentrada = $request->get('dataentrada');

        $curriculo = $request->file('curriculo');

        $usuario = $request->get('usuario');
        $datasaida = $request->get('datasaida');

        //Grava na Matrix
        $values3= array('user_id' => Auth::user()->id, 
                        'setor' => $setorcodigo, 
                        'salario' => $salario, 
                        'observacao' => $observacao, 
                        'tipovaga' => $tipocontratacao, 
                        'advogado_substituido' => $usuario,
                        'datasaida' => $datasaida,
                        'candidatonome' => $candidatonome,
                        'candidatoemail' => $candidatoemail,
                        'tipoadvogado' => $tipocargo,
                        'classificacao' => $classificacao,
                        'status' => '3',
                        'data_criacao' => $carbon,
                        'data_edicao' => $carbon,
                        'data_entrada' => $dataentrada);
        DB::table('dbo.Contratacao_Matrix')->insert($values3);   

        $id = DB::table('dbo.Contratacao_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '3', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3);   

        //Grava Anexos
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'data' => $carbon, 
                        'name' => $curriculo->getClientOriginalName());
        DB::table('dbo.Contratacao_Anexos')->insert($values3);   

        $curriculo->storeAs('contratacao', $curriculo->getClientOriginalName());
        Storage::disk('contratacao-local')->put($curriculo->getClientOriginalName(), fopen($curriculo, 'r+'));

       //Busca as informações para enviar o e-mail
       $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.Contratacao_Matrix.salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.Contratacao_Matrix.tipovaga',
                 'dbo.users.name as solicitantenome',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                 'dbo.Contratacao_Matrix.classificacao',
                 'dbo.Contratacao_Status.descricao as status',
                 'dbo.Contratacao_Matrix.data_criacao as data')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setorcodigo)->value('Id'); 

        $superintendente =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '24')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    

         //Verifica se possui SuperIntendente
         if($superintendente != null) {

            //Grava na Matrix
            $values3= array('user_id' => Auth::user()->id, 
                            'setor' => $setorcodigo, 
                            'salario' => $salario, 
                            'observacao' => $observacao, 
                            'tipovaga' => $tipocontratacao, 
                            'advogado_substituido' => $usuario,
                            'datasaida' => $datasaida,
                            'candidatonome' => $candidatonome,
                            'candidatoemail' => $candidatoemail,
                            'tipoadvogado' => $tipocargo,
                            'classificacao' => $classificacao,
                            'status' => '3',
                            'data_criacao' => $carbon,
                            'data_edicao' => $carbon,
                            'data_entrada' => $dataentrada);
            DB::table('dbo.Contratacao_Matrix')->insert($values3);   
    
            $id = DB::table('dbo.Contratacao_Matrix')->select('id')->orderby('id','desc')->value('id'); 
    
            //Grava na Hist
            $values3= array('id_matrix' => $id,
                            'user_id' => Auth::user()->id, 
                            'status' => '3', 
                            'data' => $carbon);
            DB::table('dbo.Contratacao_Hist')->insert($values3);  
            
            //Manda notificação portal
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $ceo_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

            $superintendente_id = $superintendente->id;
            $superintendente_email = $superintendente->email;
            $superintendente_name = $superintendente->nome;
    
            Mail::to($superintendente_email)
            ->send(new SuperintendenteRevisar($datas, $superintendente_name));
        
        } else {
            $ceo =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '37')
            ->first();        
    
            $ceo_id = $ceo->id;
            $ceo_email = $ceo->email;
            $ceo_name = $ceo->nome;
    
            Mail::to($ceo_email)
            ->send(new CEORevisar($datas, $ceo_name));
    
            //Manda notificação portal
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $ceo_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

        }


        flash('Solicitação cadastrada com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.Gerente.index');


    }

    public function contratacao_gerente_solicitacaorevisada(Request $request) {


        $id = $request->get('id');
        $setor = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $acao = $request->get('acao');
        $carbon= Carbon::now();

         //Se for aprovado
         if($acao == null) {

            $datas = DB::table('dbo.Contratacao_Matrix')
            ->select('dbo.Contratacao_Matrix.id',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'dbo.Contratacao_Matrix.salario',
                     'dbo.Contratacao_Matrix.observacao',
                     'dbo.Contratacao_Matrix.tipovaga',
                     'dbo.users.name as solicitantenome',
                     'dbo.Contratacao_Matrix.candidatonome',
                     'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                     'dbo.Contratacao_Matrix.classificacao',
                     'dbo.Contratacao_Status.descricao as status',
                     'dbo.Contratacao_Matrix.data_criacao as data')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
            ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
            ->where('dbo.Contratacao_Matrix.id', $id)
            ->get();
    
            $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

            $superintendente =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '24')
            ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
            ->first();    
            
            //Verifica se possui SuperIntendente
            if($superintendente != null) {

            //Update Matrix
            DB::table('dbo.Contratacao_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status' => '3', 'data_edicao' => $carbon));

            //Grava hist
            $values3= array('id_matrix' => $id,
                       'user_id' => Auth::user()->id, 
                        'status' => '3', 
                        'data' => $carbon,                     
                        'observacao' => $observacao);
            DB::table('dbo.Contratacao_Hist')->insert($values3);  

            
            $superintendente_id = $superintendente->id;
            $superintendente_email = $superintendente->email;
            $superintendente_name = $superintendente->nome;

            Mail::to($superintendente_email)
            ->send(new SuperintendenteRevisar($datas, $superintendente_name));

            //Manda notificação
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $superintendente_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);


            } else {

            $ceo =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '37')
            ->first();        

            //Update Matrix
            DB::table('dbo.Contratacao_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status' => '4', 'data_edicao' => $carbon));

            //Grava hist
            $values3= array('id_matrix' => $id,
                       'user_id' => Auth::user()->id, 
                        'status' => '4', 
                        'data' => $carbon,                     
                        'observacao' => $observacao);
            DB::table('dbo.Contratacao_Hist')->insert($values3);  

            $ceo_id = $ceo->id;
            $ceo_email = $ceo->email;
            $ceo_name = $ceo->nome;
    
            Mail::to($ceo_email)
            ->send(new CEORevisar($datas, $ceo_name));

            //Manda notificação portal
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $ceo_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação para revisar.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

            }



            } 
            //Se for glosado 
            else {

             //Update na Matrix
             DB::table('dbo.Contratacao_Matrix')
             ->where('id', $id)  
             ->limit(1) 
             ->update(array('status' => '7', 'data_edicao' => $carbon));

             //Grava Hist
             $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '7', 
                        'data' => $carbon);
              DB::table('dbo.Contratacao_Hist')->insert($values3);  

             //Envia e-mail informando ao solicitante que foi cancelado
             $solicitante_id = DB::table('dbo.Contratacao_Matrix')->select('user_id')->where('id', $id)->value('user_id'); 
             $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
             $solicitante_email = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 

             Mail::to($solicitante_email)
             ->send(new SolicitacaoCancelada($datas, $solicitante_email));

            //Manda notificação ao solicitante informando que foi cancelado
             $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação glosada.' ,'status' => 'A');
             DB::table('dbo.Hist_Notificacao')->insert($values4);

            }

            flash('Solicitação revisada com sucesso!')->success();
            return redirect()->route('Painel.Contratacao.Gerente.index');

    }

    public function contratacao_ceoo_index() {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(4,5,9))
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

       
      return view('Painel.Contratacao.CEOO.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function contratacao_ceoo_historico() {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'dbo.Contratacao_Matrix.advogado_substituido',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.link_linkedin',
                         'dbo.Contratacao_Matrix.link_indeed',
                         'dbo.Contratacao_Matrix.link_infojobs',
                         'dbo.Contratacao_Matrix.link_curriculum')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->whereIn('dbo.Contratacao_Matrix.status', array(7,9))
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

       
      return view('Painel.Contratacao.CEOO.historico', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function contratacao_ceoo_revisarsolicitacao($id) {


        $data = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'dbo.Contratacao_Matrix.salario as Salario',
                         'dbo.Contratacao_Matrix.observacao',
                         'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                         'dbo.Contratacao_Matrix.datasaida as DataSaida',
                         'dbo.Contratacao_Matrix.candidatonome',
                         'dbo.Contratacao_Matrix.candidatoemail',
                         'dbo.users.name as solicitantenome',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                         'dbo.Contratacao_Matrix.classificacao as Classificacao',
                         'dbo.Contratacao_Status.id as StatusID',
                         'dbo.Contratacao_Status.descricao as Status',
                         'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                         'dbo.Contratacao_Matrix.data_edicao as DataModificacao',
                         'dbo.Contratacao_Matrix.data_entrada as DataEntrada')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.advogado_substituido', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->first();

        $historicos = DB::table('dbo.Contratacao_Hist')
        ->select('dbo.users.name',
                 'dbo.Contratacao_Status.descricao as status',
                 'dbo.Contratacao_Hist.data as data')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Hist.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users', 'dbo.Contratacao_Hist.user_id', 'dbo.users.id')
        ->where('dbo.Contratacao_Hist.id_matrix', $id)
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

       
      return view('Painel.Contratacao.CEOO.revisarsolicitacao2', compact('data','historicos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_ceoo_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $setor = $request->get('setor');
        $tipocargo = $request->get('tipocargo');
        $salario =   str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $observacao = $request->get('observacao');
        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $acao = $request->get('acao');
        $avalconselho = $request->get('avalconselho');
        $conselhoanexo = $request->file('conselhoanexo');
        $carbon= Carbon::now();


        //Se foi aprovado pelo CEO
        if($acao == null) {

            //Coloca na Matrix status aguardando RH enviar documentação ao candidato para preenchimento
            DB::table('dbo.Contratacao_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status' => '5', 'data_edicao' => $carbon));

            //Grava na Hist
            $values3= array('id_matrix' => $id,
            'user_id' => Auth::user()->id, 
            'status' => '5', 
            'data' => $carbon,                     
            'observacao' => $observacao);
            DB::table('dbo.Contratacao_Hist')->insert($values3);  

            //Se possuir aval conselho anexar documento
            if($avalconselho == "Sim") {

                //Grava Anexos
                $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'data' => $carbon, 
                        'name' => $conselhoanexo->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values3);   

                $conselhoanexo->storeAs('contratacao', $conselhoanexo->getClientOriginalName());
                Storage::disk('contratacao-local')->put($conselhoanexo->getClientOriginalName(), fopen($conselhoanexo, 'r+'));

            }

            $datas = DB::table('dbo.Contratacao_Matrix')
            ->select('dbo.Contratacao_Matrix.id',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'dbo.Contratacao_Matrix.salario',
                     'dbo.Contratacao_Matrix.observacao',
                     'dbo.Contratacao_Matrix.tipovaga',
                     'dbo.users.name as solicitantenome',
                     'dbo.Contratacao_Matrix.candidatonome',
                     'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                     'dbo.Contratacao_Matrix.classificacao',
                     'dbo.Contratacao_Status.descricao as status',
                     'dbo.Contratacao_Matrix.data_criacao as data')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
            ->leftjoin('dbo.users', 'dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
            ->where('dbo.Contratacao_Matrix.id', $id)
            ->get();


           //Se for substituição


           //Envia e-mail AO RH copiando Marketing e T.I
           Mail::to('ronaldo.amaral@plcadvogados.com.br')
           //->cc('marketing@plcadvogados.com.br', 'suporte@plcadvogados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br')
           ->send(new RHEnviaDocumentacao($datas));

           //Manda notificação portal ao RH
           $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '239', 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação aprovada pelo CEO.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);

           //Manda notificação no portal ao Marketing
           $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '71', 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação aprovada pelo CEO.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);

           //Manda notificação no portal ao T.I
           $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '1', 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação aprovada pelo CEO.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);


        }
        //Se foi reprovado pelo CEO
        else {

             //Update na Matrix
             DB::table('dbo.Contratacao_Matrix')
             ->where('id', $id)  
             ->limit(1) 
             ->update(array('status' => '7', 'data_edicao' => $carbon));

             //Grava Hist
             $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '7', 
                        'data' => $carbon);
              DB::table('dbo.Contratacao_Hist')->insert($values3);  

             //Envia e-mail informando ao solicitante que foi cancelado
             $solicitante_id = DB::table('dbo.Contratacao_Matrix')->select('user_id')->where('id', $id)->value('user_id'); 
             $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
             $solicitante_email = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 

             Mail::to($solicitante_email)
             ->send(new SolicitacaoCancelada($datas, $solicitante_email));

            //Manda notificação ao solicitante informando que foi cancelado
             $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '10', 'obs' => 'Contratação: Nova solicitação glosada.' ,'status' => 'A');
             DB::table('dbo.Hist_Notificacao')->insert($values4);


        }



        flash('Solicitação revisada com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.CEOO.index');



    }

    public function contratacao_candidato_index() {

      return view('Painel.Contratacao.Candidato.index');

    }

    public function contratacao_candidato_preencherinformacoes($token) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeCodigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.Contratacao_Matrix.salario',
                 'dbo.Contratacao_Matrix.tipovaga',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as CargoCodigo',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as cargo',
                 'dbo.Contratacao_Status.descricao as status')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->where('dbo.Contratacao_Matrix.candidatotoken', '=', $token)
        ->first();

        $names = explode(' ', $datas->candidatonome);
        $twoNames = (isset($names[1])) ? $names[0]. '.' .$names[2] : $names[0];
        $emailplc = strtolower($twoNames) . '@plcadvogados.com.br';

        return view('Painel.Contratacao.Candidato.preencherinformacoes', compact('datahoje','datas', 'emailplc'));


    }

    public function contratacao_candidato_store(Request $request) {


        $id = $request->get('id');
        $cpf = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $request->get('cpf_cnpj'))));
        $nome =  $request->get('nomecompleto');
        $email = $request->get('email');
        $salario = str_replace (',', '.', str_replace ('.', '', $request->get('salario')));
        $cargo = $request->get('cargo');
        $cep = $request->get('cep');
        $rua = $request->get('rua');
        $bairro = $request->get('bairro');
        $cidade = $request->get('cidade');
        $complemento = $request->get('complemento');
        $setor = $request->get('setor');
        $uf = $request->get('uf');
        $telefone = $request->get('telefone');
        $oab = $request->get('numerooab');
        $dataoab = $request->get('dataoab');
        $datanascimento = $request->get('datanascimento');
        $identidade = $request->get('identidade');
        $dataexpedicao = $request->get('dataexpedicao');
        $comprovanteoab = $request->file('comprovanteoab');
        $comprovanteendereco = $request->file('comprovanteendereco');
        $curriculo = $request->file('curriculo');
        $filiacaomae = $request->get('filiacaomae');
        $filiacaopai = $request->get('filiacaopai');
        $estadocivil = $request->get('estadocivil');
        $filhos = $request->get('filhos');
        $nomeconjuge = $request->get('nomeconjuge');
        $carbon= Carbon::now();
        $banco = $request->get('banco');
        $agencia = $request->get('agencia');
        $conta = $request->get('conta');
        $tipoconta = $request->get('tipoconta');
        $tituloeleitor = $request->get('tituloeleitor');
        $zona = $request->get('zona');
        $secao = $request->get('zona');
        $ctps = $request->get('ctps');
        $serieuf = $request->get('serienf');
        $pis = $request->get('numeropis');
        $faculdade_nome = $request->get('faculdade_nome');
        $faculdade_curso = $request->get('faculdade_curso');
        $faculdade_natureza = $request->get('faculdade_natureza');
        $faculdade_status = $request->get('faculdade_status');
        $faculdade_trabalho = $request->get('faculdade_trabalho');
        $faculdade_datainicio = $request->get('faculdade_datainicio');
        $faculdade_datafim = $request->get('faculdade_datafim');
        $estrangeira_nome = $request->get('estrangeira_nome');
        $estrangeira_curso = $request->get('estrangeira_curso');
        $estrangeira_idioma = $request->get('estrangeira_idioma');
        $estrangeira_nivel = $request->get('estrangeira_nivel');
        $valetransporte_linha = $request->get('valetransporte_linha');
        $valetransporte_valorida = $request->get('valetransporte_valorida');
        $valetransporte_valorvolta = $request->get('valetransporte_valorvolta');
        $ticketrefeicao_uso = $request->get('ticketrefeicao_uso');
        $planosaude_uso = $request->get('planosaude_uso');
        $certificadoreservista = $request->get('certificadoreservista');
        $sexo = $request->get('sexo');

        //Foreach Contato
        $contato_nome = $request->get('contato_nome');
        $contato_datanasc = $request->get('contato_datanasc');
        $contato_numero1 = $request->get('contato_numero1');
        $contato_numero2 = $request->get('contato_numero2');
        
        foreach($contato_nome as $index => $contato_nome) {

            $item = array('contato_nome' => $contato_nome);
    
            $values = array(
              'id_matrix' => $id,
              'nome' => $contato_nome, 
              'datanascimento' => $contato_datanasc[$index], 
              'contato1' => $contato_numero1[$index],
              'contato2' => $contato_numero2[$index]);
              DB::table('dbo.Contratacao_Contato')->insert($values);  
        }
        //Fim Contato

        //Foreach Cursos
        $cursos_nome = $request->get('cursos_nome');
        $cursos_curso = $request->get('cursos_curso');
        $cursos_status = $request->get('cursos_status');
        $cursos_datainicio = $request->get('curso_datainicio');
        $cursos_datafim = $request->get('curso_datafim');

        if($cursos_nome != null) {

        foreach($cursos_nome as $index => $cursos_nome) {

            $item = array('cursos_nome' => $cursos_nome);
    
            $values = array(
              'id_matrix' => $id,
              'nome' => $cursos_nome, 
              'curso' => $cursos_curso[$index], 
              'status' => $cursos_status[$index],
              'datainicio' => $cursos_datainicio[$index],
              'datafim' => $cursos_datafim[$index]);
              DB::table('dbo.Contratacao_Cursos')->insert($values);  
        }
        }
        //Fim Cursos

        //Anexo Ficha Assinada
        $anexo_fichaassinada = $request->file('anexo_fichaassinada');
        $anexo_fichaassinada->storeAs('contratacao', $anexo_fichaassinada->getClientOriginalName());
        Storage::disk('contratacao-local')->put($anexo_fichaassinada->getClientOriginalName(), fopen($anexo_fichaassinada, 'r+'));

        $values = array(
            'Tabela_OR' => 'Advogados',
            'Codigo_OR' => $cpf,
            'Id_OR' => '0',
            'Descricao' => $anexo_fichaassinada->getClientOriginalName(),
            'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_fichaassinada->getClientOriginalName(), 
            'Data' => $carbon,
            'Nome' => $anexo_fichaassinada->getClientOriginalName(),
            'Responsavel' => 'portal.plc',
            'Arq_tipo' => $anexo_fichaassinada->getClientOriginalExtension(),
            'Arq_Versao' => '1',
            'Arq_Status' => 'Guardado',
            'Arq_usuario' => 'portal.plc',
            'Arq_nick' => $anexo_fichaassinada->getClientOriginalName(),
            'Obs' => 'Ficha assinada e anexado no portal no projeto de contratação.');
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
        
        $values = array(
            'id_matrix' => $id,
            'user_id' => '25', 
            'data' => $carbon, 
            'name' => $anexo_fichaassinada->getClientOriginalName());
            DB::table('dbo.Contratacao_Anexos')->insert($values);  
        
        //Anexo Copia CPF
        $anexo_cpf = $request->file('anexo_cpf');
        $anexo_cpf->storeAs('contratacao', $anexo_cpf->getClientOriginalName());
        Storage::disk('contratacao-local')->put($anexo_cpf->getClientOriginalName(), fopen($anexo_cpf, 'r+'));

        $values = array(
            'Tabela_OR' => 'Advogados',
            'Codigo_OR' => $cpf,
            'Id_OR' => '0',
            'Descricao' => $anexo_cpf->getClientOriginalName(),
            'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_cpf->getClientOriginalName(), 
            'Data' => $carbon,
            'Nome' => $anexo_cpf->getClientOriginalName(),
            'Responsavel' => 'portal.plc',
            'Arq_tipo' => $anexo_cpf->getClientOriginalExtension(),
            'Arq_Versao' => '1',
            'Arq_Status' => 'Guardado',
            'Arq_usuario' => 'portal.plc',
            'Arq_nick' => $anexo_cpf->getClientOriginalName(),
            'Obs' => 'Comprovante de CPF anexado no portal no projeto de contratação.');
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
        
        $values = array(
            'id_matrix' => $id,
            'user_id' => '25', 
            'data' => $carbon, 
            'name' => $anexo_cpf->getClientOriginalName());
            DB::table('dbo.Contratacao_Anexos')->insert($values);  

        //Anexo OAB
        $anexo_oab = $request->file('anexo_oab');

        if($anexo_oab != null) {
            $anexo_oab->storeAs('contratacao', $anexo_oab->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_oab->getClientOriginalName(), fopen($anexo_oab, 'r+'));
            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_oab->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_oab->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_oab->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_oab->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_oab->getClientOriginalName(),
                'Obs' => 'Comprovante de OAB anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_oab->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo quitação OAB
        $anexo_certidaooab = $request->file('anexo_certidaooab');
        if($anexo_certidaooab != null) {
            $anexo_certidaooab->storeAs('contratacao', $anexo_certidaooab->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_certidaooab->getClientOriginalName(), fopen($anexo_certidaooab, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_certidaooab->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_certidaooab->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_certidaooab->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_certidaooab->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_certidaooab->getClientOriginalName(),
                'Obs' => 'Comprovante de quitação da OAB anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_certidaooab->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }
        
        //Anexo certidão nascimento/casamento
        $anexo_certidao = $request->file('anexo_certidao');

        if($anexo_certidao) {
            $anexo_certidao->storeAs('contratacao', $anexo_certidao->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_certidao->getClientOriginalName(), fopen($anexo_certidao, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_certidao->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_certidao->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_certidao->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_certidao->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_certidao->getClientOriginalName(),
                'Obs' => 'Comprovante de certidão anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_certidao->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo Conclusão Faculdade
        $anexo_conclusaofaculdade = $request->file('anexo_conclusaofaculdade');

        if($anexo_conclusaofaculdade != null) {
            $anexo_conclusaofaculdade->storeAs('contratacao', $anexo_conclusaofaculdade->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_conclusaofaculdade->getClientOriginalName(), fopen($anexo_conclusaofaculdade, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_conclusaofaculdade->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_conclusaofaculdade->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_conclusaofaculdade->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_conclusaofaculdade->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_conclusaofaculdade->getClientOriginalName(),
                'Obs' => 'Comprovante de conclusão ensino superior anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_conclusaofaculdade->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo Curso Especialização Lato e Stricto Sensu
        $anexo_cursolato = $request->file('anexo_cursolato');

        if($anexo_cursolato != null) {
            $anexo_cursolato->storeAs('contratacao', $anexo_cursolato->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_cursolato->getClientOriginalName(), fopen($anexo_conclusaofaculdade, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_cursolato->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_cursolato->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_cursolato->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_cursolato->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_cursolato->getClientOriginalName(),
                'Obs' => 'Comprovante de curso especialidade Lato e/ou Stricto Sensu anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_cursolato->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo conclusao ensino medio
        $anexo_conclusaoensinomedio = $request->file('anexo_conclusaoensinomedio');
        if($anexo_conclusaoensinomedio != null) {

        $anexo_conclusaoensinomedio->storeAs('contratacao', $anexo_conclusaoensinomedio->getClientOriginalName());
        Storage::disk('contratacao-local')->put($anexo_conclusaoensinomedio->getClientOriginalName(), fopen($anexo_conclusaoensinomedio, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_conclusaoensinomedio->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_conclusaoensinomedio->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_conclusaoensinomedio->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_conclusaoensinomedio->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_conclusaoensinomedio->getClientOriginalName(),
                'Obs' => 'Comprovante de conclusão do ensino médio anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_conclusaoensinomedio->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }
        
        //Anexo Curso Idioma
        $anexo_cursoidioma = $request->file('anexo_cursoidioma');

        if($anexo_cursoidioma != null) {
            $anexo_cursoidioma->storeAs('contratacao', $anexo_cursoidioma->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_cursoidioma->getClientOriginalName(), fopen($anexo_cursoidioma, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_cursoidioma->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_cursoidioma->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_cursoidioma->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_cursoidioma->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_cursoidioma->getClientOriginalName(),
                'Obs' => 'Comprovante de curso para outras linguas anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_cursoidioma->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo quitação eleitoral
        $anexo_quitacaoeleitoral = $request->file('anexo_quitacaoeleitoral');
        if($anexo_quitacaoeleitoral != null) {
            $anexo_quitacaoeleitoral->storeAs('contratacao', $anexo_quitacaoeleitoral->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_quitacaoeleitoral->getClientOriginalName(), fopen($anexo_quitacaoeleitoral, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_quitacaoeleitoral->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_quitacaoeleitoral->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_quitacaoeleitoral->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_quitacaoeleitoral->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_quitacaoeleitoral->getClientOriginalName(),
                'Obs' => 'Comprovante de quitação eleitoral anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_quitacaoeleitoral->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo Criminal
        $anexo_criminal = $request->file('anexo_criminal');

        if($anexo_criminal != null) {
            $anexo_criminal->storeAs('contratacao', $anexo_criminal->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_criminal->getClientOriginalName(), fopen($anexo_criminal, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_criminal->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_criminal->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_criminal->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_criminal->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_criminal->getClientOriginalName(),
                'Obs' => 'Comprovante vigente de “Nada Consta” Criminal anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_criminal->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo Curso Especialização
        $anexo_cursoespecializacao = $request->file('anexo_cursoespecializacao');

        if($anexo_cursoespecializacao != null) {
            $anexo_cursoespecializacao->storeAs('contratacao', $anexo_cursoespecializacao->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_cursoespecializacao->getClientOriginalName(), fopen($anexo_cursoespecializacao, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_cursoespecializacao->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_cursoespecializacao->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_cursoespecializacao->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_cursoespecializacao->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_cursoespecializacao->getClientOriginalName(),
                'Obs' => 'Comprovante do certificado de cursos de Especialização Criminal anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_cursoespecializacao->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo comprovante endereço
        $anexo_comprovanteendereco = $request->file('anexo_comprovanteendereco');

        if($anexo_comprovanteendereco != null) {
            $anexo_comprovanteendereco->storeAs('contratacao', $anexo_comprovanteendereco->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_comprovanteendereco->getClientOriginalName(), fopen($anexo_comprovanteendereco, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_comprovanteendereco->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_comprovanteendereco->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_comprovanteendereco->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_comprovanteendereco->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_comprovanteendereco->getClientOriginalName(),
                'Obs' => 'Comprovante de endereço anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_comprovanteendereco->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }

        //Anexo Dados Bancarios
        $anexo_dadosbancarios = $request->file('anexo_dadosbancarios');

        if($anexo_dadosbancarios != null) {
            $anexo_dadosbancarios->storeAs('contratacao', $anexo_dadosbancarios->getClientOriginalName());
            Storage::disk('contratacao-local')->put($anexo_dadosbancarios->getClientOriginalName(), fopen($anexo_dadosbancarios, 'r+'));

            $values = array(
                'Tabela_OR' => 'Advogados',
                'Codigo_OR' => $cpf,
                'Id_OR' => '0',
                'Descricao' => $anexo_dadosbancarios->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $anexo_dadosbancarios->getClientOriginalName(), 
                'Data' => $carbon,
                'Nome' => $anexo_dadosbancarios->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $anexo_dadosbancarios->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $anexo_dadosbancarios->getClientOriginalName(),
                'Obs' => 'Comprovante de dados bancarios anexado no portal no projeto de contratação.');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
            
            $values = array(
                'id_matrix' => $id,
                'user_id' => '25', 
                'data' => $carbon, 
                'name' => $anexo_dadosbancarios->getClientOriginalName());
                DB::table('dbo.Contratacao_Anexos')->insert($values);  
        }


        $unidade = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Unidade')->where('Codigo', $setor)->value('Unidade'); 

        //Verifico se ele já não existe na base 
        $verifica = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Codigo')->where('Codigo','=',$cpf)->value('Codigo'); 
        //Se existir do Update na Jurid_Advogado
        if($verifica != null) {

            DB::table('PLCFULL.dbo.Jurid_Advogado')
            ->where('Codigo', $cpf)  
            ->limit(1) 
            ->update(array(
             'Dt_cad' => $carbon,
             'Nome' => $nome,
             'Razao' => $nome,
             'Endereco' => $rua,
             'Bairro' => $bairro,
             'Cidade' => $cidade,
             'Cep' => $cep,
             'UF' => $uf,
             'Pais' => 'Brasil',
             'Fone1' => $telefone,
             'Obs' => 'Identidade: ' . $identidade,
             'Status' => 'Inativo',
             'E_mail' => $email,
             'Salario' => $salario,
             'OAB' => $oab,
             'Setor' => $setor,
             'Carencia' => '1',
             'GrupoAdv' => $cargo,
             'Unidade' => $unidade,
             'Correspondente' => '0',
             'dt_oab' => $dataoab,
             'dt_entrada' => $carbon,
             'Dt_Nasc' => $datanascimento,
             'Complemento' => $complemento,

             ));

        } 
        //Se não existir insert na Jurid_Advogado
        else {

            $values = array(
                'Codigo' => $cpf,
                'Dt_cad' => $carbon,
                'Nome' => $nome,
                'Razao' => $nome,
                'Endereco' => $rua,
                'Bairro' => $bairro,
                'Cidade' => $cidade,
                'Cep' => $cep,
                'UF' => $uf,
                'Pais' => 'Brasil',
                'Fone1' => $telefone,
                'Obs' => 'Identidade: ' . $identidade,
                'Status' => 'Inativo',
                'Cad_por' => 'portal',
                'E_mail' => $email,
                'Salario' => $salario,
                'OAB' => $oab,
                'Setor' => $setor,
                'Carencia' => '1',
                'GrupoAdv' => $cargo,
                'Unidade' => $unidade,
                'Correspondente' => '0',
                'dt_oab' => $dataoab,
                'dt_entrada' => $carbon,
                'Dt_Nasc' => $datanascimento,
                'Complemento' => $complemento);
                DB::table('PLCFULL.dbo.Jurid_Advogado')->insert($values); 
        }


        //Grava na tabela Auxiliar as informações restantes
        $values = array(
            'id_matrix' => $id,
            'filiacaomae' => $filiacaomae,
            'filiacaopai' => $filiacaopai,
            'estadocivil' => $estadocivil,
            'filhos' => $filhos,
            'banco' => $banco,
            'agencia' => $agencia,
            'conta' => $conta,
            'tipoconta' => $tipoconta,
            'identidade' => $identidade,
            'dataexpedicao' => $dataexpedicao,
            'tituloeleitor' => $tituloeleitor,
            'zona' => $zona,
            'secao' => $secao,
            'ctps' => $ctps,
            'serienf' => $serieuf,
            'numeropis' => $pis,
            'faculdade_nome' => $faculdade_nome,
            'faculdade_curso' => $faculdade_curso,
            'faculdade_natureza' => $faculdade_natureza,
            'faculdade_status' => $faculdade_status,
            'faculdade_trabalho' => $faculdade_trabalho,
            'faculdade_datainicio' => $faculdade_datainicio,
            'estrangeira_nome' => $estrangeira_nome,
            'estrangeira_curso' => $estrangeira_curso,
            'estrangeira_idioma' => $estrangeira_idioma,
            'estrangeira_nivel' => $estrangeira_nivel,
            'certificadoreservista' => $certificadoreservista,
            'nomeconjuge' => $nomeconjuge,
            'valetransporte_linha' => $valetransporte_linha,
            'valetransporte_valorida' => $valetransporte_valorida,
            'valetransporte_valorvolta' => $valetransporte_valorvolta,
            'ticketrefeicao_uso' => $ticketrefeicao_uso,
            'planosaude_uso' => $planosaude_uso,
            'sexo' => $sexo);
            DB::table('dbo.Contratacao_Informacoes')->insert($values); 

        //Update na Matrix
        DB::table('dbo.Contratacao_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '8', 'data_edicao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => '25', 
                        'status' => '8', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3);   


        //Enviar informações por e-mail
        $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as Codigo',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                 'PLCFULL.dbo.Jurid_Advogado.Dt_Cad as DataCadastro',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as Cargo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'PLCFULL.dbo.Jurid_Advogado.GrupoAdv', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->where('PLCFULL.dbo.Jurid_Advogado.Codigo', $cpf)
        ->get();

        //Informa para a equipe do RH que esta aguardando que eles aprovem
            //Colocar Karina - RH
            Mail::to('ronaldo.amaral@plcadvogados.com.br')
            ->send(new AprovarInformacoes($datas));

        flash('Informações salvas com sucesso!')->success();
        return redirect()->route('Painel.Contratacao.Candidato.index');

    }

    public function contratacao_rh_index() {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'dbo.Contratacao_Matrix.salario as Salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.email as SolicitanteEmail',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                 'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                 'dbo.Contratacao_Matrix.datasaida as DataSaida',
                 'dbo.Contratacao_Matrix.status_substituicao',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                 'dbo.Contratacao_Matrix.advogado_substituido',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as TipoAdvogadoCodigo',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                 'dbo.Contratacao_Matrix.classificacao as Classificacao',
                 'dbo.Contratacao_Status.id as StatusID',
                 'dbo.Contratacao_Status.descricao as Status',
                 'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                 'dbo.Contratacao_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users','dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.advogado_substituido', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->whereIn('dbo.Contratacao_Matrix.status', array(5,6,8))        
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

        return view('Painel.Contratacao.RH.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_rh_historico() {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'dbo.Contratacao_Matrix.salario as Salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.email as SolicitanteEmail',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                 'dbo.Contratacao_Matrix.advogado_substituido',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                 'dbo.Contratacao_Matrix.classificacao as Classificacao',
                 'dbo.Contratacao_Status.id as StatusID',
                 'dbo.Contratacao_Status.descricao as Status',
                 'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao',
                 'dbo.Contratacao_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users','dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->whereIn('dbo.Contratacao_Matrix.status', array(7,9))
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

        return view('Painel.Contratacao.RH.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function contratacao_rh_anexadorecisao(Request $request) {
        
        $id = $request->get('id');
        $usuario_cpf = $request->get('usuariocpf');
        $carbon= Carbon::now();

        //Update Matrix
        DB::table('dbo.Contratacao_Matrix')
        ->limit(1)
        ->where('id', '=', $id)     
        ->update(array('status_substituicao' => '1','data_edicao' => $carbon));

        //Update Status no Portal
        DB::table('dbo.users')
        ->limit(1)
        ->where('cpf', '=', $usuario_cpf)     
        ->update(array('status' => 'Inativo'));

        return redirect()->route('Painel.Contratacao.RH.index'); 

    }

    public function contratacao_rh_revisado(Request $request) {

        $id = $request->get('id');
        $candidatonome = $request->get('candidatonome');
        $candidatoemail = $request->get('candidatoemail');
        $solicitantenome = $request->get('solicitantenome');
        $solicitanteemail = $request->get('solicitanteemail');
        $setordescricao = $request->get('setordescricao');
        $setorcodigo = $request->get('setorcodigo');
        $carbon= Carbon::now();
        $funcaocodigo = $request->get('funcaoid');


        $candidatotoken = Crypt::encryptString($id);

        //Update na Matrix
        DB::table('dbo.Contratacao_Matrix')
        ->limit(1)
        ->where('id', '=', $id)     
        ->update(array('status' => '6', 'candidatotoken' => $candidatotoken,'data_edicao' => $carbon));

        //Insert na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '6', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3); 


        //Se for seletista ou estagiario
        if($funcaocodigo == 13 || $funcaocodigo == 15) {
        //Envia o e-mail ao candidato para preenchimento das informações
        Mail::to($candidatoemail)
        ->cc(Auth::user()->email, $solicitanteemail)
        ->send(new CandidatoPreencherInformacoes($candidatotoken, $candidatonome));
        } 
        //Se for Advogado 
        else {
        Mail::to($candidatoemail)
        ->cc(Auth::user()->email, $solicitanteemail)
        ->send(new CandidatoPreencherInformacoesAdvogado($candidatotoken, $candidatonome));
        }


        return redirect()->route('Painel.Contratacao.RH.index'); 


    }

    public function contratacao_rh_revisadados($id) {

        $datas = DB::table('dbo.Contratacao_Matrix')
        ->select('dbo.Contratacao_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'dbo.Contratacao_Matrix.salario as Salario',
                 'dbo.Contratacao_Matrix.observacao',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.email as SolicitanteEmail',
                 'dbo.Contratacao_Matrix.candidatonome',
                 'dbo.Contratacao_Matrix.candidatoemail',
                 'PLCFULL.dbo.Jurid_Advogado.E_mail as emailplc',
                 'PLCFULL.dbo.Jurid_Advogado.Dt_Nasc as DataNascimento',
                 'dbo.COntratacao_Informacoes.sexo as sexo',
                 'dbo.Contratacao_Informacoes.filiacaomae as FiliacaoMae',
                 'dbo.Contratacao_Informacoes.filiacaopai as FiliacaoPai',
                 'dbo.Contratacao_Informacoes.estadocivil as EstadoCivil',
                 'dbo.Contratacao_Informacoes.banco as Banco',
                 'dbo.Contratacao_Informacoes.agencia as Agencia',
                 'dbo.Contratacao_Informacoes.conta as Conta',
                 'dbo.Contratacao_Informacoes.tipoconta as TipoConta',
                 'dbo.Contratacao_Informacoes.identidade as Identidade',
                 'dbo.Contratacao_Informacoes.dataexpedicao as DataExpedicao',
                 'dbo.Contratacao_Informacoes.tituloeleitor as TituloEleitor',
                 'dbo.Contratacao_Informacoes.zona as Zona',
                 'dbo.Contratacao_Informacoes.secao as Secao',
                 'dbo.Contratacao_Informacoes.ctps as CTPS',
                 'dbo.Contratacao_Informacoes.serienf as SerieUF',
                 'dbo.Contratacao_Informacoes.numeropis as PIS',
                 'dbo.Contratacao_Informacoes.nomeconjuge as NomeConjuge',
                 'dbo.Contratacao_Informacoes.certificadoreservista as CertificadoReservista',
                 'dbo.Contratacao_Informacoes.filhos as Filhos',
                 'dbo.Contratacao_Informacoes.faculdade_nome as FaculdadeNome',
                 'dbo.Contratacao_Informacoes.faculdade_curso as FaculdadeCurso',
                 'dbo.Contratacao_Informacoes.faculdade_natureza as FaculdadeNatureza',
                 'dbo.Contratacao_Informacoes.faculdade_trabalho as FaculdadeTrabalho',
                 'dbo.Contratacao_Informacoes.faculdade_status as FaculdadeStatus',
                 'dbo.Contratacao_Informacoes.faculdade_datainicio as FaculdadeDataInicio',
                 'dbo.Contratacao_Informacoes.faculdade_datafim as FaculdadeDataFim',
                 'dbo.Contratacao_Informacoes.estrangeira_nome as LinguaNome',
                 'dbo.Contratacao_Informacoes.estrangeira_curso as LinguaCurso',
                 'dbo.Contratacao_Informacoes.estrangeira_idioma as LinguaIdioma',
                 'dbo.Contratacao_Informacoes.estrangeira_nivel as LinguaNivel',
                 'dbo.Contratacao_Informacoes.estrangeira_datainicio as LinguaDataInicio',
                 'dbo.Contratacao_Informacoes.estrangeira_datafim as LinguaDataFim',
                 'dbo.Contratacao_Informacoes.valetransporte_linha',
                 'dbo.Contratacao_Informacoes.valetransporte_valorida',
                 'dbo.Contratacao_Informacoes.valetransporte_valorvolta',
                 'dbo.Contratacao_Informacoes.ticketrefeicao_uso',
                 'dbo.Contratacao_Informacoes.planosaude_uso',
                 'PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                 'PLCFULL.dbo.Jurid_Advogado.OAB',
                 'PLCFULL.dbo.Jurid_Advogado.Cep as CEP',
                 'PLCFULL.dbo.Jurid_Advogado.Endereco as Endereco',
                 'PLCFULL.dbo.Jurid_Advogado.Complemento',
                 'PLCFULL.dbo.Jurid_Advogado.Bairro',
                 'PLCFULL.dbo.Jurid_Advogado.Cidade',
                 'PLCFULL.dbo.Jurid_Advogado.UF',
                 'dbo.Contratacao_Matrix.tipovaga as TipoVaga',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo as CargoCodigo',
                 'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as TipoAdvogado',
                 'dbo.Contratacao_Matrix.classificacao as Classificacao',
                 'dbo.Contratacao_Matrix.data_criacao as DataSolicitacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Contratacao_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'dbo.Contratacao_Matrix.tipoadvogado', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
        ->leftjoin('dbo.Contratacao_Status', 'dbo.Contratacao_Matrix.status', 'dbo.Contratacao_Status.id')
        ->leftjoin('dbo.users','dbo.Contratacao_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.Contratacao_Matrix.candidatonome', 'PLCFULL.dbo.Jurid_Advogado.Nome')
        ->leftjoin('dbo.Contratacao_Informacoes', 'dbo.Contratacao_Matrix.id', 'dbo.Contratacao_Informacoes.id_matrix')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->first();

        $contatos = DB::table('dbo.Contratacao_Matrix')
        ->join('dbo.Contratacao_Contato', 'dbo.Contratacao_Matrix.id', 'dbo.Contratacao_Contato.id_matrix')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();

        
        $cursos = DB::table('dbo.Contratacao_Matrix')
        ->join('dbo.Contratacao_Cursos', 'dbo.Contratacao_Matrix.id', 'dbo.Contratacao_Cursos.id_matrix')
        ->where('dbo.Contratacao_Matrix.id', $id)
        ->get();


        return view('Painel.Contratacao.RH.revisadados', compact('datas', 'contatos', 'cursos'));

    }

    public function contratacao_rh_revisadodadoscandidato(Request $request) {

        $carbon= Carbon::now();
        $id = $request->get('id');
        $candidatocpf = $request->get('cpf_cnpj');
        $dataadmissao = $request->get('dataadmissao');
        $horariotrabalho_inicio = $request->get('horariotrabalhoinicio');
        $horariotrabalho_fim = $request->get('horariotrabalhofim');
        $periodoexperiencia = $request->get('prazoexperiencia');
        $prorrogavel = $request->get('prorrogavel');
        $departamento = $request->get('departamento');
        $ticketrefeicao_valor = $request->get('ticketrefeicao_valor');
        $planosaude_valor = $request->get('planosaude_valor');

        //Update Jurid_Advogado colocando Status Ativo para que o CRON rode automatico
        DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->where('Codigo', $candidatocpf)  
        ->limit(1) 
        ->update(array('Dt_cad' => $carbon,
                       'Status' => 'Ativo', 
                       'dt_entrada' => $dataadmissao));

        //Update Contratacao_Informacoes
        DB::table('dbo.Contratacao_Informacoes')
        ->where('id_matrix', $id)  
        ->limit(1) 
        ->update(array('dataadmissao' => $dataadmissao, 
                       'horariotrabalho_inicio' => $horariotrabalho_inicio,
                       'horariotrabalho_fim' => $horariotrabalho_fim,
                       'departamento' => $departamento,
                       'prazoexperiencia' => $periodoexperiencia,
                       'prorrogavel' => $prorrogavel,
                       'ticketrefeicao_valor' => $ticketrefeicao_valor,
                       'planosaude_valor' => $planosaude_valor));

        //Update Contratacao_Matrix
        DB::table('dbo.Contratacao_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '9',
                       'data_edicao' => $carbon));

        //Insert Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '9', 
                        'data' => $carbon);
        DB::table('dbo.Contratacao_Hist')->insert($values3); 

        return redirect()->route('Painel.Contratacao.RH.index'); 
    }

          
 
}
