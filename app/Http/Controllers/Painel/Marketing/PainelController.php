<?php

namespace App\Http\Controllers\Painel\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Correspondente;
use App\Models\EventModel;
use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Posts;

class PainelController extends Controller
{

    public function __construct(Correspondente $model) {
        $this->model = $model;
        $this->middleware('can:listagem_comunicados');
   }  


    public function index()
    {
        
        $totalPostCriados = DB::table('dbo.Marketing_Posts')
                ->where('dbo.Marketing_Posts.user_id', '=', Auth::user()->id)
                ->count();
        
       $totalPostVisualizados = DB::table('dbo.Marketing_Posts')
                ->join('dbo.Marketing_Posts_View','dbo.Marketing_Posts.id','=','dbo.Marketing_Posts_View.id_post')
                ->where('dbo.Marketing_Posts.user_id', '=', Auth::user()->id)
                ->count();
       
       $totalPostDesativados = DB::table('dbo.Marketing_Posts')
                ->where('dbo.Marketing_Posts.user_id', '=', Auth::user()->id)
                ->where('dbo.Marketing_Posts.status', '=', 'R')
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
               
       return view('Painel.Marketing.Layouts.principal', compact('totalPostCriados','totalPostVisualizados','totalPostDesativados', 'notificacoes',  'totalNotificacaoAbertas'));
    }
}