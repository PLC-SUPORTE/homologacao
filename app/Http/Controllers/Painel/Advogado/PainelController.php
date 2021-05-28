<?php

namespace App\Http\Controllers\Painel\Advogado;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Correspondente;
use App\Models\Advogado;
use App\Models\EventModel;
use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller {
    
    public function __construct(Advogado $model)
    {
        $this->model = $model;
        $this->middleware('can:advogado');
    }

    public function index()
    {
        $totalUser          = User::count();
        $totalProfiles      = Profile::count();
        $totalPermissions   = Permission::count();
        
        $totalSolicitacoesAdvogadoAberta = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '6')
                ->orwhere('dbo.Jurid_Situacao_Ficha.status_id', '=', '7')
                ->orwhere('dbo.Jurid_Situacao_Ficha.status_id', '=', '12')
                ->orwhere('dbo.Jurid_Situacao_Ficha.status_id', '=', '14')
                ->where('usuario_id', Auth::user()->cpf)
                ->count();
        
       $totalSolicitacoesAdvogadoPagas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '9')
                ->orwhere('dbo.Jurid_Situacao_Ficha.status_id', '=', '11')
                ->where('usuario_id', Auth::user()->cpf)
                ->count();
       
       $totalSolicitacoesAdvogadoReprovadas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '8')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(8,10))
                ->where('usuario_id', Auth::user()->cpf)
                ->count();
       
     
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
       
               
       return view('Painel.Advogado.Layouts.principal', compact('totalSolicitacoesAdvogadoAberta','totalSolicitacoesAdvogadoPagas','totalSolicitacoesAdvogadoReprovadas', 'notificacoes',  'totalNotificacaoAbertas'));
    }
}