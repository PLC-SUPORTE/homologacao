<?php

namespace App\Http\Controllers\Painel\TI;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Excel;
use App\Mail\UsuarioEditado;
use App\Mail\Usuario\NovaSenha;
use App\Mail\Agendamento\AgendamentoCancelado;
use Illuminate\Support\Facades\Mail;
use DateTime;


class TIController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    

    public function tarefasagendadas_index() {
          
       $datas = DB::table('dbo.SetorTI_TarefasAgendadas_Matrix')
             ->select('dbo.SetorTI_TarefasAgendadas_Matrix.id as Id',
                      'dbo.SetorTI_TarefasAgendadas_Matrix.descricao as Descricao',
                      'dbo.SetorTI_TarefasAgendadas_Matrix.modulo as Modulo',
                      'dbo.SetorTI_TarefasAgendadas_Matrix.frequencia as Frequencia',
                      'dbo.SetorTI_TarefasAgendadas_Matrix.horario as Horario',
                      'dbo.SetorTI_TarefasAgendadas_Matrix.status as Status')
             ->where('dbo.SetorTI_TarefasAgendadas_Matrix.status', '=', 'Ativo')
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

       
      return view('Painel.TI.tarefasagendadas.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    private function convertData($data){
      return date("Y-m-d H:i:s", strtotime($data));
    }

    public function forceOneSalvarDados($data){
      DB::table('dbo.TI_ForceOne_Matrix')->delete(); //deletar os dados antigos

      ini_set('memory_limit', '-1'); //aumentando o limite da memoria para conseguir colocar os dados em array
      set_time_limit(0);

      $client = new \GuzzleHttp\Client(); //dando get na api para pegar os novos dados
      $request = $client->get('https://force1.magma3.com.br/controller/BusinessInteligence/PegaJanelasAtivas?nome_reduzido_empresa=plcadvogados&chave_secreta=Q1tlxQG5xvEw6j5QR9fdnQiNe5JWPpvGgYV0ge78gT4b&data_inicio_foco='.$data);
      $response = $request->getBody();
      $data = $response->getContents();
      $data = json_decode($data, true);

      foreach($data['data'] as $index => $value){ // foreach para preencher os dados na base
        DB::table('dbo.TI_ForceOne_Matrix')->insert([
          'titulo' => (strlen($value['Titulo']) > 255 ? 'PLC Advogados(Titulo maior que 255) - Google Chrome' : $value['Titulo']),
          'data_criacao' => $this->convertData($value['data_criacao']),
          'data_inicio_foco' => $this->convertData($value['DataInicioFoco']),
          'data_fim_foco' => $this->convertData($value['DataFimFoco']),
          'nome_processo' => $value['NomeProcesso'],
          'pid' => $value['Pid'],
          'ativo_id' => $value['Ativo.Id'],
          'ativo_nome' => $value['Ativo.Nome'],
          'ativo_login' => $value['Ativo.Login'],
          'ativo_unidade_administrativa_hierarquia' => $value['Ativo.UnidadeAdministrativa.Hierarquia'],
          'created_at' => date('Y-m-d H:i:s')
        ]);
      }
    }

    public function tarefasagendadas_hist($id_matrix) {

        $datas = DB::table('dbo.SetorTI_TarefasAgendadas_Hist')
        ->leftjoin('dbo.SetorTI_TarefasAgendadas_Matrix', 'dbo.SetorTI_TarefasAgendadas_Hist.id_matrix', 'dbo.SetorTI_TarefasAgendadas_Matrix.id')
        ->select('dbo.SetorTI_TarefasAgendadas_Hist.id as Id',
                 'dbo.SetorTI_TarefasAgendadas_Hist.data as Data',
                 'dbo.SetorTI_TarefasAgendadas_Matrix.modulo as Modulo',
                 'dbo.SetorTI_TarefasAgendadas_Matrix.frequencia as Frequencia',
                 'dbo.SetorTI_TarefasAgendadas_Hist.proximohorario as DataProximoHorario',
                 'dbo.SetorTI_TarefasAgendadas_Hist.observacao as Observacao')
        ->where('dbo.SetorTI_TarefasAgendadas_Hist.id_matrix', '=', $id_matrix)
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

  
         return view('Painel.TI.tarefasagendadas.hist', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    

    public function chat_index() {

      $contatos = DB::table('dbo.contato_whatsapp')
      ->select('id','numero as Nome', 'descricao as Descricao')  
      ->orderby('numero', 'asc')
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
         'status', 
         'dbo.users.*')  
         ->limit(3)
         ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
         ->where('dbo.Hist_Notificacao.status','=','A')
         ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
         ->orderBy('dbo.Hist_Notificacao.data', 'desc')
         ->get();


      return view('Painel.TI.chat.index', compact('contatos','totalNotificacaoAbertas', 'notificacoes'));;

    }

    public function chat_enviamensagem(Request $request) {

      $response = $request->get('message');
      $carbon= Carbon::now();
      $contato = $request->get('contato');

      $values = array(
        'numero' => $contato,
        'visualizada' => '0', 
        'texto' => $response, 
        'data' => $carbon);
      DB::table('dbo.contato_whatsapp_envio')->insert($values);


      echo $response;

    }

    public function chat_trazmensagens(Request $request) {

      $contato_nome = $request->get('contato_id');
      $carbon= Carbon::now();
      $usuario_id = Auth::user()->id;

      $response = DB::table('dbo.contato_whatsapp_envio')
      ->rightjoin('dbo.contato_whatsapp', 'dbo.contato_whatsapp_envio.numero', '=', 'dbo.contato_whatsapp.numero')
      ->leftjoin('dbo.contato_whatsapp_recebimento', 'dbo.contato_whatsapp_envio.numero', 'dbo.contato_whatsapp_recebimento.numero')
      ->select('dbo.contato_whatsapp_envio.id','dbo.contato_whatsapp.numero','dbo.contato_whatsapp_envio.texto as textoenviado', 'dbo.contato_whatsapp_recebimento.texto as textorecebido') 
      ->where('dbo.contato_whatsapp.id', '=', $contato_nome) 
      ->orderBy('dbo.contato_whatsapp_envio.visualizada_recebimento', 'asc')
      ->get();

       echo $response;

    }

     public function chat_buscacontato(Request $request) {

  
       $query = $request->get('query');

       $data = DB::table('dbo.contato_whatsapp')
       ->select(
        'dbo.contato_whatsapp.id',
        'dbo.contato_whatsapp.numero as numero',
        'dbo.contato_whatsapp.descricao as descricao')
      ->where('dbo.contato_whatsapp.numero', 'LIKE',  $query . '%')
      ->orWhere('dbo.contato_whatsapp.descricao', 'LIKE',  $query . '%')
      ->get();

        $output = '<ul>';
       foreach($data as $row)
       {
        $output .= '
        <li class="contact active">
        <a href="javascript:onClick('.$row->id.')">
					<div class="wrap">
						<span class="contact-status online"></span>
						<img src="http://emilcarlsson.se/assets/louislitt.png" alt="" />
						<div class="meta">
							<p class="name">'.$row->numero.'</p>
							<p class="preview">'.$row->descricao.'</p>
						</div>
					</div>
          </a>
				</li>';
       }
       $output .= '</ul>';
       echo $output;
      
    }


    public function correspondente_index() {


      $title = 'Painel de Notas';
      $usuario_nome = Auth::user()->name;
      $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
            ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')  
            ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
            ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                    'PLCFULL.dbo.Jurid_Debite.Pasta',
                    'dbo.users.name as Correspondente',
                    'PLCFULL.dbo.Jurid_Debite.Data as DataFicha', 
                    DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                    DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                    'dbo.Jurid_Status_Ficha.id as StatusID',
                    'dbo.Jurid_Status_Ficha.descricao as descricao',
                    'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServicoDescricao',
                    'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante')
            ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,13,14))  
            ->get();

      $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
      ->where('status', 'A')
      ->where('destino_id','=', Auth::user()->id)
      ->count();
    
      $notificacoes = DB::table('dbo.Hist_Notificacao')
      ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
      ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
      ->where('dbo.Hist_Notificacao.status','=','A')
      ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
      ->get();


      return view('Painel.TI.correspondente.index', compact('notas','totalNotificacaoAbertas', 'notificacoes'));


    }


    public function correspondente_notificacoes($id) {

      $datas = DB::table('dbo.Hist_Notificacao')
      ->select('dbo.Hist_Notificacao.id as idNotificacao',
               'data', 
               'id_ref', 
               'user_id', 
               'tipo', 
               'obs', 
               'status', 
               'dbo.users.name as Nome')  
      ->leftjoin('dbo.users','dbo.Hist_Notificacao.destino_id','=','dbo.users.id')
      ->where('dbo.Hist_Notificacao.id_ref','=',$id)
      ->get();

      $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
      ->where('status', 'A')
      ->where('destino_id','=', Auth::user()->id)
      ->count();
    
      $notificacoes = DB::table('dbo.Hist_Notificacao')
      ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
      ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
      ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
      ->get();

      return view('Painel.TI.correspondente.notificacoes', compact('datas','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function correspondente_gerarExcelAbertas() {
        
     $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
     ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')  
     ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
     ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
     ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
     ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
     ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
     ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
             'PLCFULL.dbo.Jurid_Debite.Pasta',
             DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataSolicitacao'), 
            //  DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
             'PLCFULL.dbo.Jurid_Debite.ValorT as Valor', 
             'dbo.Jurid_Status_Ficha.descricao as Status',
             'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
             'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
             'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
             'dbo.users.name as Correspondente')  
     ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
     ->get()
     ->toArray();
$customer_array[] = array('Numero',  'Pasta', 'Correspondente', 'Solicitante', 'Setor', 'TipoServico' , 'Valor','DataSolicitacao', 'Status');
foreach($customer_data as $customer)
{
$customer_array[] = array(
'Número'  => $customer->Numero,
'Pasta'  => $customer->Pasta,
'Correspondente' => $customer->Correspondente,   
'Solicitante' => $customer->Solicitante,   
'Setor' => $customer->Setor,
'TipoServico' => $customer->TipoServico, 
'Valor' => number_format($customer->Valor,2,",","."),
'DataSolicitacao'=> date('d/m/Y', strtotime($customer->DataSolicitacao)),
'Status' => $customer->Status,
);
}
Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
$excel->setTitle('Solicitações Pagamento Abertas');
$excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
$sheet->fromArray($customer_array, null, 'A1', false, false);
});
})->download('xlsx');

    }

    public function usuario_alterarsenha(Request $request) {

      $usuario_id = $request->get('id');
      $usuario_email = $request->get('email');
      $usuario_name = $request->get('name');
      $password = $request->get('novasenha');

      $senhacriptgrafada = bcrypt($password);

      DB::table('dbo.users')
      ->where('id', $usuario_id)  
      ->limit(1) 
      ->update(array('password' => $senhacriptgrafada));

      //Envia email informando que os dados foram alterados
      Mail::to($usuario_email)
      ->cc('suporte@plcadvogados.com.br')
      ->send(new UsuarioEditado($usuario_name, $usuario_email, $password));
       return redirect()->route('Painel.TI.users.index'); 

  }

  public function usuario_alterarstatus(Request $request) {

    $usuario_id = $request->get('id');
    $usuario_email = $request->get('email');
    $usuario_name = $request->get('name');
    $data_criacao = $request->get('data_criacao');
    $data_desativacao = $request->get('data_desativacao');
    $status = $request->get('status');
    
    DB::table('dbo.users')
    ->where('id', $usuario_id)  
    ->limit(1) 
    ->update(array('data_criacao' => $data_criacao, 'data_desativacao' => $data_desativacao, 'status' => $status));

    return redirect()->route('Painel.TI.users.index'); 

}

  public function agendamentomesa_index() {

    $carbon= Carbon::now();
    $diahoje = $carbon->format('d');

    // $diaamanha = $diahoje + 1;
    $datahoje = $carbon->format('Y-m-d H:i');
    $datahojeformato = $carbon->format('Y-m-'.$diahoje);
    $horaminuto = $carbon->format('H:i');


    $datas = DB::table('dbo.TI_AgendamentoMesa_mesas')
    ->select('dbo.TI_AgendamentoMesa_matrix.id as id',
             'dbo.TI_AgendamentoMesa_mesas.descricao as Descricao',
             'dbo.TI_AgendamentoMesa_salas.andar as Andar', 
             'dbo.TI_AgendamentoMesa_mesas.corredor as Corredor',
             'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
             'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
             'dbo.TI_AgendamentoMesa_status.id as Status',
             'dbo.TI_AgendamentoMesa_salas.tipo_id as Tipo',
             'dbo.TI_AgendamentoMesa_matrix.quantidadeparticipantes as QuantidadeParticipantes',
             'dbo.TI_AgendamentoMesa_matrix.cliente as Cliente',
             'dbo.TI_AgendamentoMesa_matrix.observacao as Observacao',
             'dbo.TI_AgendamentoMesa_status.descricao as StatusDescricao',
             'dbo.TI_AgendamentoMesa_matrix.datainicio as DataInicio',
             'dbo.TI_AgendamentoMesa_matrix.datafim as DataFim')  
    ->leftjoin('dbo.users','dbo.TI_AgendamentoMesa_mesas.responsavel_id','=','dbo.users.id')
    ->join('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', '=', 'dbo.TI_AgendamentoMesa_salas.id')
    ->join('dbo.TI_AgendamentoMesa_matrix', 'dbo.TI_AgendamentoMesa_mesas.id', '=', 'dbo.TI_AgendamentoMesa_matrix.mesa_id')
    ->join('dbo.TI_AgendamentoMesa_status', 'dbo.TI_AgendamentoMesa_matrix.status_id', '=', 'dbo.TI_AgendamentoMesa_status.id')
    ->join('PLCFULL.dbo.Jurid_Unidade', 'dbo.TI_AgendamentoMesa_salas.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->where('dbo.TI_AgendamentoMesa_matrix.user_id','=', Auth::user()->id)
    ->whereDate('dbo.TI_AgendamentoMesa_matrix.datainicio', '>=', $datahoje)
    ->get();

    $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
    ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao') 
    ->whereNotIn('PLCFULL.dbo.Jurid_Unidade.Codigo', ['1.','1.1','1.15'])
    ->orderBy('PLCFULL.dbo.Jurid_Unidade.Descricao', 'asc')
    ->get();

    $horarioshojeentrada = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('descricao', '>=', $horaminuto)
    ->where('status','=', 'A')
    ->get();

    $primeiroid = $horarioshojeentrada[0]->id;

    $horarioshojesaida = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('status','=', 'A')
    ->where('descricao', '>', $horaminuto)
    ->Where('id', '!=', $primeiroid)
    ->get();

    $horariosentrada = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('status','=', 'A')
    ->get();

    $horariossaida = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('status','=', 'A')
    ->Where('id', '!=', '1')
    ->get();


    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();


    return view('Painel.TI.agendamentomesa.index', compact('datas','datahoje','datahojeformato','unidades','horarioshojeentrada','horarioshojesaida','horariosentrada','horariossaida','totalNotificacaoAbertas', 'notificacoes'));


  }

 

  public function agendamentomesa_mesasdisponiveis(Request $request) {

    $unidade = $request->get('unidade');
    $datainicio = $request->get('datainicio');
    $tiposala_id = $request->get('sala');
    $utilizacao = $request->get('agendamentodiatodo');
    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');


    $unidade_descricao = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Descricao')->where('Codigo','=',$unidade)->value('Descricao'); 

    //Verifico se o usuario já tem um agendamento para esta data
    $verifica = DB::table('dbo.TI_AgendamentoMesa_matrix')
                ->select('id')
                ->where('user_id', Auth::user()->id)
                ->where('dia','=',$datainicio)
                ->count(); 

    if($verifica >= 1) {

      //Retorna ao index com a mensagem
      \Session::flash('message', ['msg'=>'Você já possui um agendamento para está data.', 'class'=>'red']);
      return redirect()->route('Painel.TI.agendamentomesa.index');


    }


    //Se for sala comum 
    if($tiposala_id == 1) {


      if($request->get('verificadata') == "on")  {

        $horarioid_inicio = $request->get('horarioiniciohoje');
        $horarioid_fim = $request->get('horariofimhoje');  
      } else {
        $horarioid_inicio = $request->get('horarioinicio');
        $horarioid_fim = $request->get('horariofim');  
      }
      

      $horario_inicio = DB::table('dbo.TI_AgendamentoMesa_horarios')->select('descricao')->where('id','=',$horarioid_inicio)->value('descricao'); 
      $horario_fim = DB::table('dbo.TI_AgendamentoMesa_horarios')->select('descricao')->where('id','=',$horarioid_fim)->value('descricao'); 
    
      $time1 = date_create($datainicio . $horario_inicio);
      $time2 = date_create($datainicio . $horario_fim);
    
      $startTime = date_format($time1, 'Y-m-d H:i:s');
      $endTime = date_format($time2, 'Y-m-d H:i:s');



      //Se for selecionado horario vai verificar as estações de trabalho que não tenha nenhum agendamento ou o horario não seja entre um destes informados
      $datas = DB::table('dbo.TI_AgendamentoMesa_mesas')
      ->select('dbo.TI_AgendamentoMesa_mesas.id',)
      ->leftjoin('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', 'dbo.TI_AgendamentoMesa_salas.id')
      ->leftjoin('dbo.TI_AgendamentoMesa_matrix', 'dbo.TI_AgendamentoMesa_mesas.id', 'dbo.TI_AgendamentoMesa_matrix.mesa_id')
      ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', $tiposala_id)
      ->where('dbo.TI_AgendamentoMesa_salas.unidade', $unidade)
      ->whereNull('dbo.TI_AgendamentoMesa_matrix.id')
      ->orwhereDate('dbo.TI_AgendamentoMesa_matrix.dia', '=', $datainicio)
      ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', $tiposala_id)
      ->where('dbo.TI_AgendamentoMesa_salas.unidade', $unidade)
      ->orwhereDate('dbo.TI_AgendamentoMesa_matrix.dia', '!=', $datainicio)
      ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', $tiposala_id)
      ->where('dbo.TI_AgendamentoMesa_salas.unidade', $unidade)
      ->groupby('dbo.TI_AgendamentoMesa_mesas.id')
      ->orderby('dbo.TI_AgendamentoMesa_mesas.id', 'asc')
      ->get();

      
      //Grava na tabela temporaria pegando cada estação de trabalho, vendo se esta sendo utilizada para puxar depois
      foreach ($datas as $data) {

        $estacao_id = $data->id;

        $verifica =  DB::table('dbo.TI_AgendamentoMesa_matrix')->select('dia')->where('mesa_id','=',$estacao_id)->where('dia', '=', $datainicio)->value('dia');


        if($verifica == $datainicio) {

        $id_matrix = DB::table('dbo.TI_AgendamentoMesa_matrix')
                      ->select('id')
                      ->where('mesa_id','=',$estacao_id)
                      ->where('dia', '=', $datainicio)
                      ->value('id');

        $estacao = DB::table('dbo.TI_AgendamentoMesa_mesas')
        ->select('dbo.TI_AgendamentoMesa_mesas.id', 
        'dbo.TI_AgendamentoMesa_mesas.identificacao',
        'dbo.TI_AgendamentoMesa_matrix.id as agendamento',
        'dbo.TI_AgendamentoMesa_matrix.dia',
        'dbo.users.name as SocioNome',
        'dbo.TI_AgendamentoMesa_mesas.descricao',
        'dbo.TI_AgendamentoMesa_mesas.corredor',
        'dbo.TI_AgendamentoMesa_salas.descricao as sala',
        'dbo.TI_AgendamentoMesa_salas.andar as andar')
        ->leftjoin('dbo.TI_AgendamentoMesa_matrix', 'dbo.TI_AgendamentoMesa_mesas.id', 'dbo.TI_AgendamentoMesa_matrix.mesa_id')
        ->leftjoin('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', 'dbo.TI_AgendamentoMesa_salas.id')
        ->leftjoin('dbo.users', 'dbo.TI_AgendamentoMesa_matrix.user_id', 'dbo.users.id')
        ->where('dbo.TI_AgendamentoMesa_matrix.id', '=', $id_matrix)
        ->first();           

        } else {

        $estacao = DB::table('dbo.TI_AgendamentoMesa_mesas')
          ->select('dbo.TI_AgendamentoMesa_mesas.id', 
          'dbo.TI_AgendamentoMesa_mesas.identificacao',
          'dbo.TI_AgendamentoMesa_matrix.id as agendamento',
          'dbo.TI_AgendamentoMesa_matrix.dia',
          'dbo.users.name as SocioNome',
          'dbo.TI_AgendamentoMesa_mesas.descricao',
          'dbo.TI_AgendamentoMesa_mesas.corredor',
          'dbo.TI_AgendamentoMesa_salas.descricao as sala',
          'dbo.TI_AgendamentoMesa_salas.andar as andar')
          ->leftjoin('dbo.TI_AgendamentoMesa_matrix', 'dbo.TI_AgendamentoMesa_mesas.id', 'dbo.TI_AgendamentoMesa_matrix.mesa_id')
          ->leftjoin('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', 'dbo.TI_AgendamentoMesa_salas.id')
          ->leftjoin('dbo.users', 'dbo.TI_AgendamentoMesa_matrix.user_id', 'dbo.users.id')
          ->where('dbo.TI_AgendamentoMesa_mesas.id', '=', $estacao_id)
          ->first();

        }


          $value1 = array(
            'user_id' => Auth::user()->id,
            'estacao_id' => $estacao->id, 
            'agendamento' => $estacao->agendamento,
            'identificacao' => $estacao->identificacao, 
            'dia' => $estacao->dia,
            'socionome' => $estacao->SocioNome,
            'descricao' => $estacao->descricao,
            'corredor' => $estacao->corredor,
            'sala' => $estacao->sala,
            'andar' => $estacao->andar);
          DB::table('dbo.TI_AgendamentoMesa_temp')->insert($value1);
       


      }


      //Busco os dados filtrados
      $datas = DB::table('dbo.TI_AgendamentoMesa_temp')
      ->select('dbo.TI_AgendamentoMesa_temp.id', 
      'dbo.TI_AgendamentoMesa_temp.identificacao',
      'dbo.TI_AgendamentoMesa_temp.id as agendamento',
      'dbo.TI_AgendamentoMesa_temp.dia',
      'dbo.TI_AgendamentoMesa_temp.socionome as SocioNome',
      'dbo.TI_AgendamentoMesa_temp.descricao',
      'dbo.TI_AgendamentoMesa_temp.corredor',
      'dbo.TI_AgendamentoMesa_temp.descricao as sala',
      'dbo.TI_AgendamentoMesa_temp.andar as andar')
      ->where('user_id', Auth::user()->id)
      ->get();

      //Deleta os dados temp
      DB::table('dbo.TI_AgendamentoMesa_temp')->where('user_id', Auth::user()->id)->delete();        


      $socios = DB::table('dbo.TI_AgendamentoMesa_matrix')
      ->select(
      'dbo.users.name as SocioNome',
      'dbo.TI_AgendamentoMesa_mesas.descricao as Mesa',
      'PLCFULL.dbo.Jurid_Setor.Descricao as Setor')
      ->join('dbo.users', 'dbo.TI_AgendamentoMesa_matrix.user_id', 'dbo.users.id')
      ->join('dbo.TI_AgendamentoMesa_mesas', 'dbo.TI_AgendamentoMesa_matrix.mesa_id', 'dbo.TI_AgendamentoMesa_mesas.id')
      ->join('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', 'dbo.TI_AgendamentoMesa_salas.id')
      ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
      ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
      ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', $tiposala_id)
      ->where('dbo.TI_AgendamentoMesa_salas.unidade', $unidade)
      ->WhereDate('dbo.TI_AgendamentoMesa_matrix.dia', '=', $datainicio)
      ->whereRaw("'$startTime' BETWEEN dbo.TI_AgendamentoMesa_matrix.datainicio AND dbo.TI_AgendamentoMesa_matrix.datafim")
      ->orwhereRaw("'$endTime' NOT BETWEEN dbo.TI_AgendamentoMesa_matrix.datainicio AND dbo.TI_AgendamentoMesa_matrix.datafim")
      ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', $tiposala_id)
      ->where('dbo.TI_AgendamentoMesa_salas.unidade', $unidade)
      ->WhereDate('dbo.TI_AgendamentoMesa_matrix.dia', '=', $datainicio)
      ->get();

      //Verifico o dia da semana para determinar a cor
      $data = Carbon::parse($datainicio);
      $determinacor = $data->format('w');

      //Segunda-1
      //Terça-2
      //Quarta-3
      //Quinta-4
      //Sexta-5

      //Se for Unidade BHZ
      if($unidade == 1.3) {

        
      if($determinacor == 1 || $determinacor == 3 || $determinacor == 5) {
        $cor = "Verde";
      } else {
        $cor = "Verde";
      }

        return view('Painel.TI.agendamentomesa.mesasbhz', compact('cor','socios','horarioid_inicio','horarioid_fim','datas','datainicio','datahoje','unidade','unidade_descricao','startTime','endTime'));
      } 
      //Se for Unidade SP
      elseif($unidade == 1.6) {

        
      if($determinacor == 1 || $determinacor == 2 ||  $determinacor == 5) {
        $cor = "Verde";
      } else {
        $cor = "Amarelo";
      }

        return view('Painel.TI.agendamentomesa.mesasspo', compact('cor','socios','horarioid_inicio','horarioid_fim','datas','datainicio','datahoje','unidade','unidade_descricao','startTime','endTime'));
      }
      //Se for RJ
      elseif($unidade == 1.7) {
        return view('Painel.TI.agendamentomesa.mesasrjo', compact('cor','socios','horarioid_inicio','horarioid_fim','datas','datainicio','datahoje','unidade','unidade_descricao','startTime','endTime'));
      }

    } 
    //Se for sala de reunião
    else {

        
          if($request->get('verificadata') == "on")  {

            $horarioid_inicio = $request->get('horarioiniciohoje');
            $horarioid_fim = $request->get('horariofimhoje');  
          } else {
            $horarioid_inicio = $request->get('horarioinicio');
            $horarioid_fim = $request->get('horariofim');  
          }
  
        $horario_inicio = DB::table('dbo.TI_AgendamentoMesa_horarios')->select('descricao')->where('id','=',$horarioid_inicio)->value('descricao'); 
        $horario_fim = DB::table('dbo.TI_AgendamentoMesa_horarios')->select('descricao')->where('id','=',$horarioid_fim)->value('descricao'); 
      
        $time1 = date_create($datainicio . $horario_inicio);
        $time2 = date_create($datainicio . $horario_fim);
      
        $startTime = date_format($time1, 'Y-m-d H:i:s');
        $endTime = date_format($time2, 'Y-m-d H:i:s');
  
        //Se for selecionado horario vai verificar as estações de trabalho que não tenha nenhum agendamento ou o horario não seja entre um destes informados
        $datas = DB::table('dbo.TI_AgendamentoMesa_mesas')
        ->select('dbo.TI_AgendamentoMesa_mesas.id', 
        'dbo.TI_AgendamentoMesa_mesas.descricao',
        'dbo.TI_AgendamentoMesa_salas.descricao as sala',
        'dbo.TI_AgendamentoMesa_salas.unidade',
        'dbo.TI_AgendamentoMesa_salas.quantidade_participantes as quantidade',
        'dbo.TI_AgendamentoMesa_salas.tipo_id as tipo',
        'dbo.TI_AgendamentoMesa_salas.andar as andar')
        ->leftjoin('dbo.TI_AgendamentoMesa_matrix', 'dbo.TI_AgendamentoMesa_mesas.id', 'dbo.TI_AgendamentoMesa_matrix.mesa_id')
        ->join('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', 'dbo.TI_AgendamentoMesa_salas.id')
        ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', $tiposala_id)
        ->where('dbo.TI_AgendamentoMesa_salas.unidade', $unidade)
        ->whereNull('dbo.TI_AgendamentoMesa_matrix.id')
        ->orWhereDate('dbo.TI_AgendamentoMesa_matrix.dia', '=', $datainicio)
        ->whereRaw("'$startTime' NOT BETWEEN dbo.TI_AgendamentoMesa_matrix.datainicio AND dbo.TI_AgendamentoMesa_matrix.datafim")
        ->whereRaw("'$endTime' NOT BETWEEN dbo.TI_AgendamentoMesa_matrix.datainicio AND dbo.TI_AgendamentoMesa_matrix.datafim")
        ->orwhereDate('dbo.TI_AgendamentoMesa_matrix.dia', '!=', $datainicio)
        ->groupby('dbo.TI_AgendamentoMesa_mesas.id', 'dbo.TI_AgendamentoMesa_mesas.descricao', 'dbo.TI_AgendamentoMesa_salas.descricao', 'dbo.Ti_AgendamentoMesa_salas.unidade','dbo.TI_AgendamentoMesa_salas.tipo_id','dbo.TI_AgendamentoMesa_salas.andar', 'dbo.TI_AgendamentoMesa_salas.quantidade_participantes')
        ->orderBy('dbo.TI_AgendamentoMesa_salas.descricao', 'ASC')
        ->get();
      
      $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
      ->where('status', 'A')
      ->where('destino_id','=', Auth::user()->id)
      ->count();
    
      $notificacoes = DB::table('dbo.Hist_Notificacao')
      ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
      ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
      ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
      ->get();

  
      return view('Painel.TI.agendamentomesa.salasreuniaodisponiveis', compact('tiposala_id','totalNotificacaoAbertas','notificacoes','horarioid_inicio','horarioid_fim','datas','datainicio','datahoje','unidade','unidade_descricao','startTime','endTime'));


    }
 

  
  }

  public function agendamentomesa_agendamentoreuniao(Request $request) {

    $datainicio = $request->get('datainicio');
    $startTime = $request->get('startTime');
    $endTime = $request->get('endTime');
    $unidade = $request->get('unidade');
    $carbon= Carbon::now();
    $horarioid_inicio = $request->get('horarioid_inicio');
    $horarioid_fim = $request->get('horarioid_fim');
    $datainicio = $request->get('datainicio');
    $sala = $request->get('sala');
    $andar = $request->get('andar');
    $quantidade = $request->get('quantidade');
    $cliente = $request->get('cliente');


    $sala_id = DB::table('dbo.TI_AgendamentoMesa_salas')->select('id')->where('descricao','=',$sala)->value('id'); 
    $mesa = DB::table('dbo.TI_AgendamentoMesa_mesas')->select('id')->where('sala_id','=',$sala_id)->value('id'); 
    $email_salareuniao = DB::table('dbo.TI_AgendamentoMesa_salas')->select('link_outlook')->where('id','=',$sala_id)->value('link_outlook'); 
    $subject = $request->get('assunto');  
    $observacao = $request->get('observacao');    

    $unidade_descricao = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Descricao')->where('Codigo','=',$unidade)->value('Descricao'); 
    $unidade_endereco = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Endereco')->where('Codigo','=',$unidade)->value('Endereco'); 
    $unidade_bairro = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Bairro')->where('Codigo','=',$unidade)->value('Bairro'); 
    $unidade_cidade = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Cidade')->where('Codigo','=',$unidade)->value('Cidade'); 
    $unidade_uf = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('UF')->where('Codigo','=',$unidade)->value('UF'); 
    $unidade_cep =  DB::table('PLCFULL.dbo.Jurid_Unidade')->select('CEP')->where('Codigo','=',$unidade)->value('CEP'); 

    $localizacao = $unidade_endereco . ', ' . $unidade_bairro . ', ' . $unidade_cidade . ', ' . $unidade_uf . ', ' . $unidade_cep;
    
    date_default_timezone_set('America/Sao_Paulo');

    $from_name = "PORTAL PL&C ADVOGADOS";        
    $from_address = "automacao@plcadvogados.com.br";     
    
        //Se for unidade BHZ (1.3) 
         if($unidade == "1.3") {

            if($email_salareuniao != null) {
              $to_address = Auth::user()->email.";".$email_salareuniao.";suporte@plcadvogados.com.br;recepcao@plcadvogados.com.br;gumercindo.ribeiro@plcadvogados.com.br";   
            } else {
              $to_address = Auth::user()->email.";suporte@plcadvogados.com.br;recepcao@plcadvogados.com.br;gumercindo.ribeiro@plcadvogados.com.br";   
            }
  
          }
         //Se for outra unidade
         else {
          if($email_salareuniao != null) {
          $to_address = Auth::user()->email.";".$email_salareuniao.";suporte.sp@plcadvogados.com.br;recepcaosp@plcadvogados.com.br;gumercindo.ribeiro@plcadvogados.com.br";  
          } else {
          $to_address = Auth::user()->email.";suporte.sp@plcadvogados.com.br;recepcaosp@plcadvogados.com.br;gumercindo.ribeiro@plcadvogados.com.br";  
          }
        }  

        // $to_address = 'ronaldo.amaral@plcadvogados.com.br';

        //Grava na Matrix reservando esta mesa
        $values = array('mesa_id' => $mesa, 
                    'user_id' => Auth::user()->id, 
                    'dia' => $datainicio,
                    'datainicio' => $startTime,
                    'datafim' => $endTime,
                    'status_id' => '1',
                    'horarioinicio_id' => $horarioid_inicio,
                    'horariofim_id' => $horarioid_fim,
                    'quantidadeparticipantes' => $quantidade,
                    'assunto' => $subject,
                    'observacao' => $observacao,
                    'cliente' => $cliente);
        DB::table('dbo.TI_AgendamentoMesa_matrix')->insert($values);

        $id_matrix = DB::table('dbo.TI_AgendamentoMesa_matrix')->select('id')->where('mesa_id','=',$mesa)->orderby('id','desc')->value('id');  

    
        $domain = 'exchangecore.com';
    
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----".MD5(TIME());
    
        $headers = "From: ".$from_name." <".$from_address.">\n";
        $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";
    
        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<head>\n";
        $message .= "<style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
          padding: 5px;
          text-align: left;
        }
        </style>\n";
        $message .= "</head>\n";
        $message .= "<body>\n";
        $message .= '<p>Prezado(a), '. mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8").',</p>';
        $message .= '<br></br>';
        $message .= '<table style="width:100%">';
        $message .= '<tr>';
        $message .= '<td>Agendador: '. mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8").'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Sala: '.$sala.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Andar: '.$andar.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Unidade: '.$unidade_descricao.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Endereço: '.$localizacao.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Horário ínicio: '.date('d/m/Y H:i', strtotime($startTime)).'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Horário fim: '.date('d/m/Y H:i', strtotime($endTime)).'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Quantidade de participantes: '.$quantidade.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Observação: '.$observacao.'</td>';
        $message .= '</tr>';
        $message .= '<tr>';
        $message .= '<td>Cliente: '.$cliente.'</td>';
        $message .= '</tr>';
        $message .= '</table>';
        $message .= '<br></br><br></br>';
        $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
    
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
        'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
        'VERSION:2.0' . "\r\n" .
        'METHOD:REQUEST' . "\r\n" .
        'BEGIN:VTIMEZONE' . "\r\n" .
        'TZID:America/Sao_Paulo' . "\r\n" .
        'BEGIN:STANDARD' . "\r\n" .
        'DTSTART:20091101T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
        'TZOFFSETFROM:-0400' . "\r\n" .
        'TZOFFSETTO:-0500' . "\r\n" .
        'TZNAME:EST' . "\r\n" .
        'END:STANDARD' . "\r\n" .
        'BEGIN:DAYLIGHT' . "\r\n" .
        'DTSTART:20090301T020000' . "\r\n" .
        'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
        'TZOFFSETFROM:-0300' . "\r\n" .
        'TZOFFSETTO:-0300' . "\r\n" .
        'TZNAME:EDST' . "\r\n" .
        'END:DAYLIGHT' . "\r\n" .
        'END:VTIMEZONE' . "\r\n" .  
        'BEGIN:VEVENT' . "\r\n" .
        'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
        'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
        'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
        'UID:recepcao@plcadvogados.com.br\r\n"' .
        'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
        'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($startTime))."T".date("His", strtotime($startTime)). "\r\n" .
        'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($endTime))."T".date("His", strtotime($endTime)). "\r\n" .
        'TRANSP:OPAQUE'. "\r\n" .
        'SEQUENCE:1'. "\r\n" .
        'SUMMARY:' . $subject . "\r\n" .
        'LOCATION:' . $localizacao . "\r\n" .
        'CLASS:PUBLIC'. "\r\n" .
        'PRIORITY:5'. "\r\n" .
        'BEGIN:VALARM' . "\r\n" .
        'TRIGGER:-PT15M' . "\r\n" .
        'ACTION:DISPLAY' . "\r\n" .
        'DESCRIPTION:Reminder' . "\r\n" .
        'END:VALARM' . "\r\n" .
        'END:VEVENT'. "\r\n" .
        'END:VCALENDAR'. "\r\n";
        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
    
        $mailsent = mail($to_address, $subject, $message, $headers);
        //Fim


        //Participantes

        $nome_participante = $request->get('nome_novoparticipante');
        $email_participante = $request->get('email_novoparticipante');

        if($nome_participante != null && $email_participante != null) {

          foreach($nome_participante as $index => $nome_participante) {

            $email = $email_participante[$index];

            $mime_boundary = "----Meeting Booking----".MD5(TIME());
    
            $headers = "From: ".$from_name." <".$from_address.">\n";
            $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
            $headers .= "MIME-Version: 1.0\n";
            $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
            $headers .= "Content-class: urn:content-classes:calendarmessage\n";
        
            //Create Email Body (HTML)
            $message = "--$mime_boundary\r\n";
            $message .= "Content-Type: text/html; charset=UTF-8\n";
            $message .= "Content-Transfer-Encoding: 8bit\n\n";
            $message .= "<html>\n";
            $message .= "<head>\n";
            $message .= "<style>
            table, th, td {
              border: 1px solid black;
              border-collapse: collapse;
            }
            th, td {
              padding: 5px;
              text-align: left;
            }
            </style>\n";
            $message .= "</head>\n";
            $message .= "<body>\n";
            $message .= '<p>Prezado(a), '. mb_convert_case($nome_participante, MB_CASE_TITLE, "UTF-8").',</p>';
            $message .= '<br></br>';
            $message .= '<table style="width:100%">';
            $message .= '<tr>';
            $message .= '<td>Agendador: '. mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8").'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Sala: '.$sala.'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Andar: '.$andar.'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Unidade: '.$unidade_descricao.'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Endereço: '.$localizacao.'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Horário ínicio: '.date('d/m/Y H:i', strtotime($startTime)).'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Horário fim: '.date('d/m/Y H:i', strtotime($endTime)).'</td>';
            $message .= '</tr>';
            $message .= '<tr>';
            $message .= '<td>Quantidade de participantes: '.$quantidade.'</td>';
            $message .= '</tr>';
            $message .= '</table>';
            $message .= '<br></br><br></br>';
            $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
            $message .= "</body>\n";
            $message .= "</html>\n";
            $message .= "--$mime_boundary\r\n";
        
            $ical = 'BEGIN:VCALENDAR' . "\r\n" .
            'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
            'VERSION:2.0' . "\r\n" .
            'METHOD:REQUEST' . "\r\n" .
            'BEGIN:VTIMEZONE' . "\r\n" .
            'TZID:America/Sao_Paulo' . "\r\n" .
            'BEGIN:STANDARD' . "\r\n" .
            'DTSTART:20091101T020000' . "\r\n" .
            'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
            'TZOFFSETFROM:-0400' . "\r\n" .
            'TZOFFSETTO:-0500' . "\r\n" .
            'TZNAME:EST' . "\r\n" .
            'END:STANDARD' . "\r\n" .
            'BEGIN:DAYLIGHT' . "\r\n" .
            'DTSTART:20090301T020000' . "\r\n" .
            'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
            'TZOFFSETFROM:-0300' . "\r\n" .
            'TZOFFSETTO:-0300' . "\r\n" .
            'TZNAME:EDST' . "\r\n" .
            'END:DAYLIGHT' . "\r\n" .
            'END:VTIMEZONE' . "\r\n" .  
            'BEGIN:VEVENT' . "\r\n" .
            'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
            'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
            'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
            'UID:recepcao@plcadvogados.com.br\r\n"' .
            'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
            'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($startTime))."T".date("His", strtotime($startTime)). "\r\n" .
            'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($endTime))."T".date("His", strtotime($endTime)). "\r\n" .
            'TRANSP:OPAQUE'. "\r\n" .
            'SEQUENCE:1'. "\r\n" .
            'SUMMARY:' . $subject . "\r\n" .
            'LOCATION:' . $localizacao . "\r\n" .
            'CLASS:PUBLIC'. "\r\n" .
            'PRIORITY:5'. "\r\n" .
            'BEGIN:VALARM' . "\r\n" .
            'TRIGGER:-PT15M' . "\r\n" .
            'ACTION:DISPLAY' . "\r\n" .
            'DESCRIPTION:Reminder' . "\r\n" .
            'END:VALARM' . "\r\n" .
            'END:VEVENT'. "\r\n" .
            'END:VCALENDAR'. "\r\n";
            $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
            $message .= "Content-Transfer-Encoding: 8bit\n\n";
            $message .= $ical;
        
            $mailsent = mail($email, $subject, $message, $headers);


        }
      }


  

    
    //Envia notificação portal para o solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '4', 'obs' => 'Agendamento de sala de reunião.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação portal para o Gumercindo 
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '1', 'tipo' => '4', 'obs' => 'Agendamento de sala de reunião.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação portal para o suporte PLC
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '25', 'tipo' => '4', 'obs' => 'Agendamento de sala de reunião.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);


    return redirect()->route('Painel.TI.agendamentomesa.index'); 

  }


  public function agendamentomesa_agendamesa(Request $request) {


    $mesa_descricao = $request->get('mesa_descricao');
    $sala_descricao = $request->get('sala');
    $andar = $request->get('andar');
    $unidade = $request->get('unidade');
    $unidade_descricao = $request->get('unidade_descricao');
    $horarioid_inicio = $request->get('horarioid_inicio');
    $horarioid_fim = $request->get('horarioid_fim');
    $startTime = $request->get('startTime');
    $endTime = $request->get('endTime');
    $datainicio = $request->get('datainicio');
    $datainicio = $request->get('datainicio');

    $mesa = DB::table('TI_AgendamentoMesa_mesas')->select('id')->where('descricao','=',$mesa_descricao)->value('id'); 

    $carbon= Carbon::now();

    date_default_timezone_set('America/Sao_Paulo');



    $unidade_endereco = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Endereco')->where('Codigo','=',$unidade)->value('Endereco'); 
    $unidade_bairro = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Bairro')->where('Codigo','=',$unidade)->value('Bairro'); 
    $unidade_cidade = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Cidade')->where('Codigo','=',$unidade)->value('Cidade'); 
    $unidade_uf = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('UF')->where('Codigo','=',$unidade)->value('UF'); 
    $unidade_cep =  DB::table('PLCFULL.dbo.Jurid_Unidade')->select('CEP')->where('Codigo','=',$unidade)->value('CEP'); 

    $localizacao = $unidade_endereco . ', ' . $unidade_bairro . ', ' . $unidade_cidade . ', ' . $unidade_uf . ', ' . $unidade_cep;

    //Envia outlook
    $from_name = "PORTAL PL&C ADVOGADOS";        
    $from_address = "automacao@plcadvogados.com.br";     

    // //Se for unidade BHZ (1.3) 
     if($unidade == "1.3") {

        $to_address = Auth::user()->email.";suporte@plcadvogados.com.br;recepcao@plcadvogados.com.br;gumercindo.ribeiro@plcadvogados.com.br";   
      }
     //Se for outra unidade
     else {

        $to_address = Auth::user()->email.";suporte.sp@plcadvogados.com.br;recepcaosp@plcadvogados.com.br;gumercindo.ribeiro@plcadvogados.com.br";  

    }  

    // $to_address = 'ronaldo.amaral@plcadvogados.com.br';

    $subject = "[Reserva de estação de trabalho][".mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8")."][".$unidade_descricao."][".$mesa_descricao."]";              

    $domain = 'exchangecore.com';

    //Create Email Headers
    $mime_boundary = "----Meeting Booking----".MD5(TIME());

    $headers = "From: ".$from_name." <".$from_address.">\n";
    $headers .= "Reply-To: ".$from_name." <".$from_address.">\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
    $headers .= "Content-class: urn:content-classes:calendarmessage\n";

    //Create Email Body (HTML)
    $message = "--$mime_boundary\r\n";
    $message .= "Content-Type: text/html; charset=UTF-8\n";
    $message .= "Content-Transfer-Encoding: 8bit\n\n";
    $message .= "<html>\n";
    $message .= "<head>\n";
    $message .= "<style>
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
      padding: 5px;
      text-align: left;
    }
    </style>\n";
    $message .= "</head>\n";
    $message .= "<body>\n";
    $message .= '<p>Prezado(a), '. mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8").',</p>';
    $message .= '<br></br>';
    $message .= '<table style="width:100%">';
    $message .= '<tr>';
    $message .= '<td>Agendador: '. mb_convert_case(Auth::user()->name, MB_CASE_TITLE, "UTF-8").'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Estação de trabalho: '.$mesa_descricao.'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Sala: '.$sala_descricao.'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Andar: '.$andar.'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Unidade: '.$unidade_descricao.'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Endereço: '.$localizacao.'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Horário ínicio: '.date('d/m/Y H:i', strtotime($startTime)).'</td>';
    $message .= '</tr>';
    $message .= '<tr>';
    $message .= '<td>Horário fim: '.date('d/m/Y H:i', strtotime($endTime)).'</td>';
    $message .= '</tr>';
    $message .= '</table>';
    $message .= '<br></br><br></br>';
    $message .= '<img src="http://portal.plcadvogados.com.br/portal/public/assets/imgs/logo.png" alt="Logo PLC" height="156" width="276">';
    $message .= "</body>\n";
    $message .= "</html>\n";
    $message .= "--$mime_boundary\r\n";

    $ical = 'BEGIN:VCALENDAR' . "\r\n" .
    'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
    'VERSION:2.0' . "\r\n" .
    'METHOD:REQUEST' . "\r\n" .
    'BEGIN:VTIMEZONE' . "\r\n" .
    'TZID:America/Sao_Paulo' . "\r\n" .
    'BEGIN:STANDARD' . "\r\n" .
    'DTSTART:20091101T020000' . "\r\n" .
    'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=1SU;BYMONTH=11' . "\r\n" .
    'TZOFFSETFROM:-0400' . "\r\n" .
    'TZOFFSETTO:-0500' . "\r\n" .
    'TZNAME:EST' . "\r\n" .
    'END:STANDARD' . "\r\n" .
    'BEGIN:DAYLIGHT' . "\r\n" .
    'DTSTART:20090301T020000' . "\r\n" .
    'RRULE:FREQ=YEARLY;INTERVAL=1;BYDAY=2SU;BYMONTH=3' . "\r\n" .
    'TZOFFSETFROM:-0300' . "\r\n" .
    'TZOFFSETTO:-0300' . "\r\n" .
    'TZNAME:EDST' . "\r\n" .
    'END:DAYLIGHT' . "\r\n" .
    'END:VTIMEZONE' . "\r\n" .  
    'BEGIN:VEVENT' . "\r\n" .
    'ORGANIZER;CN="'.$from_name.'":MAILTO:'.$from_address. "\r\n" .
    'ATTENDEE;CN="'.Auth::user()->name.'";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:'.$to_address. "\r\n" .
    'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
    'UID:recepcao@plcadvogados.com.br\r\n"' .
    'DTSTAMP:'.date("Ymd\TGis"). "\r\n" .
    'DTSTART;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($startTime))."T".date("His", strtotime($startTime)). "\r\n" .
    'DTEND;TZID="America/Sao_Paulo":'.date("Ymd", strtotime($endTime))."T".date("His", strtotime($endTime)). "\r\n" .
    'TRANSP:OPAQUE'. "\r\n" .
    'SEQUENCE:1'. "\r\n" .
    'SUMMARY:' . $subject . "\r\n" .
    'LOCATION:' . $localizacao . "\r\n" .
    'CLASS:PUBLIC'. "\r\n" .
    'PRIORITY:5'. "\r\n" .
    'BEGIN:VALARM' . "\r\n" .
    'TRIGGER:-PT15M' . "\r\n" .
    'ACTION:DISPLAY' . "\r\n" .
    'DESCRIPTION:Reminder' . "\r\n" .
    'END:VALARM' . "\r\n" .
    'END:VEVENT'. "\r\n" .
    'END:VCALENDAR'. "\r\n";
    $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST'."\n";
    $message .= "Content-Transfer-Encoding: 8bit\n\n";
    $message .= $ical;

    $mailsent = mail($to_address, $subject, $message, $headers);
    
    //Grava na Matrix reservando esta mesa
    $values = array(
    'mesa_id' => $mesa, 
    'user_id' => Auth::user()->id, 
    'dia' => $datainicio,
    'datainicio' => $startTime,
    'datafim' => $endTime,
    'status_id' => '1',
    'horarioinicio_id' => $horarioid_inicio,
    'horariofim_id' => $horarioid_fim,
    'quantidadeparticipantes' => '1',
    'assunto' => $subject,
    'observacao' => '',
    'cliente' => '');
    DB::table('dbo.TI_AgendamentoMesa_matrix')->insert($values);

    $id_matrix = DB::table('dbo.TI_AgendamentoMesa_matrix')->select('id')->where('mesa_id','=',$mesa)->orderby('id','desc')->value('id');  

    //Envia notificação portal para o solicitante
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '4', 'obs' => 'Agendamento de estação de trabalho.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação portal para o Gumercindo 
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '1', 'tipo' => '4', 'obs' => 'Agendamento de estação de trabalho.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação portal para o suporte PLC
    $values3= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => '25', 'tipo' => '4', 'obs' => 'Agendamento de estação de trabalho.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);


    return redirect()->route('Painel.TI.agendamentomesa.index'); 
    

  }

  



  public function agendamentomesa_restrito_index() {

    $carbon= Carbon::now();

    $QuantidadeAgendamentosHojeEstacoes = DB::table('dbo.TI_AgendamentoMesa_matrix')->join('dbo.TI_AgendamentoMesa_mesas', 'dbo.TI_AgendamentoMesa_matrix.mesa_id','=','dbo.TI_AgendamentoMesa_mesas.id')->join('dbo.TI_AgendamentoMesa_salas','dbo.TI_AgendamentoMesa_mesas.sala_id','=','dbo.TI_AgendamentoMesa_salas.id')->where('dbo.TI_AgendamentoMesa_salas.tipo_id','=','1')->whereDate('datainicio', '=', $carbon)->count();
    $QuantidadeAgendamentosHojeReuniao = DB::table('dbo.TI_AgendamentoMesa_matrix')->join('dbo.TI_AgendamentoMesa_mesas', 'dbo.TI_AgendamentoMesa_matrix.mesa_id','=','dbo.TI_AgendamentoMesa_mesas.id')->join('dbo.TI_AgendamentoMesa_salas','dbo.TI_AgendamentoMesa_mesas.sala_id','=','dbo.TI_AgendamentoMesa_salas.id')->where('dbo.TI_AgendamentoMesa_salas.tipo_id','=','2')->whereDate('datainicio', '=', $carbon)->count();
    $QuantidadeAgendamentosTotal = DB::table('dbo.TI_AgendamentoMesa_matrix')->count();
    $QuantidadeAndares = DB::table('dbo.TI_AgendamentoMesa_andares')->where('status', '=', 'A')->count();
    $QuantidadeSalas = DB::table('dbo.TI_AgendamentoMesa_salas')->where('status', '=', 'A')->count();
    $QuantidadeEstacoes = DB::table('dbo.TI_AgendamentoMesa_mesas')->count();

    $QuantidadeAgendadas = DB::table('dbo.TI_AgendamentoMesa_matrix')->where('status_id','1')->count();
    $QuantidadeAguardandoCheckIn = DB::table('dbo.TI_AgendamentoMesa_matrix')->where('status_id','2')->count();
    $QuantidadeCheckInFeito = DB::table('dbo.TI_AgendamentoMesa_matrix')->where('status_id','3')->count();
    $QuantidadeFinalizadas = DB::table('dbo.TI_AgendamentoMesa_matrix')->where('status_id','4')->count();

    $reunioes = DB::table('dbo.TI_AgendamentoMesa_salas')
    ->select('dbo.TI_AgendamentoMesa_salas.id as id',
             'dbo.TI_AgendamentoMesa_salas.descricao as Descricao')  
    ->where('dbo.TI_AgendamentoMesa_salas.tipo_id', '=', '2')
    ->get();
    

    $datas = DB::table('dbo.TI_AgendamentoMesa_matrix')
    ->select('dbo.TI_AgendamentoMesa_mesas.id as id',
             'dbo.TI_AgendamentoMesa_mesas.descricao as Descricao',
             'dbo.TI_AgendamentoMesa_salas.descricao as Sala', 
             'dbo.TI_AgendamentoMesa_salas.andar as Andar',
             'dbo.TI_AgendamentoMesa_salas.unidade as Unidade',
             'dbo.TI_AgendamentoMesa_matrix.datainicio as DataInicio',
             'dbo.TI_AgendamentoMesa_matrix.datafim as DataFim',
             'dbo.TI_AgendamentoMesa_status.descricao as Status')  
    ->join('dbo.TI_AgendamentoMesa_mesas', 'dbo.TI_AgendamentoMesa_matrix.id', '=', 'dbo.TI_AgendamentoMesa_mesas.id')         
    ->join('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', '=', 'dbo.TI_AgendamentoMesa_salas.id')
    ->join('dbo.TI_AgendamentoMesa_status', 'dbo.TI_AgendamentoMesa_matrix.status_id', 'dbo.TI_AgendamentoMesa_status.id')
    ->get();

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();



    return view('Painel.TI.agendamentomesa.restrito.index', compact('QuantidadeAgendadas','QuantidadeAguardandoCheckIn','QuantidadeCheckInFeito','QuantidadeFinalizadas','reunioes','QuantidadeAgendamentosHojeEstacoes','QuantidadeAgendamentosHojeReuniao','QuantidadeAgendamentosTotal','QuantidadeAndares','QuantidadeSalas','QuantidadeEstacoes','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    
  }

  public function agendamentomesa_restrito_salas_index() {

    $datas = DB::table('dbo.TI_AgendamentoMesa_salas')
    ->select('dbo.TI_AgendamentoMesa_salas.id as id',
             'dbo.TI_AgendamentoMesa_salas.descricao as Descricao',
             'dbo.TI_AgendamentoMesa_salas.tipo_id as Tipo',
             'dbo.TI_AgendamentoMesa_tiposala.descricao as TipoDescricao',
             'dbo.TI_AgendamentoMesa_salas.andar as Andar',
             'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
             'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
             'dbo.TI_AgendamentoMesa_salas.status as Status')
    ->join('dbo.TI_AgendamentoMesa_tiposala', 'dbo.TI_AgendamentoMesa_salas.tipo_id', 'dbo.TI_AgendamentoMesa_tiposala.id')         
    ->join('PLCFULL.dbo.Jurid_Unidade', 'dbo.TI_AgendamentoMesa_salas.unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->get();


    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();


    return view('Painel.TI.agendamentomesa.restrito.salas.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

  }

  public function agendamentomesa_restrito_salas_novasala() {

    $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
    ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao') 
    ->whereNotIn('PLCFULL.dbo.Jurid_Unidade.Codigo', ['1.','1.1','1.15'])
    ->orderBy('PLCFULL.dbo.Jurid_Unidade.Descricao', 'asc')
    ->get();

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();


    return view('Painel.TI.agendamentomesa.restrito.salas.novasala', compact('unidades','totalNotificacaoAbertas', 'notificacoes'));


  }

  public function agendamentomesa_restrito_salas_salacriada(Request $request) {

    $descricao = $request->get('descricao');
    $tipo = $request->get('tipo');
    $andar = $request->get('andar');
    $unidade = $request->get('unidade');
    $status = $request->get('status');
    $link = $request->get('link');
    $quantidade = $request->get('quantidade');

    $values = array('descricao' => $descricao,
    'andar' => $andar,
    'tipo_id' => $tipo,
    'unidade' => $unidade,
    'status' => $status,
    'link_outlook' => $link,
    'quantidade_participantes' => $quantidade);
   DB::table('dbo.TI_AgendamentoMesa_salas')->insert($values);

   if($tipo == 2) {

   //Cria a mesa da sala de reunião
   $sala_id = DB::table('dbo.TI_AgendamentoMesa_salas')->orderby('id','desc')->count();

   $values = array('descricao' => 'Mesa da sala de reunião: ' .$sala_id,
   'responsavel_id' => Auth::user()->id,
   'sala_id' => $sala_id,
   'notificacao_novoagendamento' => 'SIM',
   'notificacao_diaagendamento' => 'SIM',
   'notificacao_confirmacheckin' => 'SIM',
   'notificacao_alertacheckinnaofeito' => 'SIM');
   DB::table('dbo.TI_AgendamentoMesa_mesas')->insert($values);

   }



   return redirect()->route('Painel.TI.agendamentomesa.restrito.salas.index'); 



  }

  public function agendamentomesa_restrito_mesas($sala_id) {

    //Pego todas as mesas desta sala 
    $datas = DB::table('dbo.TI_AgendamentoMesa_mesas')
    ->select('dbo.TI_AgendamentoMesa_mesas.id as id',
             'dbo.TI_AgendamentoMesa_mesas.descricao as Descricao',
             'dbo.TI_AgendamentoMesa_salas.descricao as Sala',
             'dbo.TI_AgendamentoMesa_salas.andar as Andar', 
             'dbo.TI_AgendamentoMesa_salas.unidade as Unidade',
             'dbo.TI_AgendamentoMesa_salas.tipo_id as tipo',
             'dbo.users.name as Responsavel')  
    ->join('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', 'dbo.TI_AgendamentoMesa_salas.id')         
    ->join('dbo.users', 'dbo.TI_AgendamentoMesa_mesas.responsavel_id', 'dbo.users.id')
    ->where('dbo.TI_AgendamentoMesa_mesas.sala_id', '=', $sala_id)
    ->get();

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();

    return view('Painel.TI.agendamentomesa.restrito.mesas.index', compact('sala_id','datas','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function agendamentomesa_restrito_mesas_agenda($mesa_id) {

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();

    return view('Painel.TI.agendamentomesa.restrito.mesas.agenda', compact('totalNotificacaoAbertas', 'notificacoes'));

  }

  public function agendamentomesa_restrito_mesas_agendareuniao($sala_id) {

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();

    return view('Painel.TI.agendamentomesa.restrito.salas.agendareuniao', compact('totalNotificacaoAbertas', 'notificacoes'));

  }

  public function agendamentomesa_restrito_novamesa($sala_id) {

    $responsaveis = DB::table('dbo.users')
    ->select('dbo.users.id as id', 'dbo.users.name as name') 
    ->get();

    $sala_descricao = DB::table('dbo.TI_AgendamentoMesa_salas')->where('id', $sala_id)->select('dbo.TI_AgendamentoMesa_salas.descricao')->value('dbo.TI_Agendamentomesa_salas.descricao');

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();


    return view('Painel.TI.agendamentomesa.restrito.mesas.novamesa', compact('sala_id','sala_descricao','responsaveis','totalNotificacaoAbertas', 'notificacoes'));


  }

  public function agendamentomesa_restrito_gravanovamesa(Request $request) {

    $descricao = $request->get('descricao');
    $responsavel = $request->get('responsavel');
    $sala = $request->get('sala');
    $novoagendamento = str_replace ('on', 'SIM', str_replace ('off', 'NAO', $request->get('novoagendamento')));
    $diaagendamento =   str_replace ('on', 'SIM', str_replace ('off', 'NAO', $request->get('diaagendamento')));
    $confirmacheckin =  str_replace ('on', 'SIM', str_replace ('off', 'NAO', $request->get('confirmacheckin')));
    $alertacheckinnaofeito =  str_replace ('on', 'SIM', str_replace ('off', 'NAO', $request->get('alertacheckinnaofeito')));

    //Grava na tabela Mesa
    $values = array('descricao' => $descricao,
                  'responsavel_id' => $responsavel,
                  'sala_id' => $sala,
                  'notificacao_novoagendamento' => $novoagendamento,
                  'notificacao_diaagendamento' => $diaagendamento,
                  'notificacao_confirmacheckin' => $confirmacheckin,
                  'notificacao_alertacheckinnaofeito' => $alertacheckinnaofeito);
    DB::table('dbo.TI_AgendamentoMesa_mesas')->insert($values);

    return redirect()->route('Painel.TI.agendamentomesa.restrito.index'); 

  }


  public function agendamentomesa_agendamentocancelado(Request $request) {

    $id = $request->get('id');
    $sala = $request->get('sala');
    $andar = $request->get('andar');
    $unidade = $request->get('unidade');
    $datainicio = $request->get('datainicio');
    $datafim = $request->get('datafim');
    $mesa = $request->get('mesa_descricao');

    $unidade_descricao = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Descricao')->where('Codigo','=',$unidade)->value('Descricao'); 
    $unidade_endereco = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Endereco')->where('Codigo','=',$unidade)->value('Endereco'); 
    $unidade_bairro = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Bairro')->where('Codigo','=',$unidade)->value('Bairro'); 
    $unidade_cidade = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Cidade')->where('Codigo','=',$unidade)->value('Cidade'); 
    $unidade_uf = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('UF')->where('Codigo','=',$unidade)->value('UF'); 
    $unidade_cep =  DB::table('PLCFULL.dbo.Jurid_Unidade')->select('CEP')->where('Codigo','=',$unidade)->value('CEP'); 

    
    $localizacao = $unidade_endereco . ', ' . $unidade_bairro . ', ' . $unidade_cidade . ', ' . $unidade_uf . ', ' . $unidade_cep;

    flash('Agendamento cancelado com sucesso !')->success();  

    DB::table('dbo.TI_AgendamentoMesa_matrix')->where('id', $id)->delete();   
    
    //Mandar e-mail informando que foi cancelado
    if($unidade == "1.3") {

      Mail::to(Auth::user()->email)
      ->cc('recepcao@plcadvogados.com.br','suporte@plcadvogados.com.br','gumercindo.ribeiro@plcadvogados.com.br')
      ->send(new AgendamentoCancelado($id, $mesa, $sala, $localizacao, $andar, $datainicio, $datafim));
    }
    //Se for outra unidade
    else {

      Mail::to(Auth::user()->email)
      ->cc('recepcao@plcadvogados.com.br','suporte@plcadvogados.com.br','gumercindo.ribeiro@plcadvogados.com.br')
      ->send(new AgendamentoCancelado($id, $mesa, $sala, $localizacao, $andar, $datainicio, $datafim));
    }

 
    return redirect()->route('Painel.TI.agendamentomesa.index'); 

  }

 

  public function agendamentomesa_agenda() {

    $carbon= Carbon::now();
    $diahoje = $carbon->format('d');

    // $diaamanha = $diahoje + 1;
    $datahoje = $carbon->format('Y-m-d H:i');
    $datahojeformato = $carbon->format('Y-m-'.$diahoje);
    $horaminuto = $carbon->format('H:i');

    $datas = DB::table('dbo.TI_AgendamentoMesa_matrix')
    ->select('dbo.TI_AgendamentoMesa_matrix.id as id',
             'dbo.TI_AgendamentoMesa_mesas.descricao as Descricao',
             'dbo.TI_AgendamentoMesa_salas.descricao as Sala',
             'dbo.TI_AgendamentoMesa_mesas.corredor as Corredor',
             'dbo.TI_AgendamentoMesa_salas.andar as Andar',
             'dbo.TI_AgendamentoMesa_matrix.quantidadeparticipantes as QuantidadeParticipantes',
             'dbo.TI_AgendamentoMesa_matrix.cliente as Cliente',
             'dbo.TI_AgendamentoMesa_matrix.observacao as Observacao',
             'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
             'dbo.TI_AgendamentoMesa_salas.tipo_id as Tipo',
             'dbo.TI_AgendamentoMesa_matrix.datainicio as DataInicio',
             'dbo.TI_AgendamentoMesa_matrix.datafim as DataFim')  
    ->leftjoin('dbo.TI_AgendamentoMesa_mesas', 'dbo.TI_AgendamentoMesa_matrix.mesa_id', '=', 'dbo.TI_AgendamentoMesa_mesas.id')
    ->leftjoin('dbo.TI_AgendamentoMesa_salas', 'dbo.TI_AgendamentoMesa_mesas.sala_id', '=', 'dbo.TI_AgendamentoMesa_salas.id')
    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.TI_AgendamentoMesa_salas.unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
    ->where('dbo.TI_AgendamentoMesa_matrix.user_id', '=', Auth::user()->id)
    ->get();

    $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
    ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao') 
    ->whereNotIn('PLCFULL.dbo.Jurid_Unidade.Codigo', ['1.','1.1','1.15'])
    ->orderBy('PLCFULL.dbo.Jurid_Unidade.Descricao', 'asc')
    ->get();

    $horarioshojeentrada = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('descricao', '>=', $horaminuto)
    ->where('status','=', 'A')
    ->get();

    $primeiroid = $horarioshojeentrada[0]->id;

    $horarioshojesaida = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('status','=', 'A')
    ->where('descricao', '>', $horaminuto)
    ->Where('id', '!=', $primeiroid)
    ->get();

    $horariosentrada = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('status','=', 'A')
    ->get();

    $horariossaida = DB::table('dbo.TI_AgendamentoMesa_horarios')
    ->where('status','=', 'A')
    ->Where('id', '!=', '1')
    ->get();

    $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
    ->where('status', 'A')
    ->where('destino_id','=', Auth::user()->id)
    ->count();
  
    $notificacoes = DB::table('dbo.Hist_Notificacao')
    ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
    ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
    ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
    ->get();

    return view('Painel.TI.agendamentomesa.agenda', compact('datas','unidades','horarioshojeentrada','horarioshojesaida','horariosentrada','horariossaida','datahojeformato','datahoje','totalNotificacaoAbertas', 'notificacoes'));

  }

  public function usuario_novasenha(Request $request) {

    $senhaatual = $request->get('senhaatual');
    $novasenha = $request->get('novasenha');
    $confirmacaosenha = $request->get('confirmasenha');
    $carbon= Carbon::now();
    $cpf = $request->get('cpf');
    $email = $request->get('email');

    // //Verifica primeiro se as senhas não são iguais
    // if($senhaatual != $novasenha) {

      //Verifico se as duas senhas batem
      if($novasenha == $confirmacaosenha) {

        //Gravo na TI_Usuarios_Senha
        $values = array('user_id' => Auth::user()->id, 
                        'senha' => $novasenha, 
                        'confirmacaosenha' => $confirmacaosenha, 
                        'cpf' => $cpf,
                        'email' => $email,
                        'created_at' => $carbon);
        DB::table('dbo.TI_Usuarios_Senha')->insert($values);

        //Update na users
        DB::table('dbo.users')
        ->where('id', '=',Auth::user()->id) 
        ->limit(1) 
        ->update(array('password' => bcrypt($novasenha)));

        //Envia e-mail
        Mail::to(Auth::user()->email)
        ->send(new NovaSenha($novasenha));
         return redirect()->route('Home.Principal.Show');   

      } 
      else {

        flash('Senhas diferentes, favor corrigir !')->success();  

        return redirect()->route('Home.Principal.Show'); 
      }

    } 
    //Se as senhas forem iguais ele retorna tela login
    // else {

    //   flash('Você colocou a nova senha igual a atual, favor alterar !')->success();  

    //   return redirect()->route('Home.Principal.Show'); 

    // }


    public function forceone_index(Request $request) {

      $client = new \GuzzleHttp\Client();

      $request = $client->get('https://force1.magma3.com.br/controller/BusinessInteligence/PegaJanelasAtivas?nome_reduzido_empresa=plcadvogados&chave_secreta=Q1tlxQG5xvEw6j5QR9fdnQiNe5JWPpvGgYV0ge78gT4b&data_inicio_foco=2021-01-01');


      $response = $request->getBody();
      return $response;
    }



}