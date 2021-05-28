<?php
namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\StandardController;
use App\Models\Profile;
use App\User;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ProfileController extends StandardController
{
    protected $model;
    protected $name         = 'Profile';
    protected $view         = 'painel.TI.profiles';
    protected $route        = 'perfis';
    protected $totalPage    = 100;

    public function __construct(Profile $profile)
    {
        $this->model = $profile;
        
        $this->middleware('can:profiles');
    }

    
    public  function users($id)
    {
        $profile = $this->model->find($id);
        
        $users = $profile->users()->distinct('user_id')->paginate($this->totalPage);
        
        $title = "Usuários com o perfil: {$profile->name}";
        
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

        return view('painel.TI.profiles.users', compact('profile', 'users', 'title', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function usersAdd($id)
    {
        $profile = $this->model->find($id);
        
        $users = User::whereNotIn('id', function($query) use ($profile){
            $query->select("profile_user.user_id");
            $query->from("profile_user");
            $query->whereRaw("profile_user.profile_id = {$profile->id}");
        })->get();
        
        $title = "Vincular Usuário ao Perfil: {$profile->name}";
        
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
        
        return view('painel.TI.profiles.users-add', compact('profile', 'users', 'title', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function usersAddProfile(Request $request, $id)
    {
        $profile = $this->model->find($id);
        
        $profile->users()->attach($request->get('users'));
        
        flash('Vinculo realizado com sucesso ! ')->success();
        
        
        return redirect()->route('profile.users', $id)->with(['success' => 'Vinculo realizado com sucesso!']);
    }
    
    public function deleteUser($id, $userId)
    {
        $profile = $this->model->find($id);
        
        $profile->users()->detach($userId);
        
        flash('Exclusão realizada com sucesso ! ')->success();
        
        return redirect()
                ->route('profile.users', $id)
                ->with(['success' => 'Removido com sucesso!']);
    }
    
    
    public function searchUser(Request $request, $id)
    {
        $dataForm = $request->except('_token');
        
        $profile = $this->model->find($id);
        
        //Filtra os dados
        $users = $profile
                ->users()
                ->where('users.name', 'LIKE', "%{$dataForm['key-search']}%")
                ->orWhere('users.email', $dataForm['key-search'])
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

        return view('painel.TI.profiles.users', compact('users', 'dataForm', 'profile', 'totalNotificacaoAbertas', 'notificacoes'));
    }
}