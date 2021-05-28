<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\Usuario\LembreteSenha;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/painel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm() {

        return view('Auth.passwords.email');

    }

    public function sendResetLinkEmail(Request $request) {


        $dado = $request->get('dado');
        $carbon= Carbon::now();

        //Busco os dados dele pelo CPF/E-mail

        $email = DB::table('dbo.users')
        ->select('dbo.users.email')  
        ->where('dbo.users.email','=',$dado)
        ->orwhere('dbo.users.cpf','=', $dado)
        ->value('dbo.users.email');

        //Se existir pego a senha
        if($email != null) {

            $user_id = DB::table('dbo.users')
            ->select('dbo.users.id')  
            ->where('dbo.users.email','=',$dado)
            ->orwhere('dbo.users.cpf','=', $dado)
            ->value('dbo.users.id');


            $name = DB::table('dbo.users')->select('dbo.users.name')->where('dbo.users.id','=',$user_id)->value('dbo.users.name');

            //Verifico se já tem cadastro na TI_Usuarios_Senha
            $verifica = DB::table('dbo.TI_Usuarios_Senha')->select('id')->where('user_id','=',$user_id)->value('id');

            if($verifica == null) {
            //Gera o token
                $values = array(
                'user_id' => $user_id,
                'created_at' => $carbon);
                DB::table('dbo.TI_Usuarios_Senha')->insert($values);

                //Recupera o token
                $id = DB::table('dbo.TI_Usuarios_Senha')->select('id')->where('user_id','=',$user_id)->orderby('id','desc')->value('id');
                
                $token = Crypt::encryptString($id);

                DB::table('dbo.TI_Usuarios_Senha')
                ->where('id',$id)
                ->limit(1) 
                ->update(array('token' => $token));
            } 
           //Se já tiver cadastro
            else {
                
                $token = Crypt::encryptString($verifica);

                DB::table('dbo.TI_Usuarios_Senha')
                ->where('id',$verifica)
                ->limit(1) 
                ->update(array('token' => $token, 'senha' => '', 'confirmacaosenha' => '', 'updated_at' => $carbon));

            //Envia o e-mail
            Mail::to($email)
            ->send(new LembreteSenha($name,$token));
  

            }

            return redirect()->route('login');

    }

    //Não existe retorna enviando com erro
    else {

        \Session::flash('message', ['msg'=>'Não foi possível encontrar nenhum usuário com este e-mail ou código informados.', 'class'=>'red']);
        return redirect()->route('recuperarsenha');

    }



}
}
