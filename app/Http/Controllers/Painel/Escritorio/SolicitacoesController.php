<?php

namespace App\Http\Controllers\Painel\Escritorio;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\Escritorio\Solicitacoes\NovaSolicitacao;
use App\Mail\Escritorio\Solicitacoes\GerenteFinanceiroRevisar;
use App\Mail\Escritorio\Solicitacoes\AdministrativoFinalizar;
use App\Models\Correspondente;


class SolicitacoesController extends Controller
{

    protected $model;
    protected $totalPage = 10;   
    public $timestamps = false;
    

    public function advogado_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
          
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->where('user_id', Auth::user()->id)
        ->whereIn('dbo.Escritorio_Solicitacoes_Matrix.status_id', array(1,2,3))
        ->get();

       $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
       ->select('Codigo', 'Descricao')
       ->leftjoin('dbo.setor_custo_user', 'PLCFULL.dbo.Jurid_Setor.Id', '=', 'dbo.setor_custo_user.setor_custo_id')  
       ->where('Ativo', '=', '1')
       ->where('setor_custo_user.user_id', '=', Auth::user()->id)
       ->orderBy('Codigo', 'asc')
       ->get();

       $areas = DB::table('dbo.Escritorio_Solicitacoes_Area')
       ->where('status', '=', 'A')
       ->orderBy('descricao', 'asc')
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

       
      return view('Painel.Escritorio.Solicitacoes.Advogado.index', compact('setores','areas','datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function advogado_historico() {
        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
          
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->where('user_id', Auth::user()->id)
        ->whereIn('dbo.Escritorio_Solicitacoes_Matrix.status_id', array(4,5))
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

       
      return view('Painel.Escritorio.Solicitacoes.Advogado.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function advogado_solicitacaocriada(Request $request) {


        $observacao = $request->get('observacao');
        $carbon= Carbon::now();


        //Grava na Matrix
        $values = array('user_id' => Auth::user()->id, 
                        'observacao' => $observacao,
                        'status_id' => '1',
                        'data_solicitacao' => $carbon,
                        'data_edicao' => $carbon);
        DB::table('dbo.Escritorio_Solicitacoes_Matrix')->insert($values);

        $id = DB::table('dbo.Escritorio_Solicitacoes_Matrix')->select('id')->where('user_id',Auth::user()->id)->orderby('id','desc')->value('id'); 

        //Grava na Hist 
        $values = array('id_matrix' => $id, 
                        'user_id' => Auth::user()->id, 
                        'status_id' => '1',
                        'data' => $carbon);
        DB::table('dbo.Escritorio_Solicitacoes_Hist')->insert($values);

        //Foreach
        $setor = $request->get('setor');
        $area_id = $request->get('destino_selected');
        $produto_id = $request->get('produtos');
        $quantidade = $request->get('quantidade');
    
        $data = array();
        foreach($setor as $index => $setor) {
        
            $values = array(
              'id_matrix' => $id, 
              'setor' => $setor,
              'id_produto' => $produto_id[$index], 
              'quantidade' => $quantidade[$index]);
            DB::table('dbo.Escritorio_Solicitacoes_Carrinho')->insert($values);  

            $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $setor)->value('Id'); 

            //Envia e-mail ao Coordenador e SubCoordenador do setor
            $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
            ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                     'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                     'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                     'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
            ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
            ->where('dbo.Escritorio_Solicitacoes_Matrix.id', $id)
            ->get();
    
            //Pega o Coordenador
            $coordenador =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '35')
            ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
            ->first();    
    
            $coordenador_id = $coordenador->id;
            $coordenador_email = $coordenador->email;
    
            //Pega o SubCoordenador
            $subcoordenador =  DB::table('dbo.users')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
            ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '36')
            ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
            ->first();    
    
            $subcoordenador_id = $subcoordenador->id;
            $subcoordenador_email = $subcoordenador->email;
    
            //Envia e-mail
            Mail::to($coordenador_email)
            ->cc($subcoordenador_email)
            ->send(new NovaSolicitacao($datas));
    
            //Envia notificação
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $coordenador_id, 'tipo' => '13', 'obs' => 'Solicitação de compra: Nova solicitação cadastrada.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);
    
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $subcoordenador_id, 'tipo' => '13', 'obs' => 'Solicitação de compra: Nova solicitação cadastrada.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);


        }
        //Fim Foreach

    
        flash('Nova solicitação cadastrada com sucesso!')->success();
        return redirect()->route('Painel.Escritorio.Solicitacoes.Advogado.index');

    }

    public function advogado_solicitacaocancelada(Request $request) {

        $id = $request->get('id');
        $carbon= Carbon::now();

        //Update Matrix 
        DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '5','data_edicao' => $carbon));

        //Grava na Hist 
        $values = array('id_matrix' => $id, 
                        'user_id' => Auth::user()->id, 
                        'status_id' => '5',
                        'data' => $carbon);
        DB::table('dbo.Escritorio_Solicitacoes_Hist')->insert($values);

        flash('Solicitação cancelada com sucesso!')->success();
        return redirect()->route('Painel.Escritorio.Solicitacoes.Advogado.index');
    }

    public function administrativo_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
          
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->whereIn('dbo.Escritorio_Solicitacoes_Matrix.status_id', array(1,2,3))
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

       
      return view('Painel.Escritorio.Solicitacoes.Administrativo.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }
    
    public function administrativo_historico() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
          
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->whereIn('dbo.Escritorio_Solicitacoes_Matrix.status_id', array(4,5))
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

       
      return view('Painel.Escritorio.Solicitacoes.Administrativo.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));
    }

    public function administrativo_formulario($id) {

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

        $solicitante = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.users.name as UsuarioNome',
                 'dbo.users.email as UsuarioEmail',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'dbo.Escritorio_Solicitacoes_Matrix.observacao as Observacao')
        ->leftjoin('dbo.users','dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('dbo.Escritorio_Solicitacoes_Matrix.id', $id)
        ->first();

        // $fornecedores = DB::table('dbo.Escritorio_Solicitacoes_Fornecedores')
        // ->select('dbo.Escritorio_Solicitacoes_Fornecedores.id',
        //          'dbo.Escritorio_Solicitacoes_Fornecedores.descricao',
        //          'dbo.Escritorio_Solicitacoes_Fornecedores.produto_id')
        // ->where('dbo.Escritorio_Solicitacoes_Fornecedores.status', 'Ativo')
        // ->orderBy('dbo.Escritorio_Solicitacoes_Fornecedores.quantidade_compras', 'desc')
        // ->orderBy('dbo.Escritorio_Solicitacoes_Fornecedores.data_ultimacompra', 'desc')
        // ->get();

        $datas = DB::table('dbo.Escritorio_Solicitacoes_Carrinho')
        ->select('dbo.Escritorio_Solicitacoes_Carrinho.id',
                  'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'dbo.Escritorio_Solicitacoes_Produtos.id as ProdutoId',
                 'dbo.Escritorio_Solicitacoes_Produtos.descricao as ProdutoDescricao',
                 'dbo.Escritorio_Solicitacoes_Area.descricao as AreaDescricao',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'dbo.Escritorio_Solicitacoes_Carrinho.quantidade as ProdutoQuantidade')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Produtos','dbo.Escritorio_Solicitacoes_Carrinho.id_produto', 'dbo.Escritorio_Solicitacoes_Produtos.id')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Area','dbo.Escritorio_Solicitacoes_Produtos.area_id', 'dbo.Escritorio_Solicitacoes_Area.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Escritorio_Solicitacoes_Carrinho.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->where('dbo.Escritorio_Solicitacoes_Carrinho.id_matrix', $id)
        ->get();

        return view('Painel.Escritorio.Solicitacoes.Administrativo.formulario', compact('id','solicitante','notificacoes', 'totalNotificacaoAbertas', 'datas'));

    }

    public function administrativo_formulariopreenchido(Request $request) {


        $id = $request->get('id');
        $solicitante_email = $request->get('solicitante_email');
        $solicitante_nome = $request->get('solicitante_nome');
        $carbon= Carbon::now();

        //Update Matrix 
        DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '2','data_edicao' => $carbon));

        //Grava na Hist 
        $values = array('id_matrix' => $id, 
                        'user_id' => Auth::user()->id, 
                        'status_id' => '2',
                        'data' => $carbon);
        DB::table('dbo.Escritorio_Solicitacoes_Hist')->insert($values);

        $produto_id = $request->get('produto_id');
        $produto_setor = $request->get('produto_setor');
        $fornecedor_nome = $request->get('fornecedor_nome');
        $fornecedor_cpf_cnpj = $request->get('fornecedor_cpf_cnpj');
        $fornecedor_contato = $request->get('fornecedor_contato');
        $fornecedor_prazo = $request->get('fornecedor_prazo');
        $fornecedor_formapagamento = $request->get('fornecedor_formapagamento');
        $fornecedor_valornutario =  str_replace (',', '.', str_replace ('.', '', $request->get('fornecedor_valornutario')));
        $fornecedor_parcelas = $request->get('fornecedor_parcelas');
        $fornecedor_valorfrete =  str_replace (',', '.', str_replace ('.', '', $request->get('fornecedor_valorfrete')));
        $fornecedor_valortotal =  str_replace (',', '.', str_replace ('.', '', $request->get('fornecedor_valortotal')));
        $anexo = $request->file('select_file');

        
        foreach($produto_id as $indexproduto => $produto_id) {

        //Foreach
        foreach($fornecedor_cpf_cnpj as $index => $fornecedor_cpf_cnpj) {

              //Verifica se o Fornecedor já está cadastrado, se estiver da update se não da INSERT
              $verificacnpj = DB::table('dbo.Escritorio_Solicitacoes_Fornecedores')->select('id')->where('codigo', $fornecedor_cpf_cnpj)->where('produto_id', $produto_id)->value('id');

                if($verificacnpj == null) {

                    $values = array(
                    'descricao' => $fornecedor_nome[$index], 
                    'codigo' => $fornecedor_cpf_cnpj, 
                    'contato' => $fornecedor_contato[$index],
                    'data_cadastro' => $carbon,
                    'quantidade_compras' => '0',
                    'produto_id' => $produto_id,
                    'prazo' => $fornecedor_prazo[$index],
                    'formapagamento' => $fornecedor_formapagamento[$index],
                    'valor_unitario' => $fornecedor_valornutario[$index],
                    'parcelas' => $fornecedor_parcelas[$index],
                    'valor_frete' => $fornecedor_valorfrete[$index],
                    'valor_total' => $fornecedor_valortotal[$index],
                    'status' => 'Ativo');
                    DB::table('dbo.Escritorio_Solicitacoes_Fornecedores')->insert($values);

                    $id = DB::table('dbo.Escritorio_Solicitacoes_Fornecedores')->select('id')->orderby('id','desc')->value('id'); 

                    //Update no carrinho informando o fornecedor_id
                    DB::table('dbo.Escritorio_Solicitacoes_Carrinho')
                    ->where('id', $produto_id)  
                    ->limit(1) 
                    ->update(array('fornecedor_id' => $verificacnpj,'status' => 'Aguardando revisão'));

                } else {

                    DB::table('dbo.Escritorio_Solicitacoes_Fornecedores')
                    ->where('id', $produto_id)  
                    ->limit(1) 
                    ->update(array(
                        'contato' => $fornecedor_contato[$index],
                        'prazo' => $fornecedor_prazo[$index],
                        'formapagamento' => $fornecedor_formapagamento[$index],
                        'valor_unitario' => $fornecedor_valornutario[$index],
                        'parcelas' => $fornecedor_parcelas[$index],
                        'valor_frete' => $fornecedor_valorfrete[$index],
                        'valor_total' => $fornecedor_valortotal[$index],
                        'status' => 'Ativo'));


                    //Update no carrinho informando o fornecedor_id
                    DB::table('dbo.Escritorio_Solicitacoes_Carrinho')
                    ->where('id', $produto_id)  
                    ->limit(1) 
                    ->update(array('fornecedor_id' => $verificacnpj,'status' => 'Aguardando revisão'));     
                }

                //Grava o anexo
                $values = array(
                    'id_matrix' => $id, 
                    'user_id' => Auth::user()->id, 
                    'data' => $carbon,
                    'name' => $anexo[$index]->getClientOriginalName());
                    DB::table('dbo.Escritorio_Solicitacoes_Anexo')->insert($values);

                  //Renomeia o anexo 
                  $codigo = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $fornecedor_cpf_cnpj)));
                  $newname = $codigo.'_'.$carbon->format('YmdHis');
                  Storage::disk('solicitacoes-local')->put($newname, fopen($anexo[$index], 'r+'));

        }

    }
        //Fim Foreach


        //Manda e-mail e notificação para o gerente financeiro, gerente setor e solicitante
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.Escritorio_Solicitacoes_Matrix.id', $id)
        ->get();

        $setorid = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', $produto_setor[$indexproduto])->value('Id'); 
        $solicitante_id = DB::table('dbo.Escritorio_Solicitacoes_Matrix')->select('user_id')->where('id', $id)->value('user_id'); 

        $gerente =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')  
        ->select('dbo.users.id', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '23')
        ->where('dbo.setor_custo_user.setor_custo_id', $setorid)
        ->first();  

        
        $gerente_id = $gerente->id;
        $gerente_email = $gerente->email;

        Mail::to($gerente_email)
        ->cc($solicitante_email, 'ronaldo.amaral@plcadvogados.com.br')
        ->send(new GerenteFinanceiroRevisar($datas));


        //Notificação para o Gerente Financeiro (Douglas)
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '234', 'tipo' => '13', 'obs' => 'Solicitação de compra: Aguardando revisão do financeiro.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        //Notificação para o Gerente do setor
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $gerente_id, 'tipo' => '13', 'obs' => 'Solicitação de compra: Aguardando revisão do financeiro.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        //Notificação para o solicitante
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '13', 'obs' => 'Solicitação de compra: Aguardando revisão do financeiro.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        
        flash('Formulário preenchido com sucesso !')->success();    

        return redirect()->route('Painel.Escritorio.Solicitacoes.Administrativo.index');
    }

    public function administrativo_finalizarsolicitacao($id) {

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

        $solicitante = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.users.name as UsuarioNome',
                 'dbo.users.email as UsuarioEmail',
                 'dbo.users.cpf as UsuarioCPF',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'dbo.Escritorio_Solicitacoes_Matrix.observacao as Observacao')
        ->leftjoin('dbo.users','dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('dbo.Escritorio_Solicitacoes_Matrix.id', $id)
        ->first();

        $datas = DB::table('dbo.Escritorio_Solicitacoes_Carrinho')
        ->select('dbo.Escritorio_Solicitacoes_Carrinho.id',
                  'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'dbo.Escritorio_Solicitacoes_Produtos.descricao as ProdutoDescricao',
                 'dbo.Escritorio_Solicitacoes_Area.descricao as AreaDescricao',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'dbo.Escritorio_Solicitacoes_Carrinho.id_produto as ProdutoID',
                 'dbo.Escritorio_Solicitacoes_Carrinho.quantidade as ProdutoQuantidade',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.id as FornecedorID',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.descricao as FornecedorNome',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.codigo as FornecedorCodigo',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.contato as FornecedorContato',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.quantidade_compras as QuantidadeCompras',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.data_ultimacompra as DataUltimaCompra',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.prazo as FornecedorPrazo',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.formapagamento as formapagamento',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.valor_unitario as ValorUnitario',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.parcelas as Parcelas',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.valor_frete as ValorFrete',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.valor_total as ValorTotal')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Produtos','dbo.Escritorio_Solicitacoes_Carrinho.id_produto', 'dbo.Escritorio_Solicitacoes_Produtos.id')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Area','dbo.Escritorio_Solicitacoes_Produtos.area_id', 'dbo.Escritorio_Solicitacoes_Area.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Escritorio_Solicitacoes_Carrinho.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Fornecedores', 'dbo.Escritorio_Solicitacoes_Carrinho.fornecedor_id', 'dbo.Escritorio_Solicitacoes_Fornecedores.id')
        ->where('dbo.Escritorio_Solicitacoes_Carrinho.id_matrix', $id)
        ->get();


        return view('Painel.Escritorio.Solicitacoes.Administrativo.finalizarsolicitacao', compact('id','solicitante','notificacoes', 'totalNotificacaoAbertas', 'datas'));

    }

    public function administrativo_solicitacaofinalizada(Request $request) {

        $id = $request->get('id');
        $carbon= Carbon::now();
        $dataehora = $carbon->format('Y-m-d');
        $produto_id = $request->get('produto_id');
        $produto_setor = $request->get('produto_setor');
        $fornecedor_cpf_cnpj = $request->get('fornecedor_cpf_cnpj');
        $fornecedor_formapagamento = $request->get('fornecedor_formapagamento');
        $anexo_boleto = $request->file('anexo_boleto');
        $anexo_transferencia = $request->file('anexo_transferencia');
        $fornecedor_valornutario =  str_replace (',', '.', str_replace ('.', '', $request->get('fornecedor_valornutario')));
        $fornecedor_parcelas = $request->get('fornecedor_parcelas');
        $fornecedor_valortotal =  str_replace (',', '.', str_replace ('.', '', $request->get('fornecedor_valortotal')));
        $solicitante_cpf = $request->get('solicitante_cpf');
        $observacoes = $request->get('observacoes');
        $unidade = $request->get('unidade');

         //Update na Matrix
         DB::table('dbo.Escritorio_Solicitacoes_Matrix')
         ->where('id', $id)  
         ->limit(1) 
         ->update(array('status_id' => '4','data_edicao' => $carbon));

         //Grava na Hist
         $values = array('id_matrix' => $id, 
         'user_id' => Auth::user()->id, 
         'status_id' => '4',
         'data' => $carbon);
         DB::table('dbo.Escritorio_Solicitacoes_Hist')->insert($values);

         //Foreach
         foreach($produto_id as $indexproduto => $produto_id) {

            //Foreach
            foreach($fornecedor_cpf_cnpj as $index => $fornecedor_cpf_cnpj) {

                $ultimonumero = Correspondente::orderBy('Numero', 'desc')->value('Numero'); // gets only the id
                $numero = $ultimonumero + 1;

                //Se a forma de pagamento for boleto
                if($fornecedor_formapagamento[$index] == "Boleto") {

                    //Gera o Debite
                    $values = array(
                    'Numero' => $numero, 
                    'Advogado' => $solicitante_cpf, 
                    'Cliente' => '', 
                    'Data' => $dataehora,
                    'Tipo' => 'D',
                    'Obs' => $observacoes,
                    'Status' => '3',
                    'Hist' => 'Solicitação de compra inserida pelo(a): '. Auth::user()->name .' no módulo de compras - '. $carbon->format('d-m-Y H:i:s') . ' com a forma de pagamento escolhida: Boleto.',
                    'ValorT' => $fornecedor_valortotal[$index],
                    'Usuario' => 'portal.plc',
                    'DebPago' => 'N',
                    'TipoDeb' => '028',
                    'AdvServ' => $solicitante_cpf,
                    'Setor' => $produto_setor[$indexproduto],
                    'Pasta' => '',
                    'Unidade' => $unidade,
                    'Valor_Adv' => $fornecedor_valortotal[$index],
                    'Quantidade' => '1',
                    'ValorUnitario_Adv' => $fornecedor_valornutario[$index],
                    'ValorUnitarioCliente' => $fornecedor_valornutario[$index],
                    'Revisado_DB' => '0',
                    'moeda' => 'R$');
                    DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values);  


                    //Grava o anexo
                    $values = array(
                    'id_matrix' => $id, 
                    'user_id' => Auth::user()->id, 
                    'data' => $carbon,
                    'name' => $anexo_boleto[$index]->getClientOriginalName());
                    DB::table('dbo.Escritorio_Solicitacoes_Anexo')->insert($values);

                    //Grava na GED o comprovante
                    $anexo_boleto[$index]->storeAs('solicitacoescompra', $anexo_boleto[$index]);

                    $values = array(
                        'Tabela_OR' => 'Debite',
                        'Codigo_OR' => $numero,
                        'Id_OR' => $numero,
                        'Descricao' => $anexo_boleto[$index]->getClientOriginalName(),
                        'Link' => '\\\192.168.1.65\advwin\portal\portal\solicitacoescompra/' . $anexo_boleto[$index]->getClientOriginalName(), 
                        'Data' => $carbon,
                        'Nome' => $anexo_boleto[$index]->getClientOriginalName(),
                        'Responsavel' => 'portal.plc',
                        'Arq_tipo' => $anexo_boleto[$index]->getClientOriginalExtension(),
                        'Arq_Versao' => '1',
                        'Arq_Status' => 'Guardado',
                        'Arq_usuario' => 'portal.plc',
                        'Arq_nick' => $anexo_boleto[$index]->getClientOriginalName(),
                        'Obs' => $observacoes);
                        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);   
               

                }
                
                //Se a forma de pagamento for cartão
                elseif($fornecedor_formapagamento[$index] == "Cartão") {


                    $valor = $fornecedor_valortotal[$index] / $fornecedor_parcelas[$index];
            

                    //Gera o debite em loop conforme a quantidade de parcelas
                    for ($i = 0; $i < $fornecedor_parcelas[$index]; $i++) {
                        
                        //Gera o debite
                        $values = array(
                            'Numero' => $numero, 
                            'Advogado' => $solicitante_cpf, 
                            'Cliente' => '', 
                            'Data' => $dataehora,
                            'Tipo' => 'D',
                            'Obs' => $observacoes,
                            'Status' => '3',
                            'Hist' => 'Solicitação de compra inserida pelo(a): '. Auth::user()->name .' no módulo de compras - '. $carbon->format('d-m-Y H:i:s') . ' com a forma de pagamento escolhida: Cartão com a quantidade de: ' . $fornecedor_parcelas[$index] . ' parcelas.',
                            'ValorT' => $valor[$index],
                            'Usuario' => 'portal.plc',
                            'DebPago' => 'N',
                            'TipoDeb' => '028',
                            'AdvServ' => $solicitante_cpf,
                            'Setor' => $produto_setor[$indexproduto],
                            'Pasta' => '',
                            'Unidade' => $unidade,
                            'Valor_Adv' => $valor,
                            'Quantidade' => '1',
                            'ValorUnitario_Adv' => $valor,
                            'ValorUnitarioCliente' => $valor,
                            'Revisado_DB' => '0',
                            'moeda' => 'R$');
                            DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values);  

                    $numero = $numero + 1;        
                    }

                } 
                //Se a forma de pagamento for transferencia
                else {

                     //Gera o Debite
                     $values = array(
                        'Numero' => $numero, 
                        'Advogado' => $solicitante_cpf, 
                        'Cliente' => '', 
                        'Data' => $dataehora,
                        'Tipo' => 'D',
                        'Obs' => $observacoes,
                        'Status' => '3',
                        'Hist' => 'Solicitação de compra inserida pelo(a): '. Auth::user()->name .' no módulo de compras - '. $carbon->format('d-m-Y H:i:s') . ' com a forma de pagamento escolhida: Transferência.',
                        'ValorT' => $fornecedor_valortotal[$index],
                        'Usuario' => 'portal.plc',
                        'DebPago' => 'N',
                        'TipoDeb' => '028',
                        'AdvServ' => $solicitante_cpf,
                        'Setor' => $produto_setor[$indexproduto],
                        'Pasta' => '',
                        'Unidade' => $unidade,
                        'Valor_Adv' => $fornecedor_valortotal[$index],
                        'Quantidade' => '1',
                        'ValorUnitario_Adv' => $fornecedor_valornutario[$index],
                        'ValorUnitarioCliente' => $fornecedor_valornutario[$index],
                        'Revisado_DB' => '0',
                        'moeda' => 'R$');
                        DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values);  

                        //Grava o anexo
                        $values = array(
                        'id_matrix' => $id, 
                        'user_id' => Auth::user()->id, 
                        'data' => $carbon,
                        'name' => $anexo_transferencia[$index]->getClientOriginalName());
                        DB::table('dbo.Escritorio_Solicitacoes_Anexo')->insert($values);
    
                        //Grava na GED o comprovante
                        $anexo_transferencia[$index]->storeAs('solicitacoescompra', $anexo_transferencia[$index]);
    
                        $values = array(
                            'Tabela_OR' => 'Debite',
                            'Codigo_OR' => $numero,
                            'Id_OR' => $numero,
                            'Descricao' => $anexo_transferencia[$index]->getClientOriginalName(),
                            'Link' => '\\\192.168.1.65\advwin\portal\portal\solicitacoescompra/' . $anexo_transferencia[$index]->getClientOriginalName(), 
                            'Data' => $carbon,
                            'Nome' => $anexo_transferencia[$index]->getClientOriginalName(),
                            'Responsavel' => 'portal.plc',
                            'Arq_tipo' => $anexo_transferencia[$index]->getClientOriginalExtension(),
                            'Arq_Versao' => '1',
                            'Arq_Status' => 'Guardado',
                            'Arq_usuario' => 'portal.plc',
                            'Arq_nick' => $anexo_transferencia[$index]->getClientOriginalName(),
                            'Obs' => $observacoes);
                        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);   

                    

                }


                
            }
         
        
        }





         //Fim Foreach


         //Envia notificação e e-mail ao solicitante
         flash('Solicitação finalizada com sucesso !')->success();    

         return redirect()->route('Painel.Escritorio.Solicitacoes.Administrativo.index');
    }

    public function buscaProdutos(Request $request) {

        $id = $request->get('id_destino');

        $produtos_ti = DB::table('dbo.Escritorio_Solicitacoes_Produtos')->where('area_id', '=', $id)->get();
        foreach($produtos_ti as $index) {  
            $response = '<option style="font-size:10px;" value="'.$index->id.'">'.$index->descricao.'</option>';
            echo $response;
        }
    }

    public function administrativo_buscafornecedores(Request $request) {


        //Pego os 3 principais fornecedores(Melhores valores, quantidade de compras) por produto
        $response = DB::table('dbo.Escritorio_Solicitacoes_Fornecedores')
                       ->get();

        echo $response;

    }

    public function financeiro_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
          
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->whereIn('dbo.Escritorio_Solicitacoes_Matrix.status_id', array(2,3))
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

       
      return view('Painel.Escritorio.Solicitacoes.Financeiro.index', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_historico() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
          
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->whereIn('dbo.Escritorio_Solicitacoes_Matrix.status_id', array(4,5))
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

       
      return view('Painel.Escritorio.Solicitacoes.Financeiro.historico', compact('datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_formulario($id) {

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

        $solicitante = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.users.name as UsuarioNome',
                 'dbo.users.email as UsuarioEmail',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                 'dbo.Escritorio_Solicitacoes_Matrix.observacao as Observacao')
        ->leftjoin('dbo.users','dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('dbo.Escritorio_Solicitacoes_Matrix.id', $id)
        ->first();

        $datas = DB::table('dbo.Escritorio_Solicitacoes_Carrinho')
        ->select('dbo.Escritorio_Solicitacoes_Carrinho.id',
                  'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'dbo.Escritorio_Solicitacoes_Produtos.descricao as ProdutoDescricao',
                 'dbo.Escritorio_Solicitacoes_Area.descricao as AreaDescricao',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                 'dbo.Escritorio_Solicitacoes_Carrinho.id_produto as ProdutoID',
                 'dbo.Escritorio_Solicitacoes_Carrinho.quantidade as ProdutoQuantidade',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.id as FornecedorID',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.descricao as FornecedorNome',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.codigo as FornecedorCodigo',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.contato as FornecedorContato',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.quantidade_compras as QuantidadeCompras',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.data_ultimacompra as DataUltimaCompra',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.prazo as FornecedorPrazo',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.formapagamento as formapagamento',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.valor_unitario as ValorUnitario',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.parcelas as Parcelas',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.valor_frete as ValorFrete',
                 'dbo.Escritorio_Solicitacoes_Fornecedores.valor_total as ValorTotal')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Produtos','dbo.Escritorio_Solicitacoes_Carrinho.id_produto', 'dbo.Escritorio_Solicitacoes_Produtos.id')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Area','dbo.Escritorio_Solicitacoes_Produtos.area_id', 'dbo.Escritorio_Solicitacoes_Area.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Escritorio_Solicitacoes_Carrinho.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Fornecedores', 'dbo.Escritorio_Solicitacoes_Carrinho.id_produto', 'dbo.Escritorio_Solicitacoes_Fornecedores.produto_id')
        ->where('dbo.Escritorio_Solicitacoes_Carrinho.id_matrix', $id)
        ->get();


        return view('Painel.Escritorio.Solicitacoes.Financeiro.formulario', compact('id','solicitante','notificacoes', 'totalNotificacaoAbertas', 'datas'));

    }

    public function financeiro_formulariorevisado(Request $request) {


        $id = $request->get('id');
        $acao = $request->get('acao');
        $solicitante_nome = $request->get('solicitante_nome');
        $solicitante_email = $request->get('solicitante_email');
        $observacao = $request->get('observacao');
        $carbon= Carbon::now();

        if($acao == null) {
        //Se foi aprovado

           //Update Matrix 
           DB::table('dbo.Escritorio_Solicitacoes_Matrix')
           ->where('id', $id)  
           ->limit(1) 
           ->update(array('status_id' => '3','data_edicao' => $carbon));

           //Grava na Hist
           $values = array('id_matrix' => $id, 
           'user_id' => Auth::user()->id, 
           'status_id' => '3',
           'data' => $carbon);
           DB::table('dbo.Escritorio_Solicitacoes_Hist')->insert($values);

           //Pega o fornecedores escolhidos
           $produto_id = $request->get('produto_id');
           $fornecedorescolhido = $request->get('fornecedorescolhido');



           //Envia e-mail e notificação ao Administrativo solicitando que coloque os dados de pagamento/comprovante
            //Foreach
            foreach($fornecedorescolhido as $indexfornecedor => $fornecedorescolhido) {

                foreach($produto_id as $index => $produto_id) {


                DB::table('dbo.Escritorio_Solicitacoes_Carrinho')
                ->where('id_matrix', $id)
                ->where('id_produto', $produto_id)
                ->limit(1) 
                ->update(array('fornecedor_id' => $fornecedorescolhido,'status' => 'Aprovado'));

           }
           }
           //Fim Foreach

        //Manda e-mail e notificação para o Administrativo informando que deve finalizar a solicitação
        $datas = DB::table('dbo.Escritorio_Solicitacoes_Matrix')
        ->select('dbo.Escritorio_Solicitacoes_Matrix.id as Id',
                 'dbo.users.name as UsuarioNome',
                 'dbo.Escritorio_Solicitacoes_Status.id as StatusID',
                 'dbo.Escritorio_Solicitacoes_Status.descricao as Status',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.Escritorio_Solicitacoes_Matrix.data_edicao as DataModificacao')
        ->leftjoin('dbo.Escritorio_Solicitacoes_Status','dbo.Escritorio_Solicitacoes_Matrix.status_id', 'dbo.Escritorio_Solicitacoes_Status.id')
        ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Matrix.user_id', 'dbo.users.id')
        ->where('dbo.Escritorio_Solicitacoes_Matrix.id', $id)
        ->get();

        $solicitante_id = DB::table('dbo.Escritorio_Solicitacoes_Matrix')->select('user_id')->where('id', $id)->value('user_id');

        Mail::to('ronaldo.amaral@plcadvogados.com.br')
        ->cc($solicitante_email)
        ->send(new AdministrativoFinalizar($datas));

        //Notificação para o solicitante
        $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '13', 'obs' => 'Solicitação de compra: Compra aprovada pelo financeiro.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);



        } else {
        //Se foi reprovado

          //Update Matrix 

          //Grava na Hist

          //Envia e-mail e notificação ao Administrativo solicitando que coloque novos fornecedores/valores
        }
        
        flash('Formulário revisado com sucesso !')->success();    

        return redirect()->route('Painel.Escritorio.Solicitacoes.Financeiro.index');

    }


        public function anexos($id) {

            //Busco os arquivos gravados na GED
            $datas = DB::table("dbo.Escritorio_Solicitacoes_Anexo")
            ->select('dbo.users.name as Responsavel', 'dbo.Escritorio_Solicitacoes_Anexo.name as Nome', 'dbo.Escritorio_Solicitacoes_Anexo.data as Data')
            ->leftjoin('dbo.users', 'dbo.Escritorio_Solicitacoes_Anexo.user_id', 'dbo.users.id')
            ->where('id_matrix', $id)
            ->get();

            $QuantidadeAnexos = $datas->count();

            return view('Painel.Escritorio.Solicitacoes.anexos', compact('datas', 'QuantidadeAnexos')); 
        }

        public function baixaranexo($caminho) {

            return Storage::disk('solicitacoes-local')->download($caminho);

        }

  


  
 
}
