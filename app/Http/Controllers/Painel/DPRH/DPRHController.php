<?php

namespace App\Http\Controllers\Painel\DPRH;

use App\Models\Correspondente;
use App\Models\FichaArquivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Excel;


class DPRHController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    
    public function __construct(Correspondente $model)
    {
        $this->model = $model;
        $this->middleware('can:dprh');
        
    }

    public function index() {
          
       $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->where('PLCFULL.dbo.Jurid_Advogado.Status', '=', 'Ativo')
             ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                     'PLCFULL.dbo.Jurid_Advogado.dt_entrada',
                     'PLCFULL.dbo.Jurid_Advogado.Dt_Nasc',
                     'PLCFULL.dbo.Jurid_Advogado.UF',
                     'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao')
             ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')  
             ->get();
       
                            
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

       
      return view('Painel.DPRH.Colaboradores.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    
     public function desativados() {
          
       $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->where('PLCFULL.dbo.Jurid_Advogado.Status', '=', 'Inativo')
             ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                     'PLCFULL.dbo.Jurid_Advogado.dt_entrada',
                     'PLCFULL.dbo.Jurid_Advogado.dt_saida',
                     'PLCFULL.dbo.Jurid_Advogado.UF',
                     'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao')
             ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')  
             ->get();
       
                            
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
       
      return view('Painel.DPRH.Colaboradores.desativados', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
        
  public function gerarExcelAtivos() {
         
        
   $customer_data = DB::table('PLCFULL.dbo.Jurid_Advogado')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->where('PLCFULL.dbo.Jurid_Advogado.Status', '=', 'Ativo')
             ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Advogado.dt_entrada AS DateTime) as dt_entrada'),
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Advogado.Dt_Nasc AS DateTime) as Dt_Nasc'), 
                     'PLCFULL.dbo.Jurid_Advogado.UF',
                     'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao')
             ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc') 
             ->get()
             ->toArray();
   
     $customer_array[] = array('CPF',  'Nome', 'dt_entrada' ,'Dt_Nasc', 'UF', 'Setor', 'SetorDescricao');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'CPF'  => $customer->CPF,
       'Nome'  => $customer->Nome,
       'dt_entrada'  => $customer->dt_entrada,
       'Dt_Nasc' => $customer->Dt_Nasc,
       'UF' => $customer->UF,
       'Setor' => $customer->Setor,
       'SetorDescricao' => $customer->SetorDescricao,
      );
     }
     Excel::create('Relacao colaboradores ativos', function($excel) use ($customer_array){
      $excel->setTitle('Relacao colaboradores ativos');
      $excel->sheet('Relacao colaboradores ativos', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
    
    public function gerarExcelInativos() {

   $customer_data = DB::table('PLCFULL.dbo.Jurid_Advogado')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->where('PLCFULL.dbo.Jurid_Advogado.Status', '=', 'Inativo')
             ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Advogado.dt_entrada AS DateTime) as dt_entrada'),
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Advogado.dt_saida AS DateTime) as dt_saida'), 
                     'PLCFULL.dbo.Jurid_Advogado.UF',
                     'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao')
             ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc') 
             ->get()
             ->toArray();
   
     $customer_array[] = array('CPF',  'Nome', 'dt_entrada' ,'dt_saida', 'UF', 'Setor', 'SetorDescricao');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'CPF'  => $customer->CPF,
       'Nome'  => $customer->Nome,
       'dt_entrada'  => $customer->dt_entrada,
       'dt_saida' => $customer->dt_saida,
       'UF' => $customer->UF,
       'Setor' => $customer->Setor,
       'SetorDescricao' => $customer->SetorDescricao,
      );
     }
     Excel::create('Relacao colaboradores inativos', function($excel) use ($customer_array){
      $excel->setTitle('Relacao colaboradores inativos');
      $excel->sheet('Relacao colaboradores inativos', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    
    }
          
 
}
