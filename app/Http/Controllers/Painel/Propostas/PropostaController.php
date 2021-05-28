<?php

namespace App\Http\Controllers\Painel\Propostas;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\Proposta\AtualizadaProposta;
use App\Mail\Proposta\NovaProposta;

class PropostaController extends Controller
{

    protected $model;
    protected $totalPage = 10000;   
    public $timestamps = false;


    public function anexo($anexo) {
        return Storage::disk('propostas-sftp')->download($anexo);
    }
    
    
   public function solicitacao_index() {

       $datasAdvogado = DB::table('dbo.Financeiro_Propostas_Matrix')
             ->select(
            'dbo.Financeiro_Propostas_Matrix.id as Id',   
            'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
            'dbo.Financeiro_Propostas_Matrix.data_cadastro as Data',
            'dbo.Financeiro_Propostas_Matrix.anexo as anexo',
            'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
            'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
            'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
            'dbo.Financeiro_Propostas_Matrix.anexo as Anexo',
            DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
            'dbo.Financeiro_Propostas_Matrix.status_id as StatusID',
            'dbo.Financeiro_Propostas_Status.descricao as Status')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
            ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->where('dbo.Financeiro_Propostas_Matrix.solicitante', '=', Auth::user()->cpf)
            ->get();


              
       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
              
       $notificacoes = DB::table('dbo.Hist_Notificacao')
            ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
            ->limit(3)
            ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
            ->where('dbo.Hist_Notificacao.status','=','A')
            ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
            ->orderBy('dbo.Hist_Notificacao.data', 'desc')
            ->get();
       
      return view('Painel.Financeiro.Propostas.solicitacao.index', compact('datasAdvogado','totalNotificacaoAbertas', 'notificacoes'));
    }


    public function solicitacao_create() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $segmentos = DB::table('PLCFULL.dbo.jurid_grupofinanceiro')
        ->orderBy('nome_grupofinanceiro', 'asc')
        ->get();

        $grupos = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
        ->orderBy('Descricao', 'asc')
        ->select('Codigo', 'Descricao')
        ->get();

        $clientes = DB::table('PLCFULL.dbo.Jurid_CliFor')
        ->orderBy('Razao', 'asc')
        ->select('Codigo', 'Razao')
        ->where('Status', 'Ativo')
        ->get();

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                 'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

