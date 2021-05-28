<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Painel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class HomeController extends Controller
{
    
    protected $totalPage = 4;   

  

    //------------------------------------------------------------------------------------------------------------------
    public function __construct()
    {
      $this->middleware('auth');
    }
    //------------------------------------------------------------------------------------------------------------------


    public function Show()
    {     

      $carbon= Carbon::now();

      //Se o status do usuario For inativo ele deve retornar a tela de login 
      $status = DB::table('dbo.users')->select('status')->where('id','=',Auth::user()->id)->value('status'); 


      if($status == "Inativo") {

        flash('Usuário com status ínativo. Favor entrar em contato conosco!')->error();

        return redirect()->route('logout');

      }
      
      //Profile ID 1 = Correspondente 
      $dataCorrespondente = DB::table('dbo.users')
             ->join('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
             ->join('dbo.profiles','dbo.profile_user.profile_id','=','dbo.profiles.id')
             ->where('dbo.profiles.id','=','1')
             ->where('dbo.users.id','=', Auth::user()->id)
             ->count(); 
  
      //Ele não é correspondente (Não encontrou registro dele como Profile Correspondente)
      if($dataCorrespondente == 0) {

      //Grava na Auditoria 
      $verifica = DB::table('dbo.TI_Usuarios_Auditoria')->select('user_id')->where('user_id','=',Auth::user()->id)->value('user_id'); 
      if($verifica == null) {

        $values= array(
          'user_id' => Auth::user()->id, 
          'modulo' => 'Portal', 
          'descricao' => 'Acessou o portal.',
          'data' => $carbon,
          'alerta' => '0');
           DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);
      } else {
        $values = array(
          'data' => $carbon,
          'alerta' => '0');
        DB::table('dbo.TI_Usuarios_Auditoria')
        ->where('dbo.TI_Usuarios_Auditoria.user_id', '=', Auth::user()->id)
        ->update($values);
      }

      //Se for da Especialista vai direto pro Pesquisa Patrimonial
      $verificaespecialista = DB::table('dbo.users')->select('id')->where('id','=',Auth::user()->id)->value('id'); 
      $verificaespecialista2 = DB::table('dbo.users')->join('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')->select('dbo.profile_user.profile_id')->where('dbo.users.id','=',Auth::user()->id)->value('dbo.profile_user.profile_id'); 
      if($verificaespecialista == 885) {
        return redirect()->route('Painel.PesquisaPatrimonial.nucleo.index');
      }elseif($verificaespecialista2 == 35) {
        return redirect()->route('Painel.PesquisaPatrimonial.solicitacao.index');
      }

          
           
      $datas = DB::table('dbo.users')
             ->join('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
             ->join('dbo.profiles','dbo.profile_user.profile_id','=','dbo.profiles.id')
             ->join('dbo.permission_profile', 'dbo.profiles.id', '=', 'dbo.permission_profile.profile_id')
             ->join('dbo.permissions', 'dbo.permission_profile.permission_id', '=', 'dbo.permissions.id')
             ->where('dbo.users.id','=', Auth::user()->id)
             ->select('dbo.permissions.name') 
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
             
             $comunicados = DB::table('dbo.Marketing_Posts')
             ->join('dbo.Marketing_Categoria_Posts','dbo.Marketing_Posts.categoria_id','=','dbo.Marketing_Categoria_Posts.id')
             ->leftjoin('dbo.Marketing_Posts_View', 'dbo.Marketing_Posts.id', '=', 'dbo.Marketing_Posts_View.id_post')
             ->select('dbo.Marketing_Categoria_Posts.descricao as desc', 
             'dbo.Marketing_Posts.image as anexo', 
             'dbo.Marketing_Posts.titulo',
             'dbo.Marketing_Posts.descricao', 
             'Marketing_Posts.data', 
             'Marketing_Posts.id', 
             'Marketing_Posts.user_id',
             'dbo.Marketing_Posts_View.status as lido')
             ->limit(3)
             ->orderBy('dbo.Marketing_Posts.data', 'desc')
             ->where('dbo.Marketing_Posts.status', '=', 'A')
             ->get(); 

              $totalComunicadosLidos = DB::table('dbo.Marketing_Posts_View')
              ->join('dbo.Marketing_Posts','dbo.Marketing_Posts_View.id_post','=','dbo.Marketing_Posts.id')
              ->count(); 

              $totalComunicadosCriados = DB::table('dbo.Marketing_Posts')
              ->where('status', '=', 'A')
              ->count(); 

              $totalComunicadosAbertos = $totalComunicadosCriados - $totalComunicadosLidos;
              
              $verificasenhaobrigatoria = DB::table('dbo.TI_Usuarios_Senha')->select('dbo.TI_Usuarios_Senha.id')->where('user_id', Auth::user()->id)->value('id');  
              $verificalancamentos = DB::table('dbo.web_indicadores_nota')->where('advogado', Auth::user()->cpf)->count();
              
              $cpf_criptografado = DB::table('dbo.TI_Usuarios_Senha')->select('dbo.TI_Usuarios_Senha.cpf')->where('user_id', Auth::user()->id)->value('cpf');  
              $email_criptografado = DB::table('dbo.TI_Usuarios_Senha')->select('dbo.TI_Usuarios_Senha.email')->where('user_id', Auth::user()->id)->value('email');
              if($verificasenhaobrigatoria == null) {
                $verificasenhaobrigatoria = 1;
              } else {
                $verificasenhaobrigatoria = 2;
              }

              if($cpf_criptografado == null || $email_criptografado == null) {

                $cpf_criptografado = bcrypt(Auth::user()->cpf);
                $email_criptografado = bcrypt(Auth::user()->email);
              }

              $senhaatual = 'plc@'.substr(Auth::user()->cpf,-4);


          return view('Site.Home.home', compact('verificalancamentos','cpf_criptografado','email_criptografado','senhaatual','verificasenhaobrigatoria','datas', 'comunicados', 'totalNotificacaoAbertas', 'totalComunicadosLidos', 'notificacoes', 'totalComunicadosAbertos'));
      }
      else{
          return redirect()->route('Painel.Correspondente.principal');
      }

    }

    public function anexo($anexo) {
                
     //   $disk = Storage::disk('marketing-sftp');
     //   $path = Storage::disk('marketing-sftp')->path($anexo);
        
       return Storage::disk('marketing-sftp')->download($anexo);
    }

    public function treinamento($anexo) {
                
       return Storage::disk('treinamento-sftp')->download($anexo);
    }

    public function software($anexo) {
                
        return Storage::disk('software-sftp')->download($anexo);
     }

    public function updateTable($id){

        $usuarioid = Auth::user()->id;
        $carbon= Carbon::now();

        $verifica = DB::table('dbo.marketing_posts_view')
        ->where('id_post', $id)
        ->count();  

        if($verifica == "0") {
            $values = array('id_post' => $id, 'user_id' => $usuarioid, 'data' => $carbon, 'status' => 'A');
            DB::table('dbo.marketing_posts_view')->insert($values);
        } else {
            DB::table('dbo.marketing_posts_view')
            ->where('id_post', $id)  
            ->limit(1) 
            ->update(array('user_id' => $usuarioid,'data' => $carbon));
        }
     

        return redirect()->route('Home.Principal.Show');
    }

    public function escolhernumeros() {

      return view('Painel.Escritorio.Sorteio.index');


    }

    public function numerosdefinidos(Request $request) {

      //4 sorteios

      //1 sorteio: 1 ao 200 envia e-mail 

      //2 sorteio url da QRCODE: Mostra o número do 1 sorteio

      //3 sorteio mostra o resultado dos 2 primeiros
    }

    public function TesteHome(){
      return view('Site.Home.testehome');
    }



}
