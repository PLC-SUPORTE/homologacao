<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\Usuario\ResetSenha;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    
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

    public function showResetForm($token) {

        $user_id = DB::table('dbo.TI_Usuarios_Senha')->select('user_id')->where('token','=',$token)->value('user_id');
        $email = DB::table('dbo.users')->select('email')->where('id','=',$user_id)->value('email');
        $cpf = DB::table('dbo.users')->select('cpf')->where('id','=',$user_id)->value('cpf');

        $cpf = Crypt::encryptString($cpf);
        $email = Crypt::encryptString($email);


        return view('Auth.passwords.reset', compact('token', 'email', 'cpf'));

    }

    public function reset(Request $request) {

        $token = $request->get('token');
        $novasenha = $request->get('novasenha');
        $confirmasenha = $request->get('confirmasenha');
        $carbon= Carbon::now();

        $cpf_criptografado = $request->get('cpf');
        $email_criptogrado = $request->get('email');


        //Verifico se as senhas são iguais

          //Busco os dados do usuario
          $user_id = DB::table('dbo.TI_Usuarios_Senha')->select('user_id')->where('token','=',$token)->value('user_id');
          $email = DB::table('dbo.users')->select('email')->where('id','=',$user_id)->value('email');
          $name = DB::table('dbo.users')->select('name')->where('id','=',$user_id)->value('name');
          $password = bcrypt($novasenha);

          //Atualiza na tabela users 
          DB::table('dbo.users')
          ->where('id', '=' ,$user_id) 
          ->limit(1) 
          ->update(array('password' => $password));

          //Atualiza na tabela TI_Usuarios_Senha
          DB::table('dbo.TI_Usuarios_Senha')
          ->where('user_id',$user_id)
          ->where('token', $token) 
          ->limit(1) 
          ->update(array('senha' => $novasenha, 'confirmacaosenha' => $confirmasenha, 'cpf' => $cpf_criptografado,'email' => $email_criptogrado,'updated_at' => $carbon));

          //Envia e-mail com as credenciais de acesso
          Mail::to($email)
          ->send(new ResetSenha($name, $email, $novasenha));
          return redirect()->route('login');   

    }


}
