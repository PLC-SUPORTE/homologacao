<?php

namespace App\Http\Controllers\Painel\Correspondente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Correspondente;
use App\Models\EventModel;
use App\Models\Profile;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PainelController extends Controller {

   public function __construct(Correspondente $model) {
            $this->model = $model;
        //     $this->middleware('can:advogado');
    }    

    public function index()
    {
      $totalSolicitacoes = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('usuario_id', Auth::user()->id)
                ->count();
            
      $topservico_id = DB::table('dbo.Jurid_Situacao_Ficha')
              ->groupby('tiposervico_id')
              ->selectRaw('COUNT(DISTINCT tiposervico_id) AS total')
              ->where('usuario_id', Auth::user()->id)
              ->count('tiposervico_id');
      
      $topservico_descricao = DB::table('dbo.Jurid_Nota_Tiposervico')
              ->select('descricao')
              ->where('id','=', $topservico_id)
              ->value('descricao'); 

     $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();         
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
        
     $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
       
    $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
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
               
       return view('Painel.Correspondente.Layouts.principal', compact('totalSolicitacoes', 'topservico_id', 'topservico_descricao', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente', 'notificacoes',  'totalNotificacaoAbertas'));
    }

  
}