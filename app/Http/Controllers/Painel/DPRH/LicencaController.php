<?php

namespace App\Http\Controllers\Painel\DPRH;

use App\Models\Correspondente;
use App\Models\FichaArquivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\DPRH\Licenca\SubCoordenadorRevisar;
use App\Mail\DPRH\Licenca\CoordenadorRevisar;
use App\Mail\DPRH\Licenca\SolicitacaoCancelada;
use App\Mail\DPRH\Licenca\SuperintendenteRevisar;
use App\Mail\DPRH\Licenca\GerenteRevisar;
use App\Mail\DPRH\Licenca\GPPRevisar;


class LicencaController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    public function advogado_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

          
        $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->where('user_id', Auth::user()->id)
        ->get();


        $tipos = DB::table('dbo.DPRH_Licenca_Tipos')
        ->where('status', 'A')
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

       
      return view('Painel.DPRH.Licenca.Advogado.index', compact('datahoje','datas', 'tipos','totalNotificacaoAbertas', 'notificacoes'));
    }

    public function advogado_solicitacaocriada(Request $request) {

        $tipo_id = $request->get('tipo');
        $datainicio = $request->get('datainicio');
        $datafim = $request->get('datafim');
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();

        $setor = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Setor')->where('Codigo', Auth::user()->cpf)->value('Setor');

        //Grava na Matrix
        $values = array('user_id' => Auth::user()->id, 
                        'usuario_cpf' => Auth::user()->cpf, 
                        'setor' => $setor,
                        'tipo_id' => $tipo_id,
                        'data_inicio' => $datainicio,
                        'data_fim' => $datafim,
                        'status_id' => '1',
                        'observacao' => $observacao,
                        'data_criacao' => $carbon,
                        'data_edicao' => $carbon);
        DB::table('dbo.DPRH_Licenca_Matrix')->insert($values);

        $id = DB::table('dbo.DPRH_Licenca_Matrix')->select('id')->orderby('id','desc')->value('id'); 

        //Grava na Hist 
        $values3= array('id_matrix' => $id,
                  'user_id' => Auth::user()->id, 
                  'status_id' => '1', 
                  'data' => $carbon);
         DB::table('dbo.DPRH_Licenca_Hist')->insert($values3); 


        //Manda notificação e e-mail ao SubCoordenador do setor
        $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Licenca_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

        $subcoordenador =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '36')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    

        $subcoordenador_id = $subcoordenador->id;
        $subcoordenador_email = $subcoordenador->email;
        $subcoordenador_nome = $subcoordenador->nome;

        Mail::to($subcoordenador_email)
        ->send(new SubCoordenadorRevisar($datas, $subcoordenador_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $subcoordenador_id, 'tipo' => '11', 'obs' => 'Licença: Nova solicitação de licença criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        flash('Solicitação de licença cadastrada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.Licenca.Advogado.index');

    }

    public function subcoordenador_index() {


        $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
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

       
      return view('Painel.DPRH.Licenca.SubCoordenador.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function subcoordenador_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $carbon= Carbon::now();
        $setor = $request->get('setor_codigo');
        $acao = $request->get('acao');

        //Se for aprovado
        if($acao == null) {

            //Update Matrix 
            DB::table('dbo.DPRH_Licenca_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '2','data_edicao' => $carbon));

            //Grava na Hist 
            $values3= array(
            'id_matrix' => $id,
            'user_id' => Auth::user()->id, 
            'status_id' => '2', 
            'data' => $carbon);
            DB::table('dbo.DPRH_Licenca_Hist')->insert($values3); 

       //Manda notificação e e-mail ao Coordenador do setor
       $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Licenca_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

        $coordenador =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '36')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();    

        $coordenador_id = $coordenador->id;
        $coordenador_email = $coordenador->email;
        $coordenador_nome = $coordenador->nome;

        Mail::to($coordenador_email)
        ->send(new CoordenadorRevisar($datas, $coordenador_nome));

        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '10', 'obs' => 'Progressão: Solicitação de progressão aprovada pelo SubCoordenador.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        } 
        //Se foi glosado
        else {

            //Update Matrix 
            DB::table('dbo.DPRH_Licenca_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '6','data_edicao' => $carbon));

            //Grava na Hist 
            $values3= array(
                'id_matrix' => $id,
                'user_id' => Auth::user()->id, 
                'status_id' => '6', 
                'data' => $carbon);
            DB::table('dbo.DPRH_Licenca_Matrix')->insert($values3); 

            //Manda notificação e e-mail  ao Solicitante informando que foi Gloasdo
            $solicitante_cpf = DB::table('dbo.DPRH_Licenca_Matrix')->select('usuario_cpf')->where('id', $id)->value('usuario_cpf'); 
            $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf', $solicitante_cpf)->value('id'); 
            $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
            $solicitante_name = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 
    
            Mail::to($solicitante_email)
            ->send(new SolicitacaoCancelada($datas, $solicitante_name));
    
    
            //Manda notificação ao solicitante informando que foi cancelado
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação glosada.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        

        flash('Solicitação de licença revisada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.Licenca.SubCoordenador.index');
    }

    public function coordenador_index() {

        $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
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

       
      return view('Painel.DPRH.Licenca.Coordenador.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function coordenador_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $acao = $request->get('acao');
        $carbon= Carbon::now();
        $setor = $request->get('setor_codigo');
        $acao = $request->get('acao');


        //Se for aprovado
        if($acao == null) {

       //Manda notificação e e-mail ao Superintendente do setor
       $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Licenca_Matrix.id', $id)
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

        if($superintendente != null) {

            
            //Update Matrix 
            DB::table('dbo.DPRH_Licenca_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '3','data_edicao' => $carbon));

            //Grava na Hist 
            $values3= array(
            'id_matrix' => $id,
            'user_id' => Auth::user()->id, 
            'status_id' => '3', 
            'data' => $carbon);
            DB::table('dbo.DPRH_Licenca_Hist')->insert($values3); 

            $superintendente_id = $superintendente->id;
            $superintendente_email = $superintendente->email;
            $superintendente_name = $superintendente->nome;
    
            Mail::to($superintendente_email)
            ->send(new SuperintendenteRevisar($datas, $superintendente_name));
    
            //Manda notificação
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $superintendente_id, 'tipo' => '10', 'obs' => 'Progressão: Solicitação de progressão aprovada pelo Coordenador.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);
    

        } 
        //Se não tiver superintendente, envia pro Gerente
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
            DB::table('dbo.DPRH_Licenca_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '4', 'data_edicao' => $carbon));

            //Grava hist
            $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status_id' => '4', 
                        'data' => $carbon);
            DB::table('dbo.DPRH_Licenca_Hist')->insert($values3);  

            $gerente_id = $gerente->id;
            $gerente_email = $gerente->email;
            $gerente_name = $gerente->nome;
    
            Mail::to($gerente_email)
            ->send(new GerenteRevisar($datas, $gerente_name));

            //Manda notificação portal
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação de reembolso aprovada pelo Coordenador.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);    

        }

        } 
        //Se foi glosado
        else {

            //Update Matrix 
            DB::table('dbo.DPRH_Desligamento_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '6','data_edicao' => $carbon));

            //Grava na Hist 
            $values3= array(
                'id_matrix' => $id,
                'user_id' => Auth::user()->id, 
                'status' => '6', 
                'data' => $carbon);
            DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

            //Manda notificação e e-mail  ao Solicitante informando que foi Gloasdo
            $solicitante_cpf = DB::table('dbo.Contratacao_Matrix')->select('usuario_cpf')->where('id', $id)->value('usuario_cpf'); 
            $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf', $solicitante_cpf)->value('id'); 
            $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
            $solicitante_name = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 
    
            Mail::to($solicitante_email)
            ->send(new SolicitacaoCancelada($datas, $solicitante_name));
    
    
            //Manda notificação ao solicitante informando que foi cancelado
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação glosada.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        

        flash('Solicitação de licença revisada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.Licenca.Coordenador.index');

    }

    public function superintendente_index() {

        $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
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

       
      return view('Painel.DPRH.Licenca.Superintendente.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function superintendente_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $acao = $request->get('acao');
        $carbon= Carbon::now();
        $setor = $request->get('setor_codigo');


        //Se for aprovado
        if($acao == null) {

       //Manda notificação e e-mail ao Gerente do setor
       $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Licenca_Matrix.id', $id)
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
        DB::table('dbo.DPRH_Licenca_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '4', 'data_edicao' => $carbon));

        //Grava hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status_id' => '4', 
                        'data' => $carbon);
        DB::table('dbo.DPRH_Licenca_Hist')->insert($values3);  

        $gerente_id = $gerente->id;
        $gerente_email = $gerente->email;
        $gerente_name = $gerente->nome;
    
        Mail::to($gerente_email)
        ->send(new GerenteRevisar($datas, $gerente_name));

        //Manda notificação portal
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação de reembolso aprovada pelo Superintendente.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);    

        } 
        //Se foi glosado
        else {

            //Update Matrix 
            DB::table('dbo.DPRH_Licenca_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '6','data_edicao' => $carbon));

            //Grava na Hist 
            $values3= array(
                'id_matrix' => $id,
                'user_id' => Auth::user()->id, 
                'status_id' => '6', 
                'data' => $carbon);
            DB::table('dbo.DPRH_Licenca_Hist')->insert($values3); 

            //Manda notificação e e-mail  ao Solicitante informando que foi Gloasdo
            $solicitante_cpf = DB::table('dbo.Contratacao_Matrix')->select('usuario_cpf')->where('id', $id)->value('usuario_cpf'); 
            $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf', $solicitante_cpf)->value('id'); 
            $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
            $solicitante_name = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 
    
            Mail::to($solicitante_email)
            ->send(new SolicitacaoCancelada($datas, $solicitante_name));
    
    
            //Manda notificação ao solicitante informando que foi cancelado
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação glosada.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        

        flash('Solicitação de progressão revisada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.Licenca.Superintendente.index');

    }

    public function gerente_index() {

        $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
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

       
      return view('Painel.DPRH.Licenca.Gerente.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function gerente_solicitacaorevisada(Request $request) {

        $id = $request->get('id');
        $acao = $request->get('acao');
        $carbon= Carbon::now();
        $setor = $request->get('setor_codigo');
        $acao = $request->get('acao');


        //Se for aprovado
        if($acao == null) {

       //Manda notificação e e-mail ao Gerente do setor
       $datas = DB::table('dbo.DPRH_Licenca_Matrix')
        ->select('dbo.DPRH_Licenca_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Licenca_Tipos.descricao as Tipo',
                 'dbo.DPRH_Licenca_Status.id as StatusID',
                 'dbo.DPRH_Licenca_Status.descricao as Status',
                 'dbo.DPRH_Licenca_Matrix.data_inicio as DataInicio',
                 'dbo.DPRH_Licenca_Matrix.data_fim as DataFim',
                 'dbo.DPRH_Licenca_Matrix.data_criacao as DataSolicitacao',
                 'dbo.DPRH_Licenca_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Licenca_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Licenca_Status','dbo.DPRH_Licenca_Matrix.status_id', 'dbo.DPRH_Licenca_Status.id')
        ->leftjoin('dbo.DPRH_Licenca_Tipos', 'dbo.DPRH_Licenca_Matrix.tipo_id', 'dbo.DPRH_Licenca_Tipos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Licenca_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.DPRH_Licenca_Matrix.id', $id)
        ->get();

        //Update Matrix
        DB::table('dbo.DPRH_Licenca_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '5', 'data_edicao' => $carbon));

        //Grava hist
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status_id' => '5', 
                        'data' => $carbon);
        DB::table('dbo.DPRH_Licenca_Hist')->insert($values3); 

        //Envia ao GGP ??



        //Manda notificação portal
        // $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $ceo_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação de reembolso aprovada pelo Gerente.' ,'status' => 'A');
        // DB::table('dbo.Hist_Notificacao')->insert($values4);    

        } 
        //Se foi glosado
        else {

            //Update Matrix 
            DB::table('dbo.DPRH_Licenca_Matrix')
            ->where('id', $id)  
            ->limit(1) 
            ->update(array('status_id' => '6','data_edicao' => $carbon));

            //Grava na Hist 
            $values3= array(
                'id_matrix' => $id,
                'user_id' => Auth::user()->id, 
                'status_id' => '6', 
                'data' => $carbon);
            DB::table('dbo.DPRH_Licenca_Hist')->insert($values3); 

            //Manda notificação e e-mail  ao Solicitante informando que foi Gloasdo
            $solicitante_cpf = DB::table('dbo.Contratacao_Matrix')->select('usuario_cpf')->where('id', $id)->value('usuario_cpf'); 
            $solicitante_id = DB::table('dbo.users')->select('id')->where('cpf', $solicitante_cpf)->value('id'); 
            $solicitante_email = DB::table('dbo.users')->select('email')->where('id', $solicitante_id)->value('email'); 
            $solicitante_name = DB::table('dbo.users')->select('name')->where('id', $solicitante_id)->value('name'); 
    
            Mail::to($solicitante_email)
            ->send(new SolicitacaoCancelada($datas, $solicitante_name));
    
    
            //Manda notificação ao solicitante informando que foi cancelado
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '9', 'obs' => 'Progressão: Solicitação glosada.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        

        flash('Solicitação de progressão revisada com sucesso!')->success();
        return redirect()->route('Painel.DPRH.Licenca.Gerente.index');

    }

    

        
 
}
