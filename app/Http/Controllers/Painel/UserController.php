<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Profile;
use App\Http\Requests\Painel\UserFormRequest;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NovoUsuario;
use App\Mail\Correspondente\NovoUsuarioCorrespondente;
use App\Mail\UsuarioEditado;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Excel;

class UserController extends Controller
{
    private $user;
    protected $totalPage = 1000;
    public $timestamps = false;


    public function __construct(User $user)
    {
        $this->user     = $user;
        
      //  $this->middleware('can:users')
          //      ->except(['showProfile', 'updateProfile']);
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Listagem dos Usuários';
        
        $users = $this->user->paginate($this->totalPage);
        
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

        $profiles =  DB::table('dbo.profiles')
        ->select('id', 'name')
        ->orderby('name', 'asc')
        ->get();

               
        return view('Painel.TI.users.index', compact('users', 'title', 'totalNotificacaoAbertas', 'notificacoes', 'profiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $title = 'Cadastrar Novo Usuário';
        
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
               
      $profiles =  DB::table('dbo.profiles')
      ->select('id', 'name')
      ->orderby('name', 'asc')
      ->get();

 
     return view('Painel.TI.users.create-edit', compact('title', 'totalNotificacaoAbertas', 'notificacoes', 'profiles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request){
        //Pega todos os dados do usuário
        $dataUser = $request->all();
        
        //Criptografa a senha
        $dataUser['password'] = bcrypt($dataUser['password']);
        
        //Verifica se existe a imagem
        if( $request->hasFile('image') ) {
            //Pega a imagem
            $image = $request->file('image');
            
            //Define o nome para a imagem
            $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
            
            $upload = $image->storeAs('users', $nameImage);
            
            if( $upload ) 
                $dataUser['image'] = $nameImage;
            else
                return redirect('/painel/usuarios/create')
                            ->withErrors(['errors' => 'Erro no Upload'])
                            ->withInput();
        }
        
        //Insere os dados do usuário
        $insert = $this->user->create($dataUser);
        
        if( $insert ) {
            
          flash('Usuário criado com sucesso !')->success();
          
          $name = $request->get('name');
          $email = $request->get('email');
          $senha = $request->get('password_confirmation');
          $profile_id = $request->get('profile_id');
          $escolha = $request->get('escolha');

          $cpf = $request->get('cpf');
          $codigo = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $cpf)));


          $userid = DB::table('dbo.users')->select('id')->where('email', '=', $email)->value('id'); 

          DB::table('dbo.users')
         ->where('id', $userid)  
         ->limit(1) 
         ->update(array('cpf' => $codigo));



          //Gravo o nível do usuario
          $values = array(
              'profile_id' => $profile_id, 
              'user_id' => $userid);
          DB::table('dbo.profile_user')->insert($values); 

          //Verifica se deve enviar manual ou não
          if($escolha == "SIM") {

            //Enviar manual e video do correspondente, se o perfil selecionado for esse
            if($profile_id == 1) {

           Mail::to($email)
             ->send(new NovoUsuarioCorrespondente($name, $email, $senha));
              return redirect()->route('usuarios.index');    
            }
            Mail::to($email)
            ->send(new NovoUsuario($name, $email, $senha));
             return redirect()->route('usuarios.index');    


          } else {
            Mail::to($email)
            ->send(new NovoUsuario($name, $email, $senha));
             return redirect()->route('usuarios.index');    
          }
          
        
          }  
    }

    public function storecorrespondente(UserFormRequest $request){
        //Pega todos os dados do usuário
        $dataUser = $request->all();

        $id_matrix = $request->get('id_matrix');
        
        //Criptografa a senha
        $dataUser['password'] = bcrypt($dataUser['password']);
        
        //Verifica se existe a imagem
        if( $request->hasFile('image') ) {
            //Pega a imagem
            $image = $request->file('image');
            
            //Define o nome para a imagem
            $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
            
            $upload = $image->storeAs('users', $nameImage);
            
            if( $upload ) 
                $dataUser['image'] = $nameImage;
            else
                return redirect('/painel/usuarios/create')
                            ->withErrors(['errors' => 'Erro no Upload'])
                            ->withInput();
        }
        
        //Insere os dados do usuário
        $insert = $this->user->create($dataUser);
        
        if( $insert ) {
            
          flash('Usuário criado com sucesso !')->success();
          
          $name = $request->get('name');
          $email = $request->get('email');
          $senha = $request->get('password_confirmation');
          $profile_id = $request->get('profile_id');
          $escolha = $request->get('escolha');
        
          $cpf = $request->get('cpf');
          $codigo = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $cpf)));


