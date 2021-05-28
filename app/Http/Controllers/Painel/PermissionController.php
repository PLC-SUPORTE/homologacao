<?php
namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\StandardController;
use App\Models\Permission;
use App\Models\Profile;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermissionController extends StandardController
{
    protected $model;
    protected $name         = 'Permission';
    protected $view         = 'painel.TI.permissions';
    protected $route        = 'permissoes';
    protected $totalPage    = 200000;

    public function __construct(Permission $permission)
    {
        $this->model = $permission;
        
        $this->middleware('can:permissions');
    }
    
    public function profiles($id)
    {
        $permission = $this->model->find($id);
        
        $profiles = $permission->profiles()->paginate($this->totalPage);
        
        $title = "Perfis com a permissão: {$permission->name}";
        
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

        return view('painel.TI.permissions.profiles', compact('permission', 'profiles', 'title', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function profilesAdd($id)
    {
        $permission = $this->model->find($id);
        
        $profiles = Profile::whereNotIn('id', function($query) use ($permission){
            $query->select("permission_profile.profile_id");
            $query->from("permission_profile");
            $query->whereRaw("permission_profile.permission_id = {$permission->id}");
        })->get();
        
        $title = "Vincular Perfil a Permissão: {$permission->name}";
        
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
        
        return view('painel.TI.permissions.profile-add', compact('permission', 'profiles', 'title', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function profilesAddPermission(Request $request, $id)
    {
        $permission = $this->model->find($id);
        
        $permission->profiles()->attach($request->get('profiles'));
        
        flash('Vinculo realizado com sucesso! ')->success();
        
        return redirect()
                ->route('permissao.perfis', $id)
                ->with(['success' => 'Vinculo realizado com sucesso!']);
    }
    
    public function deleteProfile($id, $profileId)
    {
        $permission = $this->model->find($id);
        
        $permission->profiles()->detach($profileId);
        
        flash('Removido com sucesso ! ')->success();
        
        return redirect()
                ->route('permissao.perfis', $id)
                ->with(['success' => 'Removido com sucesso!']);
    }
    
    public function searchProfile(Request $request, $id)
    {
        $dataForm = $request->except('_token');
        
        $permission = $this->model->find($id);
        
        //Filtra os dados
        $profiles = $permission
                ->profiles()
                ->where('profiles.name', 'LIKE', "%{$dataForm['key-search']}%")
                ->orWhere('profiles.label', 'LIKE', "%{$dataForm['key-search']}%")
                ->paginate($this->totalPage);
                
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

        return view('painel.TI.permissions.profiles', compact('permission', 'dataForm', 'profiles', 'totalNotificaoAbertas', 'notificacoes'));
    }
}