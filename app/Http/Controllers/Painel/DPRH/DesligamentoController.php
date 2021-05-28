<?php

namespace App\Http\Controllers\Painel\DPRH;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\DPRH\Desligamento\SolicitacaoCriada;
use App\Mail\DPRH\Desligamento\PrepararDocumentacao;
use App\Mail\DPRH\Desligamento\SolicitacaoFinalizadaTI;
use App\Mail\DPRH\Desligamento\SolicitacaoFinalizada;
use App\Mail\DPRH\Desligamento\SolicitacaoLiberada;


class DesligamentoController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;


    public function advogado_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
        ->select('dbo.DPRH_Desligamento_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.users.name as UsuarioNome',
                 'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                 'dbo.DPRH_Desligamento_Status.descricao as Status',
                 'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                 'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
        ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
        ->where('user_id', Auth::user()->id)
        ->get();

        $motivos = DB::table('dbo.DPRH_Desligamento_Motivos')
        ->where('status', '=', 'A')
        ->where('tipo', '=', 'T')
        ->orderBy('descricao', 'asc')
        ->get();


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

       
      return view('Painel.DPRH.Desligamento.Advogado.index', compact('setores','motivos','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));


    }

    public function advogado_solicitacaocriada(Request $request) {

        $setor = $request->get('setor');
        $motivo_id = $request->get('motivo');
        $oportunidade = $request->get('oportunidade');
        $insatisfacao = $request->get('insatisfacao');
        $datasaida = $request->get('datasaida');
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();


        //Se for Desligamento imediato notifica os responsáveis do setor e envia para o RH
         if($motivo_id == 10) {

              //Grava na Matrix 
              $values3= array(
              'user_id' => Auth::user()->id, 
              'usuario_cpf' => Auth::user()->cpf, 
              'motivo_id' => $motivo_id, 
              'status_id' => '4', 
              'observacao' => $observacao, 
              'setor' => $setor,
              'data_solicitacao' => $carbon,
              'data_saida' => $datasaida,
              'data_edicao' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

             $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

              //Grava na Hist 
              $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '4', 
                        'data' => $carbon);
               DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 
               
               $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
               ->select('dbo.DPRH_Desligamento_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                        'dbo.DPRH_Desligamento_Status.descricao as Status',
                        'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                        'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                        'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
               ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
               ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
               ->get();

              //Foreach enviando notificação e e-mail
              $responsaveis = DB::table('dbo.users')
              ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
              ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
              ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
              ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
              ->select('dbo.users.id', 'dbo.users.email') 
              ->whereIn('dbo.profiles.id', array(23,24,35,36))
              ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
              ->get();   

              foreach ($responsaveis as $data) {
       
               $responsavel_id = $data->id;
               $responsavel_email = $data->email;
       
               Mail::to($responsavel_email)
               ->send(new SolicitacaoCriada($datas));
        
               $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
               DB::table('dbo.Hist_Notificacao')->insert($values4);
       
              }
              //Fim Foreach

              //Envia para a equipe do RH
              Mail::to('ronaldo.amaral@plcadvogados.com.br')
              ->send(new PrepararDocumentacao($datas));
       
              $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '186', 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento aguardando documentação.' ,'status' => 'A');
              DB::table('dbo.Hist_Notificacao')->insert($values4);


         }
        //Se não for Desligamento imediato envia para os responsáveis
         else {

              //Grava na Matrix 
              $values3= array(
                'user_id' => Auth::user()->id, 
                'usuario_cpf' => Auth::user()->cpf, 
                'motivo_id' => $motivo_id, 
                'status_id' => '1', 
                'observacao' => $observacao, 
                'oportunidadeobs' => $oportunidade,
                'instatisfacao' => $insatisfacao,
                'setor' => $setor,
                'data_solicitacao' => $carbon,
                'data_saida' => $datasaida,
                'data_edicao' => $carbon);
               DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   
  
               $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 
  
                //Grava na Hist 
                $values3= array('id_matrix' => $id,
                          'user_id' => Auth::user()->id, 
                          'status' => '1', 
                          'data' => $carbon);
                 DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

                 $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
                 ->select('dbo.DPRH_Desligamento_Matrix.id',
                          'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                          'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                          'dbo.DPRH_Desligamento_Status.descricao as Status',
                          'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                          'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                          'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
                 ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                 ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
                 ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
                 ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
                 ->get();

                
                //Foreach enviando notificação e e-mail
                $responsaveis = DB::table('dbo.users')
                ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
                ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
                ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
                ->select('dbo.users.id', 'dbo.users.email') 
                ->whereIn('dbo.profiles.id', array(23,24,35,36))
                ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
                ->get();   

                foreach ($responsaveis as $data) {
       
                $responsavel_id = $data->id;
                $responsavel_email = $data->email;
       
                Mail::to($responsavel_email)
                ->send(new SolicitacaoCriada($datas));
        
                $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
                DB::table('dbo.Hist_Notificacao')->insert($values4);
       
                }
                //Fim Foreach


         }

         flash('Nova solicitação registrada com sucesso !')->success();    

         return redirect()->route('Painel.DPRH.Desligamento.Advogado.index');



    }
    
    
    public function subcoordenador_index() {

       $carbon= Carbon::now();
       $datahoje = $carbon->format('Y-m-d');
          
       $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
        ->select('dbo.DPRH_Desligamento_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                 'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                 'dbo.DPRH_Desligamento_Status.id as StatusID',
                 'dbo.DPRH_Desligamento_Status.descricao as Status',
                 'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                 'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                 'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
        ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
        ->get();

        $motivos = DB::table('dbo.DPRH_Desligamento_Motivos')
        ->where('status', '=', 'A')
        ->where('tipo', '=', 'T')
        ->orWhere('tipo', '=', 'R')
        ->orderBy('descricao', 'asc')
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('Codigo', 'Descricao')
        ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
        ->where('Ativo', '=', '1')
        ->where('setor_custo_user.user_id', '=', Auth::user()->id)
        ->orderBy('Codigo', 'asc')
        ->get();

       $setor = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('PLCFULL.dbo.Jurid_Setor.Id')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
       ->value('PLCFULL.dbo.Jurid_Setor.Id');
 
        //Pegar todos os usuarios do setor de custo dela
        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name')  
        ->join('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->where('dbo.setor_custo_user.setor_custo_id', $setor)
        ->orderby('dbo.users.name', 'asc')
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

       
      return view('Painel.DPRH.Desligamento.Subcoordenador.index', compact('setores','usuarios','motivos','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function subcoordenador_historico() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->whereIn('dbo.DPRH_Desligamento_Matrix.status_id', array(5))
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

      
     return view('Painel.DPRH.Desligamento.Subcoordenador.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function subcoordenador_solicitacaoglosada(Request $request) {

      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');
      $solicitante_nome = $request->get('solicitante');

      date_default_timezone_set('America/Sao_Paulo');
        
      $from_name = "PORTAL PL&C ADVOGADOS";        
      $from_address = "automacao@plcadvogados.com.br";  
      $subject = '*****[PLC][Desligamento][Novo agendamento de reunião]*****';  
   


      //Foreach enviando para cada um da equipe responsável deste setor de custo reunião
      $responsaveis = DB::table('dbo.users')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->select('dbo.users.id', 'dbo.users.name','dbo.users.email') 
      ->whereIn('dbo.profiles.id', array(23,24,35))
      ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
      ->get();   

      foreach ($responsaveis as $data) {
     
        $responsavel_id = $data->id;
        $responsavel_email = $data->email;
        $responsavel_name = $data->name;

        $domain = 'exchangecore.com';
    
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());
    
        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
    
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<head>\n";
        $message .= "<style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
          padding: 5px;
          text-align: left;
        }
        </style>\n";
        $message .= "</head>\n";
        $message .= "<body>\n";
        $message .= '<p>Prezado(a), '. mb_convert_case($responsavel_name, MB_CASE_TITLE, "UTF-8").',</p>';
        $message .= '<br></br>';
        $message .= '<table style="width:100%">';
        $message .= '<tr>';
        $message .= '<td>Solicitante: '. mb_convert_case($solicitante_nome, MB_CASE_TITLE, "UTF-8").'</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<br></br><br></br>';
        $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
    
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:America/Sao_Paulo' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0300' . "\r\n" .
        'TZOFFSETTO:-0300' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .  
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$responsavel_email. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:recepcao@plcadvogados.com.br\r\n"' .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . 'TEAMS' . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
    
        $mailsent = mail($responsavel_email, $subject, $message, $headers);


        //Manda notificação
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Novo agendamento de reunião.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);
      }  

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '3','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '3', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      flash('Solicitação atualizada com sucesso !')->success();    

      return redirect()->route('Painel.DPRH.Desligamento.SubCoordenador.index');

    }

    public function subcoordenador_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $motivo_id = $request->get('motivo');
      $oportunidade = $request->get('oportunidade');
      $insatisfacao = $request->get('insatisfacao');
      $datasaida = $request->get('datasaida');
      $observacao = $request->get('observacao');
      $carbon= Carbon::now();


      //Se for Desligamento imediato notifica os responsáveis do setor e envia para o RH
      if($motivo_id == 10) {

            //Grava na Matrix 
            $values3= array(
            'user_id' => Auth::user()->id, 
            'usuario_cpf' => Auth::user()->cpf, 
            'motivo_id' => $motivo_id, 
            'status_id' => '4', 
            'observacao' => $observacao, 
            'setor' => $setor,
            'data_solicitacao' => $carbon,
            'data_saida' => $datasaida,
            'data_edicao' => $carbon);
           DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

           $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

            //Grava na Hist 
            $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '4', 
                      'data' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 
             
             $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
             ->select('dbo.DPRH_Desligamento_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                      'dbo.DPRH_Desligamento_Status.descricao as Status',
                      'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                      'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                      'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
             ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
             ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
             ->get();

            //Foreach enviando notificação e e-mail
            $responsaveis = DB::table('dbo.users')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->select('dbo.users.id', 'dbo.users.email') 
            ->whereIn('dbo.profiles.id', array(23,24,35))
            ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
            ->get();   

            foreach ($responsaveis as $data) {
     
             $responsavel_id = $data->id;
             $responsavel_email = $data->email;
     
             Mail::to($responsavel_email)
             ->send(new SolicitacaoCriada($datas));
      
             $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
             DB::table('dbo.Hist_Notificacao')->insert($values4);
     
            }
            //Fim Foreach

            //Envia para a equipe do RH
            Mail::to('ronaldo.amaral@plcadvogados.com.br')
            ->send(new PrepararDocumentacao($datas));
     
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '186', 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento aguardando documentação.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);


       }
      //Se não for Desligamento imediato envia para os responsáveis
       else {

            //Grava na Matrix 
            $values3= array(
              'user_id' => Auth::user()->id, 
              'usuario_cpf' => Auth::user()->cpf, 
              'motivo_id' => $motivo_id, 
              'status_id' => '2', 
              'observacao' => $observacao, 
              'oportunidadeobs' => $oportunidade,
              'instatisfacao' => $insatisfacao,
              'setor' => $setor,
              'data_solicitacao' => $carbon,
              'data_saida' => $datasaida,
              'data_edicao' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

             $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

              //Grava na Hist 
              $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon);
               DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

               $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
               ->select('dbo.DPRH_Desligamento_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                        'dbo.DPRH_Desligamento_Status.descricao as Status',
                        'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                        'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                        'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
               ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
               ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
               ->get();

               

              //Foreach enviando notificação e e-mail
              $responsaveis = DB::table('dbo.users')
              ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
              ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
              ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
              ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
              ->select('dbo.users.id', 'dbo.users.email') 
              ->whereIn('dbo.profiles.id', array(23,24,35))
              ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
              ->get();   

              foreach ($responsaveis as $data) {
     
              $responsavel_id = $data->id;
              $responsavel_email = $data->email;
     
              Mail::to($responsavel_email)
              ->send(new SolicitacaoCriada($datas));
      
              $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
              DB::table('dbo.Hist_Notificacao')->insert($values4);
     
              }
              //Fim Foreach


       }

       flash('Nova solicitação registrada com sucesso !')->success();    

       return redirect()->route('Painel.DPRH.Desligamento.SubCoordenador.index');


    }

    public function subcoordenador_solicitacaoliberada(Request $request) {

      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '2','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '2', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      //Informa a todos responsáveis do setor de custo
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
      ->select('dbo.DPRH_Desligamento_Matrix.id',
               'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
               'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
               'dbo.DPRH_Desligamento_Status.descricao as Status',
               'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
               'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
               'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
      ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
      ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
      ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
      ->get();

      

     //Foreach enviando notificação e e-mail
     $responsaveis = DB::table('dbo.users')
     ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
     ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
     ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
     ->select('dbo.users.id', 'dbo.users.email') 
     ->whereIn('dbo.profiles.id', array(23,24,35))
     ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
     ->get();   

     foreach ($responsaveis as $data) {

     $responsavel_id = $data->id;
     $responsavel_email = $data->email;

     Mail::to($responsavel_email)
     ->send(new SolicitacaoLiberada($datas));

     $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Solicitação de desligamento liberada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);

     }
     //Fim Foreach

    flash('Solicitação atualizada com sucesso !')->success();    

    return redirect()->route('Painel.DPRH.Desligamento.SubCoordenador.index');

    }

    public function coordenador_index() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->get();


       $motivos = DB::table('dbo.DPRH_Desligamento_Motivos')
       ->where('status', '=', 'A')
       ->where('tipo', '=', 'T')
       ->orWhere('tipo', '=', 'R')
       ->orderBy('descricao', 'asc')
       ->get();

       $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('Codigo', 'Descricao')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
       ->get();

      $setor = DB::table('PLCFULL.dbo.Jurid_Setor')
      ->select('PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
      ->where('Ativo', '=', '1')
      ->where('setor_custo_user.user_id', '=', Auth::user()->id)
      ->orderBy('Codigo', 'asc')
      ->value('PLCFULL.dbo.Jurid_Setor.Id');

       //Pegar todos os usuarios do setor de custo dela
       $usuarios =  DB::table('dbo.users')
       ->select('dbo.users.id', 'dbo.users.name')  
       ->join('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
       ->where('dbo.setor_custo_user.setor_custo_id', $setor)
       ->orderby('dbo.users.name', 'asc')
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

      
     return view('Painel.DPRH.Desligamento.Coordenador.index', compact('setores','usuarios','motivos','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function coordenador_historico() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->whereIn('dbo.DPRH_Desligamento_Matrix.status_id', array(5))
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

      
     return view('Painel.DPRH.Desligamento.Coordenador.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function coordenador_solicitacaoliberada(Request $request) {


      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');

       //Update Matrix 
       DB::table('dbo.DPRH_Desligamento_Matrix')
       ->where('id', $id)  
       ->limit(1) 
       ->update(array('status_id' => '2','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '2', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      //Informa a todos responsáveis do setor de custo
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
      ->select('dbo.DPRH_Desligamento_Matrix.id',
               'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
               'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
               'dbo.DPRH_Desligamento_Status.descricao as Status',
               'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
               'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
               'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
      ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
      ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
      ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
      ->get();

      

     //Foreach enviando notificação e e-mail
     $responsaveis = DB::table('dbo.users')
     ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
     ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
     ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
     ->select('dbo.users.id', 'dbo.users.email') 
     ->whereIn('dbo.profiles.id', array(23,24,36))
     ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
     ->get();   

     foreach ($responsaveis as $data) {

     $responsavel_id = $data->id;
     $responsavel_email = $data->email;

     Mail::to($responsavel_email)
     ->send(new SolicitacaoLiberada($datas));

     $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Solicitação de desligamento liberada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);

     }
     //Fim Foreach

    flash('Solicitação atualizada com sucesso !')->success();    

    return redirect()->route('Painel.DPRH.Desligamento.Coordenador.index');

    }

    public function coordenador_solicitacaoglosada(Request $request) {

      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');
      $solicitante_nome = $request->get('solicitante');

      date_default_timezone_set('America/Sao_Paulo');
        
      $from_name = "PORTAL PL&C ADVOGADOS";        
      $from_address = "automacao@plcadvogados.com.br";  
      $subject = '*****[PLC][Desligamento][Novo agendamento de reunião]*****';  
  

      //Foreach enviando para cada um da equipe responsável deste setor de custo reunião
      $responsaveis = DB::table('dbo.users')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->select('dbo.users.id', 'dbo.users.name','dbo.users.email') 
      ->whereIn('dbo.profiles.id', array(23,24,36))
      ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
      ->get();   

      foreach ($responsaveis as $data) {
     
        $responsavel_id = $data->id;
        $responsavel_email = $data->email;
        $responsavel_name = $data->name;

        $domain = 'exchangecore.com';
    
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());
    
        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
    
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<head>\n";
        $message .= "<style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
          padding: 5px;
          text-align: left;
        }
        </style>\n";
        $message .= "</head>\n";
        $message .= "<body>\n";
        $message .= '<p>Prezado(a), '. mb_convert_case($responsavel_name, MB_CASE_TITLE, "UTF-8").',</p>';
        $message .= '<br></br>';
        $message .= '<table style="width:100%">';
        $message .= '<tr>';
        $message .= '<td>Solicitante: '. mb_convert_case($solicitante_nome, MB_CASE_TITLE, "UTF-8").'</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<br></br><br></br>';
        $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
    
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:America/Sao_Paulo' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0300' . "\r\n" .
        'TZOFFSETTO:-0300' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .  
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$responsavel_email. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:recepcao@plcadvogados.com.br\r\n"' .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . 'TEAMS' . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
    
        $mailsent = mail($responsavel_email, $subject, $message, $headers);


        //Manda notificação
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Novo agendamento de reunião.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);
      }  

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '3','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '3', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      flash('Solicitação atualizada com sucesso !')->success();    

      return redirect()->route('Painel.DPRH.Desligamento.Coordenador.index');

    }

    public function coordenador_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $motivo_id = $request->get('motivo');
      $oportunidade = $request->get('oportunidade');
      $insatisfacao = $request->get('insatisfacao');
      $datasaida = $request->get('datasaida');
      $observacao = $request->get('observacao');
      $carbon= Carbon::now();


      //Se for Desligamento imediato notifica os responsáveis do setor e envia para o RH
      if($motivo_id == 10) {

            //Grava na Matrix 
            $values3= array(
            'user_id' => Auth::user()->id, 
            'usuario_cpf' => Auth::user()->cpf, 
            'motivo_id' => $motivo_id, 
            'status_id' => '4', 
            'observacao' => $observacao, 
            'setor' => $setor,
            'data_solicitacao' => $carbon,
            'data_saida' => $datasaida,
            'data_edicao' => $carbon);
           DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

           $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

            //Grava na Hist 
            $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '4', 
                      'data' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 
             
             $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
             ->select('dbo.DPRH_Desligamento_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                      'dbo.DPRH_Desligamento_Status.descricao as Status',
                      'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                      'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                      'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
             ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
             ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
             ->get();

            //Foreach enviando notificação e e-mail
            $responsaveis = DB::table('dbo.users')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->select('dbo.users.id', 'dbo.users.email') 
            ->whereIn('dbo.profiles.id', array(23,24))
            ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
            ->get();   

            foreach ($responsaveis as $data) {
     
             $responsavel_id = $data->id;
             $responsavel_email = $data->email;
     
             Mail::to($responsavel_email)
             ->send(new SolicitacaoCriada($datas));
      
             $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
             DB::table('dbo.Hist_Notificacao')->insert($values4);
     
            }
            //Fim Foreach

            //Envia para a equipe do RH
            Mail::to('ronaldo.amaral@plcadvogados.com.br')
            ->send(new PrepararDocumentacao($datas));
     
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '186', 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento aguardando documentação.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);


       }
      //Se não for Desligamento imediato envia para os responsáveis
       else {

            //Grava na Matrix 
            $values3= array(
              'user_id' => Auth::user()->id, 
              'usuario_cpf' => Auth::user()->cpf, 
              'motivo_id' => $motivo_id, 
              'status_id' => '2', 
              'observacao' => $observacao, 
              'oportunidadeobs' => $oportunidade,
              'instatisfacao' => $insatisfacao,
              'setor' => $setor,
              'data_solicitacao' => $carbon,
              'data_saida' => $datasaida,
              'data_edicao' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

             $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

              //Grava na Hist 
              $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon);
               DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

               $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
               ->select('dbo.DPRH_Desligamento_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                        'dbo.DPRH_Desligamento_Status.descricao as Status',
                        'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                        'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                        'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
               ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
               ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
               ->get();

               

              //Foreach enviando notificação e e-mail
              $responsaveis = DB::table('dbo.users')
              ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
              ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
              ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
              ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
              ->select('dbo.users.id', 'dbo.users.email') 
              ->whereIn('dbo.profiles.id', array(23,24))
              ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
              ->get();   

              foreach ($responsaveis as $data) {
     
              $responsavel_id = $data->id;
              $responsavel_email = $data->email;
     
              Mail::to($responsavel_email)
              ->send(new SolicitacaoCriada($datas));
      
              $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
              DB::table('dbo.Hist_Notificacao')->insert($values4);
     
              }
              //Fim Foreach


       }

       flash('Nova solicitação registrada com sucesso !')->success();    

       return redirect()->route('Painel.DPRH.Desligamento.Coordenador.index');

    }

    public function superintendente_index() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->get();


       $motivos = DB::table('dbo.DPRH_Desligamento_Motivos')
       ->where('status', '=', 'A')
       ->where('tipo', '=', 'T')
       ->orWhere('tipo', '=', 'R')
       ->orderBy('descricao', 'asc')
       ->get();

       $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('Codigo', 'Descricao')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
       ->get();

      $setor = DB::table('PLCFULL.dbo.Jurid_Setor')
      ->select('PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
      ->where('Ativo', '=', '1')
      ->where('setor_custo_user.user_id', '=', Auth::user()->id)
      ->orderBy('Codigo', 'asc')
      ->value('PLCFULL.dbo.Jurid_Setor.Id');

       //Pegar todos os usuarios do setor de custo dela
       $usuarios =  DB::table('dbo.users')
       ->select('dbo.users.id', 'dbo.users.name')  
       ->join('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
       ->where('dbo.setor_custo_user.setor_custo_id', $setor)
       ->orderby('dbo.users.name', 'asc')
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

      
     return view('Painel.DPRH.Desligamento.Superintendente.index', compact('setores','usuarios','motivos','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function superintendente_historico() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->whereIn('dbo.DPRH_Desligamento_Matrix.status_id', array(5))
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

      
     return view('Painel.DPRH.Desligamento.Superintendente.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function superintendente_solicitacaoglosada(Request $request) {

      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');
      $solicitante_nome = $request->get('solicitante');

      date_default_timezone_set('America/Sao_Paulo');
        
      $from_name = "PORTAL PL&C ADVOGADOS";        
      $from_address = "automacao@plcadvogados.com.br";  
      $subject = '*****[PLC][Desligamento][Novo agendamento de reunião]*****';  
   


      //Foreach enviando para cada um da equipe responsável deste setor de custo reunião
      $responsaveis = DB::table('dbo.users')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->select('dbo.users.id', 'dbo.users.name','dbo.users.email') 
      ->whereIn('dbo.profiles.id', array(23,35,36))
      ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
      ->get();   

      foreach ($responsaveis as $data) {
     
        $responsavel_id = $data->id;
        $responsavel_email = $data->email;
        $responsavel_name = $data->name;

        $domain = 'exchangecore.com';
    
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());
    
        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
    
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<head>\n";
        $message .= "<style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
          padding: 5px;
          text-align: left;
        }
        </style>\n";
        $message .= "</head>\n";
        $message .= "<body>\n";
        $message .= '<p>Prezado(a), '. mb_convert_case($responsavel_name, MB_CASE_TITLE, "UTF-8").',</p>';
        $message .= '<br></br>';
        $message .= '<table style="width:100%">';
        $message .= '<tr>';
        $message .= '<td>Solicitante: '. mb_convert_case($solicitante_nome, MB_CASE_TITLE, "UTF-8").'</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<br></br><br></br>';
        $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
    
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:America/Sao_Paulo' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0300' . "\r\n" .
        'TZOFFSETTO:-0300' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .  
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$responsavel_email. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:recepcao@plcadvogados.com.br\r\n"' .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . 'TEAMS' . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
    
        $mailsent = mail($responsavel_email, $subject, $message, $headers);


        //Manda notificação
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Novo agendamento de reunião.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);
      }  

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '3','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '3', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      flash('Solicitação atualizada com sucesso !')->success();    

      return redirect()->route('Painel.DPRH.Desligamento.Superintendente.index');

    }

    
    public function superintendnete_solicitacaoliberada(Request $request) {


      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '2','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '2', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      //Informa a todos responsáveis do setor de custo
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
      ->select('dbo.DPRH_Desligamento_Matrix.id',
               'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
               'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
               'dbo.DPRH_Desligamento_Status.descricao as Status',
               'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
               'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
               'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
      ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
      ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
      ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
      ->get();

      

     //Foreach enviando notificação e e-mail
     $responsaveis = DB::table('dbo.users')
     ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
     ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
     ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
     ->select('dbo.users.id', 'dbo.users.email') 
     ->whereIn('dbo.profiles.id', array(23,35,36))
     ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
     ->get();   

     foreach ($responsaveis as $data) {

     $responsavel_id = $data->id;
     $responsavel_email = $data->email;

     Mail::to($responsavel_email)
     ->send(new SolicitacaoLiberada($datas));

     $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Solicitação de desligamento liberada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);

     }
     //Fim Foreach

    flash('Solicitação atualizada com sucesso !')->success();    

    return redirect()->route('Painel.DPRH.Desligamento.Superintendente.index');

    }

    public function superintendente_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $motivo_id = $request->get('motivo');
      $oportunidade = $request->get('oportunidade');
      $insatisfacao = $request->get('insatisfacao');
      $datasaida = $request->get('datasaida');
      $observacao = $request->get('observacao');
      $carbon= Carbon::now();


      //Se for Desligamento imediato notifica os responsáveis do setor e envia para o RH
      if($motivo_id == 10) {

            //Grava na Matrix 
            $values3= array(
            'user_id' => Auth::user()->id, 
            'usuario_cpf' => Auth::user()->cpf, 
            'motivo_id' => $motivo_id, 
            'status_id' => '4', 
            'observacao' => $observacao, 
            'setor' => $setor,
            'data_solicitacao' => $carbon,
            'data_saida' => $datasaida,
            'data_edicao' => $carbon);
           DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

           $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

            //Grava na Hist 
            $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '4', 
                      'data' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 
             
             $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
             ->select('dbo.DPRH_Desligamento_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                      'dbo.DPRH_Desligamento_Status.descricao as Status',
                      'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                      'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                      'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
             ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
             ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
             ->get();

            //Foreach enviando notificação e e-mail
            $responsaveis = DB::table('dbo.users')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->select('dbo.users.id', 'dbo.users.email') 
            ->whereIn('dbo.profiles.id', array(23))
            ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
            ->get();   

            foreach ($responsaveis as $data) {
     
             $responsavel_id = $data->id;
             $responsavel_email = $data->email;
     
             Mail::to($responsavel_email)
             ->send(new SolicitacaoCriada($datas));
      
             $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
             DB::table('dbo.Hist_Notificacao')->insert($values4);
     
            }
            //Fim Foreach

            //Envia para a equipe do RH
            Mail::to('ronaldo.amaral@plcadvogados.com.br')
            ->send(new PrepararDocumentacao($datas));
     
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '186', 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento aguardando documentação.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);


       }
      //Se não for Desligamento imediato envia para os responsáveis
       else {

            //Grava na Matrix 
            $values3= array(
              'user_id' => Auth::user()->id, 
              'usuario_cpf' => Auth::user()->cpf, 
              'motivo_id' => $motivo_id, 
              'status_id' => '2', 
              'observacao' => $observacao, 
              'oportunidadeobs' => $oportunidade,
              'instatisfacao' => $insatisfacao,
              'setor' => $setor,
              'data_solicitacao' => $carbon,
              'data_saida' => $datasaida,
              'data_edicao' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

             $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

              //Grava na Hist 
              $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '2', 
                        'data' => $carbon);
               DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

               $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
               ->select('dbo.DPRH_Desligamento_Matrix.id',
                        'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                        'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                        'dbo.DPRH_Desligamento_Status.descricao as Status',
                        'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                        'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                        'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
               ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
               ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
               ->get();

               

              //Foreach enviando notificação e e-mail
              $responsaveis = DB::table('dbo.users')
              ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
              ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
              ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
              ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
              ->select('dbo.users.id', 'dbo.users.email') 
              ->whereIn('dbo.profiles.id', array(23))
              ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
              ->get();   

              foreach ($responsaveis as $data) {
     
              $responsavel_id = $data->id;
              $responsavel_email = $data->email;
     
              Mail::to($responsavel_email)
              ->send(new SolicitacaoCriada($datas));
      
              $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento criada.' ,'status' => 'A');
              DB::table('dbo.Hist_Notificacao')->insert($values4);
     
              }
              //Fim Foreach


       }

       flash('Nova solicitação registrada com sucesso !')->success();    

       return redirect()->route('Painel.DPRH.Desligamento.Superintendente.index');

    }

    public function gerente_index() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->get();


       $motivos = DB::table('dbo.DPRH_Desligamento_Motivos')
       ->where('status', '=', 'A')
       ->where('tipo', '=', 'T')
       ->orWhere('tipo', '=', 'R')
       ->orderBy('descricao', 'asc')
       ->get();

       $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('Codigo', 'Descricao')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
       ->get();

      $setor = DB::table('PLCFULL.dbo.Jurid_Setor')
      ->select('PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
      ->where('Ativo', '=', '1')
      ->where('setor_custo_user.user_id', '=', Auth::user()->id)
      ->orderBy('Codigo', 'asc')
      ->value('PLCFULL.dbo.Jurid_Setor.Id');

       //Pegar todos os usuarios do setor de custo dela
       $usuarios =  DB::table('dbo.users')
       ->select('dbo.users.id', 'dbo.users.name')  
       ->join('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
       ->where('dbo.setor_custo_user.setor_custo_id', $setor)
       ->orderby('dbo.users.name', 'asc')
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

      
     return view('Painel.DPRH.Desligamento.Superintendente.index', compact('setores','usuarios','motivos','datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));


    }

    public function gerente_historico() {

      $carbon= Carbon::now();
      $datahoje = $carbon->format('Y-m-d');
         
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
       ->select('dbo.DPRH_Desligamento_Matrix.id',
                'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                'dbo.users.name as SolicitanteNome',
                'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                'dbo.DPRH_Desligamento_Status.id as StatusID',
                'dbo.DPRH_Desligamento_Status.descricao as Status',
                'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
       ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('dbo.setor_custo_user.user_id', Auth::user()->id)
       ->whereIn('dbo.DPRH_Desligamento_Matrix.status_id', array(5))
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

      
     return view('Painel.DPRH.Desligamento.Gerente.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    public function gerente_solicitacaoglosada(Request $request) {

      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');
      $solicitante_nome = $request->get('solicitante');

      date_default_timezone_set('America/Sao_Paulo');
        
      $from_name = "PORTAL PL&C ADVOGADOS";        
      $from_address = "automacao@plcadvogados.com.br";  
      $subject = '*****[PLC][Desligamento][Novo agendamento de reunião]*****';  
   


      //Foreach enviando para cada um da equipe responsável deste setor de custo reunião
      $responsaveis = DB::table('dbo.users')
      ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
      ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
      ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
      ->select('dbo.users.id', 'dbo.users.name','dbo.users.email') 
      ->whereIn('dbo.profiles.id', array(24,35,36))
      ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
      ->get();   

      foreach ($responsaveis as $data) {
     
        $responsavel_id = $data->id;
        $responsavel_email = $data->email;
        $responsavel_name = $data->name;

        $domain = 'exchangecore.com';
    
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());
    
        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
    
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<head>\n";
        $message .= "<style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
          padding: 5px;
          text-align: left;
        }
        </style>\n";
        $message .= "</head>\n";
        $message .= "<body>\n";
        $message .= '<p>Prezado(a), '. mb_convert_case($responsavel_name, MB_CASE_TITLE, "UTF-8").',</p>';
        $message .= '<br></br>';
        $message .= '<table style="width:100%">';
        $message .= '<tr>';
        $message .= '<td>Solicitante: '. mb_convert_case($solicitante_nome, MB_CASE_TITLE, "UTF-8").'</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<br></br><br></br>';
        $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
    
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:America/Sao_Paulo' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0300' . "\r\n" .
        'TZOFFSETTO:-0300' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .  
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$responsavel_email. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:recepcao@plcadvogados.com.br\r\n"' .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($carbon))."T".date("His", strtotime($carbon)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . 'TEAMS' . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
    
        $mailsent = mail($responsavel_email, $subject, $message, $headers);


        //Manda notificação
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Novo agendamento de reunião.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);
      }  

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '3','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '3', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      flash('Solicitação atualizada com sucesso !')->success();    

      return redirect()->route('Painel.DPRH.Desligamento.Gerente.index');

    }

    public function gerente_solicitacaoliberada(Request $request) {


      $id = $request->get('id');
      $carbon= Carbon::now();
      $setor = $request->get('setor_codigo');

      //Update Matrix 
      DB::table('dbo.DPRH_Desligamento_Matrix')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status_id' => '2','data_edicao' => $carbon));

      //Grava na Hist 
      $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '2', 
                      'data' => $carbon);
      DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

      //Informa a todos responsáveis do setor de custo
      $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
      ->select('dbo.DPRH_Desligamento_Matrix.id',
               'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
               'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
               'dbo.DPRH_Desligamento_Status.descricao as Status',
               'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
               'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
               'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
      ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
      ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
      ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
      ->get();

      

     //Foreach enviando notificação e e-mail
     $responsaveis = DB::table('dbo.users')
     ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
     ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
     ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
     ->select('dbo.users.id', 'dbo.users.email') 
     ->whereIn('dbo.profiles.id', array(24,35,36))
     ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
     ->get();   

     foreach ($responsaveis as $data) {

     $responsavel_id = $data->id;
     $responsavel_email = $data->email;

     Mail::to($responsavel_email)
     ->send(new SolicitacaoLiberada($datas));

     $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Solicitação de desligamento liberada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values4);

     }
     //Fim Foreach

    flash('Solicitação atualizada com sucesso !')->success();    

    return redirect()->route('Painel.DPRH.Desligamento.Gerente.index');

    }


    public function gerente_solicitacaocriada(Request $request) {

      $setor = $request->get('setor');
      $motivo_id = $request->get('motivo');
      $oportunidade = $request->get('oportunidade');
      $insatisfacao = $request->get('insatisfacao');
      $datasaida = $request->get('datasaida');
      $observacao = $request->get('observacao');
      $carbon= Carbon::now();

            //Grava na Matrix 
            $values3= array(
            'user_id' => Auth::user()->id, 
            'usuario_cpf' => Auth::user()->cpf, 
            'motivo_id' => $motivo_id, 
            'status_id' => '4', 
            'observacao' => $observacao, 
            'oportunidadeobs' => $oportunidade,
            'instatisfacao' => $insatisfacao,
            'setor' => $setor,
            'data_solicitacao' => $carbon,
            'data_saida' => $datasaida,
            'data_edicao' => $carbon);
           DB::table('dbo.DPRH_Desligamento_Matrix')->insert($values3);   

           $id = DB::table('dbo.DPRH_Desligamento_Matrix')->select('id')->orderby('id','desc')->value('id'); 

            //Grava na Hist 
            $values3= array('id_matrix' => $id,
                      'user_id' => Auth::user()->id, 
                      'status' => '4', 
                      'data' => $carbon);
             DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 
             
             $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
             ->select('dbo.DPRH_Desligamento_Matrix.id',
                      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                      'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                      'dbo.DPRH_Desligamento_Status.descricao as Status',
                      'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                      'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                      'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
             ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
             ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
             ->get();

            //Envia para a equipe do RH
            Mail::to('ronaldo.amaral@plcadvogados.com.br')
            ->send(new PrepararDocumentacao($datas));
     
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '186', 'tipo' => '9', 'obs' => 'Desligamento: Nova solicitação de desligamento aguardando documentação.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

       flash('Nova solicitação registrada com sucesso !')->success();    

       return redirect()->route('Painel.DPRH.Desligamento.Gerente.index');

    }


    public function rh_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
        ->select('dbo.DPRH_Desligamento_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                 'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                 'dbo.DPRH_Desligamento_Status.id as StatusID',
                 'dbo.DPRH_Desligamento_Status.descricao as Status',
                 'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                 'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                 'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
        ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('status_id', '4')
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


        return view('Painel.DPRH.Desligamento.RH.index', compact('datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function rh_anexadodocumentacao(Request $request) {


        $id = $request->get('id');
        $usuario_nome = $request->get('usuario_nome');
        $usuario_cpf = $request->get('usuario_cpf');
        $motivo_descricao = $request->get('motivo');
        $status_descricao = $request->get('status');
        $observacao = $request->get('observacao');
        $setorcodigo = $request->get('setor_codigo');
        $setor_descricao = $request->get('setor');
        $carbon= Carbon::now();

        $termodevolucaoequipamento = $request->file('termodevolucaoequipamento');
        $rescisao = $request->file('rescisao');

        //Update Matrix 
        DB::table('dbo.DPRH_Desligamento_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '5', 'data_edicao' => $carbon));

        //Grava na Hist 
        $values3= array('id_matrix' => $id,
                        'user_id' => Auth::user()->id, 
                        'status' => '5', 
                        'data' => $carbon);
        DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3);  
        
        //Anexar documentação na GED 
        $termodevolucaoequipamento->storeAs('contratacao', $termodevolucaoequipamento->getClientOriginalName());
        $rescisao->storeAs('contratacao', $rescisao->getClientOriginalName());

        $values = array(
          'Tabela_OR' => 'Advogados',
          'Codigo_OR' => $usuario_cpf,
          'Id_OR' => '0',
          'Descricao' => $termodevolucaoequipamento->getClientOriginalName(),
          'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $termodevolucaoequipamento->getClientOriginalName(), 
          'Data' => $carbon,
          'Nome' => $termodevolucaoequipamento->getClientOriginalName(),
          'Responsavel' => 'portal.plc',
          'Arq_tipo' => $termodevolucaoequipamento->getClientOriginalExtension(),
          'Arq_Versao' => '1',
          'Arq_Status' => 'Guardado',
          'Arq_usuario' => 'portal.plc',
          'Arq_nick' => $termodevolucaoequipamento->getClientOriginalName(),
          'Obs' => 'Termo de devolução equipamentos assinado pelo sócio de serviço/seletista.');
         DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 

         $values = array(
          'Tabela_OR' => 'Advogados',
          'Codigo_OR' => $usuario_cpf,
          'Id_OR' => '0',
          'Descricao' => $rescisao->getClientOriginalName(),
          'Link' => '\\\192.168.1.65\advwin\portal\portal\contratacao/' . $rescisao->getClientOriginalName(), 
          'Data' => $carbon,
          'Nome' => $rescisao->getClientOriginalName(),
          'Responsavel' => 'portal.plc',
          'Arq_tipo' => $rescisao->getClientOriginalExtension(),
          'Arq_Versao' => '1',
          'Arq_Status' => 'Guardado',
          'Arq_usuario' => 'portal.plc',
          'Arq_nick' => $rescisao->getClientOriginalName(),
          'Obs' => 'Recisão assinado pelo sócio de serviço/seletista.');
         DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values); 
         //Fim Anexos

        //Desativa no portal
        DB::table('dbo.users')
        ->where('cpf', $usuario_cpf)  
        ->limit(1) 
        ->update(array('status' => 'Inativo', 'data_desativacao' => $carbon));

        $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
        ->select('dbo.DPRH_Desligamento_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                 'PLCFULL.dbo.Jurid_Advogado.Codigo as UsuarioCPF',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as UsuarioNome',
                 'dbo.DPRH_Desligamento_Status.id as StatusID',
                 'dbo.DPRH_Desligamento_Status.descricao as Status',
                 'dbo.DPRH_Desligamento_Matrix.observacao as Observacao',
                 'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                 'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
        ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
        ->leftjoin('dbo.users', 'dbo.DPRH_Desligamento_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.DPRH_Desligamento_Matrix.usuario_cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
        ->get();

        //Envia e-mail ao T.I
        Mail::to('ronaldo.amaral@plcadvogados.com.br')
        ->send(new SolicitacaoFinalizadaTI($datas));

        //Envia e-mail a todos os responsáveis do setor de custo
        $responsaveis = DB::table('dbo.users')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->select('dbo.users.id', 'dbo.users.email') 
        ->whereIn('dbo.profiles.id', array(23,24,35,36))
        ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setorcodigo)
        ->get();   

          foreach ($responsaveis as $data) {
      
              $responsavel_id = $data->id;
              $responsavel_email = $data->email;
      
              Mail::to($responsavel_email)
              ->send(new SolicitacaoFinalizada($datas));
       
              $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '9', 'obs' => 'Desligamento: Solicitação de desligamento finalizada.' ,'status' => 'A');
              DB::table('dbo.Hist_Notificacao')->insert($values4);
      
          }
          //Fim Foreach

        flash('Solicitação atualizada com sucesso !')->success();    

        return redirect()->route('Painel.DPRH.Desligamento.RH.index');
    }


 
}