          $userid = DB::table('dbo.users')->select('id')->where('email', '=', $email)->value('id'); 

          DB::table('dbo.users')
         ->where('id', $userid)  
         ->limit(1) 
         ->update(array('cpf' => $codigo));

          //Gravo o nível do usuario
          $values = array(
              'profile_id' => $profile_id, 
              'user_id' => $userid);
          DB::table('dbo.profile_user')->insert($values); 

          //Verifica se deve enviar manual ou não
          if($escolha == "SIM") {

            //Enviar manual e video do correspondente, se o perfil selecionado for esse
            if($profile_id == 1) {

           Mail::to($email)
             ->send(new NovoUsuarioCorrespondente($name, $email, $senha));
             $carbon= Carbon::now();

             $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
             ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
             ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
             ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
             ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
             ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
             ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
             ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc', '=', 'PLCFULL.dbo.Jurid_Debite.Fatura')
             ->select(
               'dbo.PesquisaPatrimonial_Matrix.id as ID',
               'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
               'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
               'dbo.users.id as SolicitanteID',
               'dbo.users.email as SolicitanteEmail',
               'dbo.users.cpf as SolicitanteCPF',
               'dbo.users.name as SolicitanteNome',
               'dbo.PesquisaPatrimonial_Status.descricao as Status',
               'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
               'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
               'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
               DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
               'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
               'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
               'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
               'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
               'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
               'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteRazao',
               'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
               'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
               'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
               'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
               'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
               'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
               'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
               'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
               'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento')
             ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
             ->first();
           
             $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
             ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
                       DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
                      'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao')
             ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
             ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
             ->get();
           
             $cpr = $datas->CPR;
             $cpr_ident = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc', '=', $cpr)->value('Cpr_ident'); 
           
              $saldoclienter = DB::table('PLCFULL.dbo.Jurid_Banco')
             ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
             ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente')
             ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
             ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
             ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
             ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
             ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
             ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           
             $saldoclientep = DB::table('PLCFULL.dbo.Jurid_Banco')
             ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
             ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente')
             ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
             ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
             ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
             ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
             ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
             ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           
           
             $saldototal = $saldoclienter - $saldoclientep;
           
             $correspondentes = DB::table('dbo.users')
             ->select('dbo.users.id as id', 'dbo.users.name as name')
             ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
             ->where('dbo.profile_user.profile_id','=', '1')
             ->get();
           
           
             $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                         ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                         ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
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
           
           
           
             return view('Painel.PesquisaPatrimonial.nucleo.concluirsolicitacao', compact('carbon','comarcas','correspondentes','saldototal','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes'));            }
            Mail::to($email)
            ->send(new NovoUsuario($name, $email, $senha));
            $carbon= Carbon::now();

            $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
            ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
            ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
            ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
            ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
            ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
            ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
            ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc', '=', 'PLCFULL.dbo.Jurid_Debite.Fatura')
            ->select(
              'dbo.PesquisaPatrimonial_Matrix.id as ID',
              'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
              'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
              'dbo.users.id as SolicitanteID',
              'dbo.users.email as SolicitanteEmail',
              'dbo.users.cpf as SolicitanteCPF',
              'dbo.users.name as SolicitanteNome',
              'dbo.PesquisaPatrimonial_Status.descricao as Status',
              'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
              'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
              'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
              DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
              'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
              'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
              'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
              'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
              'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
              'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
              'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteRazao',
              'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
              'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
              'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
              'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
              'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
              'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
              'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
              'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
              'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento')
            ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
            ->first();
          
