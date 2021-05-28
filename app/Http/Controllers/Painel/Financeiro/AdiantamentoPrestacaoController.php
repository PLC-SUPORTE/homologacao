<?php

namespace App\Http\Controllers\Painel\Financeiro;

use App\Models\Nota;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Correspondente;
use Illuminate\Support\Facades\Storage;
use File;

class AdiantamentoPrestacaoController extends Controller
{

    protected $totalPage = 10;   
    public $timestamps = false;


    public function anexos($numerodebite) {

        //Busco os arquivos gravados na GED
        $datas = DB::table("PLCFULL.dbo.Jurid_Ged_Main")
        ->where('PLCFULL.dbo.Jurid_Ged_Main.Id_OR', $numerodebite)
        ->orWhere('PLCFULL.dbo.Jurid_Ged_Main.Codigo_OR', $numerodebite)
        ->get();

        $QuantidadeAnexos = $datas->count();

        return view('Painel.Financeiro.AdiantamentoPrestacao.anexos', compact('numerodebite','datas', 'QuantidadeAnexos')); 
    }

    public function financeiro_index()
    {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    

        //Delete tabela temp
        DB::table('dbo.Financeiro_Adiantamento_temp')->delete();        

        $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
              ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as cpf',
                     'PLCFULL.dbo.Jurid_Advogado.Nome as name',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
             ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
             ->where('PLCFULL.dbo.Jurid_Advogado.Status','=','Ativo')
             ->where('PLCFULL.dbo.Jurid_Advogado.Correspondente', '=', '0')
             ->where('PLCFULL.dbo.Jurid_Advogado.Codigo', '00000000000')
             ->orderby('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
             ->get();


        //Busco via Loop os dados do Advwin referente a Adiantamento
        foreach ($datas as $data) {

            $usuario_cpf = $data->cpf;

            //Pego o valor aguardando revisão do financeiro
            $valor_aguardandorevisao = DB::table("dbo.Financeiro_Adiantamento_Matrix")
            ->select(DB::raw('sum(dbo.Financeiro_Adiantamento_Matrix.valor_original) as Valor'))
            ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'dbo.Financeiro_Adiantamento_Matrix.numdoc', 'PLCFULL.dbo.Jurid_ContPrBx.Numdoc')
            ->whereIn('dbo.Financeiro_Adiantamento_Matrix.status_id', array(4,5))
            ->where('dbo.Financeiro_Adiantamento_Matrix.cpf', $usuario_cpf)
            ->value('dbo.Financeiro_Adiantamento_Matrix.valor_original');

            //Pego o valor aguardando a prestação de conta
            $valor_aguardandoprestacao = DB::table("dbo.Financeiro_Adiantamento_Matrix")
            ->select(DB::raw('sum(dbo.Financeiro_Adiantamento_Matrix.valor_atual) as Valor'))
            ->whereIn('dbo.Financeiro_Adiantamento_Matrix.status_id', array(2))
            ->where('dbo.Financeiro_Adiantamento_Matrix.cpf', $usuario_cpf)
            ->value('dbo.Financeiro_Adiantamento_Matrix.valor_atual');

            // //Pego o valor total transferido
            // $valor_total = DB::table("dbo.Financeiro_Adiantamento_Matrix")
            // ->select(DB::raw('sum(dbo.Financeiro_Adiantamento_Matrix.valor_original)'))
            // ->where('dbo.Financeiro_Adiantamento_Matrix.cpf', $usuario_cpf)
            // ->whereNotIn('dbo.Financeiro_Adiantamento_Matrix.status_id', array(1,4,6))
            // ->value('dbo.Financeiro_Adiantamento_Matrix.valor_original');

            //Grava na tabela temp
            $values = array('cpf' => $usuario_cpf, 
                            'valor_aguardandorevisao' => $valor_aguardandorevisao, 
                            'valor_aguardandoprestacao' => $valor_aguardandoprestacao, 
                            'valor_total' => '');
            DB::table('dbo.Financeiro_Adiantamento_temp')->insert($values);
        }

        //Puxa da tabela temporaria
        $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as cpf',
               'PLCFULL.dbo.Jurid_Advogado.Nome as name',
               'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
               'valor_aguardandorevisao',
               'valor_aguardandoprestacao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->leftjoin('dbo.Financeiro_Adiantamento_temp', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'dbo.Financeiro_Adiantamento_temp.cpf')
       ->where('PLCFULL.dbo.Jurid_Advogado.Status','=','Ativo')
       ->where('PLCFULL.dbo.Jurid_Advogado.Correspondente', '=', '0')
       ->where('PLCFULL.dbo.Jurid_Advogado.Codigo', '00000000000')
       ->orderby('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
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
             'Hist_Notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

       return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index', compact('datahoje','datas', 'totalNotificacaoAbertas', 'notificacoes'));


    }

    public function financeiro_novoadiantamento($usuario_cpf) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_cpf',
               'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
               'dbo.users.email as usuario_email',
               'dbo.users.id as usuario_id',
               'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
               'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
               'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
               'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
               'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
               'PLCFULL.dbo.Jurid_Banco.Agencia',
               'PLCFULL.dbo.Jurid_Banco.Conta',
               'PLCFULL.dbo.Jurid_Banco.moeda as Moeda',
               )
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'dbo.users.cpf')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'PLCFULL.dbo.Jurid_Banco.CodAdvogado')
       ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=',$usuario_cpf)
       ->first();

       $grupos = DB::table('PLCFULL.dbo.Jurid_GrupoCliente')
       ->orderby('Descricao', 'ASC')
       ->get();

       $motivos = DB::table('dbo.Financeiro_Adiantamento_Motivos')
       ->where('Status','=','A')
       ->orderby('descricao', 'asc')
       ->get();

       $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
       ->where('DaEmpresa', '0')
       ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
       ->where('Status', '1')
       ->orderby('Descricao', 'ASC')
       ->get();   
    

       $debito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', Auth::user()->cpf)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           
         
       $credito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', Auth::user()->cpf)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
          
        $saldo = $credito - $debito;

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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
        ->get();


       return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.novoadiantamento', compact('datahoje','grupos','datas','motivos','bancos' ,'saldo' ,'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_novoadiantamento_store(Request $request) {

        $carbon= Carbon::now();
        $setor = $request->get('setor');
        $unidade = $request->get('unidade');
        $usuario_cpf = $request->get('usuario_cpf');
        $usuario_id = $request->get('usuario_id');
        $motivo_id = $request->get('motivo');
        $portador = $request->get('portador');
        $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
        $observacao = $request->get('observacao');
        $observacaoadicionais = $request->get('observacaoadicionais');
        $moeda = $request->get('moeda');

        $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 
        $numdoc = $numdoc +1;

        //Grava na ContPrBx
        $values = array('Tipodoc' => 'TRF', 
                        'Numdoc' => $numdoc, 
                        'Cliente' => $usuario_cpf, 
                        'Tipo' => 'P',
                        'TipodocOr' => 'TRF',
                        'NumDocOr' => $numdoc,
                        'Tipolan' => 'TRANSF',
                        'Valor' => $valor,
                        'Centro' => $setor,
                        'Dt_baixa' => $carbon->format('Y-m-d'),
                        'Portador' => $portador,
                        'Obs' => $observacao . ' ' . $observacaoadicionais,
                        'Dt_Compet' => $carbon->format('Y-m-d'),
                        'Dt_Concil' => $carbon->format('Y-m-d'),
                        'Unidade' => $unidade,
                        'Ident_Rate' => '0',
                        'hist_usuario' => 'portal.plc',
                        'dataCambioBX' => $carbon->format('Y-m-d'),
                        'valorCambioBX' => $valor,
                        'moeda' => $moeda);
        DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

        $contprbx_ident = DB::table('PLCFULL.dbo.Jurid_ContPrBx')->select('B_Ident')->where('Numdoc', $numdoc)->orderby('Numdoc', 'desc')->value('B_Ident'); 

        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numdoc));

        //Grava o comprovante de pagamento
        $image = $request->file('select_file');
        $new_name = 'comprovante_'.$numdoc . '_' . $carbon . '.'  . $image->getClientOriginalExtension();
        $image->storeAs('adiantamento', $new_name);
        
        $values = array(
            'Tabela_OR' => 'Baixa',
            'Codigo_OR' => $contprbx_ident,
            'Id_OR' => $contprbx_ident,
            'Descricao' => $image->getClientOriginalName(),
            'Link' => '\\192.168.1.65\advwin\portal\portal\adiantamento/'.$new_name, 
            'Data' => $carbon,
            'Nome' => $image->getClientOriginalName(),
            'Responsavel' => 'portal.plc',
            'Arq_tipo' => $image->getClientOriginalExtension(),
            'Arq_Versao' => '1',
            'Arq_Status' => 'Guardado',
            'Arq_usuario' => 'portal.plc',
            'Arq_nick' => $new_name,
            'Obs' => $observacao . ' ' . $observacaoadicionais,
            'Texto' => 'Comprovante de adiantamento realizado pelo portal PLC');
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        //Grava na Adiantamento Matrix
        $values = array('user_id' => Auth::user()->id, 
                        'cpf' => $usuario_cpf, 
                        'numdoc' => $numdoc,
                        'data' => $carbon,
                        'valor_original' => $valor,
                        'valor_atual' => $valor,
                        'status_id' => '2',
                        'motivo_id' => $motivo_id,
                        'observacao' => $observacao . ' ' . $observacaoadicionais);
        DB::table('dbo.Financeiro_Adiantamento_Matrix')->insert($values);

        $id_matrix = DB::table('dbo.Financeiro_Adiantamento_Matrix')->select('id')->where('user_id', Auth::user()->id)->orderby('id', 'desc')->value('id'); 

        //Grava na Adiantamento Hist
        $values = array('user_id' => Auth::user()->id, 
                        'id_matrix' => $id_matrix, 
                        'data' => $carbon, 
                        'status_id' => '2');
        DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

        //Envia e-mail copiando o Douglas e notificação informando do adiantamento

        // Mail::to('ronaldo.amaral@plcadvogados.com.br')
        // ->send(new AdiantamentoEnviado);

        $values4= array('data' => $carbon, 'id_ref' => $numdoc, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Adiantamento enviado com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        $values4= array('data' => $carbon, 'id_ref' => $numdoc, 'user_id' => Auth::user()->id, 'destino_id' => $usuario_id, 'tipo' => '16', 'obs' => 'Adiantamento: Você recebeu um novo adiantamento com sucesso pela equipe do financeiro.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index');

    }

    public function financeiro_solicitacoesadiantamento($usuario_cpf) {

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
               'dbo.Financeiro_Adiantamento_Matrix.numdoc as CPR',
               'dbo.users.name as solicitante_nome',
               'dbo.users.id as solicitante_id',
               'dbo.Financeiro_Adiantamento_Matrix.numerodebite as NumeroDebite',
               'dbo.Financeiro_Adiantamento_Matrix.pasta as Pasta',
               'dbo.Financeiro_Adiantamento_Matrix.data',
               'dbo.Financeiro_Adiantamento_Matrix.valor_original',
               'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
               'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
               'dbo.Financeiro_Adiantamento_Status.descricao as status',
               'dbo.Financeiro_Adiantamento_Status.id as StatusID')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->whereIn('dbo.Financeiro_Adiantamento_Status.id', array(1,2,4,5,7,8))
       ->where('dbo.Financeiro_Adiantamento_Matrix.cpf', $usuario_cpf)
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
             'Hist_Notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

       return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.solicitacoesadiantamento', compact('usuario_cpf','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_historico($usuario_cpf) {

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
               'dbo.Financeiro_Adiantamento_Matrix.numdoc as CPR',
               'dbo.users.name as solicitante_nome',
               'dbo.users.id as solicitante_id',
               'dbo.Financeiro_Adiantamento_Matrix.numerodebite as NumeroDebite',
               'dbo.Financeiro_Adiantamento_Matrix.pasta as Pasta',
               'dbo.Financeiro_Adiantamento_Matrix.data',
               'dbo.Financeiro_Adiantamento_Matrix.valor_original',
               'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
               'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
               'dbo.Financeiro_Adiantamento_Status.descricao as status',
               'dbo.Financeiro_Adiantamento_Status.id as StatusID')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->whereIn('dbo.Financeiro_Adiantamento_Status.id', array(3,6))
       ->where('dbo.Financeiro_Adiantamento_Matrix.cpf', $usuario_cpf)
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
             'Hist_Notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

       return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.historico', compact('usuario_cpf','datas', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_revisarsolicitacao($id) {


        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.id as SolicitanteID',
                 'dbo.users.cpf as SolicitanteCPF',
                 'dbo.users.email as SolicitanteEmail',
                 'dbo.Financeiro_Adiantamento_Matrix.id',
                 'dbo.Financeiro_Adiantamento_Matrix.numdoc as Numdoc',
                 'dbo.Financeiro_Adiantamento_Matrix.data as DataSolicitacao',
                 'dbo.Financeiro_Adiantamento_Matrix.valor_original',
                 'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
                 'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
                 'dbo.Financeiro_Adiantamento_Matrix.observacao as Observacao',
                 'dbo.Financeiro_Adiantamento_Status.descricao as status',
                 'dbo.Financeiro_Adiantamento_Matrix.pasta as Pasta',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SolicitanteSetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SolicitanteSetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Codigo as SolicitanteUnidadeCodigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as SolicitanteUnidadeDescricao',
                 'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
                 'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
                 'PLCFULL.dbo.Jurid_Banco.Agencia',
                 'PLCFULL.dbo.Jurid_Banco.Conta',
                 'PLCFULL.dbo.Jurid_Banco.moeda as Moeda')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Banco.CodAdvogado')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->where('dbo.Financeiro_Adiantamento_Matrix.id', $id)
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
        ->get();


       return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarsolicitacao', compact('datahoje','datas','motivos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_solicitacaorevisada(Request $request) {


        $solicitante_cpf = $request->get('solicitantecpf');
        $solicitante_id = $request->get('solicitanteid');
        $solicitante_email = $request->get('solicitanteemail');
        $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
        $setor = $request->get('solicitantesetor');
        $unidade = $request->get('solicitanteunidade');
        $datapagamento = $request->get('datapagamento');
        $moeda = $request->get('moeda');
        $portador = $request->get('portador');
        $observacao = $request->get('observacao');
        $id = $request->get('id');
        $statusescolhido = $request->get('statusescolhido');
        $carbon= Carbon::now();
        $dataehora = $carbon->format('d-m-Y H:i:s');


        //Se a opção escolhida foi: APROVAR
        if($statusescolhido == "aprovar") {



        // //Grava na GED o comprovante de transferência
        // $image = $request->file('select_file');
        // $new_name = 'comprovante_'.$numdoc . '_' . $carbon . '.'  . $image->getClientOriginalExtension();
        // $image->storeAs('adiantamento', $new_name);
                
        // $values = array(
        //     'Tabela_OR' => 'Baixa',
        //     'Codigo_OR' => $contprbx_ident,
        //     'Id_OR' => $contprbx_ident,
        //     'Descricao' => $image->getClientOriginalName(),
        //     'Link' => '\\\192.168.1.65\advwin\ged\vault$\Baixa/'.$new_name, 
        //     'Data' => $carbon,
        //     'Nome' => $image->getClientOriginalName(),
        //     'Responsavel' => 'portal.plc',
        //     'Arq_tipo' => $image->getClientOriginalExtension(),
        //     'Arq_Versao' => '1',
        //     'Arq_Status' => 'Guardado',
        //     'Arq_usuario' => 'portal.plc',
        //     'Arq_nick' => $new_name,
        //     'Obs' => $observacao,
        //     'Texto' => 'Comprovante de adiantamento realizado pelo portal PLC');
        // DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        // $values = array(
        //     'Tabela_OR' => 'Baixa',
        //     'Codigo_OR' => $id,
        //     'Id_OR' => $id,
        //     'Descricao' => $image->getClientOriginalName(),
        //     'Link' => '\\\192.168.1.65\advwin\ged\vault$\Debite/'.$new_name, 
        //     'Data' => $carbon,
        //     'Nome' => $image->getClientOriginalName(),
        //     'Responsavel' => 'portal.plc',
        //     'Arq_tipo' => $image->getClientOriginalExtension(),
        //     'Arq_Versao' => '1',
        //     'Arq_Status' => 'Guardado',
        //     'Arq_usuario' => 'portal.plc',
        //     'Arq_nick' => $new_name,
        //     'Obs' => $observacao,
        //     'Texto' => 'Comprovante de adiantamento realizado pelo portal PLC');
        // DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        //Atualiza a Matrix
        DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->where('id', $id)
        ->limit(1)
        ->update(array('status_id' => '7'));

        $id_matrix = DB::table('dbo.Financeiro_Adiantamento_Matrix')->select('id')->orderBy('id', 'desc')->value('id'); 


        //Grava na Hist
        $values = array('user_id' => Auth::user()->id, 
        'id_matrix' => $id, 
        'data' => $carbon, 
        'status_id' => '7');
        DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

        //Envia notificação e e-mail
        $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Adiantamento aprovado com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '16', 'obs' => 'Adiantamento: Sua solicitação de adiantamento foi aprovada com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        //Fim opção: APROVAR

        //Se clicar em reprovar
        else if($statusescolhido == "reprovar") {


        } 
        //Fim opção: REPROVAR
        else {

            //Update Matrix 
            DB::table('dbo.Financeiro_Adiantamento_Matrix')
            ->where('id', $id)
            ->limit(1)
            ->update(array('status_id' => '6'));

            //Grava na Hist 
            $values = array('user_id' => Auth::user()->id, 
            'id_matrix' => $id, 
            'data' => $carbon, 
            'status_id' => '6');
            DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

            //Manda notificação e e-mail
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Solicitação de adiantamento cancelada com sucesso.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);
    
            $values4= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '16', 'obs' => 'Adiantamento: Solicitação de adiantamento cancelada com sucesso.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

        }
        //Fim opção: CANCELAR

        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index');

    }

    public function financeiro_transferencia($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
                 'dbo.users.name as SolicitanteNome',
                 'dbo.users.id as SolicitanteID',
                 'dbo.users.cpf as SolicitanteCPF',
                 'dbo.users.email as SolicitanteEmail',
                 'dbo.Financeiro_Adiantamento_Matrix.id',
                 'dbo.Financeiro_Adiantamento_Matrix.numdoc as Numdoc',
                 'dbo.Financeiro_Adiantamento_Matrix.data as DataSolicitacao',
                 'dbo.Financeiro_Adiantamento_Matrix.valor_original',
                 'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
                 'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
                 'dbo.Financeiro_Adiantamento_Matrix.observacao as Observacao',
                 'dbo.Financeiro_Adiantamento_Status.descricao as status',
                 'dbo.Financeiro_Adiantamento_Matrix.pasta as Pasta',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as SolicitanteSetorCodigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as SolicitanteSetorDescricao',
                 'PLCFULL.dbo.Jurid_Unidade.Codigo as SolicitanteUnidadeCodigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as SolicitanteUnidadeDescricao',
                 'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
                 'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
                 'PLCFULL.dbo.Jurid_Banco.Agencia',
                 'PLCFULL.dbo.Jurid_Banco.Conta',
                 'PLCFULL.dbo.Jurid_Banco.moeda as Moeda')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Banco.CodAdvogado')
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->where('dbo.Financeiro_Adiantamento_Matrix.id', $id)
       ->first();

       $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')
       ->where('Tipo','P')
       ->where('Codigo', 'NOT LIKE', '%.')
       ->orderby('Codigo', 'asc')
       ->get();  

       $motivos = DB::table('dbo.Jurid_Nota_Motivos')
       ->where('ativo','=','S')
       ->get();

       $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
       ->where('DaEmpresa', '0')
       ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
       ->where('Status', '1')
       ->orderby('Descricao', 'ASC')
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
        ->get();


       return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.transferencia', compact('datahoje','datas','tiposlan','motivos','bancos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function financeiro_transferenciastore(Request $request) {

        $solicitante_cpf = $request->get('solicitantecpf');
        $solicitante_id = $request->get('solicitanteid');
        $solicitante_email = $request->get('solicitanteemail');
        $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
        $setor = $request->get('solicitantesetor');
        $unidade = $request->get('solicitanteunidade');
        $datapagamento = $request->get('datapagamento');
        $moeda = $request->get('moeda');
        $portador = $request->get('portador');
        $observacao = $request->get('observacao');
        $id = $request->get('id');
        $statusescolhido = $request->get('statusescolhido');
        $carbon= Carbon::now();
        $dataehora = $carbon->format('d-m-Y H:i:s');
        $tipolan = $request->get('tipolan');


        //Insert ContPrBx para colocar o saldo
        $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 

        //Grava na ContPrBx
        $values = array('Tipodoc' => 'TRF', 
                        'Numdoc' => $numdoc, 
                        'Cliente' => $solicitante_cpf, 
                        'Tipo' => 'R',
                        'TipodocOr' => 'TRF',
                        'NumDocOr' => $numdoc,
                        'Tipolan' => $tipolan,
                        'Valor' => $valor,
                        'Centro' => $setor,
                        'Dt_baixa' => $datapagamento,
                        'Portador' => $portador,
                        'Obs' => $observacao,
                        'Dt_Compet' => $carbon->format('Y-m-d'),
                        'Dt_Concil' => $carbon->format('Y-m-d'),
                        'Unidade' => $unidade,
                        'Ident_Rate' => '0',
                        'hist_usuario' => 'portal.plc',
                        'dataCambioBX' => $datapagamento,
                        'valorCambioBX' => $valor,
                        'moeda' => $moeda);
        DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

        $contprbx_ident = DB::table('PLCFULL.dbo.Jurid_ContPrBx')->select('B_Ident')->where('Numdoc', $numdoc)->orderby('Numdoc', 'desc')->value('B_Ident'); 

        $numprcNovo = $numdoc + 1;
        DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));

        // //Grava na GED o comprovante de transferência
        // $image = $request->file('select_file');
        // $new_name = 'comprovante_'.$numdoc . '_' . $carbon . '.'  . $image->getClientOriginalExtension();
        // $image->storeAs('adiantamento', $new_name);
                
        // $values = array(
        //     'Tabela_OR' => 'Baixa',
        //     'Codigo_OR' => $contprbx_ident,
        //     'Id_OR' => $contprbx_ident,
        //     'Descricao' => $image->getClientOriginalName(),
        //     'Link' => '\\\192.168.1.65\advwin\ged\vault$\Baixa/'.$new_name, 
        //     'Data' => $carbon,
        //     'Nome' => $image->getClientOriginalName(),
        //     'Responsavel' => 'portal.plc',
        //     'Arq_tipo' => $image->getClientOriginalExtension(),
        //     'Arq_Versao' => '1',
        //     'Arq_Status' => 'Guardado',
        //     'Arq_usuario' => 'portal.plc',
        //     'Arq_nick' => $new_name,
        //     'Obs' => $observacao,
        //     'Texto' => 'Comprovante de adiantamento realizado pelo portal PLC');
        // DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        // $values = array(
        //     'Tabela_OR' => 'Baixa',
        //     'Codigo_OR' => $id,
        //     'Id_OR' => $id,
        //     'Descricao' => $image->getClientOriginalName(),
        //     'Link' => '\\\192.168.1.65\advwin\ged\vault$\Debite/'.$new_name, 
        //     'Data' => $carbon,
        //     'Nome' => $image->getClientOriginalName(),
        //     'Responsavel' => 'portal.plc',
        //     'Arq_tipo' => $image->getClientOriginalExtension(),
        //     'Arq_Versao' => '1',
        //     'Arq_Status' => 'Guardado',
        //     'Arq_usuario' => 'portal.plc',
        //     'Arq_nick' => $new_name,
        //     'Obs' => $observacao,
        //     'Texto' => 'Comprovante de adiantamento realizado pelo portal PLC');
        // DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        //Atualiza a Matrix
        DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->where('id', $id)
        ->limit(1)
        ->update(array('numdoc' => $numdoc,'status_id' => '2'));

        //Grava na Hist
        $values = array('user_id' => Auth::user()->id, 
        'id_matrix' => $id, 
        'data' => $carbon, 
        'status_id' => '2');
        DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

        //Envia notificação e e-mail
        $values4= array('data' => $carbon, 'id_ref' => $numdoc, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Adiantamento atualizado com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        $values4= array('data' => $carbon, 'id_ref' => $numdoc, 'user_id' => Auth::user()->id, 'destino_id' => $solicitante_id, 'tipo' => '16', 'obs' => 'Adiantamento: Sua solicitação de adiantamento foi atualizado com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);
 
        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index');

    }

    public function financeiro_movimentacaobancaria(Request $request) {

        $datainicio = $request->get('datainicio');
        $datafim = $request->get('datafim');
        $banco = $request->get('banco');
        $usuario_cpf = $request->get('usuario_cpf');
        $carbon= Carbon::now();

        $debito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
        ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
        ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
        ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
        ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $usuario_cpf)
        ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
        ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));            
          
        $credito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
        ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
        ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
        ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
        ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $usuario_cpf)
        ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
        ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
          
        $saldo = $debito - $credito;

        $banco_descricao = DB::table('PLCFULL.dbo.Jurid_Banco')
        ->select('PLCFULL.dbo.Jurid_Banco.Descricao')
        ->where('PLCFULL.dbo.Jurid_Banco.codAdvogado', $usuario_cpf)
        ->value('PLCFULL.dbo.Jurid_Banco.Descricao');

        $datas = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
        ->select('PLCFULL.dbo.Jurid_ContPrBx.Tipodoc as Tipo',
                 'PLCFULL.dbo.Jurid_ContPrBx.Numdoc',
                 'PLCFULL.dbo.Jurid_ContPrBx.Tipo as Tipo',
                 'PLCFULL.dbo.Jurid_ContPrBx.NumDocOr as Origem',
                 'PLCFULL.dbo.Jurid_ContPrBx.Dt_Baixa as Data',
                 'PLCFULL.dbo.Jurid_ContPrBx.Moeda',
                 'PLCFULL.dbo.Jurid_ContPrBx.Valor',
                 'PLCFULL.dbo.Jurid_TipoLan.Descricao as Tipolan',
                 'PLCFULL.dbo.Jurid_ContPrBx.Obs as Observacao')
        ->leftjoin('PLCFULL.dbo.Jurid_TipoLan', 'PLCFULL.dbo.Jurid_ContPrBx.Tipolan', 'PLCFULL.dbo.Jurid_Tipolan.Codigo')
        ->whereBetween('PLCFULL.dbo.Jurid_ContPrBx.Dt_Baixa', [$datainicio, $datafim])
        ->where('PLCFULL.dbo.Jurid_ContPrBx.Cliente', $usuario_cpf)
        ->orwhere('PLCFULL.dbo.Jurid_ContPrBx.Portador', $usuario_cpf)
        ->whereBetween('PLCFULL.dbo.Jurid_ContPrBx.Dt_Baixa', [$datainicio, $datafim])
        ->get();

        return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.movimentacaobancaria', compact('saldo','datas','banco_descricao','datainicio', 'datafim'));

    }

    public function financeiro_revisarprestacaodeconta($usuario_cpf, $id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    
        $dataehora = $carbon->format('dmY_HHis');

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
               'dbo.users.name as solicitante_nome',
               'dbo.users.id as solicitante_id',
               'dbo.users.cpf as solicitante_cpf',
               'dbo.users.email as solicitante_email',
               'dbo.Financeiro_Adiantamento_Matrix.numdoc as Numdoc',
               'dbo.Financeiro_Adiantamento_Matrix.data as DataSolicitacao',
               'PLCFULL.dbo.Jurid_ContPrBx.Dt_Baixa as DataPagamento',
               'dbo.Financeiro_Adiantamento_Matrix.cpf as usuario_cpf',
               'dbo.Financeiro_Adiantamento_Matrix.valor_original',
               'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
               'dbo.Financeiro_Adiantamento_Matrix.valor_prestado',
               'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
               'dbo.Financeiro_Adiantamento_Matrix.observacao as Observacao',
               'dbo.Financeiro_Adiantamento_Matrix.anexo_comprovantedevolucao',
               'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
               'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
               'PLCFULL.dbo.Jurid_Banco.Agencia',
               'PLCFULL.dbo.Jurid_Banco.Conta',
               'PLCFULL.dbo.Jurid_Banco.moeda as Moeda',
               'dbo.Financeiro_Adiantamento_Status.descricao as status')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'dbo.Financeiro_Adiantamento_Matrix.numdoc', 'PLCFULL.dbo.Jurid_ContPrBx.Numdoc')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_ContPrBx.Portador', 'PLCFULL.dbo.Jurid_Banco.Codigo')
       ->where('dbo.Financeiro_Adiantamento_Matrix.id', $id)
       ->first();

       $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
       ->where('DaEmpresa', '0')
       ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
       ->where('Status', '1')
       ->orderby('Descricao', 'ASC')
       ->get();   

       $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')->where('Tipo','P')->orderby('Codigo', 'asc')->get();  
       $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  


       $dadosusuario = DB::table('PLCFULL.dbo.Jurid_Banco')
       ->select(
              'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
              'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
              'PLCFULL.dbo.Jurid_Banco.Agencia',
              'PLCFULL.dbo.Jurid_Banco.Conta',
              'PLCFULL.dbo.Jurid_Banco.moeda as Moeda')
      ->where('PLCFULL.dbo.Jurid_Banco.codAdvogado', $usuario_cpf)
      ->first();

       $bancocodigo = $datas->CodigoBanco;

       $debitoplc = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $bancocodigo)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           

       $creditoplc = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $bancocodigo)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
         
       $saldoplc = $debitoplc - $creditoplc;


       $debites = DB::table('dbo.Financeiro_Adiantamento_lancamentos')
       ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                'PLCFULL.dbo.Jurid_Debite.Data as Data',
                'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                'PLCFULL.dbo.Jurid_Debite.Hist',
                'PLCFULL.dbo.Jurid_Debite.Cliente as ClienteCodigo',
                'PLCFULL.dbo.Jurid_Debite.Pasta',
                'PLCFULL.dbo.Jurid_Debite.Setor as SetorCodigo',
                'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                'PLCFULL.dbo.Jurid_Debite.Unidade as UnidadeCodigo',
                'dbo.Financeiro_Adiantamento_lancamentos.status_cobravel',
                'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite')
       ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.Financeiro_Adiantamento_lancamentos.numerodebite', 'PLCFULL.dbo.Jurid_Debite.Numero')
       ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
       ->where('id_matrix','=',$id)
       ->where('status_id', '1')
       ->get();

       $debito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $usuario_cpf)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           
         
       $credito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $usuario_cpf)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
         
       $saldo = $credito - $debito;

            
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
       'Hist_Notificacao.status', 
       'dbo.users.*')  
       ->limit(3)
       ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
       ->where('dbo.Hist_Notificacao.status','=','A')
       ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
       ->orderBy('dbo.Hist_Notificacao.data', 'desc')
       ->get();
        

        return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.revisarprestacaoconta', compact('datahoje','tiposlan','tiposdoc','bancos','saldo','saldoplc','datas','debites','motivos','dadosusuario','totalNotificacaoAbertas', 'notificacoes', 'dataehora'));

    }

    public function financeiro_revisarprestacaodeconta_store(Request $request) {


        $id_matrix = $request->get('id_matrix');
        $solicitanteemail = $request->get('solicitanteemail');
        $solicitantecpf = $request->get('solicitantecpf');
        $solicitanteid = $request->get('solicitanteid');
        $tiposervicodescricao = $request->get('tiposervicodescricao');
        $valor_pendente = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_pendente')));
        $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));
        $valor_prestado = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_prestado')));
        $carbon= Carbon::now();
        $numerodebite = $request->get('numerodebite');
        $data = $request->get('data');
        $tipodebite = $request->get('tipodebite');
        $motivo = $request->get('motivo');
        $motivodescricao = $request->get('motivodescricao');
        $statusescolhido = $request->get('statusescolhido');
        $pasta = $request->get('pasta');
        $numeroprocesso = $request->get('numeroprocesso');
        $unidade = $request->get('unidade');
        $setor = $request->get('setor');
        $usuario_cpf = $request->get('usuario_cpf');
        $numdocor = $request->get('numdocor');

        $hist = $request->get('hist');
        $histreprovada = $request->get('histreprovada');
        $histcancelada = $request->get('histcancelada');
        $observacao = $request->get('observacao');

        $opcaorecebervalor = $request->get('valordevolvido');
        $opcaodevolvervalor = $request->get('retornarvalor');

        $setorsolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Setor')->where('Codigo', $usuario_cpf)->value('Setor'); 
        $unidadesolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Unidade')->where('Codigo', $usuario_cpf)->value('Unidade'); 


        $banco = DB::table('PLCFULL.dbo.Jurid_ContPrBx')->select('Portador')->where('Numdoc', $numdocor)->value('Portador'); 
        $tipolan = $request->get('tipolan');
        $tipodoc = $request->get('tipodoc');

        $statusreembolso = $request->get('statusreembolso');
        $comprovantetransferencia = $request->get('comprovantedevolucao');
        $bancodevolucao = $request->get('bancodevolucao');
        $datadevolucao = $request->get('datadevolucao');

        //Se foi aprovado da baixa no Debite e cria ContPrBx
        if($statusescolhido == "aprovar") {

            //Foreach dos debites
            foreach($numerodebite as $index => $numerodebite) {


                    //Se for marcado que é reembolsavel pelo cliente
                    if($statusreembolso[$index] == "Sim") {

                       //Update no Debite
                       DB::table('PLCFULL.dbo.Jurid_Debite')
                       ->where('Numero', $numerodebite)  
                       ->limit(1) 
                       ->update(array('Status' => '0',  'Hist' => $hist[$index], 'Revisado_DB' => '1'));
                      } else {
                       ///Update no Debite
                       DB::table('PLCFULL.dbo.Jurid_Debite')
                       ->where('Numero', $numerodebite)  
                       ->limit(1) 
                       ->update(array('Status' => '2',  'Hist' => $hist[$index], 'Revisado_DB' => '1'));
                      }

            }

            DB::table('dbo.Financeiro_Adiantamento_Matrix')
            ->where('id', $id_matrix)  
            ->limit(1) 
            ->update(array('valor_atual' => '0.00',  'valor_prestado' => $valor_prestado, 'status_id' => '8'));

           //Grava na Hist
            $values = array('user_id' => Auth::user()->id, 
            'id_matrix' => $id_matrix, 
            'data' => $carbon, 
            'status_id' => '8');
             DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

           //Envia notificação
           $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Prestação de conta aprovada com sucesso.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);

           $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '16', 'obs' => 'Adiantamento: Prestação de conta aprovada com sucesso.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);

           //Envia e-mail

        }

      
        //Se foi reprovado
        else if($statusescolhido == "reprovar") {

            //Update Financeiro_lancamentos


            //Update Matrix

            //Grava na Hist

            //Envia notificação 
            
            //Envia e-mail informando


        }
        //Se foi cancelado
        else {

            //Cancela o debite
            DB::table('PLCFULL.dbo.Jurid_Debite')
            ->where('Numero', $numerodebite)  
            ->limit(1) 
            ->update(array('Status' => '3',  'Hist' => $histcancelada, 'DebPago' => 'N', 'Revisado_DB' => '1'));

            //Update Financeiro_lancamentos
            DB::table('dbo.Financeiro_Adiantamento_lancamentos')
            ->where('numerodebite', $numerodebite)  
            ->limit(1) 
            ->update(array('status_id' => '4'));

            //Update Matrix 
            DB::table('dbo.Financeiro_Adiantamento_Matrix')
            ->where('id', $id_matrix)  
            ->limit(1) 
            ->update(array('status_id' => '2'));
            
            //Grava na Hist
            $values = array('user_id' => Auth::user()->id, 
            'id_matrix' => $id, 
            'data' => $carbon, 
            'status_id' => '2');
            DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

            //Envia notificação 
            $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Prestação de conta cancelada foi cancelada pela equipe do financeiro.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

            $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '16', 'obs' => 'Adiantamento: Prestação de conta cancelada foi cancelada pela equipe do financeiro.' ,'status' => 'A');
            DB::table('dbo.Hist_Notificacao')->insert($values4);

            //Envia e-mail informando

        }

        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index');

    }

    public function financeiro_baixar($usuario_cpf, $id) {

        
        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    
        $dataehora = $carbon->format('dmY_HHis');

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
               'dbo.users.name as solicitante_nome',
               'dbo.users.id as solicitante_id',
               'dbo.users.cpf as solicitante_cpf',
               'dbo.users.email as solicitante_email',
               'dbo.Financeiro_Adiantamento_Matrix.numdoc as Numdoc',
               'dbo.Financeiro_Adiantamento_Matrix.data as DataSolicitacao',
               'PLCFULL.dbo.Jurid_ContPrBx.Dt_Baixa as DataPagamento',
               'dbo.Financeiro_Adiantamento_Matrix.cpf as usuario_cpf',
               'dbo.Financeiro_Adiantamento_Matrix.valor_original',
               'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
               'dbo.Financeiro_Adiantamento_Matrix.valor_prestado',
               'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
               'dbo.Financeiro_Adiantamento_Matrix.observacao as Observacao',
               'dbo.Financeiro_Adiantamento_Matrix.anexo_comprovantedevolucao',
               'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
               'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
               'PLCFULL.dbo.Jurid_Banco.Agencia',
               'PLCFULL.dbo.Jurid_Banco.Conta',
               'PLCFULL.dbo.Jurid_Banco.moeda as Moeda',
               'dbo.Financeiro_Adiantamento_Status.descricao as status')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'dbo.Financeiro_Adiantamento_Matrix.numdoc', 'PLCFULL.dbo.Jurid_ContPrBx.Numdoc')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_ContPrBx.Portador', 'PLCFULL.dbo.Jurid_Banco.Codigo')
       ->where('dbo.Financeiro_Adiantamento_Matrix.id', $id)
       ->first();

       $bancos = DB::table('PLCFULL.dbo.Jurid_Banco')->select('Codigo', 'Descricao')
       ->where('DaEmpresa', '0')
       ->whereIn('Codigo', array('001', '002', '003', '004', '005', '007', '010', '011', '027', '028', '034', '041'))
       ->where('Status', '1')
       ->orderby('Descricao', 'ASC')
       ->get();   

       $tiposlan = DB::table('PLCFULL.dbo.Jurid_TipoLan')->where('Ativo', '1')->where('Tipo','P')->orderby('Codigo', 'asc')->get();  
       $tiposdoc = DB::table('PLCFULL.dbo.Jurid_TipoDoc')->orderby('Descricao', 'asc')->get();  


       $dadosusuario = DB::table('PLCFULL.dbo.Jurid_Banco')
       ->select(
              'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
              'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
              'PLCFULL.dbo.Jurid_Banco.Agencia',
              'PLCFULL.dbo.Jurid_Banco.Conta',
              'PLCFULL.dbo.Jurid_Banco.moeda as Moeda')
      ->where('PLCFULL.dbo.Jurid_Banco.codAdvogado', $usuario_cpf)
      ->first();

       $bancocodigo = $datas->CodigoBanco;

       $debitoplc = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $bancocodigo)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           

       $creditoplc = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $bancocodigo)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
         
       $saldoplc = $debitoplc - $creditoplc;


       $debites = DB::table('dbo.Financeiro_Adiantamento_lancamentos')
       ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                'PLCFULL.dbo.Jurid_Debite.Data as Data',
                'PLCFULL.dbo.Jurid_Debite.ValorT as Valor',
                'PLCFULL.dbo.Jurid_Debite.Obs as Observacao',
                'PLCFULL.dbo.Jurid_Debite.Hist',
                'PLCFULL.dbo.Jurid_Debite.Cliente as ClienteCodigo',
                'PLCFULL.dbo.Jurid_Debite.Pasta',
                'PLCFULL.dbo.Jurid_Debite.Setor as SetorCodigo',
                'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
                'PLCFULL.dbo.Jurid_Debite.Unidade as UnidadeCodigo',
                'PLCFULL.dbo.Jurid_Debite.Status as status_cobravel',
                'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite')
       ->leftjoin('PLCFULL.dbo.Jurid_Debite', 'dbo.Financeiro_Adiantamento_lancamentos.numerodebite', 'PLCFULL.dbo.Jurid_Debite.Numero')
       ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Debite.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Debite.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
       ->where('id_matrix','=',$id)
       ->where('status_id', '1')
       ->get();

       $debito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', $usuario_cpf)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'P')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           
         
       $credito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', $usuario_cpf)
       ->where('PLCFULL.dbo.Jurid_contprbx.Tipo', '=', 'R')
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
         
       $saldo = $credito - $debito;

            
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
       'Hist_Notificacao.status', 
       'dbo.users.*')  
       ->limit(3)
       ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
       ->where('dbo.Hist_Notificacao.status','=','A')
       ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
       ->orderBy('dbo.Hist_Notificacao.data', 'desc')
       ->get();
        

        return view('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.baixar', compact('datahoje','tiposlan','tiposdoc','bancos','saldo','saldoplc','datas','debites','motivos','dadosusuario','totalNotificacaoAbertas', 'notificacoes', 'dataehora'));


    }

    public function financeiro_baixado(Request $request) {


        $id_matrix = $request->get('id_matrix');
        $solicitanteemail = $request->get('solicitanteemail');
        $solicitantecpf = $request->get('solicitantecpf');
        $solicitanteid = $request->get('solicitanteid');
        $tiposervicodescricao = $request->get('tiposervicodescricao');
        $valor_pendente = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_pendente')));
        $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));
        $valor_prestado = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_prestado')));
        $carbon= Carbon::now();
        $numerodebite = $request->get('numerodebite');
        $data = $request->get('data');
        $tipodebite = $request->get('tipodebite');
        $statusescolhido = $request->get('statusescolhido');
        $pasta = $request->get('pasta');
        $numeroprocesso = $request->get('numeroprocesso');
        $unidade = $request->get('unidade');
        $setor = $request->get('setor');
        $usuario_cpf = $request->get('usuario_cpf');
        $numdocor = $request->get('numdocor');

        $hist = $request->get('hist');
        $observacao = $request->get('observacao');

        $setorsolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Setor')->where('Codigo', $usuario_cpf)->value('Setor'); 
        $unidadesolicitante = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Unidade')->where('Codigo', $usuario_cpf)->value('Unidade'); 

        $banco = DB::table('PLCFULL.dbo.Jurid_ContPrBx')->select('Portador')->where('Numdoc', $numdocor)->value('Portador'); 
        $tipolan = $request->get('tipolan');
        $tipodoc = $request->get('tipodoc');

        $statusreembolso = $request->get('statusreembolso');

        $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr');

        //Retira do do saldo 
        $values = array('Tipodoc' => 'TRF', 
        'Numdoc' => $numdoc, 
        'Cliente' => $banco, 
        'Tipo' => 'R',
        'TipodocOr' => 'TRF',
        'NumDocOr' => $numdoc,
        'Tipolan' => $tipolan,
        'Valor' => $valor_prestado,
        'Centro' => $setorsolicitante,
        'Dt_baixa' => $carbon->format('Y-m-d'),
        'Portador' => $usuario_cpf,
        'Obs' => 'Baixa referente a solicitação de adiantamento: ' . $id_matrix . ', cujo o valor de R$ ' . $valor_prestado . ' foi prestado conta.',
        'Dt_Compet' => $carbon->format('Y-m-d'),
        'Dt_Concil' => $carbon->format('Y-m-d'),
        'Unidade' => $unidadesolicitante,
        'Ident_Rate' => '0',
        'hist_usuario' => 'portal.plc',
        'dataCambioBX' => $carbon->format('Y-m-d'),
        'valorCambioBX' => $valor_prestado,
        'moeda' => 'R$');
         DB::table('PLCFULL.dbo.Jurid_ContPrBx')->insert($values);

         $numprcNovo = $numdoc + 1;
         DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));

            //Foreach dos debites
            foreach($numerodebite as $index => $numerodebite) {

                $numdoc = DB::table('PLCFULL.dbo.Jurid_Default_')->select('Numcpr')->value('Numcpr'); 

                //Gera uma CPR
                $values= array(
                    'Tipodoc' => $tipodoc,
                    'Numdoc' => $numdoc,
                    'Cliente' => $banco,
                    'Tipo' => 'R',
                    'Centro' => $setor[$index],
                    'Valor' => $valor[$index],
                    'Dt_aceite' => $carbon->format('Y-m-d'),
                    'Dt_Vencim' => $carbon->format('Y-m-d'),
                    'Dt_Progr' => $carbon->format('Y-m-d'),
                    'Multa' => '0',
                    'Juros' => '0',
                    'Tipolan' => $tipolan,
                    'Desconto' => '0',
                    'Baixado' => '1',
                    'Portador' => $usuario_cpf,
                    'Status' => '2',
                    'Historico' => 'Baixa de debite referente a prestação de conta criada no portal plc no valor de: R$ ' . number_format($valor[$index],2,",","."),
                    'Obs' => $observacao[$index],
                    'Valor_Or' => $valor[$index],
                    'Dt_Digit' => $carbon->format('Y-m-d'),
                    'Codigo_Comp' => $pasta[$index],
                    'Unidade' => $unidade[$index],
                    'Moeda' => 'R$',
                    'CSLL' => '0.00',
                    'COFINS' => '0.00',
                    'PIS' => '0.00',
                    'ISS' => '0.00',
                    'INSS' => '0.00',
                    'Contrato' => '',
                    'Origem_cpr' => $id_matrix,
                    'numprocesso' => $numeroprocesso[$index],
                    'cod_pasta' => $pasta[$index]);
                    DB::table('PLCFULL.dbo.Jurid_ContaPr')->insert($values);

                    $contprbx_ident = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Cpr_ident')->where('Numdoc', $numdoc)->orderby('Numdoc', 'desc')->value('Cpr_ident'); 
                    $numprcNovo = $numdoc + 1;
                    DB::table('PLCFULL.dbo.Jurid_Default_')->limit(1)->update(array('Numcpr' => $numprcNovo));

                    //Se for marcado que é reembolsavel pelo cliente
                    if($statusreembolso[$index] == "Sim") {

                    ///Update no Debite
                    DB::table('PLCFULL.dbo.Jurid_Debite')
                    ->where('Numero', $numerodebite)  
                    ->limit(1) 
                    ->update(array('Status' => '0',  'Hist' => $hist[$index], 'DebPago' => 'S', 'Revisado_DB' => '1','tipodocpag' => $tipodoc,'numdocpag' => $numdoc, 'datapag' => $data[$index], 'nomebanco' => $usuario_cpf));
                    } else {
                       ///Update no Debite
                       DB::table('PLCFULL.dbo.Jurid_Debite')
                       ->where('Numero', $numerodebite)  
                       ->limit(1) 
                       ->update(array('Status' => '2',  'Hist' => $hist[$index], 'DebPago' => 'S', 'Revisado_DB' => '1','tipodocpag' => $tipodoc,'numdocpag' => $numdoc, 'datapag' => $data[$index], 'nomebanco' => $usuario_cpf));
                    }
                 

                    //Update Financeiro_lancamentos
                     DB::table('dbo.Financeiro_Adiantamento_lancamentos')
                     ->where('numerodebite', $numerodebite)  
                     ->limit(1) 
                     ->update(array('status_id' => '2', 'status_cobravel' => $statusreembolso[$index]));

                }
            //Fim Foreach

           //Update na Matrix
            DB::table('dbo.Financeiro_Adiantamento_Matrix')
            ->where('id', $id_matrix)  
            ->limit(1) 
            ->update(array('valor_atual' => '0.00',  'valor_prestado' => $valor_prestado, 'status_id' => '3'));

           //Grava na Hist
            $values = array('user_id' => Auth::user()->id, 
            'id_matrix' => $id_matrix, 
            'data' => $carbon, 
            'status_id' => '3');
             DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

           //Envia notificação
           $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Prestação de conta aprovada com sucesso.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);

           $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '16', 'obs' => 'Adiantamento: Prestação de conta aprovada com sucesso.' ,'status' => 'A');
           DB::table('dbo.Hist_Notificacao')->insert($values4);

           //Envia e-mail


        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Financeiro.index');

    }

    public function solicitante_index() {

        $carbon= Carbon::now();

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
               'dbo.users.name as solicitante_nome',
               'dbo.users.id as solicitante_id',
               'dbo.Financeiro_Adiantamento_Matrix.data',
               'dbo.Financeiro_Adiantamento_Matrix.valor_original',
               'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
               'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
               'dbo.Financeiro_Adiantamento_Status.descricao as status',
               'dbo.Financeiro_Adiantamento_Status.id as StatusID')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->whereIn('dbo.Financeiro_Adiantamento_Status.id', array(1,2,4,5))
       ->where('dbo.Financeiro_Adiantamento_Matrix.cpf', Auth::user()->cpf)
       ->get();

       $debito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Portador', '=', Auth::user()->cpf)
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
           
       $credito = DB::table('PLCFULL.dbo.Jurid_ContPrBx')
       ->select(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor ) as valor'))
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '<=', $carbon)
       ->where('PLCFULL.dbo.Jurid_contprbx.Dt_baixa', '>=', '20000101')
       ->where('PLCFULL.dbo.Jurid_contprbx.Cliente', '=', Auth::user()->cpf)
       ->value(DB::raw('sum(PLCFULL.dbo.Jurid_ContPrBx.Valor)'));
         
       $valor_aguardandoprestacao =  $debito - $credito;

       $statusatual = DB::table('dbo.Financeiro_Adiantamento_Matrix')
       ->select('id')
       ->where('dbo.Financeiro_Adiantamento_Matrix.cpf','=', Auth::user()->cpf)
       ->where('dbo.Financeiro_Adiantamento_Matrix.status_id', '2')
       ->orderby('id', 'desc')
       ->value('id');

       $verificabanco = DB::table('PLCFULL.dbo.Jurid_Banco')
                       ->select('Codigo')
                       ->where('codAdvogado', Auth::user()->cpf)
                       ->where('Status', '1')
                       ->value('Codigo');    
     
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
             'Hist_Notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

       return view('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index', compact('datas','valor_aguardandoprestacao' ,'verificabanco','statusatual','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function solicitante_cadastrarbanco() {

        $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_cpf',
               'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
               'dbo.users.email as usuario_email',
               'dbo.users.id as usuario_id',
               'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
               'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
               'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'dbo.users.cpf')
       ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=',Auth::user()->cpf)
       ->first();

       $bancos = DB::table('dbo.Financeiro_Bancos')->select('codigo', 'descricao')->orderby('Descricao', 'ASC')->get();    

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
             'Hist_Notificacao.status', 
             'dbo.users.*')  
             ->limit(3)
             ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
             ->where('dbo.Hist_Notificacao.status','=','A')
             ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
             ->orderBy('dbo.Hist_Notificacao.data', 'desc')
             ->get();

       return view('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.cadastrarbanco', compact('datas', 'bancos' ,'totalNotificacaoAbertas', 'notificacoes'));


    }

    public function solicitante_cadastrarbanco_store(Request $request) {

        $carbon= Carbon::now();
        $setor = $request->get('setor');
        $unidade = $request->get('unidade');
        $usuario_cpf = $request->get('usuario_cpf');
        $usuario_id = $request->get('usuario_id');
        $banco = $request->get('banco');
        $agencia = $request->get('agencia');
        $conta = $request->get('conta');

        //Cadastra na Jurid_Banco
        $values = array(
            'Codigo' => $usuario_cpf,
            'Descricao' => 'BANCO ADV - ' . Auth::user()->name,
            'Agencia' => $agencia,
            'Conta' => $conta,
            'DaEmpresa' => '1', 
            'Unidade' => $unidade,
            'b_numerobanco' => $banco,
            'b_agencia' => $agencia,
            'caixa' => '0',
            'codAdvogado' => $usuario_cpf,
            'moeda' => 'R$',
            'contaPertence' => '2',
            'ger_boleto' => '0',
            'NossoNumeroGer' => '1',
            'arq_remessa' => '0',
            'cobranca_registrada' => '0',
            'juros_dias' => '0',
            'percentual_multa' => '0',
            'protestar' => '0',
            'dias_protestar' => '0',
            'protestar_dias_tipo' => '-1',
            'dias_baixa' => '0',
            'Status' => '1');
      DB::table('PLCFULL.dbo.Jurid_Banco')->insert($values);  

        //Relaciona na GED com o Advogado
        $image = $request->file('select_file');

        $new_name = $usuario_cpf . '_dadosbancarios' . '_' . $carbon->format('dmY_His') . '.'  . $image->getClientOriginalExtension();
        $image->storeAs('adiantamento', $new_name);

        //Grava no GED
        $values = array(
              'Tabela_OR' => 'Advogado',
              'Codigo_OR' => $usuario_cpf,
              'Id_OR' => '',
              'Descricao' => $image->getClientOriginalName(),
              'Link' => '\\\192.168.1.65\advwin\ged\vault$\Advogado/'.$new_name, 
              'Data' => $carbon,
              'Nome' => $image->getClientOriginalName(),
              'Responsavel' => 'portal.plc',
              'Arq_tipo' => $image->getClientOriginalExtension(),
              'Arq_Versao' => '1',
              'Arq_Status' => 'Guardado',
              'Arq_usuario' => 'portal.plc',
              'Arq_nick' => $new_name,
              'Obs' => 'Comprovante de conta cadastrada no portal PL&C.');
        DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
        
        //Envia notificação para o usuario confirmando o cadastro
        $values4= array('data' => $carbon, 'id_ref' => $usuario_id, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Dados bancários cadastrados com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index');

    }

    public function solicitante_novasolicitacao() {

        $carbon= Carbon::now();

        $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
        ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_cpf',
               'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
               'dbo.users.email as usuario_email',
               'dbo.users.id as usuario_id',
               'PLCFULL.dbo.Jurid_Setor.Codigo as SetorCodigo',
               'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao',
               'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
               'PLCFULL.dbo.Jurid_Unidade.Descricao as UnidadeDescricao',
               'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
               'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
               'PLCFULL.dbo.Jurid_Banco.Agencia',
               'PLCFULL.dbo.Jurid_Banco.Conta',
               'PLCFULL.dbo.Jurid_Banco.moeda as Moeda')
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
       ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'dbo.users.cpf')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'PLCFULL.dbo.Jurid_Banco.CodAdvogado')
       ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=',Auth::user()->cpf)
       ->first();

       $motivos = DB::table('dbo.Financeiro_Adiantamento_Motivos')
       ->where('Status','=','A')
       ->orderby('descricao', 'asc')
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
        ->get();
        

        return view('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.novoadiantamento', compact('datas','motivos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function solicitante_novoadiantamento_store(Request $request) {

        $carbon= Carbon::now();
        $setor = $request->get('setor');
        $unidade = $request->get('unidade');
        $usuario_cpf = $request->get('usuario_cpf');
        $usuario_id = $request->get('usuario_id');
        $data = $request->get('data');
        $motivo_id = $request->get('motivo');
        $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
        $observacao = $request->get('observacao');
        $observacaoadicionais = $request->get('observacaoadicionais');
        $moeda = $request->get('moeda');
        $perguntacliente = $request->get('perguntacliente');
        $pasta = $request->get('pasta');
        $cliente = $request->get('cliente');

        $verificapermissao =  DB::table('dbo.users')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '17')
        ->where('dbo.profile_user.user_id', Auth::user()->id)
        ->first();  


        $image = $request->file('select_file');

        //Se o solicitante for um Advogado, deve passar pela revisão técnica
        if($verificapermissao != null) {

        if($image != null) {

            $new_name = 'solicitacaoadiantamento' . '_' . $carbon->format('dmY_His') . '.'  . $image->getClientOriginalExtension();
            $image->storeAs('adiantamento', $new_name);
            Storage::disk('reembolso-local')->put($new_name, File::get($image));

            $values = array('user_id' => Auth::user()->id, 
            'cpf' => $usuario_cpf, 
            'data' => $carbon,
            'valor_original' => $valor,
            'valor_atual' => $valor,
            'status_id' => '1',
            'motivo_id' => $motivo_id,
            'perguntacliente' => $perguntacliente,
            'pasta' => $pasta,
            'observacao' => $observacao . ' ' . $observacaoadicionais,
            'anexo' => $new_name);
            DB::table('dbo.Financeiro_Adiantamento_Matrix')->insert($values);

            $id_matrix = DB::table('dbo.Financeiro_Adiantamento_Matrix')->select('id')->where('user_id', Auth::user()->id)->orderby('id', 'desc')->value('id'); 


            //Grava na GED        
            $values = array(
            'Tabela_OR' => 'Debite',
            'Codigo_OR' => $id_matrix,
            'Id_OR' => $id_matrix,
            'Descricao' => $image->getClientOriginalName(),
            'Link' => '\\192.168.1.65\advwin\portal\portal\reembolso/'.$new_name, 
            'Data' => $carbon,
            'Nome' => $image->getClientOriginalName(),
            'Responsavel' => 'portal.plc',
            'Arq_tipo' => $image->getClientOriginalExtension(),
            'Arq_Versao' => '1',
            'Arq_Status' => 'Guardado',
            'Arq_usuario' => 'portal.plc',
            'Arq_nick' => $new_name,
            'Obs' => $observacao,
            'Texto' => $observacao . ' ' . $observacaoadicionais);
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  

        } else {

            $values = array('user_id' => Auth::user()->id, 
            'cpf' => $usuario_cpf, 
            'data' => $carbon,
            'valor_original' => $valor,
            'valor_atual' => $valor,
            'status_id' => '1',
            'motivo_id' => $motivo_id,
            'perguntacliente' => $perguntacliente,
            'pasta' => $pasta,
            'observacao' => $observacao . ' ' . $observacaoadicionais);
            DB::table('dbo.Financeiro_Adiantamento_Matrix')->insert($values);

        }

        $id_matrix = DB::table('dbo.Financeiro_Adiantamento_Matrix')->select('id')->where('user_id', Auth::user()->id)->orderby('id', 'desc')->value('id'); 

        //Grava na Adiantamento Hist
        $values = array('user_id' => Auth::user()->id, 
                        'id_matrix' => $id_matrix, 
                        'data' => $carbon, 
                        'status_id' => '1');
        DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);


        } 
        //Se não, vai direto ao financeiro
        else {

            if($image != null) {

                $new_name = 'solicitacaoadiantamento' . '_' . $carbon->format('dmY_His') . '.'  . $request->file('select_file')->getClientOriginalExtension(); 
                $image->storeAs('adiantamento', $new_name);
                Storage::disk('reembolso-local')->put($new_name, File::get($image));

                $values = array('user_id' => Auth::user()->id, 
                'cpf' => $usuario_cpf, 
                'data' => $carbon,
                'valor_original' => $valor,
                'valor_atual' => $valor,
                'status_id' => '4',
                'motivo_id' => $motivo_id,
                'perguntacliente' => $perguntacliente,
                'pasta' => $pasta,
                'observacao' => $observacao . ' ' . $observacaoadicionais,
                'anexo' => $new_name);
                DB::table('dbo.Financeiro_Adiantamento_Matrix')->insert($values);

                //Grava na GED
                $id_matrix = DB::table('dbo.Financeiro_Adiantamento_Matrix')->select('id')->where('user_id', Auth::user()->id)->orderby('id', 'desc')->value('id'); 

                $values = array(
                'Tabela_OR' => 'Debite',
                'Codigo_OR' => $id_matrix,
                'Id_OR' => $id_matrix,
                'Descricao' => $image->getClientOriginalName(),
                'Link' => '\\192.168.1.65\advwin\portal\portal\reembolso/'.$new_name, 
                'Data' => $carbon,
                'Nome' => $image->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $observacao,
                'Texto' => $observacao . ' ' . $observacaoadicionais);
                DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);  
    
            } else {
    
                $values = array('user_id' => Auth::user()->id, 
                'cpf' => $usuario_cpf, 
                'data' => $carbon,
                'valor_original' => $valor,
                'valor_atual' => $valor,
                'status_id' => '4',
                'motivo_id' => $motivo_id,
                'perguntacliente' => $perguntacliente,
                'pasta' => $pasta,
                'observacao' => $observacao . ' ' . $observacaoadicionais);
                DB::table('dbo.Financeiro_Adiantamento_Matrix')->insert($values);
    
            }

        $id_matrix = DB::table('dbo.Financeiro_Adiantamento_Matrix')->select('id')->where('user_id', Auth::user()->id)->orderby('id', 'desc')->value('id'); 

        //Grava na Adiantamento Hist
        $values = array('user_id' => Auth::user()->id, 
                        'id_matrix' => $id_matrix, 
                        'data' => $carbon, 
                        'status_id' => '4');
        DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

        }

        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index');

    }

    public function solicitante_realizarprestacao($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');    
        $dataminimo = $carbon->format('Y-m-01');
        $dataehora = $carbon->format('d/m/Y H:i:s');

        $datas = DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->select('dbo.Financeiro_Adiantamento_Matrix.id',
               'dbo.users.name as solicitante_nome',
               'dbo.users.id as solicitante_id',
               'dbo.users.cpf as solicitante_cpf',
               'dbo.users.email as solicitante_email',
               'dbo.Financeiro_Adiantamento_Matrix.numdoc as Numdoc',
               'dbo.Financeiro_Adiantamento_Matrix.data as DataSolicitacao',
               'PLCFULL.dbo.Jurid_ContPrBx.Dt_Baixa as DataPagamento',
               'dbo.Financeiro_Adiantamento_Matrix.valor_original',
               'PLCFULL.dbo.Jurid_ContPrBx.Numdoc',
               'PLCFULL.dbo.Jurid_Banco.Descricao as Banco',
               'PLCFULL.dbo.Jurid_Banco.Agencia as Agencia',
               'PLCFULL.dbo.Jurid_Banco.Conta as Conta',
               'PLCFULL.dbo.Jurid_Banco.cnpj_empresa as BancoCodigo',
               'dbo.Financeiro_Adiantamento_Matrix.valor_atual',
               'dbo.Financeiro_Adiantamento_Matrix.valor_prestado',
               'dbo.Financeiro_Adiantamento_Motivos.descricao as motivo',
               'dbo.Financeiro_Adiantamento_Matrix.observacao as Observacao',
               'dbo.Financeiro_Adiantamento_Status.descricao as status')
       ->leftjoin('dbo.Financeiro_Adiantamento_Status', 'dbo.Financeiro_Adiantamento_Matrix.status_id', 'dbo.Financeiro_Adiantamento_Status.id')
       ->leftjoin('dbo.Financeiro_Adiantamento_Motivos', 'dbo.Financeiro_Adiantamento_Matrix.motivo_id', 'dbo.Financeiro_Adiantamento_Motivos.id')
       ->leftjoin('dbo.users', 'dbo.Financeiro_Adiantamento_Matrix.user_id', 'dbo.users.id')
       ->leftjoin('PLCFULL.dbo.Jurid_ContPrBx', 'dbo.Financeiro_Adiantamento_Matrix.numdoc', 'PLCFULL.dbo.Jurid_ContPrBx.Numdoc')
       ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_ContPrBx.Portador', 'PLCFULL.dbo.Jurid_Banco.Codigo')
       ->where('dbo.Financeiro_Adiantamento_Matrix.id', $id)
       ->first();

       $unidadeuf = DB::table('PLCFULL.dbo.Jurid_Advogado')
                ->select('PLCFULL.dbo.Jurid_Unidade.UF')
                ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)
                ->value('PLCFULL.dbo.Jurid_Unidade.UF');

       $comarcas = DB::table('dbo.PesquisaPatrimonial_Cidades')
       ->join('dbo.Financeiro_Reembolso_UF', 'dbo.PesquisaPatrimonial_Cidades.uf_sigla', 'dbo.Financeiro_Reembolso_UF.UF')
       ->select('municipio')  
       ->get();


        $valor_unitario = DB::table('dbo.Financeiro_Reembolso_UF')->select('valor_km')->where('uf','=', $unidadeuf)->value('valr_km');
        $valor_unitario = number_format($valor_unitario, 2, '.', '');
    
       $permissao =  DB::table('dbo.users')
       ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
       ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
       ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
       ->whereIn('dbo.profiles.id', array(17,18,37,38))
       ->where('dbo.profile_user.user_id', Auth::user()->id)
       ->first();  

       $meubanco = DB::table('PLCFULL.dbo.Jurid_Advogado')
       ->select(
              'PLCFULL.dbo.Jurid_Banco.Codigo as CodigoBanco',
              'PLCFULL.dbo.Jurid_Banco.Descricao as BancoDescricao',
              'PLCFULL.dbo.Jurid_Banco.Agencia',
              'PLCFULL.dbo.Jurid_Banco.Conta',
              'PLCFULL.dbo.Jurid_Banco.moeda as Moeda',
              )
      ->leftjoin('PLCFULL.dbo.Jurid_Banco', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'PLCFULL.dbo.Jurid_Banco.CodAdvogado')
      ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=',Auth::user()->cpf)
      ->first();

       $valor_necessario = $datas->valor_original - $datas->valor_prestado;

       $tiposdebite = DB::table('PLCFULL.dbo.Jurid_TipoDebite')
       ->select('Codigo', 'Descricao')  
       ->where('Status','=','Ativo')
       ->whereIn('PLCFULL.dbo.Jurid_TipoDebite.Codigo', array(1,2,6,11,12,15,16,18,19,21,25,26,29,030))
       ->orderby('Descricao', 'asc')
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
        ->get();

       return view('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.realizarprestacao', compact('dataehora','tiposdebite','comarcas','valor_unitario','datahoje','dataminimo','datas','valor_necessario','meubanco','permissao','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function solicitante_buscarpastas(Request $request) {


        $cliente = $request->get('cliente');

        $response = DB::table('PLCFULL.dbo.Jurid_Pastas')
        ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp', 'Descricao')
        ->where('PLCFULL.dbo.Jurid_Pastas.Cliente', '=', $cliente)
        ->where('PLCFULL.dbo.Jurid_Pastas.Status', '=', 'Ativa')
        ->orderBy('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp', 'ASC')
        ->get();  

        echo $response;

    }

    public function solicitante_realizarprestacao_store(Request $request) {


        $carbon = Carbon::now();
        $solicitanteemail = $request->get('solicitanteemail');
        $solicitantecpf = $request->get('solicitantecpf');
        $solicitanteid = $request->get('solicitanteid');
        $solicitantenome = $request->get('solicitantenome');
        $tiposervico = $request->get('tiposervico');
        $data = $request->get('data');
        $pasta = $request->get('pasta');
        $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_total')));
        $valor_unitario = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_unitario')));
        $valor_prestado = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_prestado')));
        $valor_pendente = str_replace (',', '.', str_replace ('.', '.', $request->get('valor_pendente')));
        $id_matrix = $request->get('id_matrix');
        $observacao = $request->get('observacao');
        $image = $request->file('select_file');
        $numdoc = $request->get('numdoc');
        $statusreembolso = $request->get('statusreembolso');
        $quantidade = str_replace (',', '.', str_replace ('.', '.', $request->get('quantidade')));

        
        //Foreach por prestação de conta
        foreach($pasta as $index => $pasta) {

            $pasta = DB::table('PLCFULL.dbo.Jurid_Pastas')
            ->select('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
            ->where('PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros','=',$pasta)
            ->orWhere('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp', '=', $pasta)
            ->value('PLCFULL.dbo.Jurid_Pastas.Codigo_Comp');

            $prconta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('PRConta')->where('Codigo_Comp','=',$pasta)->value('PRConta');
            $setor = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Setor')->where('Codigo_Comp','=',$pasta)->value('Setor');
            $unidade = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Unidade')->where('Codigo_Comp','=',$pasta)->value('Unidade');
            $numeroprocesso = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('NumPrc1_Sonumeros')->where('Codigo_Comp','=',$pasta)->value('NumPrc1_Sonumeros');
            $tiposervicodescricao = DB::table('PLCFULL.dbo.Jurid_TipoDebite')->select('Descricao')->where('Codigo','=',$tiposervico)->value('Descricao');
            $cliente = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Cliente')->where('Codigo_Comp','=',$pasta)->value('Cliente');

            $ultimonumero = Correspondente::orderBy('Numero', 'desc')->value('Numero'); 
            $numero = $ultimonumero + 1;

            $observacao2 = 'Número da solicitação de lançamento para prestação de conta: ' . $numero . ' Número da baixa direta: ' . $numdoc . ' Solicitante: ' . $solicitantenome . ' Código da pasta: ' . $pasta . ' Tipo de serviço: ' . $tiposervicodescricao . ' Data da solicitação: ' . $carbon->format('d/m/Y') . ' Número do processo: ' . $numeroprocesso . ' Cliente: ' . $cliente . ' PRConta: ' . $prconta . ' ' . $observacao[$index];

            //Gera o debite 
            $model2 = new Correspondente();
            $model2->Numero =  $numero;
            $model2->Advogado = Auth::user()->cpf; 
            $model2->Cliente = $cliente; 
            $model2->Data = $data[$index];
            $model2->Tipo = 'D';
            $model2->Obs =  $observacao2;
            $model2->Status = '4';
            $model2->Hist = 'Número da solicitação: ' . $numero . ' Solicitação de lançamento para prestação de conta inserida pelo(a): '. Auth::user()->name .' no Portal PL&C no valor de: R$ ' . $valor[$index] . ' na data: ' . $carbon->format('d-m-Y H:i:s');
            $model2->ValorT = $valor[$index];
            $model2->Usuario = 'portal.plc';
            $model2->DebPago = 'N';
            $model2->TipoDeb = $tiposervico[$index]; 
            $model2->AdvServ = Auth::user()->cpf;
            $model2->Setor = $setor;
            $model2->Pasta = $pasta;
            $model2->Unidade = $unidade;
            $model2->PRConta = $prconta;
            $model2->Valor_Adv = $valor[$index];
            $model2->Quantidade = $quantidade[$index];
            $model2->ValorUnitario_Adv = $valor_unitario[$index];
            $model2->ValorUnitarioCliente = $valor_unitario[$index];
            $model2->Revisado_DB = '0';
            $model2->moeda = 'R$';
            $model2->save();

            //Anexa os comprovantes
            $new_name = $numero . '_' . $carbon->format('dmY_His') . '.'  . $image[$index]->getClientOriginalExtension();
            $image[$index]->storeAs('adiantamento', $new_name);
            Storage::disk('reembolso-local')->put($new_name, File::get($image[$index]));

            //Grava no GED
            $values = array(
                'Tabela_OR' => 'Debite',
                'Codigo_OR' => $numero,
                'Id_OR' => $numero,
                'Descricao' => $image[$index]->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\ged\vault$\Debite/'.$new_name, 
                'Data' => $carbon,
                'Nome' => $image[$index]->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image[$index]->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $observacao2,
                'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);

            //Grava no GED
            $values = array(
                'Tabela_OR' => 'Baixa',
                'Codigo_OR' => $id_matrix,
                'Id_OR' => $id_matrix,
                'Descricao' => $image[$index]->getClientOriginalName(),
                'Link' => '\\\192.168.1.65\advwin\ged\vault$\Baixa/'.$new_name, 
                'Data' => $carbon,
                'Nome' => $image[$index]->getClientOriginalName(),
                'Responsavel' => 'portal.plc',
                'Arq_tipo' => $image[$index]->getClientOriginalExtension(),
                'Arq_Versao' => '1',
                'Arq_Status' => 'Guardado',
                'Arq_usuario' => 'portal.plc',
                'Arq_nick' => $new_name,
                'Obs' => $observacao2,
                'id_pasta' => DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_Comp','=', $pasta)->value('id_pasta'));
            DB::table('PLCFULL.dbo.Jurid_Ged_Main')->insert($values);

            //Grava na tabela onde relaciona os debites de prestação com a solicitação principal
            $values4= array('id_matrix' => $id_matrix,
                            'user_id' =>  Auth::user()->id,
                            'numerodebite' => $numero, 
                            'status_id' => '1',
                            'status_cobravel' => $statusreembolso[$index]);
            DB::table('dbo.Financeiro_Adiantamento_lancamentos')->insert($values4);

        }
        //Fim Foreach

         DB::table('dbo.Financeiro_Adiantamento_Matrix')
        ->where('id', $id_matrix)  
        ->limit(1) 
        ->update(array('status_id' => '5', 'valor_prestado' => $valor_prestado));
        
        //Grava na Hist 
        $values = array('user_id' => Auth::user()->id, 
                        'id_matrix' => $id_matrix, 
                        'data' => $carbon, 
                        'status_id' => '5');
        DB::table('dbo.Financeiro_Adiantamento_Hist')->insert($values);

        //Envia notificação para o solicitante realizar a revisão do debite
        $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => Auth::user()->id, 'tipo' => '16', 'obs' => 'Adiantamento: Lançamento de prestação de conta criado com sucesso.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        $values4= array('data' => $carbon, 'id_ref' => $id_matrix, 'user_id' => Auth::user()->id, 'destino_id' => $solicitanteid, 'tipo' => '16', 'obs' => 'Adiantamento: Você possui um novo lançamento de prestação de conta para revisão.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

        return redirect()->route('Painel.Financeiro.AdiantamentoPrestacao.Solicitante.index');

        }

    
   
}
