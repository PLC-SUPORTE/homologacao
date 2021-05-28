<?php

namespace App\Http\Controllers\Painel\Inconsistencias;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\Inconsistencias\Inconsistencias;
use Excel;


class InconsistenciasController extends Controller
{


    public function index()
    {

    $cpfUsuario = Auth::User()->cpf;

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

    $prazo = DB::select( DB::raw("
                SELECT pasta, data, prazo_fatal, obs, ident, data_prazo
                FROM PLCFULL.dbo.jurid_agenda_table WHERE Advogado = '$cpfUsuario'"));

    return view('Painel.Financeiro.Inconsistencias.index', compact('totalNotificacaoAbertas', 'notificacoes', 'prazo'));
    
   }

    public function gerarRelatorio()
    {
        $cpfUsuario = Auth::User()->cpf;

        $prazo = DB::select( DB::raw("
        SELECT pasta, data, prazo_fatal, obs, data_prazo
        FROM PLCFULL.dbo.jurid_agenda_table WHERE Advogado = '$cpfUsuario'"));

        $data = date('d/m/Y', strtotime($prazo[0]->data));
        $data_prazo = date('d/m/Y', strtotime($prazo[0]->data_prazo));
        $data_prazo_fatal = date('d/m/Y', strtotime($prazo[0]->prazo_fatal));
      
        $pdf = PDF::loadView('Painel.Financeiro.Inconsistencias.gerarRelatorio', compact('prazo', 'data_prazo', 'data', 'data_prazo_fatal'));
        return $pdf->stream(); 
    }

    public function revisar($ident){

        $cpfUsuario = Auth::User()->cpf;

        $prazo = DB::select( DB::raw("
        SELECT ident, pasta, advogado, data, data_prazo,  prazo_fatal, obs 
        FROM PLCFULL.dbo.jurid_agenda_table WHERE Advogado = '$cpfUsuario'
        and ident = $ident"));

        $data  = date("d/m/Y", strtotime($prazo[0]->data));
        $dataPrazo  = date("d/m/Y", strtotime($prazo[0]->data_prazo));
        $prazoFatal  = date("d/m/Y", strtotime($prazo[0]->prazo_fatal));

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

        return view('Painel.Financeiro.Inconsistencias.revisar', compact('prazo', 'data', 'dataPrazo', 'prazoFatal', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function contestar($id){

        $usuarioid = Auth::user()->id;
        $name = Auth::user()->name;
        $carbon= Carbon::now();

        $dados = DB::select( DB::raw("
        SELECT pasta, advogado, data, data_prazo,  prazo_fatal
        FROM PLCFULL.dbo.jurid_agenda_table WHERE ident = '$id'"));

        $getAnaPaola = DB::select( DB::raw("
        SELECT email
        FROM users where cpf = '35448095836'"));

        $getPatricia = DB::select( DB::raw("
        SELECT email
        FROM users where cpf = '09482957679'"));

        // $values = 
        // array('advogado' => $carbon, 
        // 'mes' => $id, 
        // 'contestacoes' => $usuarioid);
        // DB::table('dbo.contestacoes')->insert($values);
        
        $advogado  = $dados[0]->advogado;
        $pasta  = $dados[0]->pasta;
        $data  = date("d/m/Y", strtotime($dados[0]->data));
        $dataPrazo  = date("d/m/Y", strtotime($dados[0]->data_prazo));
        $prazoFatal  = date("d/m/Y", strtotime($dados[0]->prazo_fatal));

        $values = 
        array('data' => $carbon, 
        'id_ref' => $id, 
        'user_id' => $usuarioid, 
        'destino_id' => 184, 
        'tipo' => '5', 
        'obs' => 'Prazo contestado', 
        'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values);

        Mail::to('mauricio.moura@plcadvogados.com.br')
        ->send(new Inconsistencias($id, $name, $advogado, $pasta, $data, $dataPrazo, $prazoFatal));

        // Mail::to($getAnaPaola[0]->email)
        // ->send(new Inconsistencias($id, $name, $advogado, $pasta, $data, $dataPrazo, $prazoFatal));

        // Mail::to($getPatricia[0]->email)
        // ->send(new Inconsistencias($id, $name, $advogado, $pasta, $data, $dataPrazo, $prazoFatal));

        flash('A inconsistência foi contestada com sucesso, email e notificação enviadas para o coordenador.')->success();    

       return redirect()->route('Painel.Inconsistencias.index');
    }

    public function gerarExcelInconsistencias(){
        $cpfUsuario = Auth::User()->cpf;

        $customer_data = DB::select( DB::raw("
        SELECT pasta, data, prazo_fatal, obs, data_prazo
        FROM PLCFULL.dbo.jurid_agenda_table WHERE Advogado = '$cpfUsuario'"));

        $customer_array[] = array(
            'pasta', 
            'data',
            'prazo_fatal',
            'obs',
            'data_prazo');
        foreach($customer_data as $customer)
        {
        $customer_array[] = array(
        'pasta'  => $customer->pasta,
        'data' => $customer->data,
        'prazo_fatal'  => $customer->prazo_fatal,
        'obs'  => $customer->obs,
        'data_prazo' => $customer->data_prazo);
        }
        Excel::create('Inconsistências', function($excel) use ($customer_array){
        $excel->setTitle('Inconsistências');
        $excel->sheet('Inconsistências', function($sheet) use ($customer_array){
        $sheet->fromArray($customer_array, null, 'A1', false, false);
        });
        })->download('xlsx');

    }

    public function dashboardInconsistencias(){

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

        $totalContestacoes = DB::table('dbo.contestacoes')
        ->select('id', 'advogado','mes',  'numero_contestacoes')  
        ->count();

        return view('Painel.Financeiro.Inconsistencias.dashboardInconsistencias', compact('totalNotificacaoAbertas', 'notificacoes', 'totalContestacoes'));
    }

}