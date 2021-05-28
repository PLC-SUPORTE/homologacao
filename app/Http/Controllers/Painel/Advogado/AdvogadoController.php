<?php

namespace App\Http\Controllers\Painel\Advogado;

use App\Models\Advogado;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Nota;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Correspondente\NovaSolicitacaoAcesso;
use App\Mail\Correspondente\NovaSolicitacaoServico;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AdvogadoController extends Controller
{

    protected $model;

    public function __construct(Advogado $model)
    {
        $this->model = $model;
        // $this->middleware('can:advogado');
    }

     public function index()
    {
       $title = 'Painel de Notas';
       $usuariocpf = Auth::user()->cpf;
       $notas = DB::table('PLCFULL.dbo.Jurid_Debite')
             ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
             ->leftjoin('dbo.Jurid_Status_Ficha', 'dbo.Jurid_Situacao_Ficha.status_id', '=', 'dbo.Jurid_Status_Ficha.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
             ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
             ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', 'dbo.Jurid_Nota_Tiposervico.id')
             ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
              ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                     'PLCFULL.dbo.Jurid_Debite.Pasta',
                     'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                     DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                     DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'), 
                     DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                     DB::raw('CAST(Quantidade AS NUMERIC(15,0)) as Quantidade'), 
                     'dbo.Jurid_Status_Ficha.*',
                     'PLCFULL.dbo.Jurid_Advogado.E_mail',
                     'dbo.users.name',
                     'dbo.Jurid_Nota_Tiposervico.descricao as TipoServicoDescricao')
             ->where('PLCFULL.dbo.Jurid_Debite.Advogado', '=', $usuariocpf) 
             ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(6,7,12,14))
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
       
      return view('Painel.Advogado.SolicitacaoDebite.index', compact('title', 'notas', 'totalNotificacaoAbertas', 'notificacoes'));
      //dd($notas);
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
                              
      return view('Painel.Advogado.SolicitacaoDebite.show', compact('numeroprocesso', 'statusAtual'));
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
                 ->leftjoin('PLCFULL.dbo.Jurid_Ged_Main', 'dbo.Jurid_Hist_Ficha.id_hist', 'PLCFULL.dbo.Jurid_Ged_Main.Id_OR')
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
        
