<?php

namespace App\Http\Controllers\Painel\DPRH;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\DPRH\MudancaArea\CoordenadorOrigemRevisar;
use App\Mail\DPRH\MudancaArea\CoordenadorDestinoRevisar;
use App\Mail\DPRH\MudancaArea\GerenteRevisar;
use App\Mail\DPRH\MudancaArea\CEORevisar;


class MudancaAreaController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    public function advogado_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

          
        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                  'PLCFULL.dbo.Jurid_Setor.Codigo as SetorDestinoCodigo',
                  'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->where('user_id', Auth::user()->id)
        ->get();

        //Pego o setor de custo dele
        $setoratual = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Setor')->where('PLCFULL.dbo.Jurid_Advogado.Codigo', Auth::user()->cpf)->value('Setor');                          
    
        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('Codigo', 'Descricao')
        ->where('Ativo', '=', '1')
        ->where('Codigo', '!=', $setoratual)
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

       
      return view('Painel.DPRH.MudancaArea.Advogado.index', compact('datahoje','datas','setoratual','setores','totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function advogado_solicitacaocriada(Request $request) {

        $setororigem_codigo = $request->get('setororigem_codigo');
        $setordestino_codigo = $request->get('setor');
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();

        //Grava na Matrix
        $values = array('user_id' => Auth::user()->id, 
                        'setor_origem' => $setororigem_codigo, 
                        'setor_destino' => $setordestino_codigo,
                        'observacao' => $observacao,
                        'status_id' => '1',
                        'data_solicitacao' => $carbon,
                        'data_edicao' => $carbon);
        DB::table('dbo.DPRH_MudancaArea_Matrix')->insert($values);

        $id = DB::table('dbo.DPRH_MudancaArea_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist 
        $values3= array(
                  'id_matrix' => $id,
                  'user_id' => Auth::user()->id, 
                  'status_id' => '1', 
                  'data' => $carbon);
         DB::table('dbo.DPRH_MudancaArea_Hist')->insert($values3); 


        //Manda notificação e e-mail ao Coordenador do setor
        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                  'PLCFULL.dbo.Jurid_Setor.Codigo as SetorDestinoCodigo',
                  'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->where('dbo.DPRH_MudancaArea_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setororigem_codigo)->value('Id'); 

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
        ->send(new CoordenadorOrigemRevisar($datas, $coordenador_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '12', 'obs' => 'Mudança de área: Nova solicitação criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        flash('Solicitação de mudança de área cadastrada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.MudancaArea.Advogado.index');

    }

    public function coordenador_index() {

        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_MudancaArea_Status.id as StatusID',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('dbo.users', 'dbo.DPRH_MudancaArea_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
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
        
               
        return view('Painel.DPRH.MudancaArea.Coordenador.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function coordenador_solicitacaorevisada(Request $request) {

        $setor = $request->get('setor');
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();
        $acao  = $request->get('acao');
        $id = $request->get('id');

        //Envia para o Coordenador do setor de destino para aprovação

        //Se foi aprovado
        if($acao == null) {

        //Update na Matrix     
        DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '2','data_edicao' => $carbon));    

        //Grava na Hist 
        $values3= array(
                  'id_matrix' => $id,
                  'user_id' => Auth::user()->id, 
                  'status_id' => '2', 
                  'data' => $carbon);
         DB::table('dbo.DPRH_MudancaArea_Hist')->insert($values3); 


        //Manda notificação e e-mail ao Coordenador do setor
        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->where('dbo.DPRH_MudancaArea_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

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
        ->send(new CoordenadorDestinoRevisar($datas, $coordenador_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '12', 'obs' => 'Mudança de área: Solicitação aprovada pelo Coordenador do setor de origem.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        //Se foi glosado
        else {

        }


        flash('Solicitação de mudança de área atualizada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.MudancaArea.Coordenador.index');


    }

    public function coordenador_solicitacaodestinorevisada(Request $request) {

        $id = $request->get('id');
        $setor = $request->get('setor');
        $acao = $request->get('acao');
        $carbon= Carbon::now();

        //Se for aprovado
        if($acao == null) {


        //Update na Matrix     
        DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '3','data_edicao' => $carbon));    

        //Grava na Hist 
        $values3= array(
                  'id_matrix' => $id,
                  'user_id' => Auth::user()->id, 
                  'status_id' => '3', 
                  'data' => $carbon);
         DB::table('dbo.DPRH_MudancaArea_Hist')->insert($values3); 


        //Manda notificação e e-mail ao Gerente da unidade
        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->where('dbo.DPRH_MudancaArea_Matrix.id', $id)
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

        $gerente_id = $gerente->id;
        $gerente_email = $gerente->email;
        $gerente_nome = $gerente->nome;

        Mail::to($gerente_email)
        ->send(new GerenteRevisar($datas, $gerente_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '12', 'obs' => 'Mudança de área: Solicitação aprovada pelo Coordenador do setor de destino.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        //Se for glosado
        else {

        }


        flash('Solicitação de mudança de área atualizada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.MudancaArea.Coordenador.index');


    }

    public function gerente_index() {

        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_MudancaArea_Status.id as StatusID',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('dbo.users', 'dbo.DPRH_MudancaArea_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
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
        
               
        return view('Painel.DPRH.MudancaArea.Gerente.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function gerente_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $setor = $request->get('setor');
        $acao = $request->get('acao');
        $carbon= Carbon::now();

        //Se for aprovado
        if($acao == null) {

        //Update na Matrix     
        DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '4','data_edicao' => $carbon));    

        //Grava na Hist 
        $values3= array(
                  'id_matrix' => $id,
                  'user_id' => Auth::user()->id, 
                  'status_id' => '4', 
                  'data' => $carbon);
         DB::table('dbo.DPRH_MudancaArea_Hist')->insert($values3); 


        //Manda notificação e e-mail ao Gerente da unidade
        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->where('dbo.DPRH_MudancaArea_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

        $ceo =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '37')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    

        $ceo_id = $ceo->id;
        $ceo_email = $ceo->email;
        $ceo_nome = $ceo->nome;

        Mail::to($ceo_email)
        ->send(new CEORevisar($datas, $ceo_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $ceo_id, 'tipo' => '12', 'obs' => 'Mudança de área: Solicitação aprovada pelo Gerente da unidade.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        //Se for glosado
        else {

        }
        

        flash('Solicitação de mudança de área atualizada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.MudancaArea.Gerente.index');

    }

    public function ceo_index() {

        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_MudancaArea_Status.id as StatusID',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->leftjoin('dbo.users', 'dbo.DPRH_MudancaArea_Matrix.user_id', 'dbo.users.id')
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
        
               
        return view('Painel.DPRH.MudancaArea.CEO.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }


    public function ceo_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $setor = $request->get('setor');
        $acao = $request->get('acao');
        $carbon= Carbon::now();

        //Se for aprovado
        if($acao == null) {

        //Update na Matrix     
        DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '5','data_edicao' => $carbon));    

        //Grava na Hist 
        $values3= array(
                  'id_matrix' => $id,
                  'user_id' => Auth::user()->id, 
                  'status_id' => '5', 
                  'data' => $carbon);
         DB::table('dbo.DPRH_MudancaArea_Hist')->insert($values3); 


        //Manda notificação e e-mail ao RH da unidade
        $datas = DB::table('dbo.DPRH_MudancaArea_Matrix')
        ->select('dbo.DPRH_MudancaArea_Matrix.id',
                  'dbo.DPRH_MudancaArea_Matrix.setor_origem as SetorOrigemCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDestinoDescricao',
                 'dbo.DPRH_MudancaArea_Status.descricao as Status',
                 'dbo.DPRH_MudancaArea_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_MudancaArea_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_MudancaArea_Matrix.setor_destino', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_MudancaArea_Status','dbo.DPRH_MudancaArea_Matrix.status_id', 'dbo.DPRH_MudancaArea_Status.id')
        ->where('dbo.DPRH_MudancaArea_Matrix.id', $id)
        ->get();

        //Envia ao RH ?

        }
        //Se for glosado
        else {

        }
        

        flash('Solicitação de mudança de área atualizada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.MudancaArea.CEO.index');

    }

    public function rh_index() {

    }

    public function rh_solicitacaofinalizada(Request $request) {

    }



        
 
}
