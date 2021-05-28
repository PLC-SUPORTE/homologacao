<?php

namespace App\Http\Controllers\Painel\Correspondente;

use App\Models\Correspondente;
use App\Models\TipoServico;
use App\Models\Moeda;
use App\Models\Advogado;
use App\Models\Pasta;
use App\Models\PesquisaCorrespondente;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Sftp\SftpAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Response;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Mail;
use App\Mail\DebiteEditado;
use App\Mail\SendMailUser;
use App\Mail\SolicitacaoCoordenador;
use App\Mail\DebiteCancelado;
use App\Mail\UsuarioEditado;
use App\Mail\Correspondente\AtualizarSetor;
use App\Mail\Correspondente\RevisarNovoPrestador;
use Excel;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use File;
use Illuminate\Database\Eloquent\Builder;
use App\Mail\Correspondente\NovaSolicitacaoServico;
use App\Mail\Correspondente\NovaSolicitacaoAcesso;

class CorrespondenteController extends Controller
{
    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    
    
//     public function __construct(Correspondente $model)
//     {
//         $this->model = $model;
//         $this->middleware('can:correspondentes');

//     }


    public function novoprestador_preencherinformacoes($tokencriptografado) {


        //Se o token não existir, da redirect informando
        $token = Crypt::decryptString($tokencriptografado);

        $existe = DB::table('dbo.Correspondente_Temp')->select('created_at')->where('id','=', $token)->value('created_at');
        if($existe == null) {
                flash('Token expirado!')->error();    
                // return redirect()->route('logout'); 
        } 
        //Verifico se o token já foi expirado (1 dia)
        else {
        $diferenciadias = Carbon::parse(Carbon::now())->diffInDays($existe);

           if($diferenciadias >= 1) {
                flash('Token expirado!')->error();    
                // return redirect()->route('logout'); 
           }
        }

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.Correspondente_Temp')
        ->select('descricao', 'email', 'cidade', 'codigo')  
        ->where('id','=',$token)
        ->first();

        $comarcas = DB::table('dbo.Correspondente_Comarcas')
        ->leftjoin('PLCFULL.dbo.Jurid_Capitais', 'dbo.Correspondente_Comarcas.comarca_id', 'PLCFULL.dbo.Jurid_Capitais.Id')
        ->select('dbo.Correspondente_Comarcas.id',
                 'PLCFULL.dbo.Jurid_Capitais.Cidade as comarca_descricao', 
                 'PLCFULL.dbo.Jurid_Capitais.UF as comarca_uf',
                 'valor_audiencia', 'valor_diligencia')  
        ->where('correspondente_email','=',$datas->email)
        ->get();

        $bancos = DB::table('dbo.Bancos')
        ->select('Codigo', 'Descricao')  
        ->where('Status','=','A')
        ->orderBy('Descricao', 'asc')
        ->get();

        $tiposconta= DB::table('PLCFULL.dbo.Jurid_TipoContaBanco')
        ->select('codigo', 'descricao_tipo')  
        ->orderBy('descricao_tipo', 'asc')
        ->get();

       return view('Painel.Correspondente.NovoPrestador.index', compact('datahoje','comarcas','bancos','tiposconta','datas','token'));

    }

