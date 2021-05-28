<?php

namespace App\Http\Controllers\Painel\Financeiro;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Correspondente;
use App\Models\EventModel;
use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller
{

    public function __construct(Correspondente $model) {
        $this->model = $model;
       // $this->middleware('can:financeiro, financeiro contas a pagar');
   }  

    public function index()
    {
        $totalUser          = User::count();
        $totalProfiles      = Profile::count();
        $totalPermissions   = Permission::count();
        
        $totalSolicitacoesFinanceiroAprovar = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '7')
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->count();
        
       $totalSolicitacoesFinanceiroPagar = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '12')
                ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
                ->count();
       
       $totalSolicitacoesFinanceiroPagas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->join('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '9')
                ->orwhere('dbo.Jurid_Situacao_Ficha.status_id', '=', '10')
                ->orwhere('dbo.Jurid_Situacao_Ficha.status_id', '=', '11')
                ->where('PLCFULL.dbo.Jurid_Debite.DebPago', '=', 'S')
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
                'Hist_Notificacao.status', 
                'dbo.users.*')  
                ->limit(3)
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderBy('dbo.Hist_Notificacao.data', 'desc')
                ->get();
       
               
       return view('Painel.Financeiro.Layouts.principal', compact('totalSolicitacoesFinanceiroAprovar','totalSolicitacoesFinanceiroPagar','totalSolicitacoesFinanceiroPagas', 'notificacoes',  'totalNotificacaoAbertas'));
    }
}