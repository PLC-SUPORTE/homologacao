<?php

namespace App\Http\Controllers\Painel\Notificacao;

use App\Models\Notificacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Laracasts\Flash\Flash;
use Auth;
use Excel;

class NotificacaoController extends Controller
{

    protected $model;

    public function __construct(Notificacao $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $usuarioid = Auth::user()->id;
        $title = 'Painel de Notificações';
        $dataEnviadas =  DB::table('dbo.Hist_Notificacao')
                   ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                           'dbo.Hist_Notificacao.id_ref',
                           'dbo.Hist_Notificacao.tipo as tipo',
                           'dbo.users.name as destino', 
                           'dbo.Hist_Notificacao.data as Data',
                           'dbo.Hist_Notificacao.obs as obs',
                           'dbo.Hist_Notificacao.status as Status')  
                   ->join('dbo.users','dbo.Hist_Notificacao.destino_id','=','dbo.users.id')
                   ->where('dbo.Hist_Notificacao.user_id','=',$usuarioid)
                   ->get();   
        
        $dataRecebidas =  DB::table('dbo.Hist_Notificacao')
                   ->select('dbo.Hist_Notificacao.id as idNotificacao', 
                           'dbo.Hist_Notificacao.id_ref',
                           'dbo.Hist_Notificacao.tipo as tipo', 
                           'dbo.users.name as RecebidaPelo', 
                           'dbo.Hist_Notificacao.data as Data',
                           'dbo.Hist_Notificacao.obs as obs',
                           'dbo.Hist_Notificacao.status as Status')  
                   ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                   ->where('dbo.Hist_Notificacao.destino_id','=',$usuarioid)
                   ->get();    

  $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesPendentes = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '8')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
     $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,10,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  
       
      $totalPendencias = DB::table('dbo.debite_temp')
                ->where('user_id', Auth::user()->id)
                ->count();
      
     $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
                ->where('status', 'A')
                ->where('destino_id','=', Auth::user()->id)
                ->count();
                
     $notificacoes = DB::table('dbo.Hist_Notificacao')
                ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
                ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
                ->where('dbo.Hist_Notificacao.status','=','A')
                ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
                ->orderby('dbo.Hist_Notificacao.data', 'desc')
                ->get();
      
      return view('Painel.Notificacoes.index', compact('totalSolicitacoesAbertas', 'totalSolicitacoesPendentes', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente', 'title', 'totalNotificacaoAbertas', 'notificacoes', 'dataEnviadas', 'dataRecebidas'));
    }


    public function update($id, $numerodebite)
    {
      //Pega o id da notificação selecionada, vai da Update na Tabela Notificação e depois ir para o numero debite selecionado
       
     DB::table('dbo.Hist_Notificacao')
      ->where('id', $id)  
      ->limit(1) 
      ->update(array('status' => 'V'));
    
     flash('Notificação lida com sucesso !')->success();   

     //Verificar qual tipo de usuario dele para direcionar para a rota
     $profile = DB::table('dbo.profiles')
     ->join('dbo.profile_user', 'dbo.profiles.id', '=', 'dbo.profile_user.profile_id')
     ->select('name')  
     ->where('dbo.profile_user.user_id','=',Auth::user()->id)
     ->value('name'); 

    //Se for Correspondente
      if($profile == "Correspondentes") {
        return redirect()
            ->route('Painel.Correspondente.index');
    }
    //Se for Advogado
      if($profile == "Advogado") {
        return redirect()
            ->route('Painel.Advogado.index');
    }
    //Se for Coordernador
    else if($profile == "Revisão Técnica (Gerente, Coordenador,Subcoordenador)") {
            return redirect()
            ->route('Painel.Coordenador.index');

    }
    //Se for Financeiro Aprova as solicitações de debite
    else if($profile == "Financeiro Aprova Solicitação") {
            return redirect()
            ->route('Painel.Financeiro.index');

    }
    //Se for Financeiro Paga no Advwin e anexa comprovante de pagamento no Portal
     else if($profile == "Financeiro Contas a Pagar") {
        return redirect()
        ->route('Painel.Financeiro.programadas');
     }
    //Não é nenhum destes
    else {
         return redirect()
            ->route('Painel.Notificacao.index');
    }    

    }

    public function destroy($id)
    {
        //
    }

    public function gerarNotificacoesRecebidas() {

        $customer_data = DB::table('dbo.Hist_Notificacao')
        ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->select(
                'dbo.Hist_Notificacao.id_ref as Numero',
                DB::raw('CAST(dbo.Hist_Notificacao.data AS DateTime) as Data'), 
                'dbo.users.name as Remetente',
                'dbo.Hist_Notificacao.obs as Observacao',
                'dbo.Hist_Notificacao.status as Status')  
        ->where('dbo.Hist_Notificacao.destino_id', '=', Auth::user()->id)
        ->get()
        ->toArray();

$customer_array[] = array('Numero', 'Data' ,'Remetente', 'Observacao', 'Status');
foreach($customer_data as $customer)
{
 $customer_array[] = array(
  'Numero'  => $customer->Numero,
  'Data'=> date('d/m/Y', strtotime($customer->Data)),
  'Remetente' => $customer->Remetente,
  'Observacao' => $customer->Observacao,
  'Status' => $customer->Status );
}
Excel::create('Notificações recebidas', function($excel) use ($customer_array){
 $excel->setTitle('Notificações recebidas');
 $excel->sheet('Notificações recebidas', function($sheet) use ($customer_array){
  $sheet->fromArray($customer_array, null, 'A1', false, false);
 });
})->download('xlsx');

    }

    public function gerarNotificacoesEnviadas() {


        $customer_data = DB::table('dbo.Hist_Notificacao')
        ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->select(
                'dbo.Hist_Notificacao.id_ref as Numero',
                DB::raw('CAST(dbo.Hist_Notificacao.data AS DateTime) as Data'), 
                'dbo.users.name as Destino',
                'dbo.Hist_Notificacao.obs as Observacao',
                'dbo.Hist_Notificacao.status as Status')  
        ->where('dbo.Hist_Notificacao.user_id', '=', Auth::user()->id)
        ->get()
        ->toArray();

$customer_array[] = array('Numero', 'Data' ,'Destino', 'Observacao', 'Status');
foreach($customer_data as $customer)
{
 $customer_array[] = array(
  'Numero'  => $customer->Numero,
  'Data'=> date('d/m/Y', strtotime($customer->Data)),
  'Destino' => $customer->Destino,
  'Observacao' => $customer->Observacao,
  'Status' => $customer->Status );
}
Excel::create('Notificações enviadas', function($excel) use ($customer_array){
 $excel->setTitle('Notificações enviadas');
 $excel->sheet('Notificações enviadas', function($sheet) use ($customer_array){
  $sheet->fromArray($customer_array, null, 'A1', false, false);
 });
})->download('xlsx');

    }



}