    public function novoprestador_gravainformacoes(Request $request) {

        //Pega informações
       $id = $request->get('id');
       $descricao = $request->get('descricao');
       $datanascimento = $request->get('datanascimento');
       $cep = $request->get('cep');
       $endereco = $request->get('endereco');
       $bairro = $request->get('bairro');
       $cidade = $request->get('cidade');
       $uf = $request->get('uf');
       $telefone = $request->get('telefone');
       $celular = $request->get('celular');
       $email = $request->get('email');
       $cpf_cnpj = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $request->get('cpf_cnpj'))));
       $identidade = $request->get('identidade');
       $banco = $request->get('banco');
       $tipoconta = $request->get('tipoconta');
       $agencia = $request->get('agencia');
       $conta = $request->get('conta');
       $carbon= Carbon::now();

  
       // dd($details->geoplugin_request);

       //Update na tabela temp
       DB::table('dbo.Correspondente_Temp')
       ->where('id', $id)  
       ->limit(1) 
       ->update(array('descricao' => $descricao, 
                      'data_nascimento' => $datanascimento, 
                      'endereco' => $endereco,
                      'bairro' => $bairro,
                      'cidade' => $cidade,
                      'cep' => $cep,
                      'uf' => $uf,
                      'telefone' => $telefone,
                      'celular' => $celular,
                      'codigo' => $cpf_cnpj,
                      'identidade' => $identidade,
                      'codigo_banco' => $banco,
                      'agencia' => $agencia,
                      'conta' => $conta,
                      'tipoconta' => $tipoconta));


        //Gravar na tabela Correspondente_Comarcas
        $comarca_uf = $request->get('comarca_uf');
        $comarca_cidade = $request->get('comarca_cidade');
        $comarca_id = $request->get('comarca_id');
        $comarca_valoraudiencia = str_replace (',', '.', str_replace ('.', '', $request->get('comarca_valoraudiencia')));
        $comarca_valordiligencia = str_replace (',', '.', str_replace ('.', '', $request->get('comarca_valordiligencia')));
            
        $data = array();
        foreach($comarca_cidade as $index => $comarca_cidade) {
    

            if($comarca_id == null) {
                $values = array(
                        'correspondente_id' => $id, 
                        'correspondente_email' => $email,
                        'comarca_id' => $comarca_cidade, 
                        'valor_audiencia' => $comarca_valoraudiencia[$index],
                        'valor_diligencia' => $comarca_valordiligencia[$index],
                        'status' => 'A');
                        DB::table('dbo.Correspondente_Comarcas')->insert($values);  
            } else {
                DB::table('dbo.Correspondente_Comarcas')
                ->where('id', '=' ,$comarca_id) 
                ->limit(1) 
                ->update(array('valor_audiencia' => $comarca_valoraudiencia[$index], 
                               'valor_diligencia' => $comarca_valordiligencia[$index], 
                               'status' => 'A'));
            }

    

        }
        ////////////////////GRAVAR NA TABELA AUXILIAR AS COMARCAS////////////////////
        

        $email_advogado = DB::table('dbo.users')
                        ->select('dbo.users.email')
                        ->join('dbo.Correspondente_Temp', 'dbo.users.id', 'dbo.Correspondente_Temp.user_id')
                        ->where('dbo.Correspondente_Temp.id','=', $id)
                        ->value('dbo.users.email'); 



       //Envia email para Isabella Silveira e para o Adv que fez a solicitação
       Mail::to($email_advogado)
       ->cc($email) 
       ->send(new RevisarNovoPrestador($descricao));

       return redirect()->route('logout'); 

    }

    public function index() {
       $title = 'Painel de Notas';
       $usuario_nome = Auth::user()->name;
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')  
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.id as StatusID',
                     'dbo.Jurid_Status_Ficha.descricao as descricao',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                      'dbo.Jurid_Nota_TipoServico.descricao as TipoServicoDescricao',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante')  
             ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', Auth::user()->cpf)
             ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
             ->get();

                            
     $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
             
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
     $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
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
       

         return view('Painel.Correspondente.SolicitacaoDebite.index', compact('totalPendencias','title', 'notas', 'usuario_nome', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
    }


   
    public function create()
    {
       $title = 'Painel Cadastro de Notas';       
       
       $totalPendencias = DB::table('dbo.debite_temp')
                ->where('user_id', Auth::user()->id)
                ->count();
       
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
       
      $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
      ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
      ->where('usuario_id', Auth::user()->id)
      ->count();
        
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
     $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();     
       
      return view('Painel.Correspondente.SolicitacaoDebite.create-edit', compact('title', 'totalPendencias', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
      
    }


//public function autocomplete(Request $request) {
   //     if($request->ajax()) {
        
    //        $data = Pasta::where('Codigo_Comp', 'LIKE',  $request->numeroprocesso . '%')
    //           ->orWhere('NumPrc1_Sonumeros', 'LIKE',  $request->numeroprocesso . '%')
     //          ->get();
            
     //       $output = '';
      //      if (count($data)>0) {
                
       //         $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';
       //         $output .= '<li class="list-group-item">Selecione aqui</li>';
       //         foreach ($data as $row){
       //             $output .= '<li class="list-group-item">'.$row->Codigo_Comp.'</li>';
       //         }
       //         $output .= '</ul>';
       //     }
       //     else {
       //         $output .= '<li class="list-group-item">'.'Nenhum resultado encontrado.'.'</li>';
       //     }
       //     return $output;
      //  }
  //  }


   public function store(Request $request) {
    
        //Valida os dados
        //$notas->validate($request, $notas->model->rules());
           
       $usuarioid= Auth::user()->id;
       
       $status_id = $request->get('status');
       $datarealizacao = $request->get('data');
       
       $tiposervico = $request->get('tiposervico');
       $codigopasta = $request->get('codigopasta');
       $valorTotal = $request->get('ValorT');

       $formatted_date = date('Y-m-d', strtotime($datarealizacao));

       $valorT =  str_replace (',', '.', str_replace ('.', '', $valorTotal));

       $tiposervico_id = DB::table('dbo.Jurid_Nota_TipoServico')
                  ->select('id')  
                  ->where('descricao','=',$tiposervico)
                  ->value('id');  

       //Insert na Tabela Debite Temporaria
       $values = array('codigocomp' => $codigopasta, 'valortotal' => $valorT, 'data' => $datarealizacao, 'user_id' => $usuarioid, 'tiposervico_id' => $tiposervico_id, 'status' => $status_id);
       DB::table('dbo.debite_temp')->insert($values);
       
      return redirect()->route('Painel.Correspondente.step3');
  
   }

   
    
    public function show($Numero)
    {
        
          $numeroprocesso= 
                 DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           'ValorT as ValorTotal', 
                           'DebPago as Pago',
                           'dbo.Jurid_Status_Ficha.descricao', 
                           'dbo.users.name as Nome')  
                   ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('dbo.users', 'dbo.Jurid_Hist_Ficha.id_user', 'dbo.users.id')
                   ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$Numero)
                   ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '6')
                   ->get()
                   ->first();
          
          
            $statusAtual = 
             DB::table('dbo.Jurid_Hist_Ficha')
                        ->select('id_status')  
                        ->where('id_hist','=',$Numero)
                        ->orderBy('id', 'desc')
                        ->value('id'); 
                                   
      return view('Painel.Correspondente.SolicitacaoDebite.show', compact('numeroprocesso', 'statusAtual'));
    }

    
    public function step2(Request $request)
    {
        //Recupera os dados do formulário
         $dataehora = time(); 
         $carbon= Carbon::now();
         $datahoje = $carbon->format('Y-m-d');
         
        //Ele vai pesquisar pelo Nª Pasta ou Numero Processo
         $tiposolicitacao = $request->get('tiposervico');
         
         $tiposervico_descricao = DB::table('dbo.Jurid_Nota_TipoServico')
             ->where('tipo','=',$tiposolicitacao)
             ->orderBy('descricao','ASC')   
             ->get();
            
         $numeroprocesso = $request->get('numeroprocesso');
         
         
         $pastas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                 ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                         'PLCFULL.dbo.Jurid_Pastas.Advogado as Advogado',
                         'PLCFULL.dbo.Jurid_Pastas.Setor as Setor',
                         'PLCFULL.dbo.Jurid_Contratos.Despesas as Status')
                 ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
                 ->where('PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros','=',$numeroprocesso)
                 ->orWhere('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp', '=', $numeroprocesso)
                 ->limit(1)
                 ->get();

  
        //Se não encontrar nenhum registro
        if($pastas->isEmpty()) {
           
        flash('Não foi encontrado este número do processo ou código da pasta !')->error();   
        return redirect()
                       ->route("Painel.Correspondente.create")
                       ->withInput();
         
       } 
       else {

        $pasta = $pastas[0]->Codigo_Comp;
        $setor = $pastas[0]->Setor;
        $codigo_advogado = $pastas[0]->Advogado;
        $correspondente = Auth::user()->name;

        $emailadvogado = DB::table('dbo.users')
                        ->select('email')
                        ->where('cpf','=', $codigo_advogado)
                        ->value('email'); 


        //Verifica se o setor da pasta é o da controladoria, se for mandar email para eles 
        if($setor == "2.2.3.1" || $setor == "2.2.3.") {

         
         //Envia email
         if($emailadvogado != null) {
                Mail::to('controladoria@plcadvogados.com.br')
                ->cc($emailadvogado)
                ->send(new AtualizarSetor($correspondente, $pasta, $setor));
        }else {
                Mail::to('controladoria@plcadvogados.com.br')
                ->send(new AtualizarSetor($correspondente, $pasta, $setor));
         }

 
         flash('Pasta informada aguardando a troca do setor de custo pela Controladoria !')->error();   
         return redirect()
                        ->route("Painel.Correspondente.create")
                        ->withInput();
 
        
        } 
        //Se for Controladoria SP
        else if($setor ==  "2.2.3.2") {
         
         //Envia email
         if($emailadvogado != null) {
                Mail::to('controladoriasp@plcadvogados.com.br')
                ->cc($emailadvogado)
                ->send(new AtualizarSetor($correspondente, $pasta, $setor));

                flash('Pasta informada aguardando a troca do setor de custo pela Controladoria !')->error();   
                return redirect()
                               ->route("Painel.Correspondente.create")
                               ->withInput();
        }else {
                Mail::to('controladoriasp@plcadvogados.com.br')
                ->send(new AtualizarSetor($correspondente, $pasta, $setor));

                flash('Pasta informada aguardando a troca do setor de custo pela Controladoria !')->error();   
                return redirect()
                               ->route("Painel.Correspondente.create")
                               ->withInput();
         }    

        }       
        else {

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
       
        $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
               ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
        $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
       $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  

              
      //flash('Início da solicitação de pagamento.')->success();    
       
      return view('Painel.Correspondente.SolicitacaoDebite.step2', compact('datahoje','pastas', 'numeroprocesso', 'tiposervico_descricao',  'dataehora', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas',  'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
      } 
      
    }
}
    
    public function step3(Request $request)
    {
        
       $usuarioid= Auth::user()->id;


        $data= DB::table('dbo.debite_temp')
                  ->select('dbo.debite_temp.codigocomp as Pasta',
                         'dbo.debite_temp.valortotal',
                          'dbo.debite_temp.data',
                          'dbo.users.name as Correspondente',
                          'PLCFULL.dbo.Jurid_Pastas.Cliente', 
                          'PLCFULL.dbo.Jurid_Pastas.Advogado', 
                          'PLCFULL.dbo.Jurid_Pastas.Descricao', 
                          'PLCFULL.dbo.Jurid_Pastas.Moeda', 
                          'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros', 
                           'PLCFULL.dbo.Jurid_Pastas.OutraParte', 
                           'PLCFULL.dbo.Jurid_Pastas.Tribunal',
                           'PLCFULL.dbo.Jurid_Pastas.Setor', 
                          'PLCFULL.dbo.Jurid_Pastas.Unidade', 
                          'PLCFULL.dbo.Jurid_Pastas.UF',
                          'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
                          'PLCFULL.dbo.Jurid_Varas.Descricao as DescricaoVara',
                          'dbo.Jurid_Nota_Tiposervico.tipo as TipoSolicitacao',
                          'dbo.Jurid_Nota_Tiposervico.descricao as TipoServico')
                  ->leftjoin('PLCFULL.dbo.Jurid_Pastas','dbo.debite_temp.codigocomp','=','PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                  ->leftjoin('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
                  ->leftjoin('PLCFULL.dbo.Jurid_Varas', 'PLCFULL.dbo.Jurid_Pastas.Varas', '=', 'PLCFULL.dbo.Jurid_Varas.Codigo')
                  ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.debite_temp.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
                  ->leftjoin('dbo.users', 'dbo.debite_temp.user_id', '=', 'dbo.users.id')
                  ->where('dbo.debite_temp.user_id','=', Auth::user()->id)
                  ->orderBy('dbo.debite_temp.id', 'desc')
                  ->limit(1) 
                  ->first();     
                         
       //Verifica qual o tipo de serviço para definir se vai ter campo anexo comprovante audiencia ou não
       $tiposervico_id = DB::table('dbo.debite_temp')
                        ->select('dbo.debite_temp.tiposervico_id')  
                        ->where('dbo.debite_temp.user_id','=',$usuarioid)
                        ->orderBy('dbo.debite_temp.id', 'desc')
                        ->value('tiposervico_id'); 
       
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
       
      $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
      ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
      ->where('usuario_id', Auth::user()->id)
      ->count();
       
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
    $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  
       
    return view('Painel.Correspondente.SolicitacaoDebite.step3', compact('data','tiposervico_id', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
    }
    
    
    public function step4($numero, $tipo)
    {
        
       $title = 'Painel Cadastro de Ficha';
       $carbon= Carbon::now();
       $usuarioid= Auth::user()->id;
       $dataehora = $carbon->toDateTimeString();

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

       $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                        ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                        ->where('usuario_id', Auth::user()->id)
                        ->count();
        
       $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
         
       $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  
        
       //A = Audiência
       //D = Diligência

        
       if($tipo == "D") {
               
       return view('Painel.Pesquisa.pesquisaCopias', compact('numero', 'dataehora', 'title', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente')); 
       }
      else {

      return view('Painel.Correspondente.SolicitacaoDebite.step4', compact('numero', 'dataehora', 'title', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
      }
          

    }

    public function pegarDadosDashboard(Request $request){
        $result = DB::table('PLCFULL.dbo.jurid_contprbx as cprbx')
                        ->selectRaw("row_number() OVER(ORDER BY de.numero) AS [num],
                        DATEPART(year, de.data) as Ano, de.data as [Data de Solicitação],
                        month(de.data) as [Descrição Mês Criação],
                        Case
                                when month(de.data) = 1 then 'Janeiro'
                                when month(de.data) = 2 then 'Fevereiro'
                                when month(de.data) = 3 then 'Março'
                                when month(de.data) = 4 then 'Abril'
                                when month(de.data) = 5 then 'Maio'
                                when month(de.data) = 6 then 'Junho'
                                when month(de.data) = 7 then 'Julho'
                                when month(de.data) = 8 then 'Agosto'
                                when month(de.data) = 9 then 'Setembro'
                                when month(de.data) = 10 then 'Outubro'
                                when month(de.data) = 11 then 'Novembro'
                                when month(de.data) = 12 then 'Dezembro'
                        End as [Mês],
                        de.Numero, cp.numdoc as [Numero_CPR], cp.cpr_ident as [CPR_IDENT],
                        cprbx.ident_cpr, cprbx.numdocor, cprbx.numdoc, isnull(a.codigo, '') as [CPF/CNPJ],
                        UPPER(isnull(a.nome, '')) as Correspondente, isnull(cprbx.valor, 0) as [Valor],
                        isnull(cprbx.dt_concil, '') as [Data de Conciliacao],
                        case
                                when isnull(cprbx.tipolan, '') = '16.06' then 'DESPESAS REEMBOLSÁVEIS'
                                when isnull(cprbx.tipolan, '') = '11.01.01' then 'CORRESPONDENTES/PARCERIAS'
                        end as [Tipo Lançamento],
                        case
                                when cprbx.tipolan = '16.01' then 'Reembolsável'
                                when cprbx.tipolan = '11.01.01' then 'Não reembolsável'
                        end as [Reembolsavel-SN],
                        se.codigo+' - '+se.descricao as [Setor],
                        un.codigo+' - '+un.descricao as [Unidade],
                        cf1.nome as [Cliente],
                        gc.Descricao as [Grupo Cliente],
                        emp.nome_empreendimento as [Negocio],
                        UPPER(ISNULL(p.uf, 'Consultivo')) as [UF PROJETO],
                        UPPER(ISNULL(p.Comarca, 'Consultivo')) as [COMARCA PROJETO],
                        ISNULL(p.NumPrc1,'') as [Processo],
                        p.codigo_comp as [Pasta],
                        case  when p.IDForm ='0' then 'Contencioso'
                        when p.IDForm ='3' then 'Consultivo'
                        else 'N/A' 
                        end as 'Tipo Pasta',
                        p.codigo_comp as [PROJETO],
                        UPPER(ISNULL(tp.Descricao,'')) as [Tipo do Projeto/Processo],
                        UPPER(ISNULL(fp.Descricao, '')) as [Fase do Projeto/Processo],
                        a.fone1 as [Telefone Correspondente],
                        a.fone2 as [Telefone2 Correspondente],
                        a.e_mail as [E-mail Correspondente],
                        UPPER(ISNULL(a.UF,'')) as [UF CORRESPONDENTE],
                        UPPER(ISNULL(a.CIDADE,'')) as [CIDADE CORRESPONDENTE],
                        UPPER(isnull(dd.descricao,'')) as [Unidade de Alocação solicitação],
                        UPPER( p.StatusPrc) as [Status Processo],
                        UPPER(ts.descricao) as [Tipo de Serviço prestado],
                        case when de.status ='3' then 'Cancelado'
                        when de.status ='1' then 'Em cobrança'
                        when de.status ='2' then 'Bloqueado'
                        else 'N/A' 
                        end as 'Status Debite',
                        (select COUNT(numero) from PLCFULL.dbo.jurid_debite where Usuario like '%portal%'
                        and Hist like '%corresp%') as 'Solicitações de Pagamento',
                        (select COUNT(distinct(codigo)) from PLCFULL.dbo.jurid_advogado where Correspondente = 1 and codigo in (select AdvServ from PLCFULL.dbo.jurid_debite)) as'Total Correspondentes',
                        (select COUNT(codigo) from PLCFULL.dbo.jurid_advogado where 
                        codigo  in (select AdvServ from PLCFULL.dbo.jurid_debite)) as 'Correspondentes Inativos',
                        gf.nome_grupofinanceiro as Seguimento,
                        ISNULL( p.esfera,'') as [Esfera],
                        ISNULL( p.rito,'') as [Rito],
                        case  
                                when p.Id_Hierarquia = '1' then 'Ativo'
                                else 'Passivo' 
                        end as 'Polo Pasta',
                        p.ValorCausa as [Valor da Causa]")
                        ->leftJoin('PLCFULL.dbo.jurid_contapr as cp', 'cprbx.ident_cpr', '=', 'cp.cpr_ident')
                        ->leftJoin('PLCFULL.dbo.jurid_debite as de', 'de.numdocpag', '=', 'cp.numdoc')
                        ->leftJoin('PLCFULL.dbo.jurid_clifor as cf', 'cprbx.cliente', '=', 'cf.codigo')
                        ->leftJoin('PLCFULL.dbo.jurid_setor as se', 'cprbx.centro', '=', 'se.codigo')
                        ->leftJoin('PLCFULL.dbo.jurid_unidade as un', 'de.unidade', '=', 'un.codigo')
                        ->join('PLCFULL.dbo.jurid_pastas as p', 'p.codigo_comp', '=', 'de.pasta')
                        ->join('PLCFULL.dbo.jurid_clifor as cf1', 'cf1.codigo', '=', 'p.cliente')
                        ->leftJoin('PLCFULL.dbo.jurid_grupocliente as gc', 'cf1.grupocli', '=', 'gc.codigo')
                        ->leftJoin('PLCFULL.dbo.jurid_empreendimento as emp', 'cf1.codigo_empreendimento', '=', 'emp.codigo_empreendimento')
                        ->leftJoin('PLCFULL.dbo.jurid_advogado as a', 'a.codigo', '=', 'de.advserv')
                        ->leftJoin('PLCFULL.dbo.jurid_unidade as dd', 'dd.codigo', '=', 'de.unidade')
                        ->join('intranet.dbo.jurid_situacao_ficha as ff', 'ff.numerodebite', '=', 'de.numero')
                        ->leftJoin('intranet.dbo.jurid_nota_tiposervico as ts', 'ts.id', '=', 'ff.tiposervico_id')
                        ->leftJoin('PLCFULL.dbo.jurid_tipop as tp', 'tp.codigo', '=', 'p.tipop')
                        ->leftJoin('PLCFULL.dbo.jurid_fasep as fp', 'fp.codigo', '=', 'p.fasep')
                        ->leftJoin('PLCFULL.dbo.jurid_grupofinanceiro as gf', 'gf.codigo_grupofinanceiro', '=', 'cf1.codigo_grupofinanceiro')
                        ->leftJoin('PLCFULL.dbo.jurid_hierarquia as hh', 'hh.id', '=', 'p.id_hierarquia')
                        ->where('de.usuario', 'like', '%portal%')
                        ->where('de.hist', 'like', '%corresp%')
                        ->where(function($query) use ($request){
                                if($request->input('ano')){
                                        $query->whereRaw("DATEPART(year, de.data) in ('".implode('\', \'', $request->input('ano'))."')");
                                }

                                if($request->input('cliente')){
                                        $query->whereIn('cf1.nome', $request->input('cliente'));
                                }

                                if($request->input('grupo_cliente')){
                                        $query->whereIn('gc.Descricao', $request->input('grupo_cliente'));
                                }

                                if($request->input('negocio')){
                                        $query->whereIn('emp.nome_empreendimento', $request->input('negocio'));
                                }

                                if($request->input('criacao')){
                                        $query->whereRaw("month(de.data) in ('".implode('\', \'',$request->input('criacao'))."')");
                                }

                                if($request->input('polo_pasta')){
                                        $query->whereRaw("(CASE WHEN p.Id_Hierarquia = 1 then 'Ativo'
                                                        ELSE 'Passivo' END) in ('".implode('\', \'', $request->input('polo_pasta'))."')");
                                }

                                if($request->input('alocacao')){
                                        $query->whereRaw("UPPER(dd.descricao) in ('".implode('\', \'', $request->input('alocacao'))."')");
                                }

                                if($request->input('setor')){
                                        $query->whereRaw("se.codigo+' - '+se.descricao in ('".implode('\', \'', $request->input('setor'))."')");
                                }

                                if($request->input('tipo_pasta')){
                                        $query
                                        ->whereRaw("(case  when p.IDForm ='0' then 'Contencioso'
                                                        when p.IDForm ='3' then 'Consultivo'
                                                        else 'N/A' 
                                                end) in ('".implode('\', \'', $request->input('tipo_pasta'))."')");
                                }

                                if($request->input('status')){
                                        $query->whereRaw("UPPER( p.StatusPrc) in ('".implode('\', \'', $request->input('status'))."')");
                                }

                                if($request->input('rito')){
                                        $query->whereIn('p.rito', $request->input('rito'));
                                }

                                if($request->input('reembolsavel')){
                                        $query
                                        ->whereRaw("(case
                                                        when cprbx.tipolan = '16.01' then 'Reembolsável'
                                                        when cprbx.tipolan = '11.01.01' then 'Não reembolsável'
                                                end) in ('".implode('\', \'', $request->input('reembolsavel'))."')");
                                }
                        })
                        ->orderBy('de.data', 'asc')
                        ->get()->toArray();

        return $result;
    }

    public function listCorrespondentes(Request $request){ // listar os correspondentes com filtro
        $Correspondente = new Correspondente();
        $Correspondente = $Correspondente->selectRaw("ISNULL(a.codigo,'') as codigo, UPPER(ISNULL(a.nome,'')) as 'nome', AVG(cprbx.valor) as [valor]")
        ->selectRaw("UPPER(ISNULL(a.UF,'')) as [UF], UPPER(ISNULL(a.CIDADE,'')) as [cidade]")
        ->selectRaw("ISNULL(avg(ah.nota), '0') as [nota]")
        // ->selectRaw("STUFF((select cast(', ' as varchar(max))+t.descricao from jurid_contprbx cpbx
        //                 left join jurid_contapr c on cpbx.Ident_Cpr = c.Cpr_ident
        //                 left join  jurid_debite d on d.numdocpag = c.numdoc
        //                 inner join Intranet..Jurid_Situacao_Ficha f on f.numerodebite = d.numero -- filtrar por tipo serviço
        //                 left join Intranet..Jurid_Nota_Tiposervico t on t.id = f.tiposervico_id
        //                 left join jurid_advogado aa on aa.codigo = d.AdvServ
        //                 where a.Codigo = aa.Codigo
        //                 group by t.descricao
        //                 for xml path('')), 1, 1, '') AS [servico]")
        ->leftJoin('PLCFULL.dbo.jurid_contapr as cp', 'cp.numdoc', '=', 'jurid_debite.numdocpag')
        ->join('PLCFULL.dbo.jurid_contprbx as cprbx', 'cprbx.Ident_Cpr', '=', 'cp.Cpr_ident')
        ->leftJoin('PLCFULL.dbo.jurid_advogado as a', 'a.codigo', '=', 'jurid_debite.AdvServ')
        ->leftJoin('Intranet.dbo.Correspondente_Avaliacao_Hist as ah', 'ah.AdvServ', '=', 'a.codigo')
        ->Join('Intranet.dbo.Jurid_Situacao_Ficha as ff', 'ff.numerodebite', '=', 'jurid_debite.numero')
        ->leftJoin('Intranet.dbo.Jurid_Nota_Tiposervico as ts', 'ts.id', '=', 'ff.tiposervico_id')
        ->whereRaw("jurid_debite.Usuario like '%portal%'")
        ->whereRaw("jurid_debite.Hist like '%corresp%'")
        ->where(function($query) use ($request){
                if($request->input('uf')){
                        $query->whereRaw("UPPER(a.uf) in ('".implode('\', \'', $request->input('uf'))."')");
                }

                if($request->input('comarca')){
                        $query->whereRaw("UPPER(a.cidade) in ('".implode('\', \'', $request->input('comarca'))."')");
                }

                if($request->input('tipo_servico')){
                        $query->whereRaw("UPPER(ts.descricao) in ('".implode('\', \'', $request->input('tipo_servico'))."')");
                }
        })
        ->groupBy('a.codigo', 'a.nome', 'a.uf', 'a.cidade')
        ->orderBy('a.nome', 'desc');

        if($request->input('valor')){
                $valor = explode("-", $request->input('valor'));
                $Correspondente = $Correspondente->havingRaw('AVG(cprbx.valor) between '.$valor[0].' and '.$valor[1]);
        }

        if($request->input('classificacao')){
                $Correspondente = $Correspondente->havingRaw("avg(ah.nota) in ('".implode('\', \'', $request->input('classificacao'))."')");
        }

        $Correspondente = $Correspondente->get()->toArray();
        return $Correspondente;
    } 

    public function avaliarCorrespondente(Request $request){
        try{
                DB::table('Intranet.dbo.Correspondente_Avaliacao_Hist')
                ->insert([
                        'AdvServ' => $request->input('codigo'),
                        'Nota' => $request->input('rating'),
                        'Usuario' => Auth::user()->cpf,
                        'Data' => date('Y-m-d H:i:s'),
                        'Descricao' => $request->input('descricao')
                ]);

                return ['message' => 'Avaliação inserida com sucesso'];
        } catch(Exception $e){
                return ['message' => 'Erro ao inserir avaliação'];
        }
    }

    public function contratarCorrespondente(Request $request){
        $Correspondente = DB::table('PLCFULL.dbo.jurid_advogado')
        ->selectRaw('Nome, E_mail')
        ->where('codigo', $request->input('codigo'))
        ->get()->first();

        $email = $Correspondente->E_mail;
        $nome = $Correspondente->Nome;
        $codigo = $request->input('codigo');
        $pasta = $request->input('pasta');
        $descricao = $request->input('descricao');
        $tiposervico = $request->input('tipo_servico');

        $file = $request->hasfile('arquivo');
        
        if($file){
                $upload = array();
                foreach($request->file('arquivo') as $index => $value){
                        if($value->isValid()){
                                // Retorna o nome original do arquivo
                                $file_name = $value->getClientOriginalName();
                                // Tamanho do arquivo
                                $file_size = $value->getClientSize();
                                
                                if($file_size < 10000000){
                                        $file_name = str_replace(' ', '_', $file_name);
                                        // $upload[] = $value->storeAs('correspondente-contratacao', $file_name);
                                        $upload[] = $value->storeAs($request->input('codigo'), $file_name, 'correspondente-contratacao');
                                }
                        }
                }

                Mail::to('ronaldo.amaral@plcadvogados.com.br')
                ->cc(Auth::user()->email)
                ->send(new NovaSolicitacaoServico($nome, $codigo, $pasta, $descricao, $tiposervico, $upload));

                return ['message' => 'Enviado com sucesso!!!'];
        }
    }

    public function novoCorrespondente(Request $request){
        $dado = $request->input('pasta');
        $email = $request->input('email');
        $tiposervico = $request->input('tipo_servico');
        $carbon= Carbon::now();

        $verifica = DB::table('dbo.users')->select('id')->where('email','=', $email)->value('id');

        if($verifica != null){
                $user = DB::table('dbo.users')->selectRaw('cpf, name')->where('email','=', $email)->get()->first();
                $senha = 'plc@'.substr($user->cpf,-4);

                Mail::to('ronaldo.amaral@plcadvogados.com.br')
                ->cc(Auth::user()->email)
                ->send(new NovaSolicitacaoServico($user->name, $user->cpf, $dado, 'sem descrição do serviço', $tiposervico, []));
        }
        else{
                $values= array(
                        'user_id' => Auth::user()->id, 
                        'email' => $email, 
                        'created_at' => $carbon);
                DB::table('dbo.Correspondente_temp')->insert($values);

                $id = DB::table('dbo.Correspondente_temp')->select('id')->where('email','=', $email)->value('id'); 

                $token = Crypt::encryptString($id);

                Mail::to('ronaldo.amaral@plcadvogados.com.br')
                ->cc(Auth::user()->email)
                ->send(new NovaSolicitacaoAcesso($token, $dado, $tiposervico));
        }

        return ['message' => 'E-mail enviado com sucesso!!!'];
    }

    public function getCorrespondente($id){
        DB::table('PLCFULL..jurid_contprbx as cprbx')
        ->selectRaw("ISNULL(a.codigo,'') as 'CPF/CNPJ', UPPER(ISNULL(a.nome,'')) as 'Correspondente', AVG(cprbx.valor) as [Valor]")
        ->selectRaw("UPPER(ISNULL(a.UF,'')) as [UF CORRESPONDENTE], UPPER(ISNULL(a.CIDADE,'')) as [CIDADE CORRESPONDENTE], UPPER(ts.descricao) as [Tipo de Serviço prestado]")
        ->leftJoin('PLCFULL..jurid_contapr as cp', 'cprbx.Ident_Cpr', '=', 'cp.Cpr_ident')
        ->leftJoin('PLCFULL..jurid_debite as de', 'de.numdocpag', '=', 'cp.numdoc')
        ->leftJoin('PLCFULL..jurid_advogado as a', 'a.codigo', '=', 'de.AdvServ')
        ->Join('Intranet..Jurid_Situacao_Ficha as ff', 'ff.numerodebite', '=', 'de.numero')
        ->leftJoin('Intranet..Jurid_Nota_Tiposervico as ts', 'ts.id', '=', 'ff.tiposervico_id')
        ->whereRaw("de.Usuario like '%portal%'")
        ->whereRaw("de.Hist like '%corresp%'")
        ->where('a.codigo', $id)
        ->groupBy('a.codigo', 'a.nome', 'a.uf', 'a.cidade', 'ts.descricao')
        ->get()->toArray();
    }

    public function pegarDadosTabela(Request $request){
        $result = DB::table('PLCFULL.dbo.jurid_contprbx as cprbx')
                ->selectRaw("row_number() OVER(ORDER BY de.numero) AS [num],
                DATEPART(year, de.data) as Ano, de.data as [Data de Solicitação],
                month(de.data) as [Descrição Mês Criação],
                Case
                        when month(de.data) = 1 then 'Janeiro'
                        when month(de.data) = 2 then 'Fevereiro'
                        when month(de.data) = 3 then 'Março'
                        when month(de.data) = 4 then 'Abril'
                        when month(de.data) = 5 then 'Maio'
                        when month(de.data) = 6 then 'Junho'
                        when month(de.data) = 7 then 'Julho'
                        when month(de.data) = 8 then 'Agosto'
                        when month(de.data) = 9 then 'Setembro'
                        when month(de.data) = 10 then 'Outubro'
                        when month(de.data) = 11 then 'Novembro'
                        when month(de.data) = 12 then 'Dezembro'
                End as [Mês],
                de.Numero, cp.numdoc as [Numero_CPR], cp.cpr_ident as [CPR_IDENT],
                cprbx.ident_cpr, cprbx.numdocor, cprbx.numdoc, isnull(a.codigo, '') as [CPF/CNPJ],
                UPPER(isnull(a.nome, '')) as Correspondente, cprbx.valor as [Valor],
                isnull(cprbx.dt_concil, '') as [Data de Conciliacao],
                case
                        when isnull(cprbx.tipolan, '') = '16.06' then 'DESPESAS REEMBOLSÁVEIS'
                        when isnull(cprbx.tipolan, '') = '11.01.01' then 'CORRESPONDENTES/PARCERIAS'
                end as [Tipo Lançamento],
                case
                        when cprbx.tipolan = '16.01' then 'Reembolsável'
                        when cprbx.tipolan = '11.01.01' then 'Não reembolsável'
                end as [Reembolsavel-SN],
                se.codigo+' - '+se.descricao as [Setor],
                un.codigo+' - '+un.descricao as [Unidade],
                cf1.nome as [Cliente],
                gc.Descricao as [Grupo Cliente],
                emp.nome_empreendimento as [Negocio],
                UPPER(ISNULL(p.uf, 'Consultivo')) as [UF PROJETO],
                UPPER(ISNULL(p.Comarca, 'Consultivo')) as [COMARCA PROJETO],
                ISNULL(p.NumPrc1,'') as [Processo],
                p.codigo_comp as [Pasta],
                case  when p.IDForm ='0' then 'Contencioso'
                when p.IDForm ='3' then 'Consultivo'
                else 'N/A' 
                end as 'Tipo Pasta',
                p.codigo_comp as [PROJETO],
                UPPER(ISNULL(tp.Descricao,'')) as [Tipo do Projeto/Processo],
                UPPER(ISNULL(fp.Descricao, '')) as [Fase do Projeto/Processo],
                a.fone1 as [Telefone Correspondente],
                a.fone2 as [Telefone2 Correspondente],
                a.e_mail as [E-mail Correspondente],
                UPPER(ISNULL(a.UF,'')) as [UF CORRESPONDENTE],
                UPPER(ISNULL(a.CIDADE,'')) as [CIDADE CORRESPONDENTE],
                UPPER(isnull(dd.descricao,'')) as [Unidade de Alocação solicitação],
                UPPER( p.StatusPrc) as [Status Processo],
                UPPER(ts.descricao) as [Tipo de Serviço prestado],
                case when de.status ='3' then 'Cancelado'
                when de.status ='1' then 'Em cobrança'
                when de.status ='2' then 'Bloqueado'
                else 'N/A' 
                end as 'Status Debite',
                (select COUNT(numero) from PLCFULL.dbo.jurid_debite where Usuario like '%portal%'
                and Hist like '%corresp%') as 'Solicitações de Pagamento',
                (select COUNT(codigo) from PLCFULL.dbo.jurid_advogado where Correspondente ='1') as'Total Correspondentes',
                (select COUNT(codigo) from PLCFULL.dbo.jurid_advogado where 
                codigo  in (select AdvServ from PLCFULL.dbo.jurid_debite)) as 'Correspondentes Inativos',
                gf.nome_grupofinanceiro as Seguimento,
                ISNULL( p.esfera,'') as [Esfera],
                ISNULL( p.rito,'') as [Rito],
                case  
                        when p.Id_Hierarquia = '1' then 'Ativo'
                        else 'Passivo' 
                end as 'Polo Pasta',
                p.ValorCausa as [Valor da Causa]")
                ->leftJoin('PLCFULL.dbo.jurid_contapr as cp', 'cprbx.ident_cpr', '=', 'cp.cpr_ident')
                ->leftJoin('PLCFULL.dbo.jurid_debite as de', 'de.numdocpag', '=', 'cp.numdoc')
                ->leftJoin('PLCFULL.dbo.jurid_clifor as cf', 'cprbx.cliente', '=', 'cf.codigo')
                ->leftJoin('PLCFULL.dbo.jurid_setor as se', 'cprbx.centro', '=', 'se.codigo')
                ->leftJoin('PLCFULL.dbo.jurid_unidade as un', 'de.unidade', '=', 'un.codigo')
                ->join('PLCFULL.dbo.jurid_pastas as p', 'p.codigo_comp', '=', 'de.pasta')
                ->join('PLCFULL.dbo.jurid_clifor as cf1', 'cf1.codigo', '=', 'p.cliente')
                ->leftJoin('PLCFULL.dbo.jurid_grupocliente as gc', 'cf1.grupocli', '=', 'gc.codigo')
                ->leftJoin('PLCFULL.dbo.jurid_empreendimento as emp', 'cf1.codigo_empreendimento', '=', 'emp.codigo_empreendimento')
                ->leftJoin('PLCFULL.dbo.jurid_advogado as a', 'a.codigo', '=', 'de.advserv')
                ->leftJoin('PLCFULL.dbo.jurid_unidade as dd', 'dd.codigo', '=', 'de.unidade')
                ->join('intranet.dbo.jurid_situacao_ficha as ff', 'ff.numerodebite', '=', 'de.numero')
                ->leftJoin('intranet.dbo.jurid_nota_tiposervico as ts', 'ts.id', '=', 'ff.tiposervico_id')
                ->leftJoin('PLCFULL.dbo.jurid_tipop as tp', 'tp.codigo', '=', 'p.tipop')
                ->leftJoin('PLCFULL.dbo.jurid_fasep as fp', 'fp.codigo', '=', 'p.fasep')
                ->leftJoin('PLCFULL.dbo.jurid_grupofinanceiro as gf', 'gf.codigo_grupofinanceiro', '=', 'cf1.codigo_grupofinanceiro')
                ->leftJoin('PLCFULL.dbo.jurid_hierarquia as hh', 'hh.id', '=', 'p.id_hierarquia')
                ->where('de.usuario', 'like', '%portal%')
                ->where('de.hist', 'like', '%corresp%')
                ->where(function($query) use ($request){
                        if($request->input('ano')){
                                $query->whereRaw("DATEPART(year, de.data) in ('".implode('\', \'', $request->input('ano'))."')");
                        }

                        if($request->input('cliente')){
                                $query->whereIn('cf1.nome', $request->input('cliente'));
                        }

                        if($request->input('grupo_cliente')){
                                $query->whereIn('gc.Descricao', $request->input('grupo_cliente'));
                        }

                        if($request->input('negocio')){
                                $query->whereIn('emp.nome_empreendimento', $request->input('negocio'));
                        }

                        if($request->input('criacao')){
                                $query->whereRaw("month(de.data) in ('".implode('\', \'',$request->input('criacao'))."')");
                        }

                        if($request->input('polo_pasta')){
                                $query->whereRaw("(CASE WHEN p.Id_Hierarquia = 1 then 'Ativo'
                                                ELSE 'Passivo' END) in ('".implode('\', \'', $request->input('polo_pasta'))."')");
                        }

                        if($request->input('alocacao')){
                                $query->whereRaw("UPPER(dd.descricao) in ('".implode('\', \'', $request->input('alocacao'))."')");
                        }

                        if($request->input('setor')){
                                $query->whereRaw("se.codigo+' - '+se.descricao in ('".implode('\', \'', $request->input('setor'))."')");
                        }

                        if($request->input('tipo_pasta')){
                                $query
                                ->whereRaw("(case  when p.IDForm ='0' then 'Contencioso'
                                                when p.IDForm ='3' then 'Consultivo'
                                                else 'N/A' 
                                        end) in ('".implode('\', \'', $request->input('tipo_pasta'))."')");
                        }

                        if($request->input('status')){
                                $query->whereRaw("UPPER( p.StatusPrc) in ('".implode('\', \'', $request->input('status'))."')");
                        }

                        if($request->input('rito')){
                                $query->whereIn('p.rito', $request->input('rito'));
                        }

                        if($request->input('reembolsavel')){
                                $query
                                ->whereRaw("(case
                                                when cprbx.tipolan = '16.01' then 'Reembolsável'
                                                when cprbx.tipolan = '11.01.01' then 'Não reembolsável'
                                        end) in ('".implode('\', \'', $request->input('reembolsavel'))."')");
                        }

                        if($request->input('uf_correspondente')){
                                $query->whereRaw("UPPER(a.UF) in ('".implode('\', \'', $request->input('uf_correspondente'))."')");
                        }

                        if($request->input('cidade_correspondente')){
                                $query->whereRaw("UPPER(a.CIDADE) in ('".implode('\', \'', $request->input('cidade_correspondente'))."')");
                        }

                        if($request->input('uf_projeto')){
                                $query->whereRaw("UPPER(ISNULL(p.uf, 'Consultivo')) in ('".implode('\', \'', $request->input('uf_projeto'))."')");
                        }

                        if($request->input('comarca_projeto')){
                                $query->whereRaw("UPPER(ISNULL(p.Comarca, 'Consultivo')) in ('".implode('\', \'', $request->input('comarca_projeto'))."')");
                        }
                })
                ->orderBy('de.data', 'asc')
                ->get()->toArray();

        return $result;
    }

    public function getComarca(Request $request){
        return DB::table('PLCFULL.dbo.Jurid_Capitais')
        ->selectRaw('UF, Cidade')
        ->whereRaw("UPPER(UF) in ('".implode('\', \'', $request->input('uf'))."')")
        ->orderBy('UF', 'Cidade')
        ->get()->toArray();
    }
    
      public function gravarpesquisa(Request $request) {
          
       $usuarionome = Auth::user()->name;
       $usuarioid = Auth::user()->id;
       $carbon= Carbon::now();
       $dataehora = $carbon->format('Y-m-d');
       $dataHoraMinuto = $carbon->format('d-m-Y');
       $numero = $request->get('numero');


       //Verifico se o debite já existe        
       $model = new PesquisaCorrespondente();
       
       $model->id_ref =  $numero;
       $model->data = $carbon;
       $model->user_id = $usuarioid;
       $model->pergunta1 = $request->get('pergunta1');
       $model->pergunta2 = $request->get('pergunta2');
       $model->pergunta2_obs = $request->get('pergunta2_obs');
       $model->pergunta3 = $request->get('pergunta3');
       $model->pergunta3_obs = $request->get('pergunta3_obs');
       $model->pergunta4 = $request->get('pergunta4');
       $model->pergunta4_obs = $request->get('pergunta4_obs');
       $model->pergunta5 = $request->get('pergunta5');
       $model->pergunta6 = $request->get('pergunta6');
       $model->pergunta7 = $request->get('pergunta7');
       $model->pergunta8 = $request->get('pergunta8');
       $model->pergunta9 = $request->get('pergunta9');
       $model->pergunta10 = $request->get('pergunta10');
       $model->pergunta11 = $request->get('pergunta11');
       //Insert no Banco Hist_Pesquisa_Ficha
       $model->save();
              

       //Mandar notificação para o Correspondente         
       $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $usuarioid, 'tipo' => '1', 'obs' => 'Solicitação pagamento criada.' ,'status' => 'A');
       DB::table('dbo.Hist_Notificacao')->insert($values3);


       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
       ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
       ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'dbo.Jurid_Hist_Ficha.id_hist', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
       ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
       ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
       ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
       ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                  'PLCFULL.dbo.Jurid_Debite.Pasta',
                  DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                  DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                  DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                 'dbo.Jurid_Status_Ficha.*',
                 'PLCFULL.dbo.Jurid_Debite.Setor',
                 'PLCFULL.dbo.Jurid_Debite.Advogado',
                 'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                 'PLCFULL.dbo.Jurid_Ged_Main.Obs as obs',
                 'dbo.Jurid_Nota_TipoServico.descricao as TipoServico')
       ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numero)
       ->first();  
       
       /////////////////////////////////////FOREACH COORDENADORES DO SETOR///////////////////////////
       $coordenadores = DB::table('dbo.users')
       ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
       ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
       ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
       ->select('dbo.users.id', 'dbo.users.email') 
       ->where('dbo.profiles.id', '=', '20')
       ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$notas->Setor)
       ->get();     

       
       foreach ($coordenadores as $data) {

        $coordenador_id = $data->id;
        $coordenador_email = $data->email;

        Mail::to($coordenador_email)
        ->send(new SolicitacaoCoordenador($notas));
 
        $values4= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $coordenador_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento criada.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

       }
       /////////////////////////////////////FIM//////////////////////////////////////////////////////
       
       //Com a notificação criada ele vai enviar o email
       //Verifica se o email esta NULL se não ira enviar
      $emailAdvogado =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('E_mail')
              ->where('Codigo','=', $notas->Advogado)
              ->value('E_mail'); 
      
      $emailCorrespondente = DB::table('dbo.users')
              ->select('email')
              ->where('id','=', $usuarioid)
              ->value('email');       
   
              
     if($emailAdvogado == NULL) {

     Mail::to(Auth::user()->email)
        ->send(new SendMailUser($notas));

      flash('Solicitação registrada com sucesso !')->success();    

      return redirect()->route('Painel.Correspondente.index');
      } else {
      //Se não tiver email do Coordenador, ira enviar apenas para o Solicitante (Advogado)
          
       flash('Solicitação registrada com sucesso !')->success();    
       //Envia o 1ª email para o Correspondente e solicitante da pasta
       Mail::to($emailCorrespondente)
        ->cc(Auth::user()->email)
        ->send(new SendMailUser($notas));

        return redirect()->route('Painel.Correspondente.index');

      }

    }

    public function DeletarRegistro() {
        
       $usuarioid = Auth::user()->id;
       DB::table('dbo.debite_temp')->where('user_id', $usuarioid)->delete();
       
       flash('Registro deletado com sucesso !')->success();   
       
      
       return redirect()->route('Painel.Correspondente.index');
    }
    
    public function anexarArquivo(Request $request) {
    
    //Pega todos os dados do formulário
       $dataForm = $request->all();
       $carbon= Carbon::now();
       $dataehora = $carbon->format('Y-m-d');
       $dataHoraMinuto = $carbon->format('d-m-Y');
        
       $pergunta = $request->get('pergunta');


     $datas= DB::table('dbo.debite_temp')
     ->select('dbo.debite_temp.codigocomp as Pasta', 
             'dbo.debite_temp.valortotal', 
             'dbo.debite_temp.data as datarealizacao',
             'dbo.debite_temp.status',
             'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_Pastas.Advogado', 'PLCFULL.dbo.Jurid_Pastas.Descricao', 'PLCFULL.dbo.Jurid_Pastas.Moeda', 'PLCFULL.dbo.Jurid_Pastas.PRConta', 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros', 'PLCFULL.dbo.Jurid_Pastas.OutraParte', 'PLCFULL.dbo.Jurid_Pastas.Tribunal', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Pastas.Unidade', 'PLCFULL.dbo.Jurid_Pastas.UF',
             'PLCFULL.dbo.Jurid_Advogado.E_mail',
             'PLCFULL.dbo.Jurid_CliFor.banco as Banco', 'PLCFULL.dbo.Jurid_CliFor.agencia as Agencia', 'PLCFULL.dbo.Jurid_CliFor.conta as Conta',
             'PLCFULL.dbo.Jurid_Tribunais.Descricao as DescricaoTribunal',
             'PLCFULL.dbo.Jurid_Varas.Descricao as DescricaoVara',
             'dbo.Jurid_Nota_TipoServico.descricao as TipoServicoDescricao')  
     ->leftjoin('PLCFULL.dbo.Jurid_Pastas','dbo.debite_temp.codigocomp','=','PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
     ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Pastas.Advogado', '=', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
     ->leftjoin('PLCFULL.dbo.Jurid_CliFor','PLCFULL.dbo.Jurid_Pastas.Advogado','=','PLCFULL.dbo.Jurid_CliFor.Codigo')     
     ->leftjoin('PLCFULL.dbo.Jurid_Tribunais','PLCFULL.dbo.Jurid_Pastas.Tribunal','=','PLCFULL.dbo.Jurid_Tribunais.Codigo')
     ->leftjoin('PLCFULL.dbo.Jurid_Varas', 'PLCFULL.dbo.Jurid_Pastas.Varas', '=', 'PLCFULL.dbo.Jurid_Varas.Codigo')
     ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.debite_temp.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')
     ->where('dbo.debite_temp.user_id','=', Auth::user()->id)
     ->orderBy('dbo.debite_temp.id', 'desc')
     ->get();      

       $ultimonumero = Correspondente::orderBy('Numero', 'desc')->value('Numero'); // gets only the id
       $numero = $ultimonumero +1;


       //Pega os Dados Para dar Insert Tabela Debite
       $model2 = new Correspondente();
       $model2->Numero =  $numero;
       $model2->Advogado = $datas[0]->Advogado; 
       $model2->Cliente = $datas[0]->Cliente; 
       $model2->Data = $dataehora;
       $model2->Tipo = 'D';
       $model2->Obs = 'Número da solicitação: ' .$numero . ' ' .  $request->get('observacao2') . ' ' . $request->get('observacao');
       $model2->Status = $datas[0]->status;
       $model2->Hist = 'Ficha inserida pelo: '.Auth::user()->name .' no Portal Correspondente - '. $dataHoraMinuto;
       $model2->ValorT = $datas[0]->valortotal;
       $model2->Usuario = 'portal.plc';
       $model2->DebPago = 'N';
       $model2->TipoDeb = '023'; 
       $model2->AdvServ = Auth::user()->cpf;
       $model2->Setor = $datas[0]->Setor;
       $model2->Pasta = $datas[0]->Pasta;
       $model2->Unidade = $datas[0]->Unidade;
       $model2->PRConta = $datas[0]->PRConta;
       $model2->Valor_Adv = $datas[0]->valortotal;
       $model2->Quantidade = '1'; 
       $model2->ValorUnitario_Adv = $datas[0]->valortotal;
       $model2->ValorUnitarioCliente = $datas[0]->valortotal;
       $model2->Revisado_DB = '0';
       $model2->moeda = 'R$';      
       $model2->save();

       $tiposervico_id = DB::table('dbo.debite_temp')->select('dbo.debite_temp.tiposervico_id')->where('dbo.debite_temp.user_id','=',Auth::user()->id)->orderBy('dbo.debite_temp.id', 'desc')->value('tiposervico_id'); 
       $tipo = DB::table('Jurid_Nota_Tiposervico')->select('tipo')->where('id','=',$tiposervico_id)->value('tipo'); 


       //Insert na Tabela Historico
       $values = array('id_hist' => $numero, 'id_status' => '6', 'id_user' => Auth::user()->id, 'data' => $carbon);
       DB::table('dbo.Jurid_Hist_Ficha')->insert($values);

       //Insert na Tabela Situacao (1ªRegistro)
       $values2 = array('numerodebite' => $numero, 'status_id' => '6', 'data_servico' => $datas[0]->datarealizacao, 'usuario_id' => Auth::user()->id, 'tiposervico_id' => $tiposervico_id);
       DB::table('dbo.Jurid_Situacao_Ficha')->insert($values2);

       //Deleta registro temporario
       DB::table('dbo.debite_temp')->where('user_id', Auth::user()->id)->delete();  

           
     //Possui Comprovante
     if($pergunta == 'SIM') {
            
     $image = $request->file('select_file');
     $image2 = $request->file('select_file2');
     
     //$new_name = $numeroprocesso . '_' . $dataehora . '.' . $image->getClientOriginalExtension();
     $new_name = $numero . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
     $new_name2 = $numero . '_comprovante_' . $dataehora . '.'  . $image2->getClientOriginalExtension();
     

     $image->storeAs('solicitacaopagamento', $new_name);
     $image2->storeAs('comprovanteservico', $new_name2);

     Storage::disk('reembolso-local')->put($new_name, File::get($image));
     Storage::disk('reembolso-local')->put($new_name2, File::get($image2));


     //Insert Jurid_Ged_Main
     $values = array(
         'Tabela_OR' => 'Debite',
         'Codigo_OR' => $numero,
         'Id_OR' => $numero,
         'Descricao' => $image->getClientOriginalName(),
         'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\solicitacaopagamento/'.$new_name2, 
         'Data' => $carbon,
         'Nome' => $image->getClientOriginalName(),
         'Responsavel' => 'portal.plc',
         'Arq_tipo' => $image->getClientOriginalExtension(),
         'Arq_Versao' => '1',
         'Arq_Status' => 'Guardado',
         'Arq_usuario' => 'portal.plc',
         'Arq_nick' => $new_name,
         'Obs' => 'Número da solicitação: ' .$numero . ' ' . $request->get('observacao2') . '' . $request->get('observacao'),
         'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $datas[0]->Pasta)->value('id_pasta'));
     DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);   

     $values = array(
        'Tabela_OR' => 'Debite',
        'Codigo_OR' => $numero,
        'Id_OR' => $numero,
        'Descricao' => $image2->getClientOriginalName(),
        'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\solicitacaopagamento/'.$new_name, 
        'Data' => $carbon,
        'Nome' => $image2->getClientOriginalName(),
        'Responsavel' => 'portal.plc',
        'Arq_tipo' => $image2->getClientOriginalExtension(),
        'Arq_Versao' => '1',
        'Arq_Status' => 'Guardado',
        'Arq_usuario' => 'portal.plc',
        'Arq_nick' => $new_name2,
        'Obs' => 'Número da solicitação: ' .$numero . ' ' . $request->get('observacao2') . '' . $request->get('observacao'),
        'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $datas[0]->Pasta)->value('id_pasta'));
    DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);   
     
     
       }
       //Não Possui Comprovante
    else {
   
      $this->validate($request, [
      'select_file'  => 'file|mimes:jpg,png,xls,xlsx,pdf,doc,docx,rtf,jpeg|max:16384',
     ]);
      
     $image = $request->file('select_file');
     $new_name = $numero . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
     
     $image->storeAs('solicitacaopagamento', $new_name);

     //Insert Jurid_Ged_Main
     $values = array(
         'Tabela_OR' => 'Debite',
         'Codigo_OR' => $numero,
         'Id_OR' => $numero,
         'Descricao' => $image->getClientOriginalName(),
         'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\solicitacaopagamento/'.$new_name, 
         'Data' => $carbon,
         'Nome' => $image->getClientOriginalName(),
         'Responsavel' => 'portal.plc',
         'Arq_tipo' => $image->getClientOriginalExtension(),
         'Arq_Versao' => '1',
         'Arq_Status' => 'Guardado',
         'Arq_usuario' => 'portal.plc',
         'Arq_nick' => $new_name,
         'Obs' => 'Número da solicitação: ' .$numero . ' ' . $request->get('observacao2') . '' . $request->get('observacao'),
         'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $datas[0]->Pasta)->value('id_pasta'));
     DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);


     }
     
    // Ao fazer o Insert ele ira para o STEP 4 (Pesquisa)
    return redirect()->route("Painel.Correspondente.step4", ["numero" => $numero, "tipo" => $tipo]);

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
                         'PLCFULL.dbo.Jurid_Debite.Obs',
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
                        'dbo.Jurid_Nota_TipoServico.descricao as TipoDebite')  
                 ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
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
                 ->where('dbo.Jurid_Hist_Ficha.id_status', '=', '6')
                 ->get()
                 ->first();

                //  $anexo2 = $numeroprocesso->anexo;
                //  $anexoformatado =  str_replace ('\\\192.168.1.65\advwin\portal\portal\solicitacaopagamento/', '', $anexo2);
        
                 //Busca o caminho do arquivo
        //         $datacomprovante = DB::table('PLCFULL.dbo.Jurid_Ged_Main')->select('Link as anexo')
        //         ->where('Id_OR','=', $Numero)
        //         ->where('Texto', '==', 'Comprovante pagamento')
        //         ->orderby('ID_doc', 'desc')
        //         ->get()
        //        ->first();
    
      return view('Painel.Correspondente.SolicitacaoDebite.print', compact('numeroprocesso',  'carbon'));
   }
    
     public function pagas() {
        
    $title = 'Painel de Notas';
       $usuario_cpf = Auth::user()->cpf;
       $usuario_nome = Auth::user()->name;
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as descricao',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServicoDescricao')  
             ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', $usuario_cpf)
            ->whereIn('status_id', array(9,10,11))
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
    
   $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
       $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  
      
       return view('Painel.Correspondente.SolicitacaoDebite.pagas', compact('title', 'notas', 'usuario_nome', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas',  'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
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
                           'PLCFULL.dbo.Jurid_Debite.Hist',
                           'PLCFULL.dbo.Jurid_Debite.Obs', 
                           'DebPago as Pago', 
                           'Pasta',
                           'PLCFULL.dbo.Jurid_Pastas.PRConta',
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'),
                           'dbo.Jurid_Status_Ficha.descricao')  
                   ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
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
             ->select('dbo.Hist_Notificacao.id as idNotificacao', 'data', 'id_ref', 'user_id', 'tipo', 'obs', 'hist_notificacao.status', 'dbo.users.*')  
             ->leftjoin('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->get();
       
     $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
       $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  
      
       return view('Painel.Correspondente.SolicitacaoDebite.cancelar', compact('notas', 'dataHoraMinuto', 'motivos', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
    }
    
    public function darbaixa(Request $request) {
        
      $usuarioid = Auth::user()->id;
      $carbon= Carbon::now();
      $dataHoraMinuto = $carbon->format('d-m-Y');
      
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
      
      //Pegar o id User do Advogado para Receber a notificacao
      $destino_id = DB::table('dbo.users')
                ->select('id')  
                ->where('cpf','=',$advogado_cpf)
                ->value('id');  
              
      //Com a notificação criada ele vai enviar o email
      //Verifica se o email esta NULL se não ira enviar
      $emailAdvogado =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('E_mail')
              ->where('Codigo','=', $advogado_cpf)
              ->value('E_mail'); 
      
      $emailCorrespondente = DB::table('dbo.users')
              ->select('email')
              ->where('id','=', $usuarioid)
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
        ->update(array('status' => '3', 'Obs' => $hist));
               
       //Manda notificação de cancelada para o Advogado da Pasta e Correspondente
       $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação cancelada', 'status' => 'A');
       DB::table('dbo.Hist_Notificacao')->insert($values3);

       $values3= array('data' => $carbon, 'id_ref' => $numero, 'user_id' => $usuarioid, 'destino_id' => $usuarioid, 'tipo' => '1', 'obs' => 'Solicitação pagamento cancelada.' ,'status' => 'A');
       DB::table('dbo.Hist_Notificacao')->insert($values3);
             
       flash('Solicitação cancelada com sucesso!')->success();
        
       if($emailAdvogado == null) {
        Mail::to($emailCorrespondente)
        ->send(new DebiteCancelado($numero));
        return redirect()->route('Painel.Correspondente.index');
       }
        else {
        Mail::to($emailAdvogado)
        ->cc($emailCorrespondente)
        ->send(new DebiteCancelado($numero));
        return redirect()->route('Painel.Correspondente.index');
       }
    }
    
    public function canceladas() {
       
       $title = 'Painel de Notas';
       $usuario_cpf = Auth::user()->cpf;
       $usuario_nome = Auth::user()->name;
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as descricao',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServicoDescricao')  
             ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', $usuario_cpf)
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '13')  
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
       
       $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  
      
       return view('Painel.Correspondente.SolicitacaoDebite.canceladas', compact('title',  'notas', 'usuario_nome', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas', 'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
    }
   
    public function pendentes() {
       
       $title = 'Painel de Notas';
       $usuario_cpf = Auth::user()->cpf;
       $usuario_nome = Auth::user()->name;
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as descricao',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServicoDescricao')  
             ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', $usuario_cpf)
             ->where('dbo.Jurid_Situacao_Ficha.status_id', '=', '8')  
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
    
      $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
     
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
         $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();  

       return view('Painel.Correspondente.SolicitacaoDebite.pendentes', compact('title', 'notas', 'usuario_nome', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas',  'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));
    }
    
      public function corrigir($numero) {

     $carbon= Carbon::now();
     $dataHoraMinuto = $carbon->format('d-m-Y');

     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
                   ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', 
                           DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                           'PLCFULL.dbo.Jurid_Debite.Status as StatusFicha', 
                           DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'),
                           'Usuario', 
                           'PLCFULL.dbo.Jurid_Debite.Obs', 
                           'DebPago as Pago', 'Pasta', 
                           'Hist',
                           'PLCFULL.dbo.Jurid_Pastas.PRConta', 
                           DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                           'dbo.Jurid_Status_Ficha.descricao',
                           'PLCFULL.dbo.Jurid_Ged_Main.Link as anexo',
                           'PLCFULL.dbo.Jurid_Ged_Main.Obs as observacao',
                           'dbo.Jurid_Nota_Motivos.descricao as motivo',
                           'dbo.Jurid_Situacao_Ficha.observacao as observacaomotivo')  
                   ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                   ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
                   ->leftjoin('dbo.Jurid_Nota_Motivos', 'dbo.Jurid_Situacao_Ficha.motivo_id', '=', 'dbo.Jurid_Nota_Motivos.id')
                   ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                   ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'dbo.Jurid_Situacao_Ficha.numerodebite', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
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
       
      $totalSolicitacoesAbertas = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
      $totalSolicitacoesCancelada = DB::table('dbo.Jurid_Situacao_Ficha')
                ->where('status_id', '=', '13')
                ->where('usuario_id', Auth::user()->id)
                ->count();
        
        $totalSolicitacoesPagasCorrespondente = DB::table('dbo.Jurid_Situacao_Ficha')
                ->whereIn('status_id', array(9,11))
                ->where('usuario_id', Auth::user()->id)
                ->count();      
                           
       return view('Painel.Correspondente.SolicitacaoDebite.corrigir', compact('notas', 'dataHoraMinuto', 'totalNotificacaoAbertas', 'notificacoes', 'totalSolicitacoesAbertas',  'totalSolicitacoesCancelada', 'totalSolicitacoesPagasCorrespondente'));  
    }
    
   public function corrigido(Request $request) {
    
      //Pega todos os dados do formulário
      $carbon= Carbon::now();
      $dataehora = $carbon->format('dmY_HHis');
      $usuarioid = Auth::user()->id;
      $numeroprocesso = $request->get('numerodebite');
      $pasta = $request->get('pasta');
      $anexo = $request->get('anexo');
      $valortotal = $request->get('ValorT');

      $hist = $request->get('hist');

      //Verifico se teve alteração no valor original
      $valororiginal =  DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select(DB::raw('CAST(ValorT AS NUMERIC(15,2))'))
              ->where('Numero','=', $numeroprocesso)
              ->value('ValorT');
      
      //Não teve alteração        
      if($valortotal == $valororiginal) {
       //Alterar  na Tabela Debite e voltando o RevisadoDB = '0' para aprovação novamente 
       DB::table('PLCFULL.dbo.Jurid_Debite')
      ->where('Numero', $numeroprocesso)  
      ->limit(1) 
      ->update(array(
      'Hist'=> $hist,
      'Revisado_DB' => '0'));

      } else {
        $valorT =  str_replace (',', '.', str_replace ('.', '', $valortotal));

        //Alterar o Valor na Tabela Debite e voltando o RevisadoDB = '0' para aprovação novamente 
       DB::table('PLCFULL.dbo.Jurid_Debite')
        ->where('Numero', $numeroprocesso)  
        ->limit(1) 
        ->update(array(
        'Hist'=> $hist,
        'ValorT' => $valorT, 
        'Valor_Adv' => $valorT, 
        'ValorUnitario_Adv' => $valorT, 
        'ValorUnitarioCliente' => $valorT, 
        'Revisado_DB' => '0'));
      }        

        
      $this->validate($request, [
      'select_file'  => 'file|mimes:jpg,png,gif,xls,pdf,doc,docx,rtf,jpeg|max:16384',
      'select_file.required' => 'Favor anexar um documento nos formatos: JPG, PNG, GIF, XLS, '
          . 'PDF, DOC, DOCX, RTF ou JPG e com tamanho máximo de 16MB.'
     ]);
      
     $image = $request->file('select_file');
     $new_name = $numeroprocesso . '_' . $dataehora . '.'  . $image->getClientOriginalExtension();
     
     $image->storeAs('solicitacaopagamento', $new_name);

     //Insert Jurid_Ged_Main
     $values = array(
         'Tabela_OR' => 'Debite',
         'Codigo_OR' => $numeroprocesso,
         'Id_OR' => $numeroprocesso,
         'Descricao' => $image->getClientOriginalName(),
         'Link' => '\\\192.168.1.65\advwin\ged\vault$\portal\solicitacaopagamento/'.$new_name, 
         'Data' => $carbon,
         'Nome' => $image->getClientOriginalName(),
         'Responsavel' => 'portal.plc',
         'Arq_tipo' => $image->getClientOriginalExtension(),
         'Arq_Versao' => '1',
         'Arq_Status' => 'Guardado',
         'Arq_usuario' => 'portal.plc',
         'Arq_nick' => $new_name,
         'Obs' => $request->get('observacao'),
         'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
     DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);       
             
    $advogado_cpf = DB::table('PLCFULL.dbo.Jurid_Debite')
              ->select('Advogado')
              ->where('Numero','=', $numeroprocesso)
              ->value('Advogado'); 

    $emailAdvogado =  DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('E_mail')
              ->where('Codigo','=', $advogado_cpf)
              ->value('E_mail'); 
      
    $emailCorrespondente = DB::table('dbo.users')
              ->select('email')
              ->where('id','=', $usuarioid)
              ->value('email'); 

     
     //Vai da Update Na Hist_Ficha informando que foi corrigido e voltado para status inicial (6) 
     //Em seguida ele dara update na Situacao Ficha 
     $values = array('id_hist' => $numeroprocesso, 'id_status' => '6', 'id_user' => $usuarioid, 'data' => $carbon);
     DB::table('dbo.Jurid_Hist_Ficha')->insert($values);
    
    DB::table('dbo.Jurid_Situacao_Ficha')
        ->where('numerodebite', $numeroprocesso)  
        ->limit(1) 
        ->update(array('status_id' => '6',  'observacao' => '', 'motivo_id' => ''));
     
    //Ao Corrigir ele ira deletar na Hist Ficha o ID Reprovado (Gumercindo solicitou para que não mostre mais no TimeLine)
    DB::table('dbo.Jurid_Hist_Ficha')
            ->where('id_hist', $numeroprocesso)
            ->where('id_status', '8')
            ->delete();
     
     
    //Pegar o id User do Advogado para Receber a notificacao
      $destino_id = DB::table('dbo.users')
                ->select('id')  
                ->where('cpf','=',$advogado_cpf)
                ->value('id');   

      $idCorrespondente = DB::table('dbo.Jurid_Hist_Ficha')
                ->select('id_user')
                ->where('id_status','=', '6')
                ->where('id_hist', '=', $numeroprocesso)
                ->value('id_user');     

     //Mandar notificação para o Correspondente e Advogado           
     $values3= array('data' => $carbon, 'id_ref' => $numeroprocesso, 'user_id' => $usuarioid, 'destino_id' => $destino_id, 'tipo' => '1', 'obs' => 'Solicitação pagamento editada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values3);

     $values3= array('data' => $carbon, 'id_ref' => $numeroprocesso, 'user_id' => $usuarioid, 'destino_id' => $idCorrespondente, 'tipo' => '1', 'obs' => 'Solicitação pagamento editada.' ,'status' => 'A');
     DB::table('dbo.Hist_Notificacao')->insert($values3);
  
     $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Hist_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Hist_Ficha.id_hist')
             ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'dbo.Jurid_Hist_Ficha.id_hist', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Hist_Ficha.id_status', '=', 'dbo.Jurid_Status_Ficha.id')
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
                     'PLCFULL.dbo.Jurid_Ged_Main.Obs as obs',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico')
            ->where('PLCFULL.dbo.Jurid_Debite.Numero','=',$numeroprocesso)
            ->limit(1)    
            ->get();
    
    
    flash('Solicitação corrigida com sucesso e enviada para revisão técnica !')->success();
    
    if($emailAdvogado == null) {
        Mail::to($emailCorrespondente)
        ->send(new DebiteEditado($notas));
         return redirect()->route('Painel.Correspondente.index');    
    } else {
        Mail::to($emailCorrespondente)
        ->cc($emailAdvogado) 
        ->send(new DebiteEditado($notas));
         return redirect()->route('Painel.Correspondente.index');  
    }

 
    }
    
    public function gerarExcelAbertas() {
    
     $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')  
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.AdvServ','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as Data'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as Status',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'dbo.users.name as Correspondente')  
             ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', Auth::user()->cpf)
             ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,8,10,12,14))
             ->get()
             ->toArray();
     $customer_array[] = array('Numero',  'Pasta', 'Data' ,'Valor', 'Quantidade', 'Status', 'Correspondente', 'TipoServico');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Número'  => $customer->Numero,
       'Pasta'  => $customer->Pasta,
       'Data'=> date('d/m/Y', strtotime($customer->Data)),
       'Valor' => $customer->Valor,
       'Quantidade' => $customer->Quantidade,
       'Status' => $customer->Status,
       'Correspondente' => $customer->Correspondente,   
       'TipoServico' => $customer->TipoServico,
      );
     }
     Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Solicitações Pagamento Abertas');
      $excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    }
    
     public function gerarExcelPagas() {
    
     $customer_data = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha', 'PLCFULL.dbo.Jurid_Debite.Numero', '=', 'dbo.Jurid_Situacao_Ficha.numerodebite')  
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_TipoServico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_TipoServico.id')  
             ->select('PLCFULL.dbo.Jurid_Debite.Numero as Numero',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS DateTime) as Data'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as Valor'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.descricao as Status',
                     'PLCFULL.dbo.Jurid_Advogado.Razao as AdvogadoPasta',
                     'dbo.Jurid_Nota_TipoServico.descricao as TipoServico',
                     'dbo.users.name as Correspodente')  
             ->where('PLCFULL.dbo.Jurid_Debite.AdvServ', '=', Auth::user()->cpf)
             ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(9,11))
             ->get()
             ->toArray();
     $customer_array[] = array('Numero',  'Pasta', 'Data' ,'Valor', 'Quantidade', 'Status', 'Correspondente', 'TipoServico');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'Número'  => $customer->Numero,
       'Pasta'  => $customer->Pasta,
       'Data'=> date('d/m/Y', strtotime($customer->Data)),
       'Valor' => $customer->Valor,
       'Quantidade' => $customer->Quantidade,
       'Status' => $customer->Status,
       'Correspondente' => $customer->Correspondente,   
       'TipoServico' => $customer->TipoServico,
      );
     }
     Excel::create('Solicitação de Pagamento', function($excel) use ($customer_array){
      $excel->setTitle('Solicitações Pagamento Pagas');
      $excel->sheet('Solicitação de Pagamento', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
    

    //return Excel::download(new SolicitacoesExports, 'solicitacoespagamento_'. time() . '.xlsx');
    }
   
    public function anexo($numerodebite) {
                
           //Busco os arquivos gravados na GED
           $datas = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
           ->where('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
           ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
           ->get();

           $QuantidadeAnexos = $datas->count();

           return view('Painel.Correspondente.SolicitacaoDebite.anexos', compact('numerodebite','datas', 'QuantidadeAnexos')); 
           
        // return Storage::disk('solicitacaopagamento-sftp')->download($anexo);   
    }
    
    public function comprovante($anexo) {
        return Storage::disk('solicitacaopagamento-sftp')->download($anexo);   
    }

    public function updateUsuario(Request $request, $id) {

        $email = $request->get('email');
        $name = $request->get('name');
        $password = bcrypt($request->get('password'));
        $senha = $request->get('password_confirmation');
        $cpf = $request->get('cpf');
        $nameImage = $id.'.png';
        $image = $request->file('image');
        //Verifica se existe a imagem
        
            
        if($image != null){
        $image->storeAs('users', $nameImage);
        }

        DB::table('dbo.users')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('name' => $name,'email' => $email, 'password' => $password, 'image' => $nameImage, 'cpf' => $cpf));

        flash('Atualizado com sucesso !')->success();

        //Manda email notificando
        Mail::to($email)
        ->send(new UsuarioEditado($name, $email, $senha));
         return redirect()->route('Home.Principal.Show');    
    }
    
    public function politicaprivacidade() {

        return view('Painel.Correspondente.NovoPrestador.politicaprivacidade');  
    }
    
}
