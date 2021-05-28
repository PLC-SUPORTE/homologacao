<?php

namespace App\Http\Controllers\Painel\TI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\EventModel;
use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller
{
    public function index()
    {
        $totalUser          = User::count();
        $totalProfiles      = Profile::count();
        //$totalPermissions   = Permission::count();
        

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
       
               
       return view('Painel.TI.Layouts.principal', compact('totalUser','totalProfiles', 'notificacoes',  'totalNotificacaoAbertas'));
    }





}