<?php
namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\StandardController;
use App\Models\SetorCusto;
use App\User;
use Laracasts\Flash\Flash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class SetorCustoController extends StandardController
{
    protected $model;
    protected $name         = 'SetorCusto';
    protected $view         = 'painel.TI.setorcusto';
    protected $route        = 'setorcusto';
    protected $totalPage    = 100;

    public function __construct(SetorCusto $setorcusto)
    {
        $this->model = $setorcusto;
        
        $this->middleware('can:profiles');
    }
    
    public function index()
    {
        $title = "Listagem {$this->name}s";

        $data = $this->model->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')->where('PLCFULL.dbo.Jurid_Setor.Codigo', 'NOT LIKE', '%.')->paginate($this->totalPage);
        
        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
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

        return view("{$this->view}.index", compact('title', 'data', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    
     public  function users($id)
    {
        $setorcusto = $this->model->find($id);
                        
        //$users = $setorcusto->users()->paginate($this->totalPage);
        
        $users =  $notas = DB::table('dbo.users')
             ->join('dbo.setor_custo_user','dbo.users.id','=','dbo.setor_custo_user.user_id')
          //   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
          //           'PLCFULL.dbo.Jurid_Debite.Pasta',
           //          DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
          //           DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
          //           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
         //            'PLCFULL.dbo.Jurid_Status_Ficha.*',
         //            'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta')
             ->where('dbo.setor_custo_user.setor_custo_id','=', $id)
         //    ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', $usuario_cpf)
             ->get();
        
        //dd($users);
        
        $title = "Usuários com o Setor Custo: {$setorcusto->Descricao}";

       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
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

        
        return view('painel.TI.setorcusto.users', compact('setorcusto', 'title', 'users', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function usersAdd($id)
    {
        $setorcusto = $this->model->find($id);
        
        $users = User::whereNotIn('id', function($query) use ($setorcusto){
            $query->select("dbo.setor_custo_user.user_id");
            $query->from("dbo.setor_custo_user");
            $query->whereRaw("dbo.setor_custo_user.setor_custo_id = {$setorcusto->Id}");
        })
            ->get();
                
        $title = "Vincular Usuário ao Setor Custo: {$setorcusto->Descricao}";

       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
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

        
        return view('painel.TI.setorcusto.users-add', compact('setorcusto', 'users', 'title', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function usersAddProfile(Request $request, $id)
    {
        $setorcusto = $this->model->find($id);
        
        $setorcusto->users()->attach($request->get('users'));
        
                
        flash('Vinculo realizado com sucesso ! ')->success();
        
        return redirect()->route('setorcusto.users', $id)->with(['success' => 'Vinculo realizado com sucesso!']);
    }
    
    public function deleteUser($id, $userId)
    {
        $setorcusto = $this->model->find($id);
        
        DB::table('dbo.setor_custo_user')->where('id', $userId)->delete();        
        
               
        flash('Exclusão realizada com sucesso ! ')->success();
        
        return redirect()
                ->route('setorcusto.users', $id)
                ->with(['success' => 'Removido com sucesso!']);
    }
    
    
    public function searchUser(Request $request, $id)
    {
        $dataForm = $request->except('_token');
        
        $setorcusto = $this->model->find($id);
        
        //Filtra os dados
        $users = $setorcusto
                ->users()
                ->where('users.name', 'LIKE', "%{$dataForm['key-search']}%")
                ->orWhere('users.email', $dataForm['key-search'])
                ->paginate($this->totalPage);

       $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->count();
       
       
       $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'status', 'dbo.users.*')  
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderby('dbo.Hist_Notificacao.data', 'desc')
                ->get();


        return view('painel.setorcusto.users', compact('users', 'dataForm', 'setorcusto', 'totalNotificacaoAbertas', 'notificacoes'));
    }
}