<?php

namespace App\Http\Controllers\Painel\Compras;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\Compras\Solicitante\SolicitanteEnvio;
use App\Mail\Compras\ComiteCompras\ComiteComprasEnvio;
use App\Mail\Compras\ComiteAprovacao\SolicitacaoReprovada;
use App\Mail\Compras\ComiteAprovacao\solicitacaoAprovada;
use App\Mail\Compras\ComiteAprovacao\solicitacaoAprovadaPorUm;


class ComprasController extends Controller
{

    public function index_solicitante(){

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

        $user_id = Auth::user()->id;

        $dados = DB::table('dbo.Solicitacao_Compra')
        ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.unidade', 
        'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
        'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'PLCFULL.dbo.Jurid_Setor.descricao', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
        ->where('user_id', '=', $user_id)   
        ->orderby('id', 'asc')   
        ->get(); 

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $unidadeSolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as cpf', 'PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)
        ->get();

        $setorSolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as cpf', 'PLCFULL.dbo.Jurid_Setor.codigo as codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao as descricao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)
        ->get();

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

        $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
        ->select('codigo' ,'descricao')
        ->get(); 

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('codigo', 'descricao')
        ->where('ativo', '=', '1')   
        ->orderby('Descricao', 'asc')   
        ->get(); 

        $setor_destino = DB::table('dbo.Setor_Destino')
        ->select('id', 'descricao')
        ->get(); 

        return view('Painel.Compras.Solicitacao.index', compact('notificacoes', 'totalNotificacaoAbertas', 'dados', 'unidadeSolicitante', 
                    'setorSolicitante', 'unidades', 'setores', 'setor_destino', 'datahoje'));
    }

    public function novasolicitacao(){

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $totalNotificacaoAbertas = DB::table('dbo.Hist_Notificacao')
        ->where('status', 'A')
        ->where('destino_id','=', Auth::user()->id)
        ->count();

        $unidadeSolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as cpf', 'PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)
        ->get();

        $setorSolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as cpf', 'PLCFULL.dbo.Jurid_Setor.codigo as codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao as descricao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)
        ->get();

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

        $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
        ->select('codigo' ,'descricao')
        ->get(); 

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('codigo', 'descricao')
        ->where('ativo', '=', '1')   
        ->orderby('Descricao', 'asc')   
        ->get(); 

        $setor_destino = DB::table('dbo.Setor_Destino')
        ->select('id', 'descricao')
        ->get(); 


        return view('Painel.Compras.Solicitacao.novasolicitacao', compact('notificacoes', 'totalNotificacaoAbertas', 'unidades', 'setores', 'datahoje', 'unidadeSolicitante', 'setorSolicitante', 'setor_destino'));
    }

    public function salvarNovaSolicitacao(Request $request){
    
        $user_id = Auth::user()->id;
        $user_name = Auth::user()->name;
        $carbon = Carbon::now();
        $dataehora = $carbon->format('dmY_HHis');
        $unidade = $request->get('unidade');
        $setor = $request->get('setor');
        $observacao = $request->get('observacao');
        $data = $request->get('data');
        $produtos = $request->get('produtos');
        $input_outros = $request->get('input_outros');
        $quantidade = $request->get('quantidade');

        $values = array('unidade' => $unidade, 
                        'setor' => $setor, 
                        'observacao' => $observacao, 
                        'setor' => $setor,
                        'datasolicitacao' => $data,
                        'status' => '1',
                        'user_id' => $user_id,
                        'nomesolicitante' => $user_name,
                        'produtos' => $produtos,
                        'produto_outros' => $input_outros,
                        'quantidade' => $quantidade);
        DB::table('dbo.Solicitacao_Compra')->insert($values);  

      
        $profile = DB::table('dbo.profile_user')
        ->select('dbo.profile_user.id', 'dbo.profile_user.profile_id', 'dbo.profile_user.user_id')
        ->where('profile_id', '=', '34')
        ->get(); 

        $datas = DB::table('dbo.Solicitacao_Compra')
        ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.unidade', 
                 'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
                 'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
                 'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade',
                 'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.nomesolicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
        ->whereRaw('Solicitacao_Compra.id = (select max(Solicitacao_Compra.id) from Solicitacao_Compra)')
        ->get(); 


        foreach($profile as $index => $p){
          
                $users = DB::table('dbo.users')
                ->select('dbo.users.id', 'dbo.users.email')
                ->where('id', '=', $p->user_id)
                ->get(); 

                foreach($users as $index => $u){

                    $values = array('data' =>  $carbon,
                                    'id_ref' => '10', 
                                    'user_id' => $user_id, 
                                    'destino_id' => $u->id, 
                                    'tipo' => '7',
                                    'tipo' => '1',
                                    'obs' => 'Solicitação de compra criada',
                                    'status' => 'A');
                    DB::table('dbo.Hist_Notificacao')->insert($values);  

                     Mail::to($u->email)
                    ->send(new SolicitanteEnvio($datas));
                }

        }

        return redirect()->route('Painel.Compras.Solicitante.index_solicitante');

    }

    public function index_comite_compras(){
        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

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

        $dados = DB::table('dbo.Solicitacao_Compra')
        ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.unidade', 
                 'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
                 'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
                 'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade',
                 'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.nomesolicitante')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
        ->orderby('id', 'asc')   
        ->get(); 

        return view('Painel.Compras.ComiteCompras.index', compact('notificacoes', 'totalNotificacaoAbertas', 'datahoje', 'dados'));

    }

    public function formulario(Request $request, $id){

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

        $dados = DB::table('dbo.Solicitacao_Compra')
        ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.nomesolicitante', 'dbo.Solicitacao_Compra.unidade', 
        'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
        'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
        'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade',
        'dbo.Solicitacao_Compra.quantidade', 'dbo.Solicitacao_Compra.produtos', 'dbo.Solicitacao_Compra.produto_outros')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
        ->where('dbo.Solicitacao_Compra.id', '=', $id)   
        ->get(); 

        return view('Painel.Compras.ComiteCompras.formulario', compact('notificacoes', 'totalNotificacaoAbertas', 'dados'));
    }

    public function enviaParaAprovacao(Request $request){

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');
        $file = $request->file('select_file');
        if($file != '' || $file != null){
            $new_name = $datahoje . '.'  . $file->getClientOriginalExtension();
            $file->storeAs('compras/comitecompras', $new_name);
        } else {
            $new_name = 'N';
        }

                
        $values = array('id_solicitante' => $request->get('id_solicitante'), 
                        'quantidade' => $request->get('quantidade'), 
                        'especificacoes' => $request->get('especificacoes'), 
                        'observacoes' => $request->get('observacoes_produto'), 
                        'status' => $request->get('status'),
                        'arquivo' => $new_name);
        DB::table('dbo.Comite_Compras')->insert($values);  

  
        $profile = DB::table('dbo.profile_user')
        ->select('dbo.profile_user.id', 'dbo.profile_user.profile_id', 'dbo.profile_user.user_id')
        ->where('profile_id', '=', '35')
        ->get(); 

        $datas = DB::table('dbo.Comite_Compras')
        ->select('Comite_Compras.id', 'Comite_Compras.id_solicitante', 'Comite_Compras.quantidade', 
        'Comite_Compras.especificacoes', 'Comite_Compras.observacoes', 'Comite_Compras.status', 'Comite_Compras.arquivo')
        ->whereRaw('Comite_Compras.id = (select max(Comite_Compras.id) from Comite_Compras)')
        ->get(); 


        foreach($profile as $index => $p){
          
                $users = DB::table('dbo.users')
                ->select('dbo.users.id', 'dbo.users.email')
                ->where('id', '=', $p->user_id)
                ->get(); 

                foreach($users as $index => $u){

                    $values = array('data' =>  $carbon,
                                    'id_ref' => '10', 
                                    'user_id' =>  Auth::user()->id, 
                                    'destino_id' => $u->id, 
                                    'tipo' => '7',
                                    'tipo' => '1',
                                    'obs' => 'Nova solicitação pendente para aprovação',
                                    'status' => 'A');
                    DB::table('dbo.Hist_Notificacao')->insert($values);  

                    Mail::to($u->email)
                    ->send(new ComiteComprasEnvio($datas));
                }

        }

        $id_solicitante = $request->get('id_solicitante');
        $fornecedor = $request->get('fornecedor');
        $cpf = $request->get('cpf_cnpj');
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $contato = $request->get('contato');
        $prazo = $request->get('prazo');
        $condicoes_pagamento = $request->get('boleto_cartao');
        $observacoes = $request->get('observacoes');
        $parcelas = $request->get('parcelas');
        $valor = $request->get('valor');
        $valor = str_replace(",",".", $valor);
        $frete = $request->get('frete');
        $frete = str_replace(",",".", $frete);
        $frete_boleto = $request->get('frete_boleto');
        $frete_boleto = str_replace(",",".", $frete_boleto);

        $id_comite_compras = DB::table('dbo.Comite_Compras')
        ->select('id')
        ->where('id_solicitante', '=', $id_solicitante)   
        ->get();   

        $id_compra = $id_comite_compras[0]->id;

        if($parcelas != null || $parcelas != ""){

            $total = $valor / $parcelas + $frete;

            $values = array('id_comite_compras' => $id_compra, 
            'fornecedor' =>  $fornecedor, 
            'cpf_cnpj' => $cpf, 
            'contato' => $contato, 
            'prazo' => $prazo,
            'condicoes_pagamento' => $condicoes_pagamento,
            'observacoes' => $observacoes,
            'valor' => $valor,
            'parcelas' => $parcelas,
            'total' => $total,
            'frete' => $frete);
            DB::table('dbo.Fornecedores_Comite_Compra')->insert($values);  
        } else {

            $total = $valor + $frete_boleto;

            $values = array('id_comite_compras' => $id_compra, 
            'fornecedor' =>  $fornecedor, 
            'cpf_cnpj' => $cpf, 
            'contato' => $contato, 
            'prazo' => $prazo,
            'condicoes_pagamento' => $condicoes_pagamento,
            'observacoes' => $observacoes,
            'valor' => $valor,
            'parcelas' => '1',
            'total' => $total,
            'frete' => $frete_boleto);
            DB::table('dbo.Fornecedores_Comite_Compra')->insert($values);  
        }


        if($request->get('fornecedor_2') != null){
            $fornecedor2 = $request->get('fornecedor_2');
            $cpf2 = $request->get('cpf_cnpj_2');
            $cpf2 = preg_replace('/[^0-9]/', '', $cpf2);
            $contato2 = $request->get('contato_2');
            $prazo2 = $request->get('prazo_2');
            $condicoes_pagamento2 = $request->get('boleto_cartao_2');
            $observacoes2 = $request->get('observacoes_2');
            $parcelas2 = $request->get('parcelas_2');
            $valor2 = $request->get('valor_2');
            $valor2 = str_replace(",",".", $valor2);
            $frete_2 = $request->get('frete_2');
            $frete_2 = str_replace(",",".", $frete_2);
            $frete_boleto_2 = $request->get('frete_boleto_2');
            $frete_boleto_2 = str_replace(",",".", $frete_boleto_2);

            if($parcelas2 != null || $parcelas2 != ""){

                $total2 = $valor2 / $parcelas2 + $frete_2;

                $values = array('id_comite_compras' => $id_compra, 
                'fornecedor' =>  $fornecedor2, 
                'cpf_cnpj' => $cpf2, 
                'contato' => $contato2, 
                'prazo' => $prazo2,
                'condicoes_pagamento' => $condicoes_pagamento2,
                'observacoes' => $observacoes2,
                'valor' => $valor2,
                'parcelas' => $parcelas2,
                'total' => $total2,
                'frete' => $frete_2);
                DB::table('dbo.Fornecedores_Comite_Compra')->insert($values);  
            } else {

                $total = $valor + $frete_boleto_2;

                $values = array('id_comite_compras' => $id_compra, 
                'fornecedor' =>  $fornecedor2, 
                'cpf_cnpj' => $cpf2, 
                'contato' => $contato2, 
                'prazo' => $prazo2,
                'condicoes_pagamento' => $condicoes_pagamento2,
                'observacoes' => $observacoes2,
                'valor' => $valor2,
                'parcelas' => '1',
                'total' => $total,
                'frete' => $frete_boleto_2);
                DB::table('dbo.Fornecedores_Comite_Compra')->insert($values);  
            }
          
        }   

        if($request->get('fornecedor_3') != null){
            $fornecedor3 = $request->get('fornecedor_3');
            $cpf3 = $request->get('cpf_cnpj_3');
            $cpf3 = preg_replace('/[^0-9]/', '', $cpf3);
            $contato3 = $request->get('contato_3');
            $prazo3 = $request->get('prazo_3');
            $condicoes_pagamento3 = $request->get('boleto_cartao_3');
            $observacoes3 = $request->get('observacoes_3');
            $parcelas3 = $request->get('parcelas_3');
            $valor3 = $request->get('valor_3');
            $valor3 = str_replace(",",".", $valor3);
            $frete3 = $request->get('frete_3');
            $frete3 = str_replace(",",".", $frete3);
            $frete_boleto_3 = $request->get('frete_boleto_3');
            $frete_boleto_3 = str_replace(",",".", $frete_boleto_3);

            if($parcelas3 != null || $parcelas != ""){
                
                $total3 = $valor3 / $parcelas3 + $frete3;

                $values = array('id_comite_compras' => $id_compra, 
                'fornecedor' =>  $fornecedor3, 
                'cpf_cnpj' => $cpf3, 
                'contato' => $contato3, 
                'prazo' => $prazo3,
                'condicoes_pagamento' => $condicoes_pagamento3,
                'observacoes' => $observacoes3,
                'valor' => $valor3,
                'parcelas' => $parcelas3,
                'total' => $total3,
                'frete' => $frete3);
                DB::table('dbo.Fornecedores_Comite_Compra')->insert($values);  
            } else {

                $total = $valor + $frete_boleto_3;

                $values = array('id_comite_compras' => $id_compra, 
                'fornecedor' =>  $fornecedor3, 
                'cpf_cnpj' => $cpf3, 
                'contato' => $contato3, 
                'prazo' => $prazo3,
                'condicoes_pagamento' => $condicoes_pagamento3,
                'observacoes' => $observacoes3,
                'valor' => $valor3,
                'parcelas' => '1',
                'total' => $total,
                'frete' => $frete_boleto_3);
                DB::table('dbo.Fornecedores_Comite_Compra')->insert($values);  
            }
           
        }   

        DB::table('dbo.Solicitacao_Compra')
        ->where('id', $id_solicitante)  
        ->limit(1) 
        ->update(array('Status' => '2'));


        return redirect()->route('Painel.Compras.ComiteCompras.index_comite');

    }

    public function historico_comite_compras(){
        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

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

        $dados = DB::table('dbo.Solicitacao_Compra')
        ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.nomesolicitante', 'dbo.Solicitacao_Compra.unidade', 
        'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
        'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
        'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
        ->orderby('id', 'asc')   
        ->get(); 

        return view('Painel.Compras.ComiteCompras.historico', compact('notificacoes', 'totalNotificacaoAbertas', 'datahoje', 'dados'));

    }

    public function index_comite_aprovacao(){
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

        $dados = DB::table('dbo.Comite_Compras')
        ->select('Comite_Compras.id', 'Comite_Compras.id_solicitante', 'Comite_Compras.quantidade', 'Comite_Compras.especificacoes', 
        'Comite_Compras.observacoes', 'Comite_Compras.status as status_comite', 'Comite_Compras.arquivo', 
        'Solicitacao_Compra.status as status_solicitacao', 'dbo.Solicitacao_Compra.nomesolicitante', 'dbo.Solicitacao_Compra.user_id')
        ->join('dbo.Solicitacao_Compra', 'dbo.Comite_Compras.id_solicitante', 'dbo.Solicitacao_Compra.id')
        ->get();
        



        return view('Painel.Compras.ComiteAprovacao.index', compact('notificacoes', 'totalNotificacaoAbertas', 'dados', 'dados'));
    }

    public function downloadAnexoAprovacao($anexo){
        return Storage::disk('compras-comite-sftp')->download($anexo);
    }

    public function aprovar($id){

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


        $dados = DB::table('dbo.Comite_Compras')
        ->select('id', 'id_solicitante', 'quantidade', 'especificacoes', 'observacoes', 
        'status')
        ->where('id_solicitante', '=', $id)
        ->get(); 

        $unidade_setor = DB::table('dbo.Solicitacao_Compra')
        ->select('id', 'unidade', 'setor')
        ->where('id', '=', $dados[0]->id_solicitante)
        ->get(); 
        
        $fornecedores = DB::table('dbo.Fornecedores_Comite_Compra')
        ->select('id', 'id_comite_compras', 'fornecedor', 'cpf_cnpj', 'contato', 
        'prazo', 'condicoes_pagamento', 'observacoes', 'valor','parcelas', 'total', 'frete')
        ->where('id_comite_compras', '=', $dados[0]->id)
        ->get(); 

        $usuario_aprovacao = DB::table('hist_aprovacoes')
        ->select('id', 'user_id', 'id_solicitacao')
        ->where('user_id', '=', Auth::user()->id)
        ->where('id_solicitacao', '=', $id)
        ->count(); 

        return view('Painel.Compras.ComiteAprovacao.aprovar', compact('notificacoes', 'totalNotificacaoAbertas', 'dados', 'fornecedores', 'unidade_setor', 'usuario_aprovacao'));
    }

    // public function excluirFornecedor($id){
    //     DB::table('dbo.Fornecedores_Comite_Compra')->where('id', $id)->delete();     
    //     return redirect()->back();   
    // }

    public function aprovarCompra(Request $request, $id){

        $carbon= Carbon::now();
        $dataehora = $carbon->format('Y-m-d');
        $dataehoraBR = $carbon->format('d-m-Y H:i:s');

        $file = $request->file('select_file');
        $new_name = $carbon . '.'  . $file->getClientOriginalExtension();
        $file->storeAs('compras/comiteaprovacao', $new_name);
        
        $fornecedor_descricao = $request->get('fornecedor_descricao');
        $parcelas = $request->get('parcelas');

        $id_fornecedor = $request->get('id_fornecedor');
        $id_solicitante = $request->get('id_solicitante');
        $fornecedor = $request->get('fornecedor');
        $cpf_cnpj = $request->get('cpf_cnpj');
        $contato = $request->get('contato');
        $prazo = $request->get('prazo');
        $observacoes = $request->get('observacoes');
        $valor = $request->get('valor');
        $total = $request->get('total');
        $setor = $request->get('setor');
        $unidade = $request->get('unidade');
        $quantidade = $request->get('quantidade');
        $status = $request->get('status');
        $id_comite_compras = $request->get('id_comite_compras');


        if($status == "Normal"){

            $user_id = Auth::user()->id;

            $values = array('user_id' =>  $user_id,
                            'id_solicitacao' => $id_solicitante);
            DB::table('dbo.hist_aprovacoes')->insert($values);  

            DB::table('dbo.Solicitacao_Compra')
            ->where('id', $id_solicitante)  
            ->limit(1) 
            ->update(array('Status' => '5'));

            DB::table('dbo.Comite_Compras')
            ->where('id', $id_comite_compras)  
            ->limit(1) 
            ->update(array('Status' => 'Aguardando segunda aprovação'));    

            $profile = DB::table('dbo.profile_user')
            ->select('dbo.profile_user.id', 'dbo.profile_user.profile_id', 'dbo.profile_user.user_id')
            ->where('profile_id', '=', '33')
            ->get(); 
    
            $datas = DB::table('dbo.Solicitacao_Compra')
            ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.nomesolicitante', 'dbo.Solicitacao_Compra.unidade', 
            'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
            'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
            'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
            ->where('dbo.Solicitacao_Compra.id', '=', $id_solicitante)
            ->orderby('id', 'asc')   
            ->get(); 

            $hist_aprovacoes = DB::table('dbo.hist_aprovacoes')
            ->select('id', 'user_id', 'id_solicitacao')
            ->where('id_solicitacao', '=', $id_solicitante)
            ->where('user_id', '!=', Auth::user()->id)
            ->get(); 

            foreach($profile as $index => $p){
              
                    $users = DB::table('dbo.users')
                    ->select('dbo.users.id', 'dbo.users.email')
                    ->where('id', '=', $p->user_id)
                    ->get(); 
    
                    foreach($users as $index => $u){

                        $values = array('data' =>  $carbon,
                                        'id_ref' => '10', 
                                        'user_id' => Auth::user()->id, 
                                        'destino_id' => $u->id, 
                                        'tipo' => '7',
                                        'tipo' => '1',
                                        'obs' => 'Aguardando segunda aprovação.',
                                        'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values);  

                        Mail::to($u->email)
                        ->send(new solicitacaoAprovadaPorUm($datas));
                    }
            }

            return redirect()->route('Painel.Compras.ComiteAprovacao.index_comite_aprovacao');
        } else {
            $ultimoNumero = DB::table('PLCFULL.dbo.Jurid_Debite')
            ->select('Numero')
            ->whereRaw('Numero = (select max(Numero) from PLCFULL.dbo.Jurid_Debite)')
            ->get();
        
            if($parcelas != null || $parcelas != ''){
                for ($i = 0; $i < $parcelas; $i++) { 
                    
                    $values = array('Numero' => Auth::user()->id . $id_fornecedor . $i, 
                                'Advogado' => Auth::user()->cpf, 
                                'Cliente' => Auth::user()->cpf, 
                                'Data' => $dataehora,
                                'Tipo' => 'D',
                                'Obs' => $observacoes,
                                'Status' => 0,
                                'Hist' => 'Solicitação de compra inserida pelo(a): '. Auth::user()->name .' no módulo de compras - '. $carbon->format('d-m-Y H:i:s'),
                                'ValorT' => $total,
                                'Usuario' => 'portal.plc',
                                'DebPago' => 'N',
                                'TipoDeb' => $quantidade,
                                'AdvServ' => Auth::user()->cpf,
                                'Setor' => $setor,
                                'Pasta' => $quantidade,
                                'Unidade' => $unidade,
                                'Valor_Adv' => $total,
                                'Quantidade' => $quantidade,
                                'ValorUnitario_Adv' => $total,
                                'ValorUnitarioCliente' => $total,
                                'Revisado_DB' => '0',
                                'moeda' => 'R$');
                DB::table('PLCFULL.dbo.Jurid_Debite')->insert($values);  
                }

                $profile = DB::table('dbo.profile_user')
                ->select('dbo.profile_user.id', 'dbo.profile_user.profile_id', 'dbo.profile_user.user_id')
                ->where('profile_id', '=', '33')
                ->get(); 
        
                $datas = DB::table('dbo.Solicitacao_Compra')
                ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.nomesolicitante', 'dbo.Solicitacao_Compra.unidade', 
                'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
                'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
                'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
                ->where('dbo.Solicitacao_Compra.id', '=', $id_solicitante)
                ->orderby('id', 'asc')   
                ->get();  
        
                foreach($profile as $index => $p){
                  
                        $users = DB::table('dbo.users')
                        ->select('dbo.users.id', 'dbo.users.email')
                        ->where('id', '=', $p->user_id)
                        ->get(); 
        
                        foreach($users as $index => $u){
        
                            $values = array('data' =>  $carbon,
                                            'id_ref' => '10', 
                                            'user_id' => Auth::user()->id, 
                                            'destino_id' => $u->id, 
                                            'tipo' => '7',
                                            'tipo' => '1',
                                            'obs' => 'Solicitação de compra aprovada.',
                                            'status' => 'A');
                            DB::table('dbo.Hist_Notificacao')->insert($values);  
        
                            Mail::to($u->email)
                            ->send(new solicitacaoAprovada($datas));
                        }
        
                }
        
                DB::table('dbo.Solicitacao_Compra')
                ->limit(1)
                ->where('id', '=', $id_solicitante)     
                ->update(array('Status' => '3'));
        
                DB::table('dbo.fornecedores_comite_compra')
                ->limit(1)
                ->where('id', '=', $id_fornecedor)     
                ->update(array('aprovado' => 'Sim'));
        
                return redirect()->route('Painel.Compras.ComiteAprovacao.index_comite_aprovacao');
        }
    }
    }

    public function historico_comite_aprovacao(){
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

        $dados = DB::table('dbo.Comite_Compras')
        ->select('Comite_Compras.id', 'Comite_Compras.id_solicitante', 'Comite_Compras.quantidade', 'Comite_Compras.especificacoes', 
        'Comite_Compras.observacoes', 'Comite_Compras.status as status_comite', 'Comite_Compras.arquivo', 'Solicitacao_Compra.status as status_solicitacao')
        ->join('dbo.Solicitacao_Compra', 'dbo.Comite_Compras.id_solicitante', 'dbo.Solicitacao_Compra.id')
        ->get(); 

        return view('Painel.Compras.ComiteAprovacao.historico', compact('notificacoes', 'totalNotificacaoAbertas', 'dados', 'dados'));
    }

    public function historico_fornecedores($id){

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


        $dados = DB::table('dbo.Comite_Compras')
        ->select('id', 'id_solicitante', 'quantidade', 'especificacoes', 'observacoes', 
        'status')
        ->where('id_solicitante', '=', $id)
        ->get(); 

        $unidade_setor = DB::table('dbo.Solicitacao_Compra')
        ->select('id', 'unidade', 'setor')
        ->where('id', '=', $dados[0]->id_solicitante)
        ->get(); 
        
        $fornecedores = DB::table('dbo.Fornecedores_Comite_Compra')
        ->select('id', 'id_comite_compras', 'fornecedor', 'cpf_cnpj', 'contato', 
        'prazo', 'condicoes_pagamento', 'observacoes', 'valor','parcelas', 'total', 'frete', 'aprovado')
        ->where('id_comite_compras', '=', $dados[0]->id)
        ->get(); 

        return view('Painel.Compras.ComiteAprovacao.historicoFornecedores', compact('notificacoes', 'totalNotificacaoAbertas', 'dados', 'unidade_setor', 'fornecedores'));
    }

    public function downloadAnexoSolicitacao($arquivo){
        return Storage::disk('compras-solicitante-sftp')->download($arquivo);   
    }

    public function reprovar($id){

        $carbon= Carbon::now();

        $solicitacao = DB::table('dbo.Solicitacao_Compra')
        ->select('id')
        ->where('id', '=', $id)
        ->get(); 

        $id_comite = DB::table('dbo.Comite_Compras')
        ->select('id')
        ->where('id_solicitante', '=', $solicitacao[0]->id)
        ->get(); 

        $id_fornecedor = DB::table('dbo.Fornecedores_Comite_Compra')
        ->select('id')
        ->where('id_comite_compras', '=', $id_comite[0]->id)
        ->get(); 

        $profile = DB::table('dbo.profile_user')
        ->select('dbo.profile_user.id', 'dbo.profile_user.profile_id', 'dbo.profile_user.user_id')
        ->where('profile_id', '=', '33')
        ->get(); 

        $datas = DB::table('dbo.Solicitacao_Compra')
        ->select('dbo.Solicitacao_Compra.id', 'dbo.Solicitacao_Compra.nomesolicitante', 'dbo.Solicitacao_Compra.unidade', 
        'dbo.Solicitacao_Compra.setor', 'dbo.Solicitacao_Compra.observacao', 'dbo.Solicitacao_Compra.datasolicitacao', 
        'dbo.Solicitacao_Compra.user_id', 'dbo.Solicitacao_Compra.status', 'dbo.Solicitacao_Compra.arquivo',
        'PLCFULL.dbo.Jurid_Setor.descricao as descricao_setor', 'PLCFULL.dbo.Jurid_Unidade.descricao as descricao_unidade')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Solicitacao_Compra.setor', 'PLCFULL.dbo.Jurid_Setor.codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Solicitacao_Compra.unidade', 'PLCFULL.dbo.Jurid_Unidade.codigo')
        ->where('dbo.Solicitacao_Compra.id', '=', $id)
        ->orderby('id', 'asc')   
        ->get(); 


        foreach($profile as $index => $p){
          
                $users = DB::table('dbo.users')
                ->select('dbo.users.id', 'dbo.users.email')
                ->where('id', '=', $p->user_id)
                ->get(); 

                foreach($users as $index => $u){

                    $values = array('data' =>  $carbon,
                                    'id_ref' => '10', 
                                    'user_id' => Auth::user()->id, 
                                    'destino_id' => $u->id, 
                                    'tipo' => '7',
                                    'tipo' => '1',
                                    'obs' => 'Solicitação de compra reprovada.',
                                    'status' => 'A');
                    DB::table('dbo.Hist_Notificacao')->insert($values);  


                     Mail::to($u->email)
                    ->send(new solicitacaoReprovada($datas));
                }

        }

        DB::table('dbo.Solicitacao_Compra')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('Status' => '4'));

        return redirect()->route('Painel.Compras.ComiteAprovacao.index_comite_aprovacao');
        
    }

    public function buscaProdutos(Request $request){
        $id_destino = $request->get('id_destino');

        if($id_destino == '1'){
            $produtos_ti = DB::table('dbo.produtos_ti')->where('id_destino', '=', '1')->get(); 

            foreach($produtos_ti as $index) {  
                $response = '<option value="'.$index->Descricao.'">'.$index->Descricao.'</option>';
                echo $response;
            }
        }

        if($id_destino == '2'){
            $produtos_marketing = DB::table('dbo.produtos_marketing')->where('id_destino', '=', '2')->get(); 

            foreach($produtos_marketing as $index) {  
                $response = '<option value="'.$index->Descricao.'">'.$index->Descricao.'</option>';
                echo $response;
            }
        }

        if($id_destino == '3'){
            $produtos_adm_fin = DB::table('dbo.Produtos_Adm_Fin')->where('id_destino', '=', '3')->get(); 

            foreach($produtos_adm_fin as $index) {  
                $response = '<option value="'.$index->Descricao.'">'.$index->Descricao.'</option>';
                echo $response;
            }
        }

    }

}
