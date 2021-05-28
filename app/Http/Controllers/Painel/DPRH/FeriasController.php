<?php

namespace App\Http\Controllers\Painel\DPRH;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DPRH\Ferias\SubCoordenadorRevisar;
use App\Mail\DPRH\Ferias\CoordenadorRevisar;
use App\Mail\DPRH\Ferias\SolicitacaoGlosada;
use App\Mail\DPRH\Ferias\SuperintendenteRevisar;
use App\Mail\DPRH\Ferias\GerenteRevisar;
use App\Mail\DPRH\Ferias\RHRevisar;
use Illuminate\Support\Facades\Storage;
use App\Mail\DPRH\Ferias\SolicitacaoAguardandoData;

class FeriasController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    

    public function advogado_index() {
          
       $datas = DB::table('dbo.DPRH_Ferias_Matrix')
               ->select('dbo.DPRH_Ferias_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                        'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                        'dbo.DPRH_Ferias_Status.descricao as Status',
                        'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                        'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
               ->where('user_id', Auth::user()->id)
               ->get();


       $carbon= Carbon::now();
       $datahoje = $carbon->modify('+30 days')->format('Y-m-d');


       $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('Codigo', 'Descricao')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
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

       
      return view('Painel.DPRH.Ferias.Advogado.index', compact('setores','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function advogado_historico() {

             $datas = DB::table('dbo.DPRH_Ferias_Matrix')
               ->select('dbo.DPRH_Ferias_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                        'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                        'dbo.DPRH_Ferias_Status.descricao as Status',
                        'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                        'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
               ->where('user_id', Auth::user()->id)
               ->get();


       $carbon= Carbon::now();
       $datahoje = $carbon->format('Y-m-d');
                            
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

       
      return view('Painel.DPRH.Ferias.Advogado.historico', compact('datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function advogado_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $carbon= Carbon::now();


      //Se possuir SubCoordenador
      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

      $subcoordenador =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '36')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();    

      if($subcoordenador != null) {

        //Grava na Matrix
        $values3= array('user_id' => Auth::user()->id, 
                        'ferias_inicio' => $datainicio, 
                        'ferias_fim' => $datafim, 
                        'setor' => $setor, 
                        'observacao' => '',
                        'status' => '1',
                        'data_criacao' => $carbon,
                        'data_modificacao' => $carbon);
         DB::table('dbo.DPRH_Ferias_Matrix')->insert($values3);   

         $id = DB::table('dbo.DPRH_Ferias_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist
        $values3= array(  'id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '1', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 

        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        $subcoordenador_id = $subcoordenador->id;
        $subcoordenador_email = $subcoordenador->email;
        $subcoordenador_nome = $subcoordenador->nome;
  
        Mail::to($subcoordenador_email)
        ->send(new SubCoordenadorRevisar($datas, $subcoordenador_nome));
  
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $subcoordenador_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);



      } else {

        $coordenador =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '35')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    

        //Grava na Matrix
        $values3= array('user_id' => Auth::user()->id, 
                        'ferias_inicio' => $datainicio, 
                        'ferias_fim' => $datafim, 
                        'setor' => $setor, 
                        'observacao' => '',
                        'status' => '2',
                        'data_criacao' => $carbon,
                        'data_modificacao' => $carbon);
         DB::table('dbo.DPRH_Ferias_Matrix')->insert($values3);   

         $id = DB::table('dbo.DPRH_Ferias_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist
        $values3= array(  'id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 

         
        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        $coordenador_id = $coordenador->id;
        $coordenador_email = $coordenador->email;
        $coordenador_nome = $coordenador->nome;

        Mail::to($coordenador_email)
        ->send(new CoordenadorRevisar($datas, $coordenador_nome));
  
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

      }

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.Advogado.index');
    }

    public function subcoordenador_index() {

        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                        'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                        'dbo.DPRH_Ferias_Status.descricao as Status',
                        'dbo.DPRH_Ferias_Matrix.status as StatusID',
                        'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                        'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                        'dbo.users.name as SolicitanteNome')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(1,2,3,4,5,6))
       ->get();

       $carbon= Carbon::now();
       $datahoje = $carbon->modify('+30 days')->format('Y-m-d');

       $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('Codigo', 'Descricao')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
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

       
      return view('Painel.DPRH.Ferias.SubCoordenador.index', compact('setores','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function subcoordenador_revisarsolicitacao($id) {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome',
                      'dbo.users.id as SolicitanteID',
                      'dbo.users.email as SolicitanteEmail')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(1,2,3,4,5,6))
     ->orderBy('dbo.DPRH_Ferias_Matrix.id', $id)
     ->get();

     $datasolicitacao = DB::table('dbo.DPRH_Ferias_Matrix')->select('ferias_inicio')->where('id', $id)->value('ferias_inicio'); 

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


      return view('Painel.DPRH.Ferias.SubCoordenador.agenda', compact('datahoje','datasolicitacao','datas', 'totalNotificacaoAbertas', 'notificacoes'));


    }

    public function subcoordenador_solicitacaorevisada(Request $request) {

      $id = $request->get('id');
      $solicitante_email = $request->get('solicitante_email');
      $solicitante_id = $request->get('solicitante_id');
      $solicitante_nome = $request->get('solicitantenome');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $acao = $request->get('acao');
      $carbon= Carbon::now();
      $setor = $request->get('setor');

      //Se foi aprovado
      if($acao == null) {

        //Update Matrix
        DB::table('dbo.DPRH_Ferias_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '2', 'ferias_inicio' => $datainicio, 'ferias_fim' => $datafim,'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 

         $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Descricao', $setor)->value('Id'); 

        //Envia para o Coordenador do setor de custo copiando o solicitante
        $coordenador =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '35')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        $coordenador_id = $coordenador->id;
        $coordenador_email = $coordenador->email;
        $coordenador_nome = $coordenador->nome;

        Mail::to($coordenador_email)
        ->cc($solicitante_email)
        ->send(new CoordenadorRevisar($datas, $coordenador_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias revisada pelo Subcoordenador.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias revisada pelo Subcoordenador.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

      } 
      //Se foi glosado
      else {

        //Update Matrix
        DB::table('dbo.DPRH_Ferias_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '8', 'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '8', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        //Envia para o Solicitante informando que foi Glosado
        Mail::to($solicitante_email)
        ->send(new SolicitacaoGlosada($datas, $solicitante_nome));

      }

    
      flash('Solicitação revisada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.SubCoordenador.index');

      

    }

    public function subcoordenador_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $carbon= Carbon::now();

      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

      $coordenador =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '35')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();    

      //Grava na Matrix
      $values3= array('user_id' => Auth::user()->id, 
                        'ferias_inicio' => $datainicio, 
                        'ferias_fim' => $datafim, 
                        'setor' => $setor, 
                        'observacao' => '',
                        'status' => '2',
                        'data_criacao' => $carbon,
                        'data_modificacao' => $carbon);
      DB::table('dbo.DPRH_Ferias_Matrix')->insert($values3);   

      $id = DB::table('dbo.DPRH_Ferias_Matrix')->select('id')->orderby('id','desc')->value('id'); 

      //Grava na Hist
      $values3= array(  'id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon, 
                        'observacao' => '');
      DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 

      //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();


      $coordenador_id = $coordenador->id;
      $coordenador_email = $coordenador->email;
      $coordenador_nome = $coordenador->nome;

      Mail::to($coordenador_email)
      ->send(new CoordenadorRevisar($datas, $coordenador_nome));
  
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.SubCoordenador.index');

    }

    public function coordenador_index() {

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(2,3,4,5,6))
     ->get();

     $carbon= Carbon::now();
     $datahoje = $carbon->modify('+30 days')->format('Y-m-d');

     $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
     ->select('Codigo', 'Descricao')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->where('Ativo', '=', '1')
     ->where('setor_custo_user.user_id', '=', Auth::user()->id)
     ->orderBy('Codigo', 'asc')
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

     
    return view('Painel.DPRH.Ferias.Coordenador.index', compact('setores','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));


    }

    public function coordenador_revisarsolicitacao($id) {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome',
                      'dbo.users.id as SolicitanteID',
                      'dbo.users.email as SolicitanteEmail')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(2,3,4,5,6))
     ->orderBy('dbo.DPRH_Ferias_Matrix.id', $id)
     ->get();

     $datasolicitacao = DB::table('dbo.DPRH_Ferias_Matrix')->select('ferias_inicio')->where('id', $id)->value('ferias_inicio'); 

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


      return view('Painel.DPRH.Ferias.Coordenador.agenda', compact('datahoje','datasolicitacao','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function coordenador_solicitacaorevisada(Request $request) {
  
      $id = $request->get('id');
      $solicitante_email = $request->get('solicitante_email');
      $solicitante_id = $request->get('solicitante_id');
      $solicitante_nome = $request->get('solicitantenome');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $acao = $request->get('acao');
      $carbon= Carbon::now();
      $setor = $request->get('setor');

      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Descricao', $setor)->value('Id'); 

      //Se foi aprovado
      if($acao == null) {

      //Verifica se possui SuperIntendente, se não envia para o Gerente do setor 
      $superintendente =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '24')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();      

      if($superintendente != null) {

       //Update Matrix
       DB::table('dbo.DPRH_Ferias_Matrix')
       ->where('id', $id)  
       ->limit(1) 
       ->update(array('status' => '3', 'ferias_inicio' => $datainicio, 'ferias_fim' => $datafim,'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '3', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

      $superintendente_id = $superintendente->id;
      $superintendente_email = $superintendente->email;
      $superintendente_nome = $superintendente->nome;

      Mail::to($superintendente_email)
      ->send(new SuperintendenteRevisar($datas, $superintendente_nome));
  
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $superintendente_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias revisada pelo Coordenador.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);

      } 
      //Envia para o gerente 
      else {

      $gerente =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '23')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();          


      //Update Matrix
       DB::table('dbo.DPRH_Ferias_Matrix')
       ->where('id', $id)  
       ->limit(1) 
       ->update(array('status' => '4', 'ferias_inicio' => $datainicio, 'ferias_fim' => $datafim,'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '4', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

      $gerente_id = $gerente->id;
      $gerente_email = $gerente->email;
      $gerente_nome = $gerente->nome;

      Mail::to($gerente_email)
      ->send(new GerenteRevisar($datas, $gerente_nome));
  
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias revisada pelo Coordenador.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);

      }
    
    }
    //Se foi glosado 
    else {

        //Update Matrix
        DB::table('dbo.DPRH_Ferias_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '8', 'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '8', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        //Envia para o Solicitante informando que foi Glosado
        Mail::to($solicitante_email)
        ->send(new SolicitacaoGlosada($datas, $solicitante_nome));

    }

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.Coordenador.index');

    }

    public function coordenador_solicitacaocriada(Request $request) {


      $setor = $request->get('setor');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $carbon= Carbon::now();

      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

      $superintendente =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '24')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();  

      //Se possuir Superintendente
      if($superintendente != null) {

      //Grava na Matrix
      $values3= array('user_id' => Auth::user()->id, 
                        'ferias_inicio' => $datainicio, 
                        'ferias_fim' => $datafim, 
                        'setor' => $setor, 
                        'observacao' => '',
                        'status' => '3',
                        'data_criacao' => $carbon,
                        'data_modificacao' => $carbon);
      DB::table('dbo.DPRH_Ferias_Matrix')->insert($values3);   

      $id = DB::table('dbo.DPRH_Ferias_Matrix')->select('id')->orderby('id','desc')->value('id'); 

      //Grava na Hist
      $values3= array(  'id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '3', 
                        'data' => $carbon, 
                        'observacao' => '');
      DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 

      //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

      $superintendente_id = $superintendente->id;
      $superintendente_email = $superintendente->email;
      $superintendente_nome = $superintendente->nome;

      Mail::to($superintendente_email)
      ->send(new SuperintendenteRevisar($datas, $superintendente_nome));
  
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $superintendente_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);

      }
      //Envia para o gerente
      else {

        $gerente =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '23')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();          

        $values3= array('user_id' => Auth::user()->id, 
                        'ferias_inicio' => $datainicio, 
                        'ferias_fim' => $datafim, 
                        'setor' => $setor, 
                        'observacao' => '',
                        'status' => '4',
                        'data_criacao' => $carbon,
                        'data_modificacao' => $carbon);
         DB::table('dbo.DPRH_Ferias_Matrix')->insert($values3);   

        $id = DB::table('dbo.DPRH_Ferias_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist
        $values3= array(  'id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '4', 
                        'data' => $carbon, 
                        'observacao' => '');
        DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        $gerente_id = $gerente->id;
        $gerente_email = $gerente->email;
        $gerente_nome = $gerente->nome;

        Mail::to($gerente_email)
        ->send(new GerenteRevisar($datas, $superintendente_nome));
  
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);


      }


      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.Coordenador.index');

    }

    public function superintendente_index() {

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(3,4,5,6))
     ->get();

     $carbon= Carbon::now();
     $datahoje = $carbon->modify('+30 days')->format('Y-m-d');

     $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
     ->select('Codigo', 'Descricao')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->where('Ativo', '=', '1')
     ->where('setor_custo_user.user_id', '=', Auth::user()->id)
     ->orderBy('Codigo', 'asc')
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

     
    return view('Painel.DPRH.Ferias.Superintendente.index', compact('setores','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function superintendente_revisarsolicitacao($id) {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome',
                      'dbo.users.id as SolicitanteID',
                      'dbo.users.email as SolicitanteEmail')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(3,4,5,6))
     ->orderBy('dbo.DPRH_Ferias_Matrix.id', $id)
     ->get();

     $datasolicitacao = DB::table('dbo.DPRH_Ferias_Matrix')->select('ferias_inicio')->where('id', $id)->value('ferias_inicio'); 

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


      return view('Painel.DPRH.Ferias.Superintendente.agenda', compact('datahoje','datasolicitacao','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function superintendente_solicitacaorevisada(Request $request) {


      $id = $request->get('id');
      $solicitante_email = $request->get('solicitante_email');
      $solicitante_id = $request->get('solicitante_id');
      $solicitante_nome = $request->get('solicitantenome');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $acao = $request->get('acao');
      $carbon= Carbon::now();
      $setor = $request->get('setor');

      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Descricao', $setor)->value('Id'); 

      //Se foi aprovado
      if($acao == null) {


      $gerente =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '23')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();          

      //Update Matrix
       DB::table('dbo.DPRH_Ferias_Matrix')
       ->where('id', $id)  
       ->limit(1) 
       ->update(array('status' => '4', 'ferias_inicio' => $datainicio, 'ferias_fim' => $datafim,'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '4', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

      $gerente_id = $gerente->id;
      $gerente_email = $gerente->email;
      $gerente_nome = $gerente->nome;

      Mail::to($gerente_email)
      ->send(new GerenteRevisar($datas, $gerente_nome));
  
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias revisada pelo Superintendente.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);
    
    }
    //Se foi glosado 
    else {

        //Update Matrix
        DB::table('dbo.DPRH_Ferias_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '8', 'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '8', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        //Envia para o Solicitante informando que foi Glosado
        Mail::to($solicitante_email)
        ->send(new SolicitacaoGlosada($datas, $solicitante_nome));

    }

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.Superintendente.index');

    }

    public function superintendente_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $carbon= Carbon::now();

      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

      $gerente =  DB::table('dbo.users')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
      ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
      ->where('dbo.profiles.id', '=', '23')
      ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
      ->first();          

      $values3= array('user_id' => Auth::user()->id, 
                        'ferias_inicio' => $datainicio, 
                        'ferias_fim' => $datafim, 
                        'setor' => $setor, 
                        'observacao' => '',
                        'status' => '4',
                        'data_criacao' => $carbon,
                        'data_modificacao' => $carbon);
      DB::table('dbo.DPRH_Ferias_Matrix')->insert($values3);   

      $id = DB::table('dbo.DPRH_Ferias_Matrix')->select('id')->orderby('id','desc')->value('id'); 

      //Grava na Hist
      $values3= array(  'id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '4', 
                        'data' => $carbon, 
                        'observacao' => '');
      DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

      $gerente_id = $gerente->id;
      $gerente_email = $gerente->email;
      $gerente_nome = $gerente->nome;

      Mail::to($gerente_email)
      ->send(new GerenteRevisar($datas, $superintendente_nome));
  
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias criada.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.Superintendente.index');

    }

    public function ggp_index() {

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(4,5,6))
     ->get();

     $carbon= Carbon::now();
     $datahoje = $carbon->modify('+30 days')->format('Y-m-d');

     $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
     ->select('Codigo', 'Descricao')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->where('Ativo', '=', '1')
     ->where('setor_custo_user.user_id', '=', Auth::user()->id)
     ->orderBy('Codigo', 'asc')
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

     
    return view('Painel.DPRH.Ferias.GGP.index', compact('setores','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function ggp_revisarsolicitacao($id) {
      

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');

      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                      'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                      'dbo.DPRH_Ferias_Status.descricao as Status',
                      'dbo.DPRH_Ferias_Matrix.status as StatusID',
                      'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                      'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                      'dbo.users.name as SolicitanteNome',
                      'dbo.users.id as SolicitanteID',
                      'dbo.users.email as SolicitanteEmail')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
     ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
     ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
     ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
     ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(4,5,6))
     ->orderBy('dbo.DPRH_Ferias_Matrix.id', $id)
     ->get();

     $datasolicitacao = DB::table('dbo.DPRH_Ferias_Matrix')->select('ferias_inicio')->where('id', $id)->value('ferias_inicio'); 

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


      return view('Painel.DPRH.Ferias.GGP.agenda', compact('datahoje','datasolicitacao','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function ggp_solicitacaorevisada(Request $request) {

      $id = $request->get('id');
      $solicitante_email = $request->get('solicitante_email');
      $solicitante_id = $request->get('solicitante_id');
      $solicitante_nome = $request->get('solicitantenome');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $acao = $request->get('acao');
      $carbon= Carbon::now();
      $setor = $request->get('setor');

      $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Descricao', $setor)->value('Id'); 

      //Se foi aprovado
      if($acao == null) {     

      //Update Matrix
       DB::table('dbo.DPRH_Ferias_Matrix')
       ->where('id', $id)  
       ->limit(1) 
       ->update(array('status' => '5', 'ferias_inicio' => $datainicio, 'ferias_fim' => $datafim,'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '5', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

      //Envia e-mail ao RH
      Mail::to('ronaldo.amaral@plcadvogados.com.br')
      ->send(new RHRevisar($datas));
  
      //Notificação ao RH
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '239', 'tipo' => '10', 'obs' => 'Férias: Nova solicitação de férias revisada pelo Gerente.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);
    
    }
    //Se foi glosado 
    else {

        //Update Matrix
        DB::table('dbo.DPRH_Ferias_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status' => '8', 'data_modificacao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '8', 
                        'data' => $carbon, 
                        'observacao' => '');
         DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 


        //Busca as informações para enviar o e-mail
        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Ferias_Matrix.id', $id)
        ->get();

        //Envia para o Solicitante informando que foi Glosado
        Mail::to($solicitante_email)
        ->send(new SolicitacaoGlosada($datas, $solicitante_nome));

    }

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.GGP.index');

    }


    public function rh_index() {

        $datas = DB::table('dbo.DPRH_Ferias_Matrix')
        ->select('dbo.DPRH_Ferias_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Ferias_Matrix.ferias_inicio as DataFeriasInicio',
                        'dbo.DPRH_Ferias_Matrix.ferias_fim as DataFeriasFim',
                        'dbo.DPRH_Ferias_Status.descricao as Status',
                        'dbo.DPRH_Ferias_Matrix.status as StatusID',
                        'dbo.DPRH_Ferias_Matrix.data_criacao as DataSolicitacao',
                        'dbo.DPRH_Ferias_Matrix.data_modificacao as DataModificacao',
                        'dbo.users.name as SolicitanteNome',
                        'dbo.users.id as solicitante_id',
                        'dbo.users.email as solicitante_email',
                        'dbo.users.cpf as solicitante_cpf')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->leftjoin('dbo.DPRH_Ferias_Status','dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->whereIn('dbo.DPRH_Ferias_Matrix.status', array(5,6))
       ->get();
  
       $carbon= Carbon::now();
       $datahoje = $carbon->format('Y-m-d');

                            
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
  
       
      return view('Painel.DPRH.Ferias.RH.index', compact('datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function rh_solicitacaorevisada(Request $request) {

      $id = $request->get('id');
      $solicitante_nome = $request->get('solicitante');
      $solicitante_id = $request->get('solicitante_id');
      $solicitante_email = $request->get('solicitante_email');
      $solicitante_cpf = $request->get('solicitante_cpf');
      $setordescricao = $request->get('setor');
      $datainicio = $request->get('datainicio');
      $datafim = $request->get('datafim');
      $carbon= Carbon::now();

      //Anexa o documento
      $documento = $request->file('documento');
      $documento->storeAs('ferias', $documento->getClientOriginalName());
      Storage::disk('ferias-local')->put($documento->getClientOriginalName(), fopen($documento, 'r+'));

      $values = array(
        'Tabela_OR' => 'Advogados',
        'Codigo_OR' => $solicitante_cpf,
        'Id_OR' => '0',
        'Descricao' => $documento->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $documento->getClientOriginalName(), 
        'Data' => $carbon,
        'Nome' => $documento->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $documento->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $documento->getClientOriginalName(),
        'Obs' => 'Documento anexado pela equipe do RH no modulo de solicitação de férias.');
      DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 

      //Update Matrix
      DB::table('dbo.DPRH_Ferias_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status' => '6', 'data_modificacao' => $carbon));

      //Grava na Hist
      $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '6', 
                        'data' => $carbon, 
                        'observacao' => '');
      DB::table('dbo.DPRH_Ferias_Hist')->insert($values3); 

      //Busca as informações para enviar o e-mail
      $datas = DB::table('dbo.DPRH_Ferias_Matrix')
      ->select('dbo.DPRH_Ferias_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                 'dbo.DPRH_Ferias_Matrix.ferias_inicio as datainicio',
                 'dbo.DPRH_Ferias_Matrix.ferias_fim as datafim',
                 'dbo.DPRH_Ferias_Status.descricao as status',
                 'dbo.DPRH_Ferias_Matrix.data_criacao as data',
                 'dbo.users.name as solicitante')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Ferias_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
      ->leftjoin('dbo.DPRH_Ferias_Status', 'dbo.DPRH_Ferias_Matrix.status', 'dbo.DPRH_Ferias_Status.id')
      ->leftjoin('dbo.users', 'dbo.DPRH_Ferias_Matrix.user_id', 'dbo.users.id')
      ->where('dbo.DPRH_Ferias_Matrix.id', $id)
      ->get();

      $grupoadvogado =DB::table('PLCFULL.dbo.Jurid_Advogado')->select('GrupoAdv')->where('Codigo', '=', $solicitante_cpf)->value('GrupoAdv'); 
      
      //Se o solicitante for um advogado envia e-mail ao GGP (ggp@plcadvogados.com.br) copiando o T.I
      if($grupoadvogado != 15) {

      Mail::to($solicitante_email)
      ->cc('ronaldo.amaral@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br')
      ->send(new SolicitacaoAguardandoData($datas));

      } 
      else {
      Mail::to($solicitante_email)
      ->cc('ronaldo.amaral@plcadvogados.com.br')
      ->send(new SolicitacaoAguardandoData($datas));
      }

      //Notificação ao solicitante
      $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '10', 'obs' => 'Férias: Solicitação de férias aguardando data de ínicio.' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values4);

      flash('Solicitação cadastrada com sucesso!')->success();
      return redirect()->route('Painel.DPRH.Ferias.RH.index');


    }


  
 
}