        return view('Painel.Financeiro.Propostas.solicitacao.newcreate', compact('datahoje','segmentos','clientes','grupos','totalNotificacaoAbertas', 'notificacoes'));

    }



    public function solicitacao_store(Request $request) {

        $carbon= Carbon::now();
        $segmento = $request->get('segmento');
        $grupo = $request->get('grupo');
        $cliente = $request->get('cliente');
        $socio = $request->get('socio');
        $data = $request->get('data');
        $referral = $request->get('referral');
        $valorsemformato = $request->get('valor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
        $escopo = $request->get('escopo');
        $observacao = $request->get('observacao');

        $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo','=', $cliente)->value('Unidade'); 
        
        $setor = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Setor')->where('Codigo','=', $cliente)->value('Setor'); 
        $ano= $carbon->format('Y');
        $antigoid = DB::table('dbo.Financeiro_Propostas_Matrix')->select('id')->orderBy('id', 'desc')->value('id'); 
        $novoid = $antigoid + 1;
        $proposta = $ano.'-'.$unidade.'-'.$setor.'-'.$novoid;

        //Grava Anexo
        $image = $request->file('select_file');
        $new_name = $proposta . '.'  . $image->getClientOriginalExtension();
        $image->storeAs('propostas', $new_name);

        //Grava na Matrix
        $values = array(
            'proposta' => $proposta, 
            'data_cadastro' => $data, 
            'setor' => $setor, 
            'unidade' => $unidade,
            'segmento' => $segmento,
            'grupo' => $grupo,
            'cliente' => $cliente,
            'socio' => $socio,
            'advogado' => Auth::user()->cpf,
            'referral' => $referral,
            'valor' => $valor,
            'escopo' => $escopo,
            'obs' => $observacao,
            'anexo' => $new_name,
            'solicitante' => Auth::user()->cpf,
            'status_id' => '1');
        DB::table('dbo.Financeiro_Propostas_Matrix')->insert($values);

      //Grava na Hist
       $values = array(
        'proposta' => $proposta, 
        'versao' => '1', 
        'data_alteracao' => $data, 
        'valor' => $valor,
        'status' => '1',
        'advogado' => Auth::user()->cpf);
       DB::table('dbo.Financeiro_Propostas_Hist')->insert($values);


      //Pega o responsável pelo setor de custo
      $responsavel_id = DB::table('dbo.users')
                       ->select('dbo.users.id')
                       ->where('dbo.profile_user.profile_id','=', '30')
                       ->where('PLCFULL.dbo.Jurid_Setor.Codigo', $setor)
                       ->join('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
                       ->join('dbo.setor_custo_user', 'dbo.users.id', 'dbo.setor_custo_user.user_id')
                       ->join('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', 'PLCFULL.dbo.Jurid_Setor.Id')
                       ->value('dbo.users.id'); 

      
      if($responsavel_id != null)  {

      $responsavel_email = DB::table('dbo.users')->select('email')->where('id','=', $responsavel_id)->value('email'); 
      $unidade_descricao =  DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Descricao')->where('Codigo','=', $unidade)->value('Descricao'); 
      $setor_descricao =  DB::table('PLCFULL.dbo.Jurid_Setor')->select('Descricao')->where('Codigo','=', $setor)->value('Descricao'); 
      $cliente_descricao =  DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Razao')->where('Codigo','=', $cliente)->value('Razao'); 
      $grupo_descricao =  DB::table('PLCFULL.dbo.Jurid_GrupoCliente')->select('Descricao')->where('Codigo','=', $grupo)->value('Descricao'); 

      //Envia notificação interna para o responsavel do setor
      $values3= array('data' => $carbon, 'id_ref' => $novoid, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '7', 'obs' => 'Propostas: Você acaba de receber uma nova proposta para revisão.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);
  
      //Envia e-mail para o responsavel do setor
      Mail::to($responsavel_email)
      ->send(new NovaProposta($proposta, $unidade_descricao, $setor_descricao, $cliente_descricao, $grupo_descricao, $valor, Auth::user()->name, $carbon));

      }

       flash('Nova proposta cadastrada com sucesso !')->success();

       return redirect()->route('Painel.Proposta.solicitacao.index');

  
    }


    public function solicitacao_editar($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $segmentos = DB::table('PLCFULL.dbo.jurid_grupofinanceiro')
        ->orderBy('nome_grupofinanceiro', 'asc')
        ->get();

        $grupos = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
        ->orderBy('Descricao', 'asc')
        ->select('Codigo', 'Descricao')
        ->get();

        $clientes = DB::table('PLCFULL.dbo.Jurid_CliFor')
        ->orderBy('Razao', 'asc')
        ->select('Codigo', 'Razao')
        ->where('Status', 'Ativo')
        ->get();


        $data = DB::table('dbo.Financeiro_Propostas_Matrix')
        ->select(
                 'dbo.Financeiro_Propostas_Matrix.id as Id',
                 'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
                 'dbo.Financeiro_Propostas_Hist.versao as Versao',
                 'dbo.Financeiro_Propostas_Matrix.segmento as segmento_codigo',
                 'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as Segmento',
                 'dbo.Financeiro_Propostas_Matrix.grupo as grupo_codigo',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                 'dbo.Financeiro_Propostas_Matrix.cliente as CodigoCliente',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                 'dbo.users.name as Solicitante',
                 'dbo.Financeiro_Propostas_Matrix.referral as Referral',
                 DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
                 'dbo.Financeiro_Propostas_Matrix.escopo as Escopo',
                 'dbo.Financeiro_Propostas_Matrix.obs as Observacao',
                 DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.data_cadastro AS Date) as Data'), )
        ->leftjoin('dbo.Financeiro_Propostas_Hist', 'dbo.Financeiro_Propostas_Matrix.proposta', '=', 'dbo.Financeiro_Propostas_Hist.proposta')
        ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
        ->leftjoin('dbo.users', 'dbo.Financeiro_Propostas_Matrix.advogado', '=', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'dbo.Financeiro_Propostas_Matrix.segmento', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('dbo.Financeiro_Propostas_Matrix.id','=', $id)
        ->first();

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                 'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

        return view('Painel.Financeiro.Propostas.solicitacao.edit', compact('clientes','datahoje','segmentos','grupos','data','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function solicitacao_update(Request $request) {


        $carbon= Carbon::now();
        $segmento = $request->get('segmento');
        $grupo = $request->get('grupo');
        $cliente = $request->get('cliente');
        $socio = $request->get('socio');
        $referral = $request->get('referral');
        $valorsemformato = $request->get('valor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
        $escopo = $request->get('escopo');
        $observacao = $request->get('observacao');
        $proposta = $request->get('proposta');
        $id = $request->get('id');

        $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo','=', $cliente)->value('Unidade'); 
        $setor = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Setor')->where('Codigo','=', $cliente)->value('Setor'); 


        //Update na Matrix
        DB::table('dbo.Financeiro_Propostas_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array(
            'setor' => $setor,
            'unidade' => $unidade, 
            'segmento' => $segmento,
            'grupo' => $grupo,
            'cliente' => $cliente,
            'socio' => $socio,
            'advogado' => Auth::user()->cpf,
            'referral' => $referral,
            'valor' => $valor,
            'escopo' => $escopo,
            'obs' => $observacao,
            'status_id' => '1'
        ));


     //Insert na Hist
      $values = array(
        'proposta' => $proposta, 
        'versao' => '2',
        'data_alteracao' => $carbon, 
        'valor' => $valor,
        'status' => '1',
        'advogado' => Auth::user()->cpf);
        DB::table('dbo.Financeiro_Propostas_Hist')->insert($values); 

       //Envia notificação para a Revisão Propostas do setor de custo

       //Envia e-mail para a Revisão Propostas do setor de custo

       flash('Edição da proposta realizada com sucesso !')->success();

       return redirect()->route('Painel.Proposta.solicitacao.index');

    }

    public function hierarquia_index() {

        $datas = DB::table('dbo.Financeiro_Propostas_Matrix')
             ->select(
            'dbo.Financeiro_Propostas_Matrix.id as Id',   
            'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
            'dbo.Financeiro_Propostas_Matrix.data_cadastro as Data',
            'dbo.Financeiro_Propostas_Matrix.anexo as anexo',
            'dbo.users.name as Solicitante',
            'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
            'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
            'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
            'dbo.Financeiro_Propostas_Matrix.anexo as Anexo',
            DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
            'dbo.Financeiro_Propostas_Matrix.status_id as StatusID',
            'dbo.Financeiro_Propostas_Status.descricao as Status')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
            ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('dbo.users', 'dbo.Financeiro_Propostas_Matrix.solicitante', 'dbo.users.cpf')
            ->join('dbo.web_hierarquia', 'dbo.Financeiro_Propostas_Matrix.solicitante', 'dbo.web_hierarquia.advogado')
            ->where('dbo.Financeiro_Propostas_Matrix.solicitante', '=', Auth::user()->cpf)
            ->orWhere('dbo.web_hierarquia.responsavel','=', '03089973600')
            ->get();
                
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
                   
        $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                     'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
            
        return view('Painel.Financeiro.Propostas.hierarquia.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));
         
    }

    public function hierarquia_create() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $segmentos = DB::table('PLCFULL.dbo.jurid_grupofinanceiro')
        ->orderBy('nome_grupofinanceiro', 'asc')
        ->get();

        $grupos = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
        ->orderBy('Descricao', 'asc')
        ->select('Codigo', 'Descricao')
        ->get();

        $clientes = DB::table('PLCFULL.dbo.Jurid_CliFor')
        ->orderBy('Razao', 'asc')
        ->select('Codigo', 'Razao')
        ->where('Status', 'Ativo')
        ->get();

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                 'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

        return view('Painel.Financeiro.Propostas.hierarquia.create', compact('datahoje','segmentos','clientes','grupos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function hierarquia_store(Request $request) {

        $carbon= Carbon::now();
        $segmento = $request->get('segmento');
        $grupo = $request->get('grupo');
        $cliente = $request->get('cliente');
        $socio = $request->get('socio');
        $data = $request->get('data');
        $referral = $request->get('referral');
        $valorsemformato = $request->get('valor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
        $escopo = $request->get('escopo');
        $observacao = $request->get('observacao');

        $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo','=', $cliente)->value('Unidade'); 
        
        $setor = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Setor')->where('Codigo','=', $cliente)->value('Setor'); 
        $ano= $carbon->format('Y');
        $antigoid = DB::table('dbo.Financeiro_Propostas_Matrix')->select('id')->orderBy('id', 'desc')->value('id'); 
        $novoid = $antigoid + 1;
        $proposta = $ano.'-'.$unidade.'-'.$setor.'-'.$novoid;

        //Grava Anexo
        $image = $request->file('select_file');
        $new_name = $proposta . '.'  . $image->getClientOriginalExtension();
        $image->storeAs('propostas', $new_name);

        //Grava na Matrix
        $values = array(
            'proposta' => $proposta, 
            'data_cadastro' => $data, 
            'setor' => $setor, 
            'unidade' => $unidade,
            'segmento' => $segmento,
            'grupo' => $grupo,
            'cliente' => $cliente,
            'socio' => $socio,
            'advogado' => Auth::user()->cpf,
            'referral' => $referral,
            'valor' => $valor,
            'escopo' => $escopo,
            'obs' => $observacao,
            'anexo' => $new_name,
            'solicitante' => Auth::user()->cpf,
            'status_id' => '1');
        DB::table('dbo.Financeiro_Propostas_Matrix')->insert($values);

      //Grava na Hist
       $values = array(
        'proposta' => $proposta, 
        'versao' => '1', 
        'data_alteracao' => $data, 
        'valor' => $valor,
        'status' => '1',
        'advogado' => Auth::user()->cpf);
       DB::table('dbo.Financeiro_Propostas_Hist')->insert($values);

       flash('Nova proposta cadastrada com sucesso !')->success();

       //Envia notificação para a Revisão Propostas do setor de custo

       //Envia e-mail para a Revisão Propostas do setor de custo

       return redirect()->route('Painel.Proposta.hierarquia.index');

    }

    public function hierarquia_editar($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $segmentos = DB::table('PLCFULL.dbo.jurid_grupofinanceiro')
        ->orderBy('nome_grupofinanceiro', 'asc')
        ->get();

        $grupos = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
        ->orderBy('Descricao', 'asc')
        ->select('Codigo', 'Descricao')
        ->get();

        $clientes = DB::table('PLCFULL.dbo.Jurid_CliFor')
        ->orderBy('Razao', 'asc')
        ->select('Codigo', 'Razao')
        ->where('Status', 'Ativo')
        ->get();


        $data = DB::table('dbo.Financeiro_Propostas_Matrix')
        ->select(
                 'dbo.Financeiro_Propostas_Matrix.id as Id',
                 'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
                 'dbo.Financeiro_Propostas_Hist.versao as Versao',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.cpf as SolicitanteCPF',
                 'dbo.Financeiro_Propostas_Matrix.segmento as segmento_codigo',
                 'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as Segmento',
                 'dbo.Financeiro_Propostas_Matrix.grupo as grupo_codigo',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                 'dbo.Financeiro_Propostas_Matrix.cliente as CodigoCliente',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                 'dbo.Financeiro_Propostas_Matrix.referral as Referral',
                 DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
                 'dbo.Financeiro_Propostas_Matrix.escopo as Escopo',
                 'dbo.Financeiro_Propostas_Matrix.obs as Observacao',
                 DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.data_cadastro AS Date) as Data'), )
        ->leftjoin('dbo.Financeiro_Propostas_Hist', 'dbo.Financeiro_Propostas_Matrix.proposta', '=', 'dbo.Financeiro_Propostas_Hist.proposta')
        ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
        ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'dbo.Financeiro_Propostas_Matrix.segmento', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.users', 'dbo.Financeiro_Propostas_Matrix.solicitante', 'dbo.users.cpf')
        ->where('dbo.Financeiro_Propostas_Matrix.id','=', $id)
        ->first();

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                 'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

    return view('Painel.Financeiro.Propostas.hierarquia.editar', compact('clientes','datahoje','segmentos','grupos','data','totalNotificacaoAbertas', 'notificacoes'));

    }
    
    public function hierarquia_update(Request $request) {

        
        $carbon= Carbon::now();
        $segmento = $request->get('segmento');
        $grupo = $request->get('grupo');
        $cliente = $request->get('cliente');
        $socio = $request->get('socio');
        $referral = $request->get('referral');
        $valorsemformato = $request->get('valor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
        $escopo = $request->get('escopo');
        $observacao = $request->get('observacao');
        $proposta = $request->get('proposta');
        $id = $request->get('id');

        $unidade = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Unidade')->where('Codigo','=', $cliente)->value('Unidade'); 
        $setor = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Setor')->where('Codigo','=', $cliente)->value('Setor'); 


        //Update na Matrix
        DB::table('dbo.Financeiro_Propostas_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array(
            'setor' => $setor,
            'unidade' => $unidade, 
            'segmento' => $segmento,
            'grupo' => $request->get('grupo'),
            'cliente' => $request->get('cliente'),
            'socio' => $socio,
            'referral' => $referral,
            'valor' => $valor,
            'escopo' => $escopo,
            'obs' => $observacao,
            'status_id' => '1'
        ));

        //Insert na Hist
        $values = array(
            'proposta' => $proposta, 
            'versao' => '2',
            'data_alteracao' => $carbon, 
            'valor' => $valor,
            'status' => '1',
            'advogado' => Auth::user()->cpf);
            DB::table('dbo.Financeiro_Propostas_Hist')->insert($values); 

     $solicitante_cpf = $request->get('solicitante');
     $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf', $solicitante_cpf)->value('id'); 
     $soliciante_email = DB::table('dbo.users')->select('email')->where('cpf', $solicitante_cpf)->value('email'); 
     $solicitante_nome = DB::table('dbo.users')->select('name')->where('cpf', $solicitante_cpf)->value('name'); 

     $status_descricao = DB::table('dbo.Financeiro_Propostas_Status')->select('descricao')->where('id', '1')->value('descricao');
     $cliente = DB::table('PLCFULL.dbo.Jurid_CliFor')->select('Razao')->where('Codigo', $request->get('cliente'))->value('Razao');
     $grupo = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')->select('Descricao')->where('Codigo', $request->get('grupo'))->value('Descricao');

     //Envia notificação para o solicitante
     $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '7', 'obs' => 'Propostas: O responsável da hierarquia acaba de atualizar o status de sua proposta' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values3);

     //Envia e-mail para o solicitante
     Mail::to($soliciante_email)
     ->send(new AtualizadaProposta($proposta, $unidade, $setor, $cliente, $grupo, $valor, $solicitante_nome, $status_descricao, $carbon));


    flash('Edição da proposta realizada com sucesso !')->success();

    return redirect()->route('Painel.Proposta.hierarquia.index');

    }

    public function revisao_index() {


      $QuantidadeAguardandoRevisao = DB::table('dbo.Financeiro_Propostas_Matrix')->where('status_id', '1')->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id)->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')->count();
      $QuantidadePropostasAprovadas = DB::table('dbo.Financeiro_Propostas_Matrix')->where('status_id', '3')->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id)->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')->count();
      $QuantidadePropostasReprovadas = DB::table('dbo.Financeiro_Propostas_Matrix')->where('status_id', '4')->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id)->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')->count();
      $QuantidadePropostasCanceladas = DB::table('dbo.Financeiro_Propostas_Matrix')->where('status_id', '5')->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id)->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')->count();
      $QuantidadePropostasSubstituidas = DB::table('dbo.Financeiro_Propostas_Matrix')->where('status_id', '6')->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id)->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')->count();
      $QuantidadeSetores = DB::table('dbo.setor_custo_user')->where('user_id', Auth::user()->id)->count();
  
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

       $datas = DB::table('dbo.Financeiro_Propostas_Matrix')
             ->select(
            'dbo.Financeiro_Propostas_Matrix.id as Id',   
            'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
            'dbo.Financeiro_Propostas_Matrix.data_cadastro as Data',
            'dbo.users.name as Solicitante',
            'dbo.Financeiro_Propostas_Matrix.anexo as Anexo',
            'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
            'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
            'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
            DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
            'dbo.Financeiro_Propostas_Matrix.status_id as StatusID',
            'dbo.Financeiro_Propostas_Status.descricao as Status')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
            ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')   
            ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
            ->leftjoin('dbo.users', 'dbo.Financeiro_Propostas_Matrix.solicitante', 'dbo.users.cpf')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id) 
            ->where('dbo.Financeiro_Propostas_Matrix.status_id', '1')
            ->get();
  

      return view('Painel.Financeiro.Propostas.revisao.index', compact('QuantidadeAguardandoRevisao','QuantidadePropostasAprovadas','QuantidadePropostasReprovadas','QuantidadePropostasCanceladas','QuantidadePropostasSubstituidas','QuantidadeSetores','datas','totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function revisao_listagemrevisar() {

    $datas = DB::table('dbo.Financeiro_Propostas_Matrix')
        ->select(
       'dbo.Financeiro_Propostas_Matrix.id as Id',   
       'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
       'dbo.Financeiro_Propostas_Matrix.data_cadastro as Data',
       'dbo.users.name as Solicitante',
       'dbo.Financeiro_Propostas_Matrix.anexo as Anexo',
       'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as Grupo',
       'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
       'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
       'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
       DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
       'dbo.Financeiro_Propostas_Matrix.status_id as StatusID',
       'dbo.Financeiro_Propostas_Status.descricao as Status')
       ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
       ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')   
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->leftjoin('dbo.users', 'dbo.Financeiro_Propostas_Matrix.solicitante', 'dbo.users.cpf')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->where('dbo.setor_custo_user.user_id', '=', Auth::user()->id) 
       ->where('dbo.Financeiro_Propostas_Matrix.status_id', '1')
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

       return view('Painel.Financeiro.Propostas.revisao.listagemrevisar', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function revisao_revisarproposta($id) {

        $data = DB::table('dbo.Financeiro_Propostas_Matrix')
        ->select(
                 'dbo.Financeiro_Propostas_Matrix.id as Id',
                 'dbo.Financeiro_Propostas_Matrix.proposta as NumeroProposta',
                 'dbo.Financeiro_Propostas_Hist.versao as Versao',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.cpf as SolicitanteCPF',
                 'dbo.Financeiro_Propostas_Matrix.segmento as segmento_codigo',
                 'PLCFULL.dbo.jurid_grupofinanceiro.nome_grupofinanceiro as Segmento',
                 'dbo.Financeiro_Propostas_Matrix.grupo as grupo_codigo',
                 'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
                 'dbo.Financeiro_Propostas_Matrix.cliente as CodigoCliente',
                 'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.Financeiro_Propostas_Matrix.referral as Referral',
                 DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.valor AS NUMERIC(15,2)) as Valor'), 
                 'dbo.Financeiro_Propostas_Matrix.escopo as Escopo',
                 'dbo.Financeiro_Propostas_Matrix.obs as Observacao',
                 DB::raw('CAST(dbo.Financeiro_Propostas_Matrix.data_cadastro AS Date) as Data'), )
        ->leftjoin('dbo.Financeiro_Propostas_Hist', 'dbo.Financeiro_Propostas_Matrix.proposta', '=', 'dbo.Financeiro_Propostas_Hist.proposta')
        ->leftjoin('dbo.Financeiro_Propostas_Status', 'dbo.Financeiro_Propostas_Matrix.status_id', '=', 'dbo.Financeiro_Propostas_Status.id')
        ->leftjoin('PLCFULL.dbo.jurid_grupofinanceiro', 'dbo.Financeiro_Propostas_Matrix.segmento', '=', 'PLCFULL.dbo.jurid_grupofinanceiro.codigo_grupofinanceiro')
        ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'dbo.Financeiro_Propostas_Matrix.grupo', '=', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'dbo.Financeiro_Propostas_Matrix.cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Financeiro_Propostas_Matrix.setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Financeiro_Propostas_Matrix.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.users', 'dbo.Financeiro_Propostas_Matrix.solicitante', 'dbo.users.cpf')
        ->where('dbo.Financeiro_Propostas_Matrix.id','=', $id)
        ->first();

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')->where('status', 'A')->where('destino_id','=', Auth::user()->id)->count();
              
        $notificacoes = DB::table('dbo.Hist_Notificacao')
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                 'data','id_ref', 'user_id','tipo', 'obs','hist_notificacao.status', 'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

    return view('Painel.Financeiro.Propostas.revisao.revisarsolicitacao', compact('data','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function revisao_update(Request $request) {

        $usuario_id = Auth::user()->id;
        $carbon= Carbon::now();
        $segmento = $request->get('segmento');
        $grupo = $request->get('grupo');
        $cliente = $request->get('cliente');
        $socio = $request->get('socio');
        $referral = $request->get('referral');
        $valorsemformato = $request->get('valor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
        $escopo = $request->get('escopo');
        $observacao = $request->get('observacao');
        $proposta = $request->get('proposta');
        $id = $request->get('id');
        $setor = $request->get('setor');
        $unidade = $request->get('unidade');
        $status_id = $request->get('status');

        $novaproposta = $request->get('novaproposta');

        //Se o status for de substituida 
        if($status_id == 6) {

        //Update na Matrix
        DB::table('dbo.Financeiro_Propostas_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array(
            'obs' => $observacao,
            'status_id' => $status_id,
            'novaproposta' => $novaproposta,
        ));

            
        }
        //Se não
        else {

        //Update na Matrix
        DB::table('dbo.Financeiro_Propostas_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array(
            'obs' => $observacao,
            'status_id' => $status_id
        ));

        }

     //Insert na Hist
      $values = array(
      'proposta' => $proposta, 
      'versao' => '2',
      'data_alteracao' => $carbon, 
      'valor' => $valor,
      'status' => $status_id,
      'advogado' => Auth::user()->cpf);
      DB::table('dbo.Financeiro_Propostas_Hist')->insert($values); 


    $solicitante_cpf = $request->get('solicitante');
    $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf', $solicitante_cpf)->value('id'); 
    $soliciante_email = DB::table('dbo.users')->select('email')->where('cpf', $solicitante_cpf)->value('email'); 
    $solicitante_nome = DB::table('dbo.users')->select('name')->where('cpf', $solicitante_cpf)->value('name'); 

    $status_descricao = DB::table('dbo.Financeiro_Propostas_Status')->select('descricao')->where('id', $status_id)->value('descricao');

    //Envia notificação para o solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => $usuario_id, 'destino_id' => $solicitante_id, 'tipo' => '7', 'obs' => 'Propostas: O responsável pela revisão da proposta acaba de atualizar o status.' ,'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia e-mail para o solicitante
    Mail::to($soliciante_email)
    ->send(new AtualizadaProposta($proposta, $unidade, $setor, $cliente, $grupo, $valor, $solicitante_nome, $status_descricao, $carbon));

    flash('Revisão da proposta realizada com sucesso !')->success();

    return redirect()->route('Painel.Proposta.revisao.revisar');

    }

    public function revisao_listagemgeral() {



    }




    
  
}
