<?php

namespace App\Http\Controllers\Sorteio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\Sorteio\EnviaSorteio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SorteioController extends Controller
{
 
    
    public function index() {

        $datas = DB::table('dbo.Sorteio_Tipos')
        ->select('dbo.Sorteio_Matrix.id', 
        'dbo.Sorteio_Tipos.premio as Premio',
        'dbo.Sorteio_Tipos.descricao as Sorteio',
        'dbo.Sorteio_Matrix.numero as Numero', 
        'dbo.Sorteio_Tipos.resultado as Resultado',
        'dbo.Sorteio_Tipos.data_resultado as DataResultado',
        'dbo.users.name as VencedorNome',
        'dbo.Sorteio_Matrix.data as Data',
        'dbo.Sorteio_Tipos.img as Link')  
        ->leftjoin('dbo.Sorteio_Matrix','dbo.Sorteio_Tipos.id','=','dbo.Sorteio_Matrix.tipo_id')
        ->leftjoin('dbo.users', 'dbo.Sorteio_Tipos.vencedor', 'dbo.users.id')
        ->where('dbo.Sorteio_Matrix.user_id','=', Auth::user()->id)
        ->orderBy('dbo.Sorteio_Matrix.id', 'asc')
        ->get();

        return view('Sorteio.index', compact('datas'));

    }

    public function gerarsorteio1() {

        return view('Sorteio.primeiro');

    }

    public function primeirosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '1')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '1')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {

                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '1')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '1')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '1',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '1',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '1',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio2() {

        return view('Sorteio.segundo');

    }

    public function segundosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '2')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '2')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {
                return redirect()->route('sorteioindex');

            }
            else {

                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '2')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '2')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '2',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '2',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '2',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio3() {

        return view('Sorteio.terceiro');

    }

    public function terceirosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '3')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '3')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {

                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '3')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '3')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '3',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A' 
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '3',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '3',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio4() {

        return view('Sorteio.quarto');

    }

    public function quartosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '4')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '4')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {

                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '4')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '4')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '4',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '4',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '4',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio5() {

        return view('Sorteio.quinto');

    }

    public function quintosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '5')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '5')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {

                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '5')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '5')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '5',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '5',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '1',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A' 
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio6() {

        return view('Sorteio.sexto');

    }

    public function sextosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '6')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '6')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {
                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '6')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '6')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '6',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '6',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '6',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio7() {

        return view('Sorteio.setimo');

    }

    public function setimosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '7')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '7')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {
                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '7')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '7')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '7',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '7',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '7',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }

    public function gerarsorteio8() {

        return view('Sorteio.oitavo');

    }

    public function oitavosorteiogerado(Request $request) {

        $carbon= Carbon::now()->setTime(0,0)->format('Y-m-d H:i:s');
        $email = $request->get('dado');
        $user_id = DB::table('dbo.users')->select('id')->orwhere('email', $email)->value('id');  
        $name = DB::table('dbo.users')->select('name')->where('email', $email)->value('name');  

        ///Verifico se ele já ganhou algum sorteio
        $verificavitoria  = DB::table('dbo.Sorteio_Tipos')->select('vencedor')->where('vencedor', $user_id)->value('vencedor');  
        $verificaparticipacao  = DB::table('dbo.Sorteio_Matrix')->select('id')->where('user_id', $user_id)->where('tipo_id', '8')->value('id');  
        $verificastatus =  DB::table('dbo.Sorteio_Tipos')->select('status')->where('id', '8')->value('status');  

            //Se já ganhou um sorteio volta ao index
            if($verificavitoria != null || $verificaparticipacao  != null|| $verificastatus == "Inativo") {

                return redirect()->route('sorteioindex');

            }
            else {

                $numerosorteio = random_int (1,999);

                $quantidadeparticipantes = DB::table('dbo.Sorteio_Tipos')->select('quantidade_participantes')->where('id', '8')->value('quantidade_participantes');  
                $quantidadeparticipantes = $quantidadeparticipantes + 1;

                //Verifica se já existe este número para está pessoa ou para este sorteio
                $verifica = DB::table('dbo.Sorteio_Matrix')
                    ->select('id')
                    ->where('numero', '=', $numerosorteio)
                    ->value('id');  

                //Update quantidade participantes
                DB::table('dbo.Sorteio_Tipos')
                ->where('id', '8')  
                ->limit(1) 
                ->update(array('quantidade_participantes' => $quantidadeparticipantes));            

        //Se não existir grava no banco
        if($verifica == null) {

            $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '8',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A'
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

           //Manda o e-mail
           Mail::to($email)
           ->send(new EnviaSorteio($name, $numerosorteio));   

        }            
        //Se existir gera outro numero
        else {

            $novonumero = random_int (1,999);

            if($novonumero != $numerosorteio) {

                $values= array(
                    'user_id' => $user_id, 
                    'tipo_id' => '8',
                    'numero' => $novonumero,
                    'data' => $carbon,
                    'status' => 'A'
                );
                DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $novonumero));   
            } else {
             $numerosorteio = random_int (1,999);
             $values= array(
                'user_id' => $user_id, 
                'tipo_id' => '8',
                'numero' => $numerosorteio,
                'data' => $carbon,
                'status' => 'A' 
            );
            DB::table('dbo.Sorteio_Matrix')->insert($values);

            //Manda o e-mail
            Mail::to($email)
            ->send(new EnviaSorteio($name, $numerosorteio));
            }
        }
    }

            return redirect()->route('sorteioindex');


    }


  


    

    
}
