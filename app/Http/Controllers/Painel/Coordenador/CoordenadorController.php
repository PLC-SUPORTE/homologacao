<?php
namespace App\Http\Controllers\Painel\Coordenador;

use App\Models\Coordenador;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DebiteReprovadoCoordenador;
use App\Mail\DebiteAprovado;
use App\Mail\DebiteCancelado;
use App\Mail\EmailFinanceiroAprovar;
use Excel;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SFTP;
use Neoxia\Filesystem\SftpServiceProvider;
use Spatie\GoogleCalendar\Event;

class CoordenadorController extends Controller
{

    protected $model;
    protected $totalPage = 100;   
    public $timestamps = false;
    

    public function __construct(Coordenador $model)
    {
        $this->model = $model;
        $this->middleware('can:coordenador');
    }

    public function getAuth() {
        return $this->client->getAuth();
    }

    public function index()
    {
       $title = 'Painel de Notas';
       $usuarioid = Auth::user()->id;
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')   
               ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
               ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
               ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
               ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
               ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
               ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
               ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
               ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                     DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                    'dbo.Jurid_Status_Ficha.*',
                    'PLCFULL.dbo.Jurid_Debite.Setor as Setor',
                    'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                    'PLCFULL.dbo.Jurid_Advogado.E_mail',
                    'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros AS NumeroProcesso',
                    'dbo.Jurid_Nota_Tiposervico.descricao as Tiposervico',
                    'dbo.users.name')
             ->where('dbo.Jurid_Situacao_Ficha.status_id','=','6')
             ->where('dbo.setor_custo_user.user_id', '=', $usuarioid) 
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
       
       
     return view('Painel.Coordenador.SolicitacaoDebite.index', compact('title', 'notas', 'totalNotificacaoAbertas', 'notificacoes'));
      //dd($notas);
    }
    
      
  public function show($Numero)
    {
        
            $statusAtual = DB::table('dbo.Jurid_Hist_Ficha')
                        ->select('id_status')  
                        ->where('id_hist','=',$Numero)
                        ->orderBy('id', 'desc')
                        ->value('id'); 
                              
      return view('Painel.Coordenador.SolicitacaoDebite.show', compact('statusAtual'));
    }
    
    public function aprovar($numero) {


        $carbon= Carbon::now();
        $dataHoraMinuto = $carbon->format('d-m-Y');
        
        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', 
                           'PLCFULL.dbo.Jurid_Debite.Status AS Status_Debite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                         'PLCFULL.dbo.Jurid_Debite.Data as DataFicha',
                          'dbo.Jurid_Situacao_Ficha.data_servico as DataServico',
                           DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'),
                         DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                          'Usuario', 'PLCFULL.dbo.Jurid_Debite.Obs', 
                           'Pasta', 
                           'Hist',
                          'PLCFULL.dbo.Jurid_Pastas.Moeda',
                           'PLCFULL.dbo.Jurid_Pastas.UF',
                          'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                          'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                          'PLCFULL.dbo.Jurid_Pastas.PRConta',
                          'PLCFULL.dbo.Jurid_Pastas.Contrato',
                       'PLCFULL.dbo.Jurid_Pastas.Setor',
                          'PLCFULL.dbo.Jurid_Pastas.RefCliente',
                           'PLCFULL.dbo.Jurid_Pastas.Descricao as PastaDescricao',
                          'PLCFULL.dbo.Jurid_Pastas.Status as PastaStatus',
                          'PLCFULL.dbo.Jurid_Pastas.Comarca',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                     'PLCFULL.dbo.Jurid_Pastas.Unidade',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                     'dbo.Jurid_Status_Ficha.descricao',
                          'PLCFULL.dbo.Jurid_Desp_Contrato.Aut_desp as ContratoStatus',
                          'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao')  
                  ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                  ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                    ->leftjoin('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Cliente','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                    ->leftjoin('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')   
                    ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                    ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                    ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                    ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                    ->leftjoin('PLCFULL.dbo.Jurid_Desp_Contrato', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Desp_Contrato.Contrato')
                    ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
                   ->get()
                   ->first();   
                   
     
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
    
    return view('Painel.Coordenador.SolicitacaoDebite.aprovar', compact('notas', 'dataHoraMinuto', 'totalNotificacaoAbertas', 'notificacoes'));
  
    }
    
       public function aprovado(Request $request) {
    
          
       $usuarioid = Auth::user()->id;
       $carbon= Carbon::now();
       $numerodebite = $request->get('numerodebite');
       $ressalva = $request->get('ressalva');
       $hist = $request->get('hist');

       $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
       ->select('id_user')
       ->where('id_status','=', '6')
       ->where('id_hist', '=', $numerodebite)
       ->value('id_user'); 

                        
        //Aprova a ficha colocando o RevisadoDB = 1 e automaticamente dando Insert no StatusFicha
       DB::table('PLCFULL.dbo.Jurid_Debite')
        ->where('Numero', $numerodebite)  
        ->limit(1) 
        ->update(array('Revisado_DB' => '1', 'Hist' => $hist));   
       
       //Verifica se foi aprovado com ressalva, ele vai gravar "SIM", se não "NÃO"
       DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numerodebite)  
        ->limit(1) 
        ->update(array('status_id' => '7', 'ressalva' => $ressalva));  
       
      $advogado_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select('Advogado')
              ->where('Numero','=', $numerodebite)
              ->value('Advogado'); 
      
      $destino_id = DB::table('dbo.users')
                ->select('id')  
                ->where('cpf','=',$advogado_cpf)
                ->value('id');  
       
    //Envia notificação para o Correspondente
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Revisão Técnica aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);
    
    //Envia notificação para o Advogado solicitante
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Revisão Técnica aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    //Envia notificação para a Revisão Técnica que aprovou
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => $usuarioid, 'tipo' => '1', 'obs' => 'Revisão Técnica aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);

    
    //Notificação para Danielle Financeiro Aprovar
    $values3= array('data' => $carbon, 'id_ref' => $numerodebite, 'user_id' => $usuarioid, 'destino_id' => '235', 'tipo' => '1', 'obs' => 'Revisão Técnica aprovada.', 'status' => 'A');
    DB::table('dbo.Hist_Notificacao')->insert($values3);
      
    //Insert na Tabela Historico
    $values = array('id_hist' => $numerodebite, 'id_status' => '7', 'id_user' => $usuarioid, 'data' => $carbon);
    DB::table('dbo.Jurid_Hist_Ficha')->insert($values);  
       
       
    $emailAdvogado =  DB::table('dbo.users')->select('email')->where('email','=', $advogado_cpf)->value('email'); 

    $emailCorrespondente = DB::table('dbo.users')->select('email')->where('id','=', $idCorrespondente)->value('email'); 
    
       
    if($emailAdvogado == NULL) {

        flash('Solicitação de debite aprovada com sucesso !')->success();

             
        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
        ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
        ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
        ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
        ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                 'PLCFULL.dbo.Jurid_Debite.Pasta',
                 DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                 DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                 DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                'dbo.Jurid_Status_Ficha.*',
                'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                'dbo.Jurid_Nota_TipoServico.descricao as TipoServico')
       ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numerodebite)
       ->limit(1)    
       ->get();

        Mail::to('daniele.oliveira@plcadvogados.com.br')
        ->cc('roberta.povoa@plcadvogados.com.br')
        ->send(new EmailFinanceiroAprovar($notas));


        return redirect()->route('Painel.Coordenador.index');

      } 
      
      else {
     
         $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                      'PLCFULL.dbo.Jurid_Debite.Pasta',
                      DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                      DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                      DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.*',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico')
            ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numerodebite)
            ->limit(1)    
            ->get();
          
    flash('Solicitação de debite aprovada com sucesso !')->success();

    Mail::to($emailAdvogado)
           ->cc($emailCorrespondente, $Auth::user()->email) 
           ->send(new DebiteAprovado($notas));

     //Manda um email para Danielle informando que possui uma nova solicitação para aprovaçaõ
            
     Mail::to('daniele.oliveira@plcadvogados.com.br')
     ->cc('roberta.povoa@plcadvogados.com.br')
     ->send(new EmailFinanceiroAprovar($notas));

      return redirect()->route('Painel.Coordenador.index');    
      }

   }
    
    public function acompanharSolicitacoes() {
        
        $usuarioid = Auth::user()->id;
        $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')   
             ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
             ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
             ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     'dbo.Jurid_Nota_Tiposervico.descricao as Tiposervico',
                     'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros AS NumeroProcesso',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                     DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Situacao_Ficha.status_id as StatusID',
                     'dbo.Jurid_Status_Ficha.descricao',
                     'PLCFULL.dbo.Jurid_Debite.Setor as Setor',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                     'PLCFULL.dbo.Jurid_Advogado.E_mail',
                     'dbo.users.name',
                     'PLCFULL.dbo.Jurid_Debite.DebPago')
             ->where('dbo.Jurid_Situacao_Ficha.status_id','!=','6')
             ->where('dbo.setor_custo_user.user_id', '=', $usuarioid)  
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
       
       return view('Painel.Coordenador.SolicitacaoDebite.acompanhar', compact('notas', 'totalNotificacaoAbertas', 'notificacoes'));
       //dd($notas);
    }
    
    
   public function reprovar($numero) {
           
     $carbon= Carbon::now();
     $dataHoraMinuto = $carbon->format('d-m-Y');
 
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 'Usuario', 'PLCFULL.dbo.Jurid_Debite.Obs', 'DebPago as Pago', 'Pasta', 'PLCFULL.dbo.Jurid_Pastas.PRConta', DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 'dbo.Jurid_Status_Ficha.descricao')  
                   ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
                   ->get()
                   ->first();   
     
    $motivos = DB::table('dbo.Jurid_Nota_Motivos')
             ->where('ativo','=','S')
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
                    
     return view('Painel.Coordenador.SolicitacaoDebite.reprovar', compact('notas', 'dataHoraMinuto','motivos', 'totalNotificacaoAbertas' ,'notificacoes'));    
    }
    
    
     public function pendenciar(Request $request) {
        
      $usuarioid = Auth::user()->id;
      $carbon= Carbon::now();
      
      $numero = $request->get('numerodebite');
      $motivo = $request->get('motivo');
      $observacao = $request->get('observacao');
      $hist = $request->get('hist');
      
      //Pega o ID do Motivo Selecionado para gravar na Tabela Situação Ficha
      $motivo_id = DB::table('dbo.Jurid_Nota_Motivos')
              ->select('id')
              ->where('descricao','=', $motivo)
              ->value('id'); 

      $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
              ->select('id_user')
              ->where('id_status','=', '6')
              ->where('id_hist', '=', $numero)
              ->value('id_user'); 
       
      $values = array('id_hist' => $numero, 'id_status' => '8', 'id_user' => $usuarioid, 'data' => $carbon);
      DB::table('dbo.Jurid_Hist_Ficha')->insert($values);
      
      $advogado_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')->select('Advogado')->where('Numero','=', $numero)->value('Advogado'); 
 
      $destino_id = DB::table('dbo.users')->select('id')->where('cpf','=',$advogado_cpf)->value('id');  
       
      //Manda notificação para o Advogado Solicitante
      $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação de pagamento reprovada.', 'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Manda notificação para o Correspondente
      $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Solicitação de pagamento reprovada.', 'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      //Manda notificação para a Revisão Técnica
      $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $usuarioid, 'tipo' => '1', 'obs' => 'Solicitação de pagamento reprovada.', 'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      
     //Com a notificação criada ele vai enviar o email
     //Verifica se o email esta NULL se não ira enviar
     $emailAdvogado =  DB::table('dbo.users')->select('email')->where('cpf','=', $advogado_cpf)->value('email'); 
    
     $emailCorrespondente = DB::table('dbo.users')->select('email')->where('id','=', $idCorrespondente)->value('email'); 

     //Update na Jurid_Debite atualizando o Controle
     DB::table('PLCFULL.dbo.Jurid_Debite')
     ->where('Numero', $numero)  
     ->limit(1) 
     ->update(array('Hist' => $hist));
       
      //Update Status 8(Pendenciada) na Tabela Situacao
      DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numero)  
        ->limit(1) 
        ->update(array('status_id' => '8','observacao' => $observacao, 'motivo_id' => $motivo_id));

     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Jurid_Situacao_Ficha.motivo_id', '=', 'dbo.Jurid_Nota_Motivos.id')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                      'PLCFULL.dbo.Jurid_Debite.Pasta',
                      DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                      DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                      DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.*',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'dbo.Jurid_Situacao_Ficha.observacao as ObservacaoMotivo',
                     'dbo.Jurid_Nota_Motivos.descricao as Motivo')  
            ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
            ->limit(1)    
            ->get();
      
      
      flash('Solicitação enviada para correção com sucesso !')->success();
      
      Mail::to($emailAdvogado)
           ->cc($emailCorrespondente, Auth::user()->email) 
           ->send(new DebiteReprovadoCoordenador($notas));
            return redirect()->route('Painel.Coordenador.index');    
        
      return redirect()->route('Painel.Coordenador.acompanharSolicitacoes'); 
    }
    
    
     public function cancelar($numero) {

     $carbon= Carbon::now();
     $dataHoraMinuto = $carbon->format('d-m-Y');
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                    DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                    'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha',
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     'Usuario', 
                     'PLCFULL.dbo.Jurid_Debite.Obs',
                     'DebPago as Pago',
                    'Pasta',
                    'Hist',
                    'PLCFULL.dbo.Jurid_Pastas.PRConta',
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'),
                    'dbo.Jurid_Status_Ficha.descricao')  
                   ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
                   ->get()
                   ->first();  
     
    $motivos = DB::table('dbo.Jurid_Nota_Motivos')
             ->where('ativo','=','S')
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
  
      return view('Painel.Coordenador.SolicitacaoDebite.cancelar', compact('notas', 'dataHoraMinuto', 'motivos', 'totalNotificacaoAbertas' ,'notificacoes'));   
    }
    
   public function darbaixa(Request $request) {
        
      $usuarioid = Auth::user()->id;
      $carbon= Carbon::now();
      
      $numero = $request->get('numerodebite');
      $motivo = $request->get('motivo');
      $observacao = $request->get('observacao');
      $hist = $request->get('hist');
      
      //Pega o ID do Motivo Selecionado para gravar na Tabela Situação Ficha
      $motivo_id = DB::table('dbo.Jurid_Nota_Motivos')
              ->select('id')
              ->where('descricao','=', $motivo)
              ->value('id'); 
       
      $values = array('id_hist' => $numero, 'id_status' => '13', 'id_user' => $usuarioid, 'data' => $carbon);
      DB::table('dbo.Jurid_Hist_Ficha')->insert($values);
      
     $advogado_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select('Advogado')
              ->where('Numero','=', $numero)
              ->value('Advogado'); 

     $destino_id = DB::table('dbo.users')
                ->select('id')  
                ->where('cpf','=',$advogado_cpf)
                ->value('id');  

     $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
                ->select('id_user')
                ->where('id_status','=', '6')
                ->where('id_hist', '=', $numero)
                ->value('id_user');           
       
      $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento cancelada' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);

      $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Solicitação pagamento cancelada' ,'status' => 'A');
      DB::table('dbo.Hist_Notificacao')->insert($values3);
              
      //Com a notificação criada ele vai enviar o email
      //Verifica se o email esta NULL se não ira enviar
      $emailAdvogado =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('E_mail')
              ->where('Codigo','=', $advogado_cpf)
              ->value('E_mail'); 
      
      $emailCorrespondente = DB::table('dbo.users')
              ->select('email')
              ->where('id','=', $idCorrespondente)
              ->value('email'); 
       
       //Update na Tabela Situacao
      DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numero)  
        ->limit(1) 
        ->update(array('status_id' => '13','observacao' => $observacao, 'motivo_id' => $motivo_id));
       
     //Update Status 3(Cancelado) na Tabela Debite
     DB::table('PLCFULL.dbo.Jurid_Debite')
     ->where('Numero', $numero)
     ->limit(1) 
     ->update(array('status' => '3', 'Hist' => $hist));
        
     flash('Solicitação de pagamento cancelada com sucesso !')->success();
        
     if($emailAdvogado == null) {
        Mail::to($emailCorrespondente)
        ->send(new DebiteCancelado($numero));
        return redirect()->route('Painel.Coordenador.index');   

     } else {
        Mail::to($emailCorrespondente)
        ->cc($emailAdvogado)
        ->send(new DebiteCancelado($numero));
        return redirect()->route('Painel.Coordenador.index');
     }
        
    }

    
     public function imprimir($Numero){  
       
         $carbon= Carbon::now();
          $numeroprocesso= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'DebPago as Pago',
                           'PLCFULL.dbo.Jurid_Debite.Moeda as Moeda',
                           'Pasta', 
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.UF as UF',
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal as Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte as OutraParte',
                           'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumPrc1_Sonumeros',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'PLCFULL.dbo.Jurid_Advogado.Razao as Advogado',
                           'PLCFULL.dbo.Jurid_Advogado.E_mail as AdvogadoEmail',
                           'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                           'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                           'PLCFULL.dbo.Jurid_Unidade.Endereco as UnidadeEndereco',
                           'PLCFULL.dbo.Jurid_Unidade.Bairro as UnidadeBairro',
                           'PLCFULL.dbo.Jurid_Unidade.Cidade as UnidadeCidade',
                           'PLCFULL.dbo.Jurid_Unidade.CEP as UnidadeCEP',
                           'PLCFULL.dbo.Jurid_Unidade.UF as UnidadeUF',
                           'PLCFULL.dbo.Jurid_Unidade.fone_1 as UnidadeTelefone',
                           'PLCFULL.dbo.Jurid_Unidade.complemento_endereco as UnidadeComplemento',
                           'PLCFULL.dbo.Jurid_CliFor.banco as Banco',
                           'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia',
                           'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
                           'dbo.Jurid_Situacao_Ficha.data_servico as DataServico',
                           'dbo.Jurid_Hist_Ficha.data as DataSolicitacao',
                           'dbo.users.name as Nome',
                           'dbo.users.email as email',
                           'PLCFULL.dbo.Jurid_Ged_Main.Obs as Obs',
                           'PLCFULL.dbo.Jurid_Ged_Main.Link as anexo',
                          'dbo.Jurid_Nota_TipoServico.descricao as TipoDebite')  
                   ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'PLCFULL.dbo.Jurid_Debite.Numero', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
                   ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Debite.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
                   ->leftjoin('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                   ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                   ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
                   ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Debite.AdvServ', 'dbo.users.cpf')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->get()
                   ->first();

                   $anexo2 = $numeroprocesso->anexo;
                   $anexoformatado =  str_replace ('\\\192.168.1.65\advwin\portal\portal\solicitacaopagamento/', '', $anexo2);
   //Busca o caminho do arquivo
   $datacomprovante = DB::table('PLCFULL.dbo.Jurid_Ged_Main')
            ->select('Link as anexo')
            ->where('Id_OR','=', $Numero)
            ->where('Texto', '==', 'Comprovante pagamento')
            ->orderby('ID_doc', 'desc')
            ->get()
            ->first();
     
       return view('Painel.Coordenador.SolicitacaoDebite.print', compact('numeroprocesso', 'anexoformatado','datacomprovante',  'carbon'));
    }
    
    public function anexo($anexo) {
                
        return Storage::disk('solicitacaopagamento-sftp')->download($anexo);   
    }
  
  public function gerarExcelAbertas() {
      
  $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', '=', 'PLCFULL.dbo.Jurid_empreendimento.codigo_empreendimento')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'PLCFULL.dbo.Jurid_Pastas.UF as Estado',
                     'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     'PLCFULL.dbo.Jurid_empreendimento.nome_empreendimento as Empreendimento',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as Data'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as Status',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                     'dbo.users.name as Correspondente')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '0')
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '6')
             ->get()
             ->toArray();
     $customer_array[] = array('Numero',  'Solicitante', 'Correspondente' ,'Cliente', 'Unidade', 'Setor', 'Estado', 'Comarca', 'TipoServico',  'Empreendimento', 'Grupo', 'Valor', 'Data', 'Status');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Número'  => $customer->Numero,
       'Solicitante'  => $customer->Solicitante,
       'Correspondente'  => $customer->Correspondente,
       'Cliente' => $customer->Cliente,
       'Unidade' => $customer->Unidade,
       'Setor' => $customer->Setor,
       'Estado' => $customer->Estado,
       'Comarca' => $customer->Comarca,
       'TipoServico' => $customer->TipoServico,
       'Empreendimento' => $customer->Empreendimento,   
       'Grupo'=> $customer->Grupo,
       'Valor'=> $customer->Valor,
       'Data'=> date('d/m/Y H:m:s', strtotime($customer->Data)),
       'Status'=> $customer->Status,
      );
     }
     Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Solicitações Pagamento Abertas');
      $excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
    
  public function gerarExcelAcompanhamento() {
      
  $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo' )
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')  
             ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_grupofinanceiro', 'PLCFULL.dbo.Jurid_CliFor.codigo_grupofinanceiro', '=', 'PLCFULL.dbo.Jurid_grupofinanceiro.codigo_grupofinanceiro')
             ->leftjoin('PLCFULL.dbo.Jurid_empreendimento', 'PLCFULL.dbo.Jurid_CliFor.codigo_empreendimento', '=', 'PLCFULL.dbo.Jurid_empreendimento.codigo_empreendimento')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
                     'PLCFULL.dbo.Jurid_CliFor.Nome as Cliente',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'PLCFULL.dbo.Jurid_Pastas.UF as Estado',
                     'PLCFULL.dbo.Jurid_Pastas.Comarca as Comarca',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'PLCFULL.dbo.Jurid_grupofinanceiro.nome_grupofinanceiro as Grupo',
                     'PLCFULL.dbo.Jurid_empreendimento.nome_empreendimento as Empreendimento',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as Data'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as Status',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                     'dbo.users.name as Correspondente')
             ->where('PLCFULL.dbo.Jurid_Debite.Revisado_DB', '=', '1')
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '!=', '6')
             ->get()
             ->toArray();
     $customer_array[] = array('Numero',  'Solicitante', 'Correspondente' ,'Cliente', 'Unidade', 'Setor', 'Estado', 'Comarca', 'TipoServico',  'Empreendimento', 'Grupo', 'Valor', 'Data', 'Status');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Número'  => $customer->Numero,
       'Solicitante'  => $customer->Solicitante,
       'Correspondente'  => $customer->Correspondente,
       'Cliente' => $customer->Cliente,
       'Unidade' => $customer->Unidade,
       'Setor' => $customer->Setor,
       'Estado' => $customer->Estado,
       'Comarca' => $customer->Comarca,
       'TipoServico' => $customer->TipoServico,
       'Empreendimento' => $customer->Empreendimento,   
       'Grupo'=> $customer->Grupo,
       'Valor'=> $customer->Valor,
       'Data'=> date('d/m/Y H:m:s', strtotime($customer->Data)),
       'Status'=> $customer->Status,
      );
     }
     Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Solicitações Pagamento Abertas');
      $excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
 
    public function solicitacaoAbertura(){

        //Busca os cargos com status A
        $cargos = DB::table('dbo.Contratacao_Cargo')
        ->where('status','=','A')
        ->orderBy('descricao','ASC')   
        ->get();

        //Busca os tipos de cargo com status A
        $tiposcargo = DB::table('dbo.Contratacao_TipoCargo')
        ->where('status','=','A')
        ->orderBy('descricao','ASC')   
        ->get();


        $setor = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->leftjoin('dbo.setor_custo_user','PLCFULL.dbo.Jurid_Setor.Id','=','dbo.setor_custo_user.setor_custo_id')
        ->select('Codigo')
        ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
        ->where('dbo.setor_custo_user.user_id','=', Auth::user()->id)
        ->value('Codigo'); 

        //Busca os funcionarios com Status = Ativo e Setor do usuario
        $funcionarios = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'dbo.users.cpf')
        ->select('PLCFULL.dbo.Jurid_Advogado.Nome as Nome', 'dbo.users.id')
        ->where('Status','=','Ativo')
        ->where('Setor', '=', $setor)
        ->orderBy('Nome','ASC')   
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
        ->orderby('dbo.Hist_Notificacao.data', 'desc')
        ->get();

        $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
        ->select('PLCFULL.dbo.Jurid_Unidade.Codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao', 'PLCFULL.dbo.Jurid_Unidade.Cidade')
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('PLCFULL.dbo.Jurid_Setor.Codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao', 'PLCFULL.dbo.Jurid_Setor.Id')  
        ->leftjoin('dbo.setor_custo_user','PLCFULL.dbo.Jurid_Setor.Id','=','dbo.setor_custo_user.setor_custo_id')
        ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
        ->where('dbo.setor_custo_user.user_id','=', Auth::user()->id)
        ->orderby('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
        ->get();

        // dd($setores);

        return view('Painel.Coordenador.Contratacao.index', compact('cargos', 'funcionarios','tiposcargo','totalNotificacaoAbertas', 'notificacoes', 'unidades', 'setores'));
    }

    public function solicitado(Request $request) {
    
     //dd($request->all());   

     $valorsemformato = $request->get('custo');
     $carbon= Carbon::now();
     $dataehora = $carbon->format('dmY_HHis');

     //Grava Curriculo
     $curriculo = $request->file('select_file');
     $new_name = $dataehora . '.'  . $curriculo->getClientOriginalExtension();
     $curriculo->storeAs('curriculos', $curriculo);


     //Data Cadastro

     $tipo = $request->get('tipocontratacao');
     $usuarioid = Auth::user()->id;
     $advogado_id = $request->get('funcionario');
     $cargo_id = $request->get('cargo');
     $tipocargo_id = $request->get('tipo');
     $custo =  str_replace (',', '.', str_replace ('.', '.', $valorsemformato));
     $setor = $request->get('setor');
     $unidade = $request->get('unidade');
     $headhunder = $request->get('headhunder');
     $link_curriculo = $new_name;
     $obs = $request->get('observacao');
     //Status Inicial
     $status_id = "1";

     //Insert Tabela
     $values = array(
        'data' => $carbon,
        'tipo' => $tipo,
        'id_user' => $usuarioid,
        'id_advogado' => $advogado_id,
        'cargo_id' => $cargo_id, 
        'tipocargo_id' => $tipocargo_id,
        'custo' => $custo,
        'setor_id' => $setor,
        'unidade_id' => $unidade,
        'head_hunter' => $headhunder,
        'link_curriculo' => $link_curriculo,
        'obs' => $obs,
        'status_id' => $status_id);
      DB::table('dbo.Contratacao_Main')->insert($values);        


     //Verifica se possui HeadHunter
     if($headhunder == "S") {

   //  $event = new Event;
   //  $event->name = 'Agendamento reunião com COO';
   //  $event->startDateTime = Carbon::now();
   //  $event->endDateTime = Carbon::now()->addHour();
   //  $event->addAttendee(['email' => 'ronaldocotemig@gmail.com']);

   //  $event->save();

     flash('Solicitação de contratação/substituição cadastrada com sucesso e gravado na Google Agenda !')->success();

     return redirect()->route('Home.Principal.Show');


     } else {

      return redirect()->route('Home.Principal.Show');
      flash('Solicitação de contratação/substituição cadastrada com sucesso !')->success();

     }

    }


   
}