            $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
            ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
                      DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
                     'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao')
            ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
            ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
            ->get();
          
            $cpr = $datas->CPR;
            $cpr_ident = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc', '=', $cpr)->value('Cpr_ident'); 
          
             $saldoclienter = DB::table('PLCFULL.dbo.Jurid_Banco')
            ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
            ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente')
            ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
            ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
            ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
            ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
            ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
            ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
          
            $saldoclientep = DB::table('PLCFULL.dbo.Jurid_Banco')
            ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
            ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente')
            ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
            ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
            ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
            ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
            ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
            ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
          
          
            $saldototal = $saldoclienter - $saldoclientep;
          
            $correspondentes = DB::table('dbo.users')
            ->select('dbo.users.id as id', 'dbo.users.name as name')
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->where('dbo.profile_user.profile_id','=', '1')
            ->get();
          
          
            $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
                        ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
                        ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
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
          
          
          
            return view('Painel.PesquisaPatrimonial.nucleo.concluirsolicitacao', compact('carbon','comarcas','correspondentes','saldototal','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes'));

          } else {
            Mail::to($email)
            ->send(new NovoUsuario($name, $email, $senha));

            $carbon= Carbon::now();

  $datas = DB::table('dbo.PesquisaPatrimonial_Matrix')
  ->leftjoin('dbo.users','dbo.PesquisaPatrimonial_Matrix.solicitante_id','=','dbo.users.id')
  ->leftjoin('dbo.PesquisaPatrimonial_Status', 'dbo.PesquisaPatrimonial_Matrix.status_id', '=', 'dbo.PesquisaPatrimonial_Status.id')
  ->leftjoin('dbo.PesquisaPatrimonial_Servicos', 'dbo.PesquisaPatrimonial_Matrix.tiposervico_id', '=', 'dbo.PesquisaPatrimonial_Servicos.id')
  ->leftjoin('PLCFULL.dbo.Jurid_CLiFor', 'dbo.PesquisaPatrimonial_Matrix.cliente_id', '=', 'PLCFULL.dbo.Jurid_CliFor.id_cliente')
  ->leftjoin('PLCFULL.dbo.Jurid_GrupoCliente', 'PLCFULL.dbo.Jurid_CliFor.GrupoCli', 'PLCFULL.dbo.Jurid_GrupoCliente.Codigo')
  ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_CliFor.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
  ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'dbo.PesquisaPatrimonial_Matrix.id_pasta', '=', 'PLCFULL.dbo.Jurid_Pastas.id_pasta')
  ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.PesquisaPatrimonial_Matrix.setor_id', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
  ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'dbo.PesquisaPatrimonial_Matrix.id', '=', 'PLCFULL.dbo.Jurid_ContaPr.Origem_cpr')
  ->leftjoin('PLCFULL.dbo.Jurid_Contratos', 'PLCFULL.dbo.Jurid_Pastas.Contrato', '=', 'PLCFULL.dbo.Jurid_Contratos.Numero')
  ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'PLCFULL.dbo.Jurid_ContaPr.Numdoc', '=', 'PLCFULL.dbo.Jurid_Debite.Fatura')
  ->select(
    'dbo.PesquisaPatrimonial_Matrix.id as ID',
    'dbo.PesquisaPatrimonial_Matrix.codigo as Codigo',
    'dbo.PesquisaPatrimonial_Matrix.nome as OutraParte',
    'dbo.users.id as SolicitanteID',
    'dbo.users.email as SolicitanteEmail',
    'dbo.users.cpf as SolicitanteCPF',
    'dbo.users.name as SolicitanteNome',
    'dbo.PesquisaPatrimonial_Status.descricao as Status',
    'dbo.PesquisaPatrimonial_Matrix.classificacao as TipoSolicitacao',
    'dbo.PesquisaPatrimonial_Servicos.descricao as TipoServico',
    'PLCFULL.dbo.Jurid_CliFor.UF as Estado',
    DB::raw('CAST(dbo.PesquisaPatrimonial_Matrix.valor AS NUMERIC(15,2)) as Valor'),
    'dbo.PesquisaPatrimonial_Matrix.data as DataSolicitacao',
    'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade',
    'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
    'PLCFULL.dbo.Jurid_Contratos.Numero as Contrato',
    'PLCFULL.dbo.Jurid_Contratos.Descricao as ContratoDescricao',
    'PLCFULL.dbo.Jurid_GrupoCliente.Descricao as GrupoCliente',
    'PLCFULL.dbo.Jurid_CliFor.Nome as ClienteRazao',
    'PLCFULL.dbo.Jurid_CliFor.Codigo as CodigoCliente',
    'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp as Pasta',
    'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
    'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
    'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
    'dbo.PesquisaPatrimonial_Matrix.observacao as Observacao',
    'PLCFULL.dbo.Jurid_ContaPr.Numdoc as CPR',
    'PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
    'PLCFULL.dbo.Jurid_ContaPr.Dt_baixa as DataPagamento')
  ->where('dbo.PesquisaPatrimonial_Matrix.id', '=', $id_matrix) 
  ->first();

  $solicitacoes = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
  ->select('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id as id',
            DB::raw('CAST(dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.valor AS NUMERIC(15,2)) as Valor'),
           'dbo.PesquisaPatrimonial_Servicos_UF.descricao as descricao')
  ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF','dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao','=','dbo.PesquisaPatrimonial_Servicos_UF.id')
  ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix','=', $id_matrix)
  ->get();

  $cpr = $datas->CPR;
  $cpr_ident = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc', '=', $cpr)->value('Cpr_ident'); 

   $saldoclienter = DB::table('PLCFULL.dbo.Jurid_Banco')
  ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
  ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente')
  ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
  ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
  ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
  ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
  ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
  ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));

  $saldoclientep = DB::table('PLCFULL.dbo.Jurid_Banco')
  ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
  ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'PLCFULL.dbo.Jurid_Banco.Codigo', '=', 'PLCFULL.dbo.Jurid_ContPrBx.Cliente')
  ->leftjoin('PLCFULL.dbo.Jurid_ContaPr', 'PLCFULL.dbo.Jurid_ContPrBx.origem_cpr', '=', 'PLCFULL.dbo.Jurid_ContaPr.Cpr_Ident')
  ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
  ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
  ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
  ->where('PLCFULL.dbo.Jurid_ContaPr.NumDoc', '=', $cpr)
  ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));


  $saldototal = $saldoclienter - $saldoclientep;

  $correspondentes = DB::table('dbo.users')
  ->select('dbo.users.id as id', 'dbo.users.name as name')
  ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
  ->where('dbo.profile_user.profile_id','=', '1')
  ->get();


  $comarcas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_Comarcas')
              ->leftjoin('dbo.PesquisaPatrimonial_Estados', 'dbo.PesquisaPatrimonial_Solicitacao_Comarcas.uf_id', '=', 'dbo.PesquisaPatrimonial_Estados.id_api')
              ->where('dbo.PesquisaPatrimonial_Solicitacao_Comarcas.id_matrix', '=', $id_matrix )
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



  return view('Painel.PesquisaPatrimonial.nucleo.concluirsolicitacao', compact('carbon','comarcas','correspondentes','saldototal','solicitacoes','datas','totalNotificacaoAbertas', 'notificacoes'));
        }
          
        
          }  
    }


    public function show($id)
    {
        //Recupera o usuário
       $user = $this->user->find($id);
       $carbon= Carbon::now();
       $datahoje = $carbon->format('Y-m-d');    

       $profile = DB::table('dbo.profiles')
       ->select('dbo.profiles.name')
       ->leftjoin('dbo.profile_user','dbo.profiles.id','=','dbo.profile_user.profile_id')
       ->where('dbo.profile_user.user_id','=', $id)
       ->value('dbo.profiles.name');
        
       $title = "Usuário: {$user->name}";
        
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
        
        return view('Painel.TI.users.show', compact('user', 'datahoje' ,'title', 'profile','totalNotificacaoAbertas', 'notificacoes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Recupera o usuário pelo id
        $user = $this->user->find($id);
        
        $title = "Editar Usuário: {$user->name}";
        
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

      $profiles =  DB::table('dbo.profiles')
                ->select('id', 'name')
                ->orderby('name', 'asc')
                ->get();
        
        return view('Painel.TI.users.create-edit', compact('user', 'title', 'profiles','totalNotificacaoAbertas', 'notificacoes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, $id)
    {
        //Pega todos os dados do usuário
                
        $email = $request->get('email');
        $name = $request->get('name');
        $password = bcrypt($request->get('password'));
        $senha = $request->get('password_confirmation');
        $cpf = $request->get('cpf');
        $nameImage = $id.'.png';
        $image = $request->file('image');
        //Verifica se existe a imagem
            
        $image->storeAs('users', $nameImage);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Recupera o usuário
        $user = $this->user->find($id);
        
        //deleta
        $delete = $user->delete();
        
        if( $delete ) {
            flash('Deletado com sucesso !')->success();
            
            return redirect()->route('usuarios.index');
        } else {
            return redirect()->route('usuarios.show', ['id' => $id])
                                        ->withErrors(['errors' => 'Falha ao deletar']);
        }
    }
    
    
    public function search(Request $request)
    {
        //Recupera os dados do formulário
        $dataForm = $request->except('_token');
        
        //Filtra os usuários
        $users = $this->user
                    ->where('name', 'LIKE', "%{$dataForm['key-search']}%")
                    ->orWhere('email', $dataForm['key-search'])
                    ->paginate($this->totalPage);
                    
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

        return view('Painel.TI.users.index', compact('users', 'dataForm', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showProfile()
    {
        //Recupera o usuário
        $user = auth()->user();
        
        $title = 'Meu Perfil';
        
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
       
        flash('Usuario encontrado com sucesso !')->success();
        
        return view('Painel.TI.users.profile', compact('user', 'title', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(UserFormRequest $request, $id)
    {
      //  $this->authorize('update_profile', $id);
        
        //Pega todos os dados do usuário
        $dataUser = $request->all();
        
        //Cria o objeto de usuário
        $user = $this->user->find($id);
        
        //Criptografa a senha
        $dataUser['password'] = bcrypt($dataUser['password']);
        
        //Remove o e-mail do usuário para não atualizar
        unset($dataUser['email']);
        
        //Verifica se existe a imagem
        if( $request->hasFile('image') ) {
            //Pega a imagem
            $image = $request->file('image');
            
            //Verifica se o nome da imagem não existe
            if( $user->image == '' ){
                $nameImage = uniqid(date('YmdHis')).'.'.$image->getClientOriginalExtension();
                $dataUser['image'] = $nameImage;
            }else {
                $nameImage = $user->image;
                $dataUser['image'] = $user->image;
            }
            
            $upload = $image->storeAs('users', $nameImage);
            
            if( !$upload ) 
                return redirect()->route('profile')
                                            ->withErrors(['errors' => 'Erro no Upload'])
                                            ->withInput();
        }
        
        
        //Altera os dados do usuário
        $update = $user->update($dataUser);
        
        if( $update ) {
            
            flash('Atualizado com sucesso !')->success();
            
            return redirect()
                        ->route('rofilep')
                        ->with(['success' => 'Perfil atualizado com sucesso']);
    }else
            return redirect()->route('profile')
                                        ->withErrors(['errors' => 'Falha ao atualizar o perfil.'])
                                        ->withInput();
    }

    public function usuarios_importacaomassa(Request $request) {

      $carbon= Carbon::now();

      $path = $request->file('select_file')->getRealPath();
      $data = Excel::load($path)->get();

      if(count($data) > 0){
                $count = 0;
                foreach($data->toArray() as $key => $value){

                    $cpfsemmascara = $value['cpf'];
                    $cpf = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $cpfsemmascara)));
                    $nome = $value['advogado'];
                    $ultimos4caracteres = substr($cpf,-4);
                    $profile_id = $value['nivel'];                 
                    $senha = "plc@".$ultimos4caracteres;

                    $password = bcrypt($senha);

                    $email = $value['email'];
                            
                    //Grava na tabela
                    $value1 = array(
                        'name' => $nome, 
                        'email' => $email, 
                        'password' => $password, 
                        'cpf' => $cpf);
                    DB::table('dbo.users')->insert($value1);

                    $user_id = DB::table('dbo.users')->select('id')->where('cpf','=', $cpf)->orderBy('id', 'desc')->value('id');

                    //Gravo o nível do usuario
                    $values = array(
                    'profile_id' => $profile_id, 
                    'user_id' => $user_id);
                    DB::table('dbo.profile_user')->insert($values); 

                   Mail::to($email)
                  ->send(new NovoUsuario($nome, $email, $senha));

                  $count++;
                }

        }
        

        flash('Importação realizada com sucesso !')->success();
        return redirect()->route('Painel.TI.users.index');

        

    }

    public function salvarnewusuario(Request $request){

      $name = $request->name;
      $emailusuario = $request->emailusuario;
      $senha = bcrypt($request->senha);
      $senha_confirma = $request->senha_confirma;
      $cpf_cnpj = $request->cpf_cnpj;
      $newcpfcnpj = preg_replace('/[^0-9]/', '', $cpf_cnpj);
      $simenao = $request->simenao;
      $nivelusuario = $request->nivelusuario;

      $insert = $values = array(
          'name' => $name, 
          'email' => $emailusuario,
          'password' => $senha,
          'cpf' => $newcpfcnpj);
      DB::table('dbo.users')->insert($values); 

      $userid = DB::table('dbo.users')->select('id')->where('email', '=', $emailusuario)->value('id'); 

       $values = array(
        'profile_id' => $nivelusuario, 
        'user_id' => $userid);
        DB::table('dbo.profile_user')->insert($values); 

    if($simenao == "Sim") {

      if($nivelusuario == 1) {
          Mail::to($emailusuario)
          ->send(new NovoUsuarioCorrespondente($name, $emailusuario, $senha_confirma));
          return redirect()->route('usuarios.index');    
      }

      Mail::to($emailusuario)
      ->send(new NovoUsuario($name, $emailusuario, $senha_confirma));
      return redirect()->route('usuarios.index');    

      } else {
        Mail::to($emailusuario)
        ->send(new NovoUsuario($name, $emailusuario, $senha_confirma));
        return redirect()->route('usuarios.index');    
      }
    
    }

}