//Busca o caminho do arquivo
   $datacomprovante = DB::table('PLCFULL.dbo.Jurid_Ged_Main')
            ->select('Link as anexo')
            ->where('Id_OR','=', $Numero)
            ->where('Texto', '==', 'Comprovante pagamento')
            ->orderby('ID_doc', 'desc')
            ->get()
            ->first();
    
      return view('Painel.Advogado.SolicitacaoDebite.print', compact('numeroprocesso', 'datacomprovante',  'carbon'));
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
             ->where('PLCFULL.dbo.Jurid_Debite.Advogado', '=', $usuario_cpf)
             ->whereIn('dbo.Jurid_Situacao_Ficha.status_id', array(9,11))
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
      
       return view('Painel.Advogado.SolicitacaoDebite.pagas', compact('title', 'notas', 'usuario_nome', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function novoprestador_index() {

      $datas = DB::table('dbo.Correspondente_Matrix')
        ->select(
                'dbo.Correspondente_Matrix.id',
                'dbo.Correspondente_Comarcas.comarca_id as comarca_id',
                'dbo.Correspondente_Matrix.nome as correspondente_nome',
                'dbo.Correspondente_Matrix.email as correspondente_email',
                'dbo.Correspondente_Matrix.codigo as correspondente_codigo',
                'dbo.Correspondente_Matrix.classificacao as correspondente_classificacao',
                'dbo.Correspondente_Matrix.classificacao as correspondente_classificacao',
                'dbo.Correspondente_Matrix.observacao as correspondente_observacao',
                'PLCFULL.dbo.Jurid_Capitais.Cidade as comarca_descricao',
                'dbo.Correspondente_Comarcas.id as comarca_id',
                'dbo.Correspondente_Comarcas.valor_audiencia as comarca_valoraudiencia',
                'dbo.Correspondente_Comarcas.valor_diligencia as comarca_valordiligencia',
                'PLCFULL.dbo.Jurid_Capitais.UF as comarca_uf')
        ->leftjoin('dbo.Correspondente_Comarcas', 'dbo.Correspondente_Matrix.email', 'dbo.Correspondente_Comarcas.correspondente_email')
        ->leftjoin('PLCFULL.dbo.Jurid_Capitais', 'dbo.Correspondente_Comarcas.comarca_id', 'PLCFULL.dbo.Jurid_Capitais.Id')
        ->where('dbo.Correspondente_Matrix.status_id', '=', '1')
        ->orderBy('dbo.Correspondente_Matrix.nome', 'asc')
        // ->limit(100)
        ->get();
                
        $tipos = DB::table('dbo.Jurid_Nota_TipoServico')
        ->orderBy('descricao','ASC')   
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

        return view('Painel.Advogado.NovoCorrespondente.index', compact('datas','tipos','totalNotificacaoAbertas','notificacoes'));

    }


    public function novoprestador_solicitacaoenviada(Request $request) {


        $correspondente_id = $request->get('correspondente_id');
        $correspondente_nome = $request->get('correspondente_nome');
        $correspondente_email = $request->get('correspondente_email');
        $correspondente_cpf = $request->get('correspondente_cpf');
        $comarca_descricao = $request->get('comarca_descricao');
        $dado = $request->get('dado');
        $tiposervico = $request->get('tiposervico');
        $carbon= Carbon::now();


        //Verifica se está cadastrado no sistema
        $verifica = DB::table('dbo.users')->select('id')->where('cpf','=', $correspondente_cpf)->value('id'); 
        
        if($verifica != null) {

           //Envia e-mail informando os dados e solicitando que cadastre a solicitação no portal
           $senha = 'plc@'.substr($correspondente_cpf,-4);

           Mail::to('ronaldo.amaral@plcadvogados.com.br')
           ->cc(Auth::user()->email)
           ->send(new NovaSolicitacaoServico($dado, $tiposervico));
        } 

        //Envia e-mail para cadastro no sistema
        else {


            //Grava na temp
            $values3= array(
                'user_id' => Auth::user()->id, 
                'descricao' => $correspondente_nome,
                'email' => $correspondente_email, 
                'cidade' => $comarca_descricao,
                'created_at' => $carbon);
            DB::table('dbo.Correspondente_temp')->insert($values3);

            $id = DB::table('dbo.Correspondente_temp')->select('id')->where('email','=', $correspondente_email)->value('id'); 

            $token = Crypt::encryptString($id);

            Mail::to('ronaldo.amaral@plcadvogados.com.br')
            ->cc(Auth::user()->email)
            ->send(new NovaSolicitacaoAcesso($token, $dado, $tiposervico));

        }

        flash('Enviado com sucesso e-mail para cadastro das informações')->success();   
        return redirect()->route("Painel.Advogado.NovoPrestador.index");

    }

    public function novoprestador_novocorrespondente(Request $request) {

        $correspondente_email = $request->get('correspondente_email');
        $dado = $request->get('dado');
        $tiposervico = $request->get('tiposervico');
        $carbon= Carbon::now();

        //Grava na temp
        $values3= array(
            'user_id' => Auth::user()->id, 
            'descricao' => $correspondente_nome,
            'email' => $correspondente_email, 
            'cidade' => $comarca_descricao,
            'created_at' => $carbon);
        DB::table('dbo.Correspondente_temp')->insert($values3);

        $id = DB::table('dbo.Correspondente_temp')->select('id')->where('email','=', $email)->value('id'); 

        $token = Crypt::encryptString($id);

        Mail::to($correspondente_email)
        ->cc(Auth::user()->email)
        ->send(new NovaSolicitacaoAcesso($token, $dado, $tiposervico));

        flash('Enviado com sucesso e-mail para cadastro das informações')->success();   
        return redirect()->route("Painel.Advogado.NovoPrestador.index");

    }

    public function novoprestador_editarclassificacao(Request $request) {


        $id = $request->get('id');
        $correspondente_cpf = $request->get('cpf');
        $correspondente_email = $request->get('email');
        $classificacao = $request->get('classificacao');
        $carbon= Carbon::now();
        $observacao = $request->get('observacao');
        $comarca_id = $request->get('comarca_id');
        $comarca_valoraudiencia = str_replace (',', '.', str_replace ('.', '', $request->get('comarca_valoraudiencia')));
        $comarca_valordiligencia = str_replace (',', '.', str_replace ('.', '', $request->get('comarca_valordiligencia')));

        //Update Matrix
        DB::table('dbo.Correspondente_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('classificacao' => $classificacao,'observacao' => $observacao,'data_modificacao' => $carbon));

        //Update Comarcas
        DB::table('dbo.Correspondente_Comarcas')
        ->where('id', $comarca_id)  
        ->limit(1) 
        ->update(array('valor_audiencia' => $comarca_valoraudiencia,'valor_diligencia' => $comarca_valordiligencia));

        flash('Informações atualizadas com sucesso!')->success();   
        return redirect()->route("Painel.Advogado.NovoPrestador.index");
    }

    
}
