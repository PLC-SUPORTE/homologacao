<?php

namespace App\Http\Controllers\Painel\Gestao;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Mail\Gestao\PrazoContestacao;
use Illuminate\Support\Facades\Mail;
use Excel;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use File;
use PDF;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Pagination\Paginator;

class GestaoController extends Controller
{

    protected $totalPage = 100;   
    public $timestamps = false;

      public function meritocracia_index() {

        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');
        setlocale(LC_TIME, 'PTB'); 
    
        $mesatual =  $carbon->subMonth(2)->formatLocalized('%B');

        $mespassado = $mes - 1;

        // //Verifico se ele ja assinou o contrato, se não abrir o modal 
        // $contrato = DB::table('dbo.Gestao_Contrato')
        //        ->select('id')
        //        ->where('user_id','=', Auth::user()->id)
        //        ->where('dbo.Gestao_Contrato.tipo_id', 1)
        //        ->whereYear('dbo.Gestao_Contrato.ano', '=', 2021)
        //        ->value('id');
        

        // //Grava na Auditoria informando que acessou a Meritocracia Index
        // $values= array(
        // 'user_id' => Auth::user()->id, 
        // 'modulo' => 'Financeiro', 
        // 'descricao' => 'Acessou dashboard individual acumulado.',
        // 'data' => $carbon);
        //  DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);

       $unidade_descricao = DB::table('PLCFULL.dbo.Jurid_Advogado')->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', '=', 'PLCFULL.dbo.Jurid_Unidade.Codigo')->select('PLCFULL.dbo.Jurid_Unidade.Descricao')->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)->value('PLCFULL.dbo.Jurid_Unidade.Descricao');
       $setor_descricao = DB::table('PLCFULL.dbo.Jurid_Advogado')
               ->select('Descricao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', '=', 'PLCFULL.dbo.Jurid_Setor.Codigo')
               ->where('PLCFULL.dbo.Jurid_Advogado.Codigo', '=', Auth::user()->cpf)
               ->value('PLCFUL.dbo.Jurid_Setor.Descricao');
              
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


      $cpf = DB::table('dbo.TI_Usuarios_Senha')->select('cpf')->where('user_id','=', Auth::user()->id)->orderBy('id', 'desc')->value('cpf');
      $email = DB::table('dbo.TI_Usuarios_Senha')->select('email')->where('user_id','=', Auth::user()->id)->orderBy('id', 'desc')->value('email');

      $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');
      $plc_porcent = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.plc_porcent')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('plc_porcent');   
      $unidade_porcent = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.unidade_porcent')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('unidade_porcent');   
      $gerencia_porcent = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.unidade_porcent')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('gerencia_porcent');   
      $area_porcent = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.area_porcent')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('area_porcent');   
      $score_porcent = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.score_porcent')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('score_porcent');   
      $rv_maximo = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.rv_maximo')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('rv_maximo');   
      $rv_projetado = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.rv_projetado')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('rv_projetado');   
      $rv_apurado = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.rv_apurado')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('rv_apurado');   
      $rv_recebido = DB::table('dbo.Gestao_CartaRV')->select('dbo.Gestao_CartaRV.rv_recebido')->where('dbo.Gestao_CartaRV.mes_referencia', '=', 4)->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)->where('user_id',Auth::user()->id)->value('rv_recebido');   

      return view('Painel.Gestao.Meritocracia.index', compact('cpf','email','rv_maximo','rv_projetado','rv_apurado','rv_recebido','plc_porcent','unidade_porcent','gerencia_porcent','area_porcent','score_porcent','nivel','ano','mespassado','mesatual','unidade_descricao','setor_descricao','totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function anexo($anexo) {

        return Storage::disk('gestao-sftp')->download($anexo);
    }

    public function meritocracia_minhasnotas() {

        $carbon= Carbon::now();
        $mesatual = $carbon->format('m');
        $ano = $carbon->format('Y');
        $mespassado = $mesatual - 1;

        //Grava na Auditoria informando que acessou a Meritocracia Minhas Notas Acumulada
        // $values= array(
        // 'user_id' => Auth::user()->id, 
        // 'modulo' => 'Financeiro', 
        // 'descricao' => 'Acessou minhas notas acumulada.',
        // 'data' => $carbon);
        //  DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);

        //Pego o nivel dele
        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');

        $notaconsolidada = DB::table('dbo.web_indicadores_notaconsolidada')->select('nota_acumulada')
                          ->where('dbo.web_indicadores_notaconsolidada.advogado','=', Auth::user()->cpf)
                          ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', $mespassado)
                          ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', 2021)
                          ->where('dbo.web_indicadores_notaconsolidada.nivel', $nivel)
                          ->orderBy('id', 'desc')
                          ->value('nota_acumulada');

        //Pego todas as notas do mês passado de acordo com o nivel dele
        if($nivel == "Advogado") {

        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(3,7,10,21,22))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->where('dbo.web_indicadores_nota.nivel', $nivel)
        ->get();
        
        } 

        else if($nivel == "Advogado Controladoria" || $nivel == "Advogado ControladoriaSP") {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(4,6,10,21,22))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();

        } 

        else if($nivel == "Superintendente") {

         $datas = DB::table('dbo.web_indicadores_nota')
         ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
         ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
         ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(7,10,17,20,21,13))
         ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
         ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
         ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
         ->get();

        } 

        else if($nivel == "Coordenador") {

         $datas = DB::table('dbo.web_indicadores_nota')
         ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
         ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
         ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(3,7,10,18,21,13))
         ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
         ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
         ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
         ->get();

        }

        else if($nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2" ) {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(3,7,10,18,21,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();
        } 

        else if($nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP") {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(4,6,10,21, 13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();
        } 

        else if($nivel == "Superintendente") {
            $datas = DB::table('dbo.web_indicadores_nota')
            ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
            ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
            ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(18, 13, 7,10,20))
            ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
            ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
            ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
            ->get();
        } 

        else if($nivel == "Gerente" || $nivel == "Gerente Equipe Passiva") {
            $datas = DB::table('dbo.web_indicadores_nota')
            ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
            ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
            ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(13,7,10))
            ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
            ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
            ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
            ->get(); 
        }

        else if($nivel == "COO") {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(7,10,16,18,20,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get(); 
        }

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

        return view('Painel.Gestao.Meritocracia.minhasnotas', compact('nivel','notaconsolidada','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_minhasnotasmes() {

        $carbon= Carbon::now();
        $mesatual = $carbon->format('m');
        $anoatual  = $carbon->format('Y');
        $mespassado = $mesatual - 1;

        //Grava na Auditoria informando que acessou a Meritocracia Minhas Notas Mês
        // $values= array(
        // 'user_id' => Auth::user()->id, 
        // 'modulo' => 'Financeiro', 
        // 'descricao' => 'Acessou minhas notas mês.',
        // 'data' => $carbon);
        //  DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);

        //Pego o nivel dele
        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');

        $notaconsolidada = DB::table('dbo.web_indicadores_notaconsolidada')->select('nota_consolidada')
                          ->where('dbo.web_indicadores_notaconsolidada.advogado','=', Auth::user()->cpf)
                          ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', $mespassado)
                          ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', 2021)
                          ->where('dbo.web_indicadores_notaconsolidada.nivel', $nivel)
                          ->orderBy('id', 'desc')
                          ->value('nota_consolidada');

        //Pego todas as notas do mês passado de acordo com o nivel dele
        if($nivel == "Advogado") {

        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(3,7,10,21,22))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->where('dbo.web_indicadores_nota.nivel', $nivel)
        ->get();
        
        } 

        else if($nivel == "Advogado Controladoria" || $nivel == "Advogado ControladoriaSP") {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(4,6,10,21,22))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();

        } 

        else if($nivel == "Superintendente") {
         $datas = DB::table('dbo.web_indicadores_nota')
         ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
         ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(7,10,17,20,21,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();

        } 

        else if($nivel == "Coordenador") {
         $datas = DB::table('dbo.web_indicadores_nota')
         ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
         ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(3,7,10,18,21,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();
        }

        else if($nivel == "Subcoordenador 1" || $nivel == "Subcoordenador 2" ) {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(3,7,10,18,21,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();
        } 

        else if($nivel == "Coordenador Controladoria" || $nivel == "Coordenador ControladoriaSP") {
     
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(4,6,10,21,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get();

        } 

        else if($nivel == "Gerente Equipe Passiva" || $nivel == "Gerente") {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(7,10,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get(); 
        }

        else if($nivel == "COO") {
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id','dbo.web_indicadores_nota.nota', 'dbo.web_indicadores_objetivo.id as objetivo_id','dbo.web_indicadores_objetivo.objetivo as objetivo')
        ->join('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->whereIn('dbo.web_indicadores_nota.id_objetivo', array(7,10,16,18,20,13))
        ->where('dbo.web_indicadores_nota.advogado','=', Auth::user()->cpf)
        ->where('dbo.web_indicadores_nota.mes_referencia', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', 2021)
        ->get(); 
        }


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

        return view('Painel.Gestao.Meritocracia.minhasnotasmes', compact('nivel','notaconsolidada','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_detalhamento() {

        //Pego nivel
        $carbon= Carbon::now();
        $mesatual = $carbon->format('m');
        $anoatual =  $carbon->format('Y');
        $mespassado = $mesatual - 1;

        //Grava na Auditoria informando que acessou a Meritocracia Minhas Notas Detalhamento
        // $values= array(
        // 'user_id' => Auth::user()->id, 
        // 'modulo' => 'Financeiro', 
        // 'descricao' => 'Acessou minhas notas detalhamento.',
        // 'data' => $carbon);
        //  DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);

        $count = 0;

        //Pego o nivel dele
        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');
        $advogado = Auth::user()->cpf;

        $indicadoresnota = DB::select( DB::raw("
        SELECT a.advogado,u.nome,
        a.nivel,
        a.id_objetivo,
        o.objetivo,
        a.ano_referencia,
        convert(numeric(5,2),sum(a.nota)/count(*)) AS realizado,
        m.score90,
        m.meta,
        m.score120
        ,m.peso,
        m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado%'
                AND a.id_objetivo not in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,m.score90,m.meta,m.score120,m.peso,m.uom
                UNION ALL
                SELECT a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota AS realizado,m.score90,m.meta,m.score120,m.peso,m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado%'
                AND a.id_objetivo in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                AND a.mes_referencia in (SELECT max(aux.mes_referencia) 
                                         FROM web_indicadores_nota AS aux 
                                         WHERE a.advogado = aux.advogado
                                         and aux.ano_referencia = a.ano_referencia)
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota,m.score90,m.meta,m.score120,m.peso,m.uom
                ORDER BY a.id_objetivo ASC"));


           foreach ($indicadoresnota as $indicadoresnotas) {

            $indicadoresnotas->nota = ((100*$indicadoresnotas->realizado)/$indicadoresnotas->meta);

            if($indicadoresnotas->realizado >= $indicadoresnotas->score120 && $indicadoresnotas->id_objetivo <> 4){
                $indicadoresnotas->nota = 120;

            } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90){
                $indicadoresnotas->nota = 0;
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->score90 && $indicadoresnotas->realizado < $indicadoresnotas->meta){
                $indicadoresnotas->nota = (100-((10/($indicadoresnotas->meta-$indicadoresnotas->score90))*($indicadoresnotas->meta- $indicadoresnotas->realizado)));
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->meta && $indicadoresnotas->realizado < $indicadoresnotas->score120){
                $indicadoresnotas->nota = (((20/($indicadoresnotas->score120 - $indicadoresnotas->meta))*($indicadoresnotas->realizado - $indicadoresnotas->meta))+100);
            }

            if($indicadoresnotas->id_objetivo == 4){
                if($indicadoresnotas->realizado <= $indicadoresnotas->score120){
                    $indicadoresnotas->nota = 120;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score90){
                    $indicadoresnotas->nota = 0;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score120 && $indicadoresnotas->realizado <= $indicadoresnotas->meta){
                        $indicadoresnotas->nota = (((20/($indicadoresnotas->meta - $indicadoresnotas->score120))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);

                } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90 && $indicadoresnotas->realizado > $indicadoresnotas->meta){
                    $indicadoresnotas->nota = (((10/($indicadoresnotas->score90 - $indicadoresnotas->meta ))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);
                }
            }

            $indicadoresnotas->nota = number_format((float)$indicadoresnotas->nota, 2, '.', ''); 
            $count = $count+1;
            
        }       

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
 
         return view('Painel.Gestao.Meritocracia.detalhamento.detalhamento', compact('nivel','indicadoresnota','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_detalhamentoobjetivo($id_objetivo) {

        $advogado = Auth::user()->cpf;
        $carbon= Carbon::now();
        $ano = $carbon->format('Y');
        $mesapuracao = $carbon->format('m') - 1;

        //Grava na Auditoria informando que acessou a Meritocracia Minhas Notas Detalhamento
        // $values= array(
        // 'user_id' => Auth::user()->id, 
        // 'modulo' => 'Financeiro', 
        // 'descricao' => 'Acessou minhas notas detalhamento objetivo.',
        // 'data' => $carbon);
        //  DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);


        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');


        $notaDetalhadaPorObjetivo = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.id as mes_id,Gestao_Mes.descricao as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        WHERE a.advogado like '%$advogado%'
        AND a.id_objetivo = $id_objetivo
        AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        ORDER BY Gestao_Mes.id ASC"));
        $notaacumulada = 0;
        $count = 0;
        $soma = 1;

        if ($id_objetivo == 12 || $id_objetivo == 16 ||  $id_objetivo == 17 ||  $id_objetivo == 18){

                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = 0;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = $notaPorObjetivo->nota;
                    // Fim //
                    
                    // Converte variável para ter apenas duas casas decimais //
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    // Fim //
                    
                    $count = $count+1; 
                }
    
            } elseif ($id_objetivo != 12 || $id_objetivo == 16 || $id_objetivo != 17 || $id_objetivo != 18){
    
                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);

    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->id_objetivo)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->realizado <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota = 120;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota = 0;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score120 && $notaPorObjetivo->realizado <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
    
                        } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90 && $notaPorObjetivo->realizado > $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((10/($notaPorObjetivo->score90-$notaPorObjetivo->meta))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
                        }
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = $notaacumulada/$soma;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = ((100*$notaPorObjetivo->nota_acumulada)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota_consolidada_acumulada = 120;
                    
                    } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota_consolidada_acumulada = 0;
                
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada)));
                    
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->nota_acumulada-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota_consolidada_acumulada = 120;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota_consolidada_acumulada = 0;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score120 && $notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
    
                        } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
                        }
                    }
                    
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    
                    $count = $count+1;
                    $soma = $soma+1;
                    
                }       
        }


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


        return view('Painel.Gestao.Meritocracia.detalhamento.detalhamentoobjetivo', compact('nivel','ano','mesapuracao','notaDetalhadaPorObjetivo','totalNotificacaoAbertas', 'notificacoes'));
    }

    public function meritocracia_cartasrv_historico() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');
        $mesapuracao = $carbon->format('m') - 1;

        // //Grava na Auditoria informando que acessou a Meritocracia Dashboard historico individual
        // $values= array(
        // 'user_id' => Auth::user()->id, 
        // 'modulo' => 'Financeiro', 
        // 'descricao' => 'Acessou dashboard histórico individual.',
        // 'data' => $carbon);
        //  DB::table('dbo.TI_Usuarios_Auditoria')->insert($values);


        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');

    
        $datas = DB::table('dbo.Gestao_CartaRV')
            ->select(
                     'dbo.Gestao_CartaRV.id as id',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.Gestao_Mes.id as mes_id',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.Gestao_CartaRV.ano_referencia as ano',
                     'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                     'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                     'dbo.Gestao_CartaRV.gerencia_porcent',
                     'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                     'dbo.Gestao_CartaRV.score_porcent as score_porcent',
                     'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                     'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                     'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                     'dbo.Gestao_CartaRV.rv_projetado as rv_projetado')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Gestao_CartaRV.unidade_codigo', 'PLCFULL.dbo.Jurid_Unidade.Codigo')          
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_CartaRV.setor_codigo', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.users', 'dbo.Gestao_CartaRV.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_CartaRV.mes_referencia', 'dbo.Gestao_Mes.id')
            ->whereYear('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
            ->where('dbo.Gestao_CartaRV.user_id', Auth::user()->id)
            ->orderBy('dbo.Gestao_Mes.id', 'desc')
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
    
    
        return view('Painel.Gestao.Meritocracia.CartaRV.historico', compact('nivel','mesapuracao','ano','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_prazos_index() {

        //Pego todos os prazos do usuario atual que a data de fechamento foi > que a data prazo
        $carbon= Carbon::now();
        $mesatual = $carbon->format('m');
        $mespassado = $mesatual - 1;

        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');

        $datas = DB::table('PLCFULL.dbo.Jurid_agenda_table')
        ->select('PLCFULL.dbo.Jurid_agenda_table.Ident as Ident',
                 'PLCFULL.dbo.Jurid_agenda_table.Pasta as Pasta',
                 'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                 'PLCFULL.dbo.Jurid_CodMov.Descricao as Mov',
                 'PLCFULL.dbo.Jurid_agenda_table.Data as Data',
                 'PLCFULL.dbo.Jurid_agenda_table.Status as Status',
                 'PLCFULL.dbo.Jurid_agenda_table.Data_prazo as DataPrazo',
                 'PLCFULL.dbo.Jurid_agenda_table.Obs as Observacao',
                 'PLCFULL.dbo.Jurid_agenda_table.Data_Fech as DataFechamento',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade')
        ->join('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_agenda_table.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->join('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_agenda_table.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
        ->join('PLCFULL.dbo.Jurid_CodMov', 'PLCFULL.dbo.Jurid_agenda_table.CodMov', 'PLCFULL.dbo.Jurid_CodMov.Codigo')
        ->where(DB::raw('CAST(PLCFULL.dbo.Jurid_agenda_table.Data_Fech AS Date)'),'>',DB::raw('CAST(PLCFULL.dbo.Jurid_agenda_table.prazo_fatal AS Date)'))
        ->where('PLCFULL.dbo.Jurid_agenda_table.Advogado', Auth::user()->cpf)
        // ->whereMonth('PLCFULL.dbo.Jurid_agenda_table.prazo_fatal', '>=', 1)
        ->whereMonth('PLCFULL.dbo.Jurid_agenda_table.prazo_fatal', '=', $mespassado)
        ->whereYear('PLCFULL.dbo.Jurid_agenda_table.prazo_fatal', '=', $carbon->format('Y'))
        ->whereNotIn('PLCFULL.dbo.Jurid_agenda_table.CodMov', ['MOV001','MOV002','MOV015','MOV223','MOV202','MOV201','MOV213','MOV206','MOV212','MOV205','MOV207','MOV210','MOV214',
        'MOV216','MOV209','MOV211','MOV215','MOV208','MOV055','MOV043','MOV046','MOV156','MOV215','NA05',
        'NA38','MOV081','MOV144','MOV116','MOV285','MOV126'])
        ->orderBy('PLCFULL.dbo.Jurid_agenda_table.data_prazo', 'desc')
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

        return view('Painel.Gestao.Meritocracia.Prazos.index', compact('nivel','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_prazos_contestar($ident) {

        $datas = DB::table('PLCFULL.dbo.Jurid_agenda_table')
        ->select('PLCFULL.dbo.Jurid_agenda_table.Ident as Ident',
                 'PLCFULL.dbo.Jurid_agenda_table.Pasta as Pasta',
                 'PLCFULL.dbo.Jurid_agenda_table.CodMov as Mov',
                 'PLCFULL.dbo.Jurid_agenda_table.Data as Data',
                 'PLCFULL.dbo.Jurid_agenda_table.Status as Status',
                 'PLCFULL.dbo.Jurid_agenda_table.Data_prazo as DataPrazo',
                 'PLCFULL.dbo.Jurid_agenda_table.Obs as Observacao',
                 'PLCFULL.dbo.Jurid_agenda_table.Data_Fech as DataFechamento',
                 'PLCFULL.dbo.Jurid_Unidade.Codigo as UnidadeCodigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_agenda_table.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->where('PLCFULL.dbo.Jurid_agenda_table.Ident', $ident)
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

        return view('Painel.Gestao.Meritocracia.Prazos.contestar', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_prazos_contestado(Request $request) {

        $carbon= Carbon::now();
        $ident = $request->get('ident');
        $pasta = $request->get('pasta');
        $mov = $request->get('mov');
        $unidade = $request->get('unidade');
        $data = $request->get('data');
        $dataprazo = $request->get('dataprazo');
        $dataprazofatal = $request->get('dataprazofatal');
        $datafechamento = $request->get('datafechamento');
        $justificativa = $request->get('justificativa');

        //Grava na tabela
        $value1 = array(
            'user_id' => Auth::user()->id, 
            'prazo_ident' => $ident, 
            'justificativa' => $justificativa, 
            'updated_at' => $carbon);
        DB::table('dbo.Gestao_Prazos')->insert($value1);

        $id = DB::table('dbo.Gestao_Prazos')->select('id')->where('prazo_ident','=', $ident)->orderBy('id', 'desc')->value('id');

        //Envia notificação interna Ana Paola
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '282', 'tipo' => '6', 'obs' => 'Foi feita uma nova contestação de prazo no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);
        //Envia notificação interna Patricia Santos
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => '216', 'tipo' => '6', 'obs' => 'Foi feita uma nova contestação de prazo no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        //Envia Email 
        Mail::to(Auth::user()->email)
        ->cc('anapaola.ito@plcadvogados.com.br','patricia.santos@plcadvogados.com.br')
        ->send(new PrazoContestacao($ident, $pasta, $mov, $unidade, $data, $dataprazo, $dataprazofatal, $datafechamento, $justificativa));

       flash('Solicitação registrada com sucesso !')->success();    

       return redirect()->route('Painel.Gestao.Meritocracia.Prazos.index');

    }

    public function meritocracia_hierarquia_index() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');    
        $mespassado = $carbon->format('m') - 1;
        $mespassado2 = $carbon->format('m') - 2;


        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');

        $datas = DB::table('dbo.web_indicadores_notaconsolidada')
               ->select(
                'dbo.web_indicadores_notaconsolidada.id as id',
                 'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_codigo',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.Gestao_CartaRV.mes_referencia',
                 'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                 'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                 'dbo.Gestao_CartaRV.gerencia_porcent',
                 'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                 'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                 'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                 'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                 'dbo.Gestao_CartaRV.rv_projetado as rv_projetado',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                 'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.users', 'dbo.web_indicadores_notaconsolidada.advogado', 'dbo.users.cpf')
        ->leftjoin('dbo.Gestao_CartaRV', 'dbo.users.id', 'dbo.Gestao_CartaRV.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_notaconsolidada.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->join('dbo.web_hierarquia', 'dbo.users.cpf', 'dbo.web_hierarquia.advogado')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_hierarquia.responsavel', Auth::user()->cpf)
        ->where('dbo.web_hierarquia.ativo', 'S')
        ->where('dbo.Gestao_CartaRV.mes_referencia', '=', '4')
        ->where('dbo.Gestao_CartaRV.ano_referencia', '=', '2021')
        //  ->where('dbo.Gestao_CartaRV.mes_referencia', '=', 1)

        // ->orwhere('dbo.Gestao_CartaRV.ano_referencia', '=', '2021')
        // ->where('dbo.Gestao_CartaRV.mes_referencia', '=', '2')
        // ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        // ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado2)
        // ->where('dbo.web_hierarquia.responsavel', Auth::user()->cpf)
        // ->where('dbo.web_hierarquia.ativo', 'S')

        ->orwhere('dbo.Gestao_CartaRV.ano_referencia', '=', null)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_hierarquia.responsavel', Auth::user()->cpf)
        ->where('dbo.web_hierarquia.ativo', 'S')

        ->orwhere('dbo.Gestao_CartaRV.ano_referencia', '=', null)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado2)
        ->where('dbo.web_hierarquia.responsavel', Auth::user()->cpf)
        ->where('dbo.web_hierarquia.ativo', 'S')

        // ->orwhere('dbo.Gestao_CartaRV.mes_referencia', '=', $mespassado2)
        // ->orwhere('dbo.Gestao_CartaRV.mes_referencia', '=', 12)
        // ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        // ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', '3')
        // ->where('dbo.web_hierarquia.responsavel', Auth::user()->cpf)
        // ->where('dbo.web_hierarquia.ativo', 'S')

        ->groupby(
            'dbo.web_indicadores_notaconsolidada.id',
            'PLCFULL.dbo.Jurid_Advogado.Codigo',
            'PLCFULL.dbo.Jurid_Advogado.Nome',
            'PLCFULL.dbo.Jurid_Unidade.Descricao',
            'PLCFULL.dbo.Jurid_Setor.Descricao',
            'dbo.Gestao_CartaRV.mes_referencia',
            'dbo.Gestao_CartaRV.plc_porcent',
            'dbo.Gestao_CartaRV.unidade_porcent',
            'dbo.Gestao_CartaRV.gerencia_porcent',
            'dbo.Gestao_CartaRV.area_porcent',
            'dbo.Gestao_CartaRV.rv_maximo',
            'dbo.Gestao_CartaRV.rv_apurado',
            'dbo.Gestao_CartaRV.rv_recebido',
            'dbo.Gestao_CartaRV.rv_projetado',
            'dbo.Gestao_Mes.descricao',
            'dbo.web_indicadores_notaconsolidada.nota_consolidada',
            'dbo.web_indicadores_notaconsolidada.nota_acumulada')
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
    
    
        return view('Painel.Gestao.Meritocracia.Hierarquia.index', compact('datas','nivel','totalNotificacaoAbertas', 'notificacoes', 'mespassado', 'mespassado2'));

    }

    public function meritocracia_hierarquiasetor_index() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');    
        $mespassado = $carbon->format('m') - 1;
        $mespassado2 = $carbon->format('m') - 1;

        //Pego o nivel do usuario
        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');


        $permissao_coo =  DB::table('dbo.users')->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email')->where('dbo.profile_user.user_id', '=', Auth::user()->id)->where('dbo.profile_user.profile_id', '=', '36')->first();  

        $permissao_superintendente =  DB::table('dbo.users')->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email')->where('dbo.profile_user.user_id', '=', Auth::user()->id)->where('dbo.profile_user.profile_id', '=', '24')->first();  

        $permissao_gerente =  DB::table('dbo.users')->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email')->where('dbo.profile_user.user_id', '=', Auth::user()->id)->where('dbo.profile_user.profile_id', '=', '23')->first();  

        $permissao_coordenador =  DB::table('dbo.users')->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email')->where('dbo.profile_user.user_id', '=', Auth::user()->id)->where('dbo.profile_user.profile_id', '=', '40')->first();  

        $permissao_subcoordenador =  DB::table('dbo.users')->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email')->where('dbo.profile_user.user_id', '=', Auth::user()->id)->whereIn('dbo.profile_user.profile_id', array(37,38))->first();  

        //Se for COO (Visualiza de todos) do setor de custo
        if($permissao_coo != null) {

            $datas = DB::table('dbo.users')
            ->select(
                'dbo.web_indicadores_notaconsolidada.id as id',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_codigo',
                'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                'PLCFULL.dbo.Jurid_Advogado.Setor as setor',
                'dbo.Gestao_CartaRV.mes_referencia',
                'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                'dbo.Gestao_CartaRV.gerencia_porcent',
                'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                'dbo.Gestao_CartaRV.rv_projetado as rv_projetado',
                'dbo.Gestao_Mes.descricao as mes',
                'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', 'dbo.setor_custo_user.user_id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
            ->leftjoin('dbo.web_hierarquia_setor', 'PLCFULL.dbo.Jurid_Setor.Codigo', 'dbo.web_hierarquia_setor.setor')
            ->leftjoin('web_indicadores_notaconsolidada', 'dbo.users.cpf', 'web_indicadores_notaconsolidada.advogado')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.Gestao_CartaRV', 'dbo.users.id', 'dbo.Gestao_CartaRV.user_id')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
            ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
            ->where('dbo.Gestao_CartaRV.mes_referencia', '=', 3)
            ->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
            ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
            ->where('dbo.web_hierarquia_setor.ativo', 'S')
            ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
            ->whereIn('dbo.profile_user.profile_id', array(23,24,40,37,38,17))
            // ->where('dbo.Gestao_CartaRV.mes_referencia', '=', $mespassado)
    
    
            ->orwhere('dbo.Gestao_CartaRV.mes_referencia', '=', '2021')
            ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
            ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
            ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
            ->where('dbo.web_hierarquia_setor.ativo', 'S')
            ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
            ->whereIn('dbo.profile_user.profile_id', array(23,24,40,37,38,17))


            ->groupby(
             'dbo.web_indicadores_notaconsolidada.id',
             'PLCFULL.dbo.Jurid_Advogado.Codigo',
             'PLCFULL.dbo.Jurid_Advogado.Nome',
             'PLCFULL.dbo.Jurid_Unidade.Descricao',
             'PLCFULL.dbo.Jurid_Advogado.Setor',
             'dbo.Gestao_CartaRV.mes_referencia',
             'dbo.Gestao_CartaRV.plc_porcent',
             'dbo.Gestao_CartaRV.unidade_porcent',
             'dbo.Gestao_CartaRV.gerencia_porcent',
             'dbo.Gestao_CartaRV.area_porcent',
             'dbo.Gestao_CartaRV.rv_maximo',
             'dbo.Gestao_CartaRV.rv_apurado',
             'dbo.Gestao_CartaRV.rv_recebido',
             'dbo.Gestao_CartaRV.rv_projetado',
             'dbo.Gestao_Mes.descricao',
             'dbo.web_indicadores_notaconsolidada.nota_consolidada',
             'dbo.web_indicadores_notaconsolidada.nota_acumulada')
            ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
            ->get();

        }
        //Se for superintendente(Visualiza Gerente, Coordenadores, SubCoord., Adv.)
        else if($permissao_superintendente != null) {

        $datas = DB::table('dbo.users')
        ->select(
            'dbo.web_indicadores_notaconsolidada.id as id',
            'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_codigo',
            'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
            'PLCFULL.dbo.Jurid_Advogado.Setor as setor',
            'dbo.Gestao_CartaRV.mes_referencia',
            'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
            'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
            'dbo.Gestao_CartaRV.gerencia_porcent',
            'dbo.Gestao_CartaRV.area_porcent as area_porcent',
            'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
            'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
            'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
            'dbo.Gestao_CartaRV.rv_projetado as rv_projetado',
            'dbo.Gestao_Mes.descricao as mes',
            'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
            'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.web_hierarquia_setor', 'PLCFULL.dbo.Jurid_Setor.Codigo', 'dbo.web_hierarquia_setor.setor')
        ->leftjoin('web_indicadores_notaconsolidada', 'dbo.users.cpf', 'web_indicadores_notaconsolidada.advogado')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.Gestao_CartaRV', 'dbo.users.id', 'dbo.Gestao_CartaRV.user_id')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')

        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.Gestao_CartaRV.mes_referencia', '=', 3)
        ->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(23,40,37,38,17))
        // ->where('dbo.Gestao_CartaRV.mes_referencia', '=', $mespassado)


        ->orwhere('dbo.Gestao_CartaRV.mes_referencia', '=', null)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(23,40,37,38,17))

        ->groupby(
         'dbo.web_indicadores_notaconsolidada.id',
         'PLCFULL.dbo.Jurid_Advogado.Codigo',
         'PLCFULL.dbo.Jurid_Advogado.Nome',
         'PLCFULL.dbo.Jurid_Unidade.Descricao',
         'PLCFULL.dbo.Jurid_Advogado.Setor',
         'dbo.Gestao_CartaRV.mes_referencia',
         'dbo.Gestao_CartaRV.plc_porcent',
         'dbo.Gestao_CartaRV.unidade_porcent',
         'dbo.Gestao_CartaRV.gerencia_porcent',
         'dbo.Gestao_CartaRV.area_porcent',
         'dbo.Gestao_CartaRV.rv_maximo',
         'dbo.Gestao_CartaRV.rv_apurado',
         'dbo.Gestao_CartaRV.rv_recebido',
         'dbo.Gestao_CartaRV.rv_projetado',
         'dbo.Gestao_Mes.descricao',
         'dbo.web_indicadores_notaconsolidada.nota_consolidada',
         'dbo.web_indicadores_notaconsolidada.nota_acumulada')
        ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
        ->get();
    }
    //Se for gerente(Visualiza Coordenadores, SubCoord., Adv.)
    elseif($permissao_gerente != null) {

        $datas = DB::table('dbo.users')
        ->select(
            'dbo.web_indicadores_notaconsolidada.id as id',
            'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_codigo',
            'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
            'PLCFULL.dbo.Jurid_Advogado.Setor as setor',
            'dbo.Gestao_CartaRV.mes_referencia',
            'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
            'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
            'dbo.Gestao_CartaRV.gerencia_porcent',
            'dbo.Gestao_CartaRV.area_porcent as area_porcent',
            'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
            'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
            'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
            'dbo.Gestao_CartaRV.rv_projetado as rv_projetado',
            'dbo.Gestao_Mes.descricao as mes',
            'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
            'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.web_hierarquia_setor', 'PLCFULL.dbo.Jurid_Setor.Codigo', 'dbo.web_hierarquia_setor.setor')
        ->leftjoin('web_indicadores_notaconsolidada', 'dbo.users.cpf', 'web_indicadores_notaconsolidada.advogado')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.Gestao_CartaRV', 'dbo.users.id', 'dbo.Gestao_CartaRV.user_id')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')

        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.Gestao_CartaRV.mes_referencia', '=', 3)
        ->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(40,37,38,17))
        // ->where('dbo.Gestao_CartaRV.mes_referencia', '=', $mespassado)


        ->orwhere('dbo.Gestao_CartaRV.mes_referencia', '=', null)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(40,37,38,17))

        ->groupby(
         'dbo.web_indicadores_notaconsolidada.id',
         'PLCFULL.dbo.Jurid_Advogado.Codigo',
         'PLCFULL.dbo.Jurid_Advogado.Nome',
         'PLCFULL.dbo.Jurid_Unidade.Descricao',
         'PLCFULL.dbo.Jurid_Advogado.Setor',
         'dbo.Gestao_CartaRV.mes_referencia',
         'dbo.Gestao_CartaRV.plc_porcent',
         'dbo.Gestao_CartaRV.unidade_porcent',
         'dbo.Gestao_CartaRV.gerencia_porcent',
         'dbo.Gestao_CartaRV.area_porcent',
         'dbo.Gestao_CartaRV.rv_maximo',
         'dbo.Gestao_CartaRV.rv_apurado',
         'dbo.Gestao_CartaRV.rv_recebido',
         'dbo.Gestao_CartaRV.rv_projetado',
         'dbo.Gestao_Mes.descricao',
         'dbo.web_indicadores_notaconsolidada.nota_consolidada',
         'dbo.web_indicadores_notaconsolidada.nota_acumulada')
        ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
        ->get();

    }
    //Se for Coordenador(Visualiza SubCoordenadores, Adv.)
    elseif($permissao_coordenador != null) {

        $datas = DB::table('dbo.users')
        ->select(
            'dbo.web_indicadores_notaconsolidada.id as id',
            'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_codigo',
            'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
            'PLCFULL.dbo.Jurid_Advogado.Setor as setor',
            'dbo.Gestao_CartaRV.mes_referencia',
            'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
            'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
            'dbo.Gestao_CartaRV.gerencia_porcent',
            'dbo.Gestao_CartaRV.area_porcent as area_porcent',
            'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
            'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
            'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
            'dbo.Gestao_CartaRV.rv_projetado as rv_projetado',
            'dbo.Gestao_Mes.descricao as mes',
            'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
            'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.web_hierarquia_setor', 'PLCFULL.dbo.Jurid_Setor.Codigo', 'dbo.web_hierarquia_setor.setor')
        ->leftjoin('web_indicadores_notaconsolidada', 'dbo.users.cpf', 'web_indicadores_notaconsolidada.advogado')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.Gestao_CartaRV', 'dbo.users.id', 'dbo.Gestao_CartaRV.user_id')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')

        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.Gestao_CartaRV.mes_referencia', '=', 3)
        ->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(37,38,17))
        // ->where('dbo.Gestao_CartaRV.mes_referencia', '=', $mespassado)


        ->orwhere('dbo.Gestao_CartaRV.mes_referencia', '=', null)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(37,38,17))

        ->groupby(
         'dbo.web_indicadores_notaconsolidada.id',
         'PLCFULL.dbo.Jurid_Advogado.Codigo',
         'PLCFULL.dbo.Jurid_Advogado.Nome',
         'PLCFULL.dbo.Jurid_Unidade.Descricao',
         'PLCFULL.dbo.Jurid_Advogado.Setor',
         'dbo.Gestao_CartaRV.mes_referencia',
         'dbo.Gestao_CartaRV.plc_porcent',
         'dbo.Gestao_CartaRV.unidade_porcent',
         'dbo.Gestao_CartaRV.gerencia_porcent',
         'dbo.Gestao_CartaRV.area_porcent',
         'dbo.Gestao_CartaRV.rv_maximo',
         'dbo.Gestao_CartaRV.rv_apurado',
         'dbo.Gestao_CartaRV.rv_recebido',
         'dbo.Gestao_CartaRV.rv_projetado',
         'dbo.Gestao_Mes.descricao',
         'dbo.web_indicadores_notaconsolidada.nota_consolidada',
         'dbo.web_indicadores_notaconsolidada.nota_acumulada')
        ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
        ->get();
    }
    //Se for SubCoordenador 1 ou 2
    else if($permissao_subcoordenador != null) {
        $datas = DB::table('dbo.users')
        ->select(
            'dbo.web_indicadores_notaconsolidada.id as id',
            'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_codigo',
            'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
            'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
            'PLCFULL.dbo.Jurid_Advogado.Setor as setor',
            'dbo.Gestao_CartaRV.mes_referencia',
            'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
            'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
            'dbo.Gestao_CartaRV.area_porcent as area_porcent',
            'dbo.Gestao_CartaRV.gerencia_porcent',
            'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
            'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
            'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
            'dbo.Gestao_CartaRV.rv_projetado as rv_projetado',
            'dbo.Gestao_Mes.descricao as mes',
            'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
            'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.web_hierarquia_setor', 'PLCFULL.dbo.Jurid_Setor.Codigo', 'dbo.web_hierarquia_setor.setor')
        ->leftjoin('web_indicadores_notaconsolidada', 'dbo.users.cpf', 'web_indicadores_notaconsolidada.advogado')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.Gestao_CartaRV', 'dbo.users.id', 'dbo.Gestao_CartaRV.user_id')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')

        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.Gestao_CartaRV.mes_referencia', '=', 3)
        ->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(17))
        // ->where('dbo.Gestao_CartaRV.mes_referencia', '=', $mespassado)


        ->orwhere('dbo.Gestao_CartaRV.ano_referencia', '=', null)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', 2021)
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_hierarquia_setor.advogado', '=', Auth::user()->cpf)
        ->where('dbo.web_hierarquia_setor.ativo', 'S')
        ->where('dbo.web_indicadores_notaconsolidada.advogado', '!=',Auth::user()->cpf)
        ->whereIn('dbo.profile_user.profile_id', array(17))

        ->groupby(
         'dbo.web_indicadores_notaconsolidada.id',
         'PLCFULL.dbo.Jurid_Advogado.Codigo',
         'PLCFULL.dbo.Jurid_Advogado.Nome',
         'PLCFULL.dbo.Jurid_Unidade.Descricao',
         'PLCFULL.dbo.Jurid_Advogado.Setor',
         'dbo.Gestao_CartaRV.mes_referencia',
         'dbo.Gestao_CartaRV.plc_porcent',
         'dbo.Gestao_CartaRV.unidade_porcent',
         'dbo.Gestao_CartaRV.gerencia_porcent',
         'dbo.Gestao_CartaRV.area_porcent',
         'dbo.Gestao_CartaRV.rv_maximo',
         'dbo.Gestao_CartaRV.rv_apurado',
         'dbo.Gestao_CartaRV.rv_recebido',
         'dbo.Gestao_CartaRV.rv_projetado',
         'dbo.Gestao_Mes.descricao',
         'dbo.web_indicadores_notaconsolidada.nota_consolidada',
         'dbo.web_indicadores_notaconsolidada.nota_acumulada')
        ->orderBy('PLCFULL.dbo.Jurid_Advogado.Nome', 'asc')
        ->get();
    }

    

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
    
    
        return view('Painel.Gestao.Meritocracia.Hierarquia.Setor.index', compact('datas','nivel','totalNotificacaoAbertas', 'notificacoes', 'mespassado', 'mespassado2'));

    }

    public function meritocracia_hierarquia_notasadvogado_detalha($advogado) {

        $carbon= Carbon::now();
        $mesatual = $carbon->format('m');
        $anoatual =  $carbon->format('Y');
        $mespassado = $mesatual - 1;

        //Pego o nivel dele
        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', $advogado)->orderBy('id', 'desc')->value('nivel');

        $count = 0;
        $indicadoresnota = DB::select( DB::raw("
        SELECT a.advogado,u.nome,
        a.nivel,
        a.id_objetivo,
        o.objetivo,
        a.ano_referencia,
        convert(numeric(5,2),sum(a.nota)/count(*)) AS realizado,
        m.score90,
        m.meta,
        m.score120
        ,m.peso,
        m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado%'
                AND a.id_objetivo not in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,m.score90,m.meta,m.score120,m.peso,m.uom
                UNION ALL
                SELECT a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota AS realizado,m.score90,m.meta,m.score120,m.peso,m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado%'
                AND a.id_objetivo in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                AND a.mes_referencia in (SELECT max(aux.mes_referencia) 
                                         FROM web_indicadores_nota AS aux 
                                         WHERE a.advogado = aux.advogado
                                         and aux.ano_referencia = a.ano_referencia)
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota,m.score90,m.meta,m.score120,m.peso,m.uom
                ORDER BY a.id_objetivo ASC"));


           foreach ($indicadoresnota as $indicadoresnotas) {

            $indicadoresnotas->nota = ((100*$indicadoresnotas->realizado)/$indicadoresnotas->meta);

            if($indicadoresnotas->realizado >= $indicadoresnotas->score120 && $indicadoresnotas->id_objetivo <> 4){
                $indicadoresnotas->nota = 120;

            } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90){
                $indicadoresnotas->nota = 0;
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->score90 && $indicadoresnotas->realizado < $indicadoresnotas->meta){
                $indicadoresnotas->nota = (100-((10/($indicadoresnotas->meta-$indicadoresnotas->score90))*($indicadoresnotas->meta- $indicadoresnotas->realizado)));
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->meta && $indicadoresnotas->realizado < $indicadoresnotas->score120){
                $indicadoresnotas->nota = (((20/($indicadoresnotas->score120 - $indicadoresnotas->meta))*($indicadoresnotas->realizado - $indicadoresnotas->meta))+100);
            }

            if($indicadoresnotas->id_objetivo == 4){
                if($indicadoresnotas->realizado <= $indicadoresnotas->score120){
                    $indicadoresnotas->nota = 120;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score90){
                    $indicadoresnotas->nota = 0;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score120 && $indicadoresnotas->realizado <= $indicadoresnotas->meta){
                        $indicadoresnotas->nota = (((20/($indicadoresnotas->meta - $indicadoresnotas->score120))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);

                } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90 && $indicadoresnotas->realizado > $indicadoresnotas->meta){
                    $indicadoresnotas->nota = (((10/($indicadoresnotas->score90 - $indicadoresnotas->meta ))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);
                }
            }

            $indicadoresnotas->nota = number_format((float)$indicadoresnotas->nota, 2, '.', ''); 
            $count = $count+1;
            
        } 
            
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
    
    
        return view('Painel.Gestao.Meritocracia.Hierarquia.detalhanotasadvogado', compact('indicadoresnota','mesatual','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_hierarquia_notasadvogado_detalhamentoobjetivo($advogado, $id_objetivo) {

        
        $carbon= Carbon::now();
        $mesatual = $carbon->format('m');
        $ano = $carbon->format('Y');
        $mesapuracao = $mesatual - 1;

        $notaDetalhadaPorObjetivo = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota as realizado,m.score90,m.meta,m.score120,m.peso,m.uom, 
        Gestao_Mes.descricao as mes, Gestao_Mes.id as mes_id
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        WHERE a.advogado like '%$advogado%'
        AND a.id_objetivo = $id_objetivo
        AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        ORDER BY Gestao_Mes.id DESC"));


        $notaacumulada = 0;
        $count = 0;
        $soma = 1;

        if ($id_objetivo == 12 || $id_objetivo == 16 ||  $id_objetivo == 17 ||  $id_objetivo == 18){

                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = 0;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = $notaPorObjetivo->nota;
                    // Fim //
                    
                    // Converte variável para ter apenas duas casas decimais //
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    // Fim //
                    
                    $count = $count+1; 
                }
    
            } elseif ($id_objetivo != 12 || $id_objetivo == 16 || $id_objetivo != 17 || $id_objetivo != 18){
    
                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);

    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->id_objetivo)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->realizado <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota = 120;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota = 0;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score120 && $notaPorObjetivo->realizado <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
    
                        } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90 && $notaPorObjetivo->realizado > $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((10/($notaPorObjetivo->score90-$notaPorObjetivo->meta))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
                        }
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = $notaacumulada/$soma;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = ((100*$notaPorObjetivo->nota_acumulada)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota_consolidada_acumulada = 120;
                    
                    } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota_consolidada_acumulada = 0;
                
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada)));
                    
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->nota_acumulada-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota_consolidada_acumulada = 120;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota_consolidada_acumulada = 0;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score120 && $notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
    
                        } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
                        }
                    }
                    
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    
                    $count = $count+1;
                    $soma = $soma+1;
                    
                }       
        }


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


        return view('Painel.Gestao.Meritocracia.Hierarquia.detalhamentoobjetivonotasadvogado', compact('mesapuracao','ano','advogado','notaDetalhadaPorObjetivo','totalNotificacaoAbertas', 'notificacoes'));
    }
    

    public function faturamentosemanal_index() {
        
        $cpfLogado = Auth::User()->cpf;
        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');

        $faturamentoSemanal = DB::select( DB::raw("
                SELECT t.cod_setor,
                t.setor,
                t.valor_orcado_mes,
                t.valor_emitido_mes,
                (t.valor_emitido_mes - t.valor_orcado_mes) AS desvio1,
                t.total_recebido,
                t.recebido_do_mes,
                (t.total_recebido - t.recebido_do_mes) AS recebido_de_meses_anteriores,
                t.a_vencer_do_mes,
                t.a_vencer_proximos_meses,
                t.programado_de_outros_meses,
                (
                (
                t.total_recebido + t.a_vencer_do_mes + programado_de_outros_meses
                ) - t.valor_orcado_mes
                ) AS desvio2_previsto,
                t.inadimplencia_acumulada,
                (t.valor_emitido_mes - t.recebido_do_mes -a_vencer_do_mes) AS inadimplencia
                FROM   vw_painel_faturamentoSemanal t
                INNER JOIN web_hierarquia_setor h
                ON  t.cod_setor = h.setor
                WHERE  (1 = 1)
                AND (h.advogado = '$cpfLogado')
                AND (h.ativo = 'S')
                "));
                                       
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
                
     return view('Painel.Gestao.Faturamentosemanal.index', compact('faturamentoSemanal', 'totalNotificacaoAbertas', 'notificacoes'));
    
    }

    public function faturamentosemanal_detalhado() {
        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');
    
    
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
    
        return view('Painel.Gestao.FaturamentoSemanal.detalhado', compact('totalNotificacaoAbertas', 'notificacoes'));
    
    }
    
    public function PegarDadosFaturamentoSemanalDetalhado(Request $request){
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
    
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
    
        // $data = DB::select( DB::raw("
        //     SELECT *
        //     FROM   vw_painel_faturamentosemanal_detalhado t
        //     LEFT JOIN temp_valororcado v
        //             ON  t.cod_setor = v.setor
        //     INNER JOIN web_hierarquia_setor h
        //             ON  t.cod_setor = h.setor
        //     INNER JOIN PLCFULL.dbo.Jurid_Tipolan lan 
        //             ON t.tipo_lancamento = lan.Codigo
        //     WHERE  (1 = 1)
        //     AND (h.advogado = '".Auth::User()->cpf."')
        //     AND (h.ativo = 'S')
        // "));
        
        $currentPage = (substr($start+10, 0, -1));
    
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
    
        $data = DB::table('vw_painel_faturamentosemanal_detalhado as t')
                ->selectRaw('v.setor, t.cod_setor, numdoc, numdocor, tipo_lancamento, cod_cliente, cliente, grupo_economico')
                ->selectRaw('negocio, data_vencimento, data_baixa, status, t.valor')
                ->selectRaw('segmento, conta_identificadora, data_programacao')
                ->selectRaw('data_competencia, valor_bruto, valor_pago, tabela, t.tipo')
                ->leftJoin('temp_valororcado as v', 't.cod_setor', '=', 'v.setor')
                ->leftJoin('web_hierarquia_setor as h', 't.cod_setor', '=', 'h.setor')
                ->leftJoin('PLCFULL.dbo.Jurid_Tipolan as lan', 't.tipo_lancamento', '=', 'lan.Codigo')
                ->where('h.advogado', Auth::User()->cpf);
        
        $data_arr = array();
        $count = $data->count();
        
        foreach($data->paginate(10)->items() as $record){
            $data_arr[] = array(
                "numdoc" => $record->numdoc,
                "numdocor" => $record->numdocor,
                'codigo_cliente' => $record->cod_cliente,
                'segmento' => $record->segmento,
                "grupo_economico" => $record->grupo_economico,
                "negocio" => $record->negocio,
                "cliente" => $record->cliente,
                'conta_identificadora' => $record->conta_identificadora,
                'codigo_setor' => $record->cod_setor,
                "setor" => $record->setor,
                "data_vencimento" => ($record->data_vencimento ? date('d/m/Y', strtotime($record->data_vencimento)) : ''),
                'data_programacao' => ($record->data_programacao ? date('d/m/Y', strtotime($record->data_programacao)) : ''),
                "data_baixa" => ($record->data_baixa ? date('d/m/Y', strtotime($record->data_baixa)) : ''),
                'data_competencia' => ($record->data_competencia ? date('d/m/Y', strtotime($record->data_competencia)) : ''),
                'tipo' => $record->tipo,
                "status" => $record->status,
                "valor" => number_format($record->valor),
                "valor_bruto" => number_format($record->valor_bruto),
                "valor_pago" => number_format($record->valor_pago),
                "tipo_lancamento" => $record->tipo_lancamento,
                'tabela' => $record->tabela
            );
        }
        
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => count($data_arr),
            "recordsFiltered" => $count,
            "data" => $data_arr
        );
    
        return json_encode($response);
    }
    
    public function MostrarDadosFaturamentoSemanalDetalhado($id){
        $data = DB::table('vw_painel_faturamentosemanal_detalhado as t')
                ->selectRaw('v.setor, numdoc, numdocor, tipo_lancamento, cliente, grupo_economico')
                ->selectRaw('negocio, data_vencimento, data_baixa, status, t.valor')
                ->selectRaw('segmento, conta_identificadora, cod_setor, data_programacao')
                ->selectRaw('data_competencia, valor_bruto, valor_pago, tabela')
                ->leftJoin('temp_valororcado as v', 't.cod_setor', '=', 'v.setor')
                ->leftJoin('web_hierarquia_setor as h', 't.cod_setor', '=', 'h.setor')
                ->leftJoin('PLCFULL.dbo.Jurid_Tipolan as lan', 't.tipo_lancamento', '=', 'lan.Codigo')
                ->where('h.advogado', Auth::User()->cpf)
                ->where('h.ativo', 'S')
                ->where('numdoc', $id)->get()->toArray();
    
        return $data;
    }
    
    public function faturamentosemanal_detalhado_exportar() {
    
        ini_set('memory_limit','-1'); 
        
        $cpfLogado = Auth::User()->cpf;
        // $carbon= Carbon::now();
        // $mes = $carbon->format('m');
        // $ano = $carbon->format('Y');
    
        $customer_data = DB::select( DB::raw("
        SELECT v.setor, t.cod_setor, numdoc, numdocor, tipo_lancamento, 
        cod_cliente, cliente, grupo_economico,
        negocio, data_vencimento, data_baixa, status, t.valor,
        segmento, conta_identificadora, data_programacao,
        data_competencia, valor_bruto, valor_pago, tabela, t.tipo
        FROM   vw_painel_faturamentosemanal_detalhado t
        LEFT JOIN temp_valororcado v
                ON  t.cod_setor = v.setor
        INNER JOIN web_hierarquia_setor h
                ON  t.cod_setor = h.setor
        INNER JOIN PLCFULL.dbo.Jurid_Tipolan lan 
                ON t.tipo_lancamento = lan.Codigo        
        WHERE h.advogado = '$cpfLogado' AND h.ativo = 'S'
        "));
    
        $customer_array[] = array(
            "numdoc",
            "numdocor",
            'codigo_cliente',
            'segmento',
            "grupo_economico",
            "negocio",
            "cliente",
            'conta_identificadora',
            'codigo_setor',
            "setor",
            "data_vencimento",
            'data_programacao',
            "data_baixa",
            'data_competencia',
            'tipo',
            "status",
            "valor",
            "valor_bruto",
            "valor_pago",
            "tipo_lancamento",
            'tabela'
        );
    
        foreach($customer_data as $record){
            $customer_array[] = array(
                "numdoc" => $record->numdoc, // A
                "numdocor" => $record->numdocor, // B
                'codigo_cliente' => $record->cod_cliente, // C
                'segmento' => $record->segmento, // D
                "grupo_economico" => $record->grupo_economico, // E
                "negocio" => $record->negocio, // F
                "cliente" => $record->cliente, // G
                'conta_identificadora' => $record->conta_identificadora, // H
                'codigo_setor' => $record->cod_setor, // I
                "setor" => $record->setor, // J
                "data_vencimento" => ($record->data_vencimento ? date('d/m/Y', strtotime($record->data_vencimento)) : ''),
                'data_programacao' => ($record->data_programacao ? date('d/m/Y', strtotime($record->data_programacao)) : ''),
                "data_baixa" => ($record->data_baixa ? date('d/m/Y', strtotime($record->data_baixa)) : ''),
                'data_competencia' => ($record->data_competencia ? date('d/m/Y', strtotime($record->data_competencia)) : ''),
                'tipo' => $record->tipo, // O
                "status" => $record->status, // P
                "valor" => floatval(number_format($record->valor)), // Q
                "valor_bruto" => floatval(number_format($record->valor_bruto)), // R
                "valor_pago" => floatval(number_format($record->valor_pago)), // S
                "tipo_lancamento" => $record->tipo_lancamento, // T
                'tabela' => $record->tabela // U
            );
        }
        
        Excel::create('Faturamento semanal detalhado', function($excel) use ($customer_array){
            $excel->setTitle('Faturamento semanal');
            $excel->sheet('Faturamento semanal', function($sheet) use ($customer_array) {
            $sheet->fromArray($customer_array, null, 'A1', false, false);
            $sheet->setColumnFormat(array(
                'Q' => 'R$ 0.00',
                'R' => 'R$ 0.00',
                'S' => 'R$ 0.00',
            ));
            });
        })->download('xlsx');
    
    }

    public function controlador_index() {

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

    return view('Painel.Gestao.Controlador.index', compact('totalNotificacaoAbertas', 'notificacoes'));

    }


    public function controlador_cartarv_index() {

    $carbon= Carbon::now();
    $mes = $carbon->format('m');
    $ano = $carbon->format('Y');
    setlocale(LC_TIME, 'PTB'); 

    $mespassado = $mes - 1;

    $datas = DB::table('dbo.Gestao_CartaRV')
        ->select(
                 'dbo.Gestao_CartaRV.id as id',
                 'dbo.users.name as usuario_nome',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                 'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                 'dbo.Gestao_CartaRV.gerencia_porcent',
                 'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                 'dbo.Gestao_CartaRV.score_porcent as score_porcent',
                 'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                 'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                 'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                 'dbo.Gestao_CartaRV.rv_projetado as rv_projetado')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Gestao_CartaRV.unidade_codigo', 'PLCFULL.dbo.Jurid_Unidade.Codigo')          
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_CartaRV.setor_codigo', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.users', 'dbo.Gestao_CartaRV.user_id', 'dbo.users.id')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_CartaRV.mes_referencia', 'dbo.Gestao_Mes.id')
        ->where('dbo.Gestao_CartaRV.mes_referencia', '=', '4')
        ->whereYear('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
        ->orderBy('dbo.users.name', 'asc')
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

    $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderby('dbo.users.name', 'asc')
        ->get(); 

    $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
    ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao') 
    ->whereNotIn('PLCFULL.dbo.Jurid_Unidade.Codigo', ['1.','1.15'])
    ->orderBy('PLCFULL.dbo.Jurid_Unidade.Descricao', 'asc')
    ->get();

    $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
    ->select('PLCFULL.dbo.Jurid_Setor.Codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao')  
    ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
    ->orderby('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
    ->get();

    return view('Painel.Gestao.Controlador.CartaRV.index', compact('datas','usuarios','setores','unidades','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_cartarv_exportarmes() {

        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');
        setlocale(LC_TIME, 'PTB'); 
    
        $mespassado = $mes - 1;
    
        $customer_data = DB::table('dbo.Gestao_CartaRV')
            ->select(
                     'dbo.users.name as nome',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                     'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                     'dbo.Gestao_CartaRV.gerencia_porcent',
                     'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                     'dbo.Gestao_CartaRV.score_porcent as score_porcent',
                     'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                     'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                     'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                     'dbo.Gestao_CartaRV.rv_projetado as rv_projetado')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Gestao_CartaRV.unidade_codigo', 'PLCFULL.dbo.Jurid_Unidade.Codigo')          
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_CartaRV.setor_codigo', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.users', 'dbo.Gestao_CartaRV.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_CartaRV.mes_referencia', 'dbo.Gestao_Mes.id')
            ->where('dbo.Gestao_CartaRV.mes_referencia', '=', '4')
            ->whereYear('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
            ->orderBy('dbo.users.name', 'asc')
            ->get();
  
           $customer_array[] = array(
                   'nome', 
                   'unidade',
                   'setor',
                   'mes',
                   'plc_porcent',
                   'superintendencia_porcent',
                   'gerencia_porcent',
                   'area_porcent',
                   'score_porcent',
                   'rv_maximo',
                   'rv_apurado',
                   'rv_recebido',
                   'rv_projetado'
                );
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'nome'  => $customer->nome,
                                'unidade' => $customer->unidade,
                                'setor' => $customer->setor,
                                'mes' => $customer->mes,
                                'plc_porcent' => $customer->plc_porcent,
                                'superintendencia_porcent' => $customer->unidade_porcent,
                                'gerencia_porcent' => $customer->gerencia_porcent,
                                'area_porcent' => $customer->area_porcent,
                                'score_porcent' => $customer->score_porcent,
                                'rv_maximo' => number_format($customer->rv_maximo,2,",","."),
                                'rv_apurado' => number_format($customer->rv_apurado,2,",","."),
                                'rv_recebido' => number_format($customer->rv_recebido,2,",","."),
                                'rv_projetado' => number_format($customer->rv_projetado,2,",","."),
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Informativos de rv mensal', function($excel) use ($customer_array){
                        $excel->setTitle('Informativos de rv mensal');
                        $excel->sheet('Informativo rv mensal', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');


    }

    public function controlador_cartarv_historico() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');
    
        $datas = DB::table('dbo.Gestao_CartaRV')
            ->select(
                     'dbo.Gestao_CartaRV.id as id',
                     'dbo.users.name as usuario_nome',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                     'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                     'dbo.Gestao_CartaRV.gerencia_porcent',
                     'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                     'dbo.Gestao_CartaRV.score_porcent as score_porcent',
                     'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                     'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                     'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                     'dbo.Gestao_CartaRV.rv_projetado as rv_projetado')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Gestao_CartaRV.unidade_codigo', 'PLCFULL.dbo.Jurid_Unidade.Codigo')          
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_CartaRV.setor_codigo', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.users', 'dbo.Gestao_CartaRV.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_CartaRV.mes_referencia', 'dbo.Gestao_Mes.id')
            ->where('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
            ->orderBy('dbo.users.name', 'asc')
            ->whereYear('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
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
    
        $usuarios =  DB::table('dbo.users')
            ->select('dbo.users.id', 'dbo.users.name')  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->where('dbo.profile_user.profile_id','!=', '1')
            ->orderby('dbo.users.name', 'asc')
            ->get(); 
    
        $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
        ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao') 
        ->whereNotIn('PLCFULL.dbo.Jurid_Unidade.Codigo', ['1.','1.15'])
        ->orderBy('PLCFULL.dbo.Jurid_Unidade.Descricao', 'asc')
        ->get();
    
        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('PLCFULL.dbo.Jurid_Setor.Codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao')  
        ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
        ->orderby('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
        ->get();
    
        return view('Painel.Gestao.Controlador.CartaRV.historico', compact('datas','usuarios','setores','unidades','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_cartarv_exportarano() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');
        setlocale(LC_TIME, 'PTB'); 
        
        $customer_data = DB::table('dbo.Gestao_CartaRV')
            ->select(
                     'dbo.users.name as nome',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                     'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                     'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                     'dbo.Gestao_CartaRV.score_porcent as score_porcent',
                     'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                     'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                     'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                     'dbo.Gestao_CartaRV.rv_projetado as rv_projetado')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Gestao_CartaRV.unidade_codigo', 'PLCFULL.dbo.Jurid_Unidade.Codigo')          
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_CartaRV.setor_codigo', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.users', 'dbo.Gestao_CartaRV.user_id', 'dbo.users.id')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_CartaRV.mes_referencia', 'dbo.Gestao_Mes.id')
            ->whereYear('dbo.Gestao_CartaRV.ano_referencia', '=', 2021)
            ->orderBy('dbo.users.name', 'asc')
            ->get();
  
           $customer_array[] = array(
                   'nome', 
                   'unidade',
                   'setor',
                   'mes',
                   'plc_porcent',
                   'unidade_porcent',
                   'area_porcent',
                   'score_porcent',
                   'rv_maximo',
                   'rv_apurado',
                   'rv_recebido',
                   'rv_projetado'
                );
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'nome'  => $customer->nome,
                                'unidade' => $customer->unidade,
                                'setor' => $customer->setor,
                                'mes' => $customer->mes,
                                'plc_porcent' => $customer->plc_porcent,
                                'unidade_porcent' => $customer->unidade_porcent,
                                'area_porcent' => $customer->area_porcent,
                                'score_porcent' => $customer->score_porcent,
                                'rv_maximo' => number_format($customer->rv_maximo,2,",","."),
                                'rv_apurado' => number_format($customer->rv_apurado,2,",","."),
                                'rv_recebido' => number_format($customer->rv_recebido,2,",","."),
                                'rv_projetado' => number_format($customer->rv_projetado,2,",","."),
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Informativos de rv anual', function($excel) use ($customer_array){
                        $excel->setTitle('Informativos de rv anual');
                        $excel->sheet('Informativo rv anual', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');


    }


    public function controlador_cartarv_gravar(Request $request) {


        $opcao = $request->get('opcao');
        $carbon= Carbon::now();
        
        if($opcao == "manualmente") {
        //Se for manualmente 
        
        $user_id = $request->get('usuario');
        $unidade = $request->get('unidade');
        $setor = $request->get('setor');
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $plc_porcent = str_replace (',', '.', str_replace ('.', '.', $request->get('plc_porcent')));
        $unidade_porcent = str_replace (',', '.', str_replace ('.', '.', $request->get('unidade_porcent')));
        $gerencia_porcent = str_replace (',', '.', str_replace ('.', '.', $request->get('gerencia_porcent')));
        $area_porcent = str_replace (',', '.', str_replace ('.', '.', $request->get('area_porcent')));
        $score_porcent = str_replace (',', '.', str_replace ('.', '.', $request->get('score_porcent')));
        $rv_maximo = str_replace (',', '.', str_replace ('.', '', $request->get('rv_maximo')));
        $rv_apurado = str_replace (',', '.', str_replace ('.', '', $request->get('rv_apurado')));
        $rv_recebido = str_replace (',', '.', str_replace ('.', '', $request->get('rv_recebido')));
        $rv_projetado = str_replace (',', '.', str_replace ('.', '', $request->get('rv_projetado')));

        //Grava na tabela
        $value1 = array(
            'user_id' => $user_id, 
            'unidade_codigo' => $unidade, 
            'setor_codigo' => $setor, 
            'mes_referencia' => $mes,
            'ano_referencia' => $ano, 
            'plc_porcent' => $plc_porcent, 
            'unidade_porcent' => $unidade_porcent,
            'gerencia_porcent' => $gerencia_porcent,
            'area_porcent' => $area_porcent,
            'score_porcent' => $score_porcent,
            'rv_maximo' => $rv_maximo,
            'rv_apurado' => $rv_apurado,
            'rv_recebido' => $rv_recebido,
            'rv_projetado' => $rv_projetado);
        DB::table('dbo.Gestao_CartaRV')->insert($value1);

        $id_carta = DB::table('dbo.Gestao_CartaRV')->select('id')->where('user_id','=', $user_id)->orderBy('id', 'desc')->value('id');

        //Manda notificação para o usuario
        $values3= array('data' => $carbon, 'id_ref' => $id_carta, 'user_id' => Auth::user()->id, 'destino_id' => $user_id, 'tipo' => '6', 'obs' => 'Nota da carta de RV incluida no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        }
        else 
        //Se for importação
        {
          
            $path = $request->file('select_file')->getRealPath();
            $data = Excel::load($path)->get();

           if(count($data) > 0){
                $count = 0;
                foreach($data->toArray() as $key => $value){


                        $advogado = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $value['advogado'])));
                        $unidade = $value['unidade'];
                        $area = $value['area'];
                        $mes_apuracao = $value['mes_apuracao'];
                        $ano_referencia = $value['ano_apuracao'];
                        $plc_porcentagem = str_replace (',', '.', str_replace ('.', '.', $value['plc_porcent']));
                        $unidade_porcentagem = str_replace (',', '.', str_replace ('.', '.', $value['superintendencia_porcent']));
                        $gerencia_porcentagem = str_replace (',', '.', str_replace ('.', '.', $value['gerencia_porcent']));
                        $area_porcentagem = str_replace (',', '.', str_replace ('.', '.', $value['area_porcent']));
                        $score_individual = str_replace (',', '.', str_replace ('.', '.', $value['score_porcent']));
                        $rv_maximo = str_replace (',', '.', str_replace ('.', '.', $value['rv_maximo']));
                        $rv_apurado = str_replace (',', '.', str_replace ('.', '.', $value['rv_apurado']));
                        $rv_recebido = str_replace (',', '.', str_replace ('.', '.', $value['rv_recebido']));
                        $rv_projetado = str_replace (',', '.', str_replace ('.', '.', $value['rv_projetado']));

                        $advogado_id = DB::table('dbo.users')->select('id')->where('cpf','=', $advogado)->value('id');

                        if($advogado_id != null && $unidade != null && $area != null && $mes_apuracao != null 
                        && $plc_porcentagem != null && $unidade_porcentagem != null && $score_individual != null
                        && $rv_maximo != null && $rv_apurado != null && $rv_recebido != null && $rv_projetado != null) {


                        //Grava na tabela
                        $value1 = array(
                        'user_id' => $advogado_id, 
                        'unidade_codigo' => $unidade, 
                        'setor_codigo' => $area, 
                        'mes_referencia' => $mes_apuracao, 
                        'ano_referencia' => $ano_referencia,
                        'plc_porcent' => $plc_porcentagem, 
                        'unidade_porcent' => $unidade_porcentagem,
                        'gerencia_porcent' => $gerencia_porcentagem,
                        'area_porcent' => $area_porcentagem,
                        'score_porcent' => $score_individual,
                        'rv_maximo' => $rv_maximo,
                        'rv_apurado' => $rv_apurado,
                        'rv_recebido' => $rv_recebido,
                       'rv_projetado' => $rv_projetado);
                        DB::table('dbo.Gestao_CartaRV')->insert($value1);

                        $id_carta = DB::table('dbo.Gestao_CartaRV')->select('id')->where('user_id','=', $advogado_id)->orderBy('id', 'desc')->value('id');

                        //Manda notificação para o usuario
                        $values3= array('data' => $carbon, 'id_ref' => $id_carta, 'user_id' => Auth::user()->id, 'destino_id' => $advogado_id, 'tipo' => '6', 'obs' => 'Nota da carta de RV incluida no portal.', 'status' => 'A');
                        DB::table('dbo.Hist_Notificacao')->insert($values3);    
                        
                    }
                        $count++;
                }

        }
        
        }
        //Fim importação 

        flash('Importação realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.CartaRV.historico');
    }

    public function controlador_cartarv_editar($id) {

        //Pega os dados deste registro
        $datas = DB::table('dbo.Gestao_CartaRV')
        ->select(
                 'dbo.Gestao_CartaRV.id as id',
                 'dbo.users.id as usuario_id',
                 'dbo.users.name as usuario_nome',
                 'dbo.Gestao_Mes.id as mes_id',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.Gestao_CartaRV.ano_referencia as ano',
                 'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade_codigo',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                 'PLCFULL.dbo.Jurid_Setor.Codigo as setor_codigo',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor_descricao',
                 'dbo.Gestao_CartaRV.plc_porcent as plc_porcent',
                 'dbo.Gestao_CartaRV.unidade_porcent as unidade_porcent',
                 'dbo.Gestao_CartaRV.gerencia_porcent as gerencia_porcent',
                 'dbo.Gestao_CartaRV.area_porcent as area_porcent',
                 'dbo.Gestao_CartaRV.score_porcent as score_porcent',
                 'dbo.Gestao_CartaRV.rv_maximo as rv_maximo',
                 'dbo.Gestao_CartaRV.rv_apurado as rv_apurado',
                 'dbo.Gestao_CartaRV.rv_recebido as rv_recebido',
                 'dbo.Gestao_CartaRV.rv_projetado as rv_projetado')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'dbo.Gestao_CartaRV.unidade_codigo', 'PLCFULL.dbo.Jurid_Unidade.Codigo')          
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_CartaRV.setor_codigo', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.users', 'dbo.Gestao_CartaRV.user_id', 'dbo.users.id')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_CartaRV.mes_referencia', 'dbo.Gestao_Mes.id')
        ->where('dbo.Gestao_CartaRV.id', '=', $id)
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

    $unidades = DB::table('PLCFULL.dbo.Jurid_Unidade')
    ->select('PLCFULL.dbo.Jurid_Unidade.Codigo as codigo', 'PLCFULL.dbo.Jurid_Unidade.Descricao as descricao') 
    ->whereNotIn('PLCFULL.dbo.Jurid_Unidade.Codigo', ['1.','1.15'])
    ->orderBy('PLCFULL.dbo.Jurid_Unidade.Descricao', 'asc')
    ->get();

    $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
    ->select('PLCFULL.dbo.Jurid_Setor.Codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao')  
    ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
    ->orderby('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
    ->get();

    return view('Painel.Gestao.Controlador.CartaRV.editar', compact('datas','setores','unidades','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_cartarv_editado(Request $request) {

        $carbon= Carbon::now();

        $id = $request->get('id');
        $user_id = $request->get('usuario_id');
        $unidade = $request->get('unidade');
        $setor = $request->get('setor');
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $plc_porcent = $request->get('plc_porcent');
        $unidade_porcent = $request->get('unidade_porcent');
        $gerencia_porcent = $request->get('gerencia_porcent');
        $area_porcent = $request->get('area_porcent');
        $score_porcent = $request->get('score_porcent');
        $rv_maximo = str_replace (',', '.', str_replace ('.', '', $request->get('rv_maximo')));
        $rv_apurado = str_replace (',', '.', str_replace ('.', '', $request->get('rv_apurado')));
        $rv_recebido = str_replace (',', '.', str_replace ('.', '', $request->get('rv_recebido')));
        $rv_projetado = str_replace (',', '.', str_replace ('.', '', $request->get('rv_projetado')));

        
        //Atualiza na tabela
        $values = array(
            "unidade_codigo" => $unidade,
            'setor_codigo' => $setor,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
            "plc_porcent" => $plc_porcent,
            "unidade_porcent" => $unidade_porcent,
            "gerencia_porcent" => $gerencia_porcent,
            "area_porcent" => $area_porcent,
            "score_porcent" => $score_porcent,
            "rv_maximo" => $rv_maximo,
            "rv_apurado" => $rv_apurado,
            "rv_recebido" => $rv_recebido,
            "rv_projetado" => $rv_projetado);
        DB::table('dbo.Gestao_CartaRV')
       ->where('dbo.Gestao_CartaRV.id', '=', $id)
       ->update($values);

        //Envia notificação
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $user_id, 'tipo' => '6', 'obs' => 'Nota da carta de RV editada no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        flash('Edição realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.CartaRV.index');
    }

    public function controlador_notasadvogado_index() {

        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');

        $mespassado = $mes - 1;    
    
        $datas = DB::table('dbo.web_indicadores_nota')
            ->select('dbo.web_indicadores_nota.id as id',
                     'dbo.users.name as usuario_nome',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.web_indicadores_nota.nivel as nivel',
                     'dbo.web_indicadores_objetivo.objetivo as objetivo',
                     'dbo.web_indicadores_nota.nota as nota',
                     'dbo.web_indicadores_nota.nota as realizado')
            ->leftjoin('dbo.users', 'dbo.web_indicadores_nota.advogado', 'dbo.users.cpf')
            ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_nota.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_nota.mes_referencia', 'dbo.Gestao_Mes.id')
            ->where('dbo.web_indicadores_nota.mes_referencia', '=', $mespassado)
            ->where('dbo.web_indicadores_nota.ano_referencia', '=', '2021')
            ->orderBy('dbo.web_indicadores_nota.nota', 'desc')
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
    
        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderBy('dbo.users.name', 'ASC')
        ->distinct()
        ->get(); 
        
        $objetivos = DB::table('dbo.web_indicadores_objetivo')
            ->where('dbo.web_indicadores_objetivo.ativo', 'S')
            ->orderBy('dbo.web_indicadores_objetivo.objetivo', 'asc')
            ->get();
    
        return view('Painel.Gestao.Controlador.NotasAdvogado.index', compact('datas','objetivos','ano','usuarios','totalNotificacaoAbertas', 'notificacoes'));


    }


    public function controlador_notasadvogado_historico() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');
    
        $datas = DB::table('dbo.web_indicadores_nota')
            ->select('dbo.web_indicadores_nota.id as id',
                     'dbo.users.name as usuario_nome',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.web_indicadores_nota.nivel as nivel',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.web_indicadores_objetivo.objetivo as objetivo',
                     'dbo.web_indicadores_nota.nota as nota',
                     'dbo.web_indicadores_nota.nota as realizado')
            ->leftjoin('dbo.users', 'dbo.web_indicadores_nota.advogado', 'dbo.users.cpf')
            ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_nota.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_nota.mes_referencia', 'dbo.Gestao_Mes.id')
            ->where('dbo.web_indicadores_nota.ano_referencia', '=', '2021')
            ->orderBy('dbo.web_indicadores_nota.nota', 'desc')
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
    
        $usuarios =  DB::table('dbo.users')
            ->select('dbo.users.id', 'dbo.users.name')  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->where('dbo.profile_user.profile_id','!=', '1')
            ->get(); 

        $objetivos = DB::table('dbo.web_indicadores_objetivo')
            ->where('dbo.web_indicadores_objetivo.ativo', 'S')
            ->orderBy('dbo.web_indicadores_objetivo.objetivo', 'asc')
            ->get();
    
        return view('Painel.Gestao.Controlador.NotasAdvogado.historico', compact('datas','objetivos','ano','usuarios','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_notasadvogado_exportarnotasmes() {

        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');

        $mespassado = $mes - 1;    

        $customer_data = DB::table('dbo.web_indicadores_nota')
        ->select(
                 'dbo.users.name as nome',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.web_indicadores_nota.nivel as nivel',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.web_indicadores_objetivo.objetivo as objetivo',
                 'dbo.web_indicadores_nota.nota as nota',
                 'dbo.web_indicadores_nota.nota as realizado')
        ->leftjoin('dbo.users', 'dbo.web_indicadores_nota.advogado', 'dbo.users.cpf')
        ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_nota.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_nota.mes_referencia', 'dbo.Gestao_Mes.id')
        ->where('dbo.web_indicadores_nota.mes_referencia', '=', $mespassado)
        ->where('dbo.web_indicadores_nota.ano_referencia', '=', '2021')
        ->orderBy('dbo.web_indicadores_nota.nota', 'desc')
        ->get()
        ->toArray();

        $customer_array[] = array(
                'nome', 
                'unidade',
                'setor',
                'nivel',
                'mes',
                'objetivo',
                'nota',
                'realizado');
            foreach($customer_data as $customer)
            {
             $customer_array[] = array(
              'nome'  => $customer->nome,
              'unidade'  => $customer->unidade,
              'setor'  => $customer->setor,
              'nivel' => $customer->nivel,
              'mes' => $customer->mes,
              'objetivo' => $customer->objetivo,
              'nota' => $customer->nota,
              'realizado' => $customer->realizado
             );
            }
            Excel::create('Notas sócios mês', function($excel) use ($customer_array){
             $excel->setTitle('Notas sócios mês');
             $excel->sheet('Notas sócios mês', function($sheet) use ($customer_array){
              $sheet->fromArray($customer_array, null, 'A1', false, false);
             });
            })->download('xlsx');

    }

    public function controlador_notasadvogado_exportarnotasano() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');

        $customer_data = DB::table('dbo.web_indicadores_nota')
        ->select(
                 'dbo.users.name as nome',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.web_indicadores_nota.nivel as nivel',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.web_indicadores_objetivo.objetivo as objetivo',
                 'dbo.web_indicadores_nota.nota as nota',
                 'dbo.web_indicadores_nota.nota as realizado')
        ->leftjoin('dbo.users', 'dbo.web_indicadores_nota.advogado', 'dbo.users.cpf')
        ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_nota.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_nota.mes_referencia', 'dbo.Gestao_Mes.id')
        ->where('dbo.web_indicadores_nota.ano_referencia', '=', '2021')
        ->orderBy('dbo.web_indicadores_nota.nota', 'desc')
        ->get()
        ->toArray();

        $customer_array[] = array(
                'nome', 
                'unidade',
                'setor',
                'nivel',
                'mes',
                'objetivo',
                'nota',
                'realizado');
            foreach($customer_data as $customer)
            {
             $customer_array[] = array(
              'nome'  => $customer->nome,
              'unidade'  => $customer->unidade,
              'setor'  => $customer->setor,
              'nivel' => $customer->nivel,
              'mes' => $customer->mes,
              'objetivo' => $customer->objetivo,
              'nota' => $customer->nota,
              'realizado' => $customer->realizado
             );
            }
            Excel::create('Notas sócios ano', function($excel) use ($customer_array){
             $excel->setTitle('Notas sócios ano');
             $excel->sheet('Notas sócios ano', function($sheet) use ($customer_array){
              $sheet->fromArray($customer_array, null, 'A1', false, false);
             });
            })->download('xlsx');

    }

    public function controlador_notasadvogado_deletarnota($id) {

        //Deletar nota 
        DB::table('dbo.web_indicadores_nota')->where('id', $id)->delete();

        flash('Remoção da nota advogado realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.NotasAdvogado.index');
    }

    public function controlador_notasadvogado_gravar(Request $request) {

        $carbon= Carbon::now();

        $opcao = $request->get('opcao');


        //Se for manualmente
        if($opcao == "manualmente") {

        $user_id = $request->get('usuario');
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $nivel = $request->get('nivel');
        $objetivo = $request->get('objetivo');
        $nota = $request->get('nota');

        $user_cpf = DB::table('dbo.users')->select('cpf')->where('id','=', $user_id)->value('cpf');

        //Grava na tabela
        $value1 = array(
            'advogado' => $user_cpf, 
            'nivel' => $nivel, 
            'id_objetivo' => $objetivo, 
            'mes_referencia' => $mes, 
            'ano_referencia' => $ano, 
            'nota' => $nota);
        DB::table('dbo.web_indicadores_nota')->insert($value1);

        $id_carta = DB::table('dbo.web_indicadores_nota')->select('id')->where('advogado','=', $user_cpf)->orderBy('id', 'desc')->value('id');

        //Manda notificação para o usuario
        $values3= array('data' => $carbon, 'id_ref' => $id_carta, 'user_id' => Auth::user()->id, 'destino_id' => $user_id, 'tipo' => '6', 'obs' => 'Nota advogado incluida no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        //Verifico se já esta cadastro na web_indicadores_advogado
          $verifica = DB::table('dbo.web_indicadores_advogado')->select('id')->where('advogado','=', $user_cpf)->whereNull('datafim')->orderBy('id', 'desc')->value('id');
          
          if($verifica == null) {
            $value1 = array(
                'advogado' => $user_cpf, 
                'nivel' => $nivel, 
                'datainicio' => $carbon);
            DB::table('dbo.web_indicadores_advogado')->insert($value1);
          }

        
        flash('Inclusão da nota advogado realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.NotasAdvogado.index');

        }
        //Se for importação em massa
        else {

            $path = $request->file('select_file')->getRealPath();
            $data = Excel::load($path)->get();

           if(count($data) > 0){
                $count = 0;
                foreach($data->toArray() as $key => $value){

                    $advogadosemformato = $value['advogado'];
                    $advogado = str_replace ('.', '', str_replace('/', '', str_replace ('-', '', $advogadosemformato)));
                    $nivel = $value['nivel'];
                    $id_objetivo = $value['id_objetivo'];
                    $mes_referencia = $value['mes_referencia'];
                    $ano_referencia = $value['ano_referencia'];
                    $notasemformato = $value['nota'];
                    $nota = str_replace (',', '.', str_replace('.', '',str_replace('%', '',  $notasemformato)));

                    $advogado_id = DB::table('dbo.users')->select('id')->where('cpf','=', $advogado)->value('id');

                    if($advogado != null && $nivel != null && $id_objetivo != null && $mes_referencia != null 
                    && $ano_referencia != null && $nota != null) {
                            
                    //Grava na tabela
                    $value1 = array(
                        'advogado' => $advogado, 
                        'nivel' => $nivel, 
                        'id_objetivo' => $id_objetivo, 
                        'mes_referencia' => $mes_referencia, 
                        'ano_referencia' => $ano_referencia, 
                        'nota' => $nota);
                    DB::table('dbo.web_indicadores_nota')->insert($value1);

                    $id_nota = DB::table('dbo.web_indicadores_nota')->select('id')->where('advogado','=', $advogado)->orderBy('id', 'desc')->value('id');

                    //Manda notificação para o usuario
                    $values3= array('data' => $carbon, 'id_ref' => $id_nota, 'user_id' => Auth::user()->id, 'destino_id' => $advogado_id, 'tipo' => '6', 'obs' => 'Nota advgado incluida no portal.', 'status' => 'A');
                    DB::table('dbo.Hist_Notificacao')->insert($values3);    

                    //Verifico se já esta cadastro na web_indicadores_advogado
                    $verifica = DB::table('dbo.web_indicadores_advogado')->select('id')->where('advogado','=', $advogado)->whereNull('datafim')->orderBy('id', 'desc')->value('id');
          
                    if($verifica == null) {
                    $value1 = array(
                    'advogado' => $advogado, 
                    'nivel' => $nivel, 
                    'datainicio' => $carbon);
                    DB::table('dbo.web_indicadores_advogado')->insert($value1);
                    }


                   } else {
                    //Se tiver algum campo em branco ele vai armazenar, para no final criar uma planilha e enviar ao Rodrigo
                        $insert_fail[] = array(
                            'advogado' => $advogado,
                            'nivel' => $nivel,
                            'id_objetivo' => $id_objetivo,
                            'mes_referencia' => $mes_referencia,
                            'ano_referencia' => $ano_referencia,
                            'nota' => $nota);
                        }
                        $count++;
                }

        }
        
        // if(!empty($insert_fail)) {

        //     Excel::create('Erros importacao', function($excel) use ($insert_fail){
        //     $excel->setTitle('Erros importacao');
        //     $excel->sheet('Erros', function($sheet) use ($insert_fail){
        //     $sheet->fromArray($insert_fail, null, 'A1', false, false);
        //     });
        //     })->download('xlsx');
        // }

        flash('Importação realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.NotasAdvogado.index');

        }
        //Fim importação 


    }

    public function controlador_notasadvogado_editar($id) {

        //Pega os dados deste registro
        $datas = DB::table('dbo.web_indicadores_nota')
        ->select('dbo.web_indicadores_nota.id as id',
                 'dbo.users.id as usuario_id',
                 'dbo.users.name as usuario_nome',
                 'dbo.web_indicadores_nota.mes_referencia as mes_referencia',
                 'dbo.web_indicadores_nota.ano_referencia as ano_referencia',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.web_indicadores_nota.nivel as nivel',
                 'dbo.web_indicadores_objetivo.id as objetivo_id',
                 'dbo.web_indicadores_objetivo.objetivo as objetivo',
                 'dbo.web_indicadores_nota.nota as nota')
        ->leftjoin('dbo.users', 'dbo.web_indicadores_nota.advogado', 'dbo.users.cpf')
        ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_nota.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_nota.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->where('dbo.web_indicadores_nota.id', '=', $id)
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
        
        $objetivos = DB::table('dbo.web_indicadores_objetivo')
            ->where('dbo.web_indicadores_objetivo.ativo', 'S')
            ->orderBy('dbo.web_indicadores_objetivo.objetivo', 'asc')
            ->get();
    
        
        return view('Painel.Gestao.Controlador.NotasAdvogado.editar', compact('datas','objetivos','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_notasadvogado_editado(Request $request) {

        $carbon= Carbon::now();

        $id = $request->get('id');
        $user_id = $request->get('usuario_id');
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $nivel = $request->get('nivel');
        $objetivo = $request->get('objetivo');
        $nota  = str_replace (',', '.', str_replace ('.', '.', $request->get('nota')));

        //Atualiza na tabela
        $values = array(
            "nivel" => $nivel,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
            "nota" => $nota);
        DB::table('dbo.web_indicadores_nota')
       ->where('dbo.web_indicadores_nota.id', '=', $id)
       ->update($values);

        //Envia notificação
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $user_id, 'tipo' => '6', 'obs' => 'Nota advogado editada no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        flash('Edição realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.NotasAdvogado.index');
    }
    
    public function controlador_notasconsolidada_index() {

        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');

        $mespassado = $mes - 1;
    
        $datas = DB::table('dbo.web_indicadores_notaconsolidada')
            ->select('dbo.web_indicadores_notaconsolidada.id as id',
                     'dbo.users.cpf as usuario_cpf',
                     'dbo.users.name as usuario_nome',
                     'dbo.Gestao_Mes.descricao as mes',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.web_indicadores_notaconsolidada.nivel as nivel',
                     'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                     'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
            ->leftjoin('dbo.users', 'dbo.web_indicadores_notaconsolidada.advogado', 'dbo.users.cpf')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_notaconsolidada.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
            ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
            ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', '2021')
            ->orderBy('dbo.web_indicadores_notaconsolidada.nota_consolidada', 'desc')
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

    
        return view('Painel.Gestao.Controlador.NotaConsolidada.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_notasconsolidada_historico() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');
    
        $datas = DB::table('dbo.web_indicadores_notaconsolidada')
            ->select('dbo.web_indicadores_notaconsolidada.id as id',
                     'dbo.users.cpf as usuario_cpf',
                     'dbo.users.name as usuario_nome',
                     'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                     'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                     'dbo.Gestao_Mes.descricao as mes',
                     'dbo.web_indicadores_notaconsolidada.nivel as nivel',
                     'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                     'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
            ->leftjoin('dbo.users', 'dbo.web_indicadores_notaconsolidada.advogado', 'dbo.users.cpf')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_notaconsolidada.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
            ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', '2021')
            ->orderBy('dbo.web_indicadores_notaconsolidada.nota_consolidada', 'desc')
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

    
        return view('Painel.Gestao.Controlador.NotaConsolidada.historico', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_notasconsolidada_exportarnotasmes() {

        $carbon= Carbon::now();
        $mes = $carbon->format('m');
        $ano = $carbon->format('Y');

        $mespassado = $mes - 1;    

        $customer_data = DB::table('dbo.web_indicadores_notaconsolidada')
        ->select(
                 'dbo.users.name as nome',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.web_indicadores_notaconsolidada.nivel as nivel',
                 'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                 'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.users', 'dbo.web_indicadores_notaconsolidada.advogado', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_notaconsolidada.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        ->where('dbo.web_indicadores_notaconsolidada.mes_referencia', '=', $mespassado)
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', '2021')
        ->orderBy('dbo.web_indicadores_notaconsolidada.nota_consolidada', 'desc')
        ->get()
        ->toArray();

        $customer_array[] = array(
                'nome', 
                'unidade',
                'setor',
                'nivel',
                'mes',
                'notaconsolidada',
                'notaacumulada');
            foreach($customer_data as $customer)
            {
             $customer_array[] = array(
              'nome'  => $customer->nome,
              'unidade'  => $customer->unidade,
              'setor'  => $customer->setor,
              'nivel' => $customer->nivel,
              'mes' => $customer->mes,
              'notaconsolidada' => $customer->notaconsolidada,
              'notaacumulada' => $customer->notaacumulada,
             );
            }
            Excel::create('Notas consolidada mês', function($excel) use ($customer_array){
             $excel->setTitle('Notas consolidada mês');
             $excel->sheet('Notas consolidada mês', function($sheet) use ($customer_array){
              $sheet->fromArray($customer_array, null, 'A1', false, false);
             });
            })->download('xlsx');

    }

    public function controlador_notasconsolidada_exportarnotasano() {

        $carbon= Carbon::now();
        $ano = $carbon->format('Y');

        $customer_data = DB::table('dbo.web_indicadores_notaconsolidada')
        ->select(
                 'dbo.users.name as nome',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.Gestao_Mes.descricao as mes',
                 'dbo.web_indicadores_notaconsolidada.nivel as nivel',
                 'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                 'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
        ->leftjoin('dbo.users', 'dbo.web_indicadores_notaconsolidada.advogado', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_notaconsolidada.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.web_indicadores_notaconsolidada.mes_referencia', 'dbo.Gestao_Mes.id')
        ->where('dbo.web_indicadores_notaconsolidada.ano_referencia', '=', '2021')
        ->orderBy('dbo.web_indicadores_notaconsolidada.nota_consolidada', 'desc')
        ->get()
        ->toArray();

        $customer_array[] = array(
                'nome', 
                'unidade',
                'setor',
                'nivel',
                'mes',
                'notaconsolidada',
                'notaacumulada');
            foreach($customer_data as $customer)
            {
             $customer_array[] = array(
              'nome'  => $customer->nome,
              'unidade'  => $customer->unidade,
              'setor'  => $customer->setor,
              'nivel' => $customer->nivel,
              'mes' => $customer->mes,
              'notaconsolidada' => $customer->notaconsolidada,
              'notaacumulada' => $customer->notaacumulada,
             );
            }
            Excel::create('Notas consolidada ano', function($excel) use ($customer_array){
             $excel->setTitle('Notas consolidada ano');
             $excel->sheet('Notas consolidada ano', function($sheet) use ($customer_array){
              $sheet->fromArray($customer_array, null, 'A1', false, false);
             });
            })->download('xlsx');

    }

    public function controlador_notasconsolidada_detalhar($advogado_cpf) {

    
        $count = 0;
        $indicadoresnota = DB::select( DB::raw("
        SELECT a.advogado,u.nome,
        a.nivel,
        a.id_objetivo,
        o.objetivo,
        a.ano_referencia,
        convert(numeric(5,2),sum(a.nota)/count(*)) AS realizado,
        m.score90,
        m.meta,
        m.score120
        ,m.peso,
        m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado_cpf%'
                AND a.id_objetivo not in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,m.score90,m.meta,m.score120,m.peso,m.uom
                UNION ALL
                SELECT a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota AS realizado,m.score90,m.meta,m.score120,m.peso,m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado_cpf%'
                AND a.id_objetivo in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                AND a.mes_referencia in (SELECT max(aux.mes_referencia) 
                                         FROM web_indicadores_nota AS aux 
                                         WHERE a.advogado = aux.advogado
                                         and aux.ano_referencia = a.ano_referencia)
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota,m.score90,m.meta,m.score120,m.peso,m.uom
                ORDER BY a.id_objetivo ASC"));


           foreach ($indicadoresnota as $indicadoresnotas) {

            $indicadoresnotas->nota = ((100*$indicadoresnotas->realizado)/$indicadoresnotas->meta);

            if($indicadoresnotas->realizado >= $indicadoresnotas->score120 && $indicadoresnotas->id_objetivo <> 4){
                $indicadoresnotas->nota = 120;

            } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90){
                $indicadoresnotas->nota = 0;
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->score90 && $indicadoresnotas->realizado < $indicadoresnotas->meta){
                $indicadoresnotas->nota = (100-((10/($indicadoresnotas->meta-$indicadoresnotas->score90))*($indicadoresnotas->meta- $indicadoresnotas->realizado)));
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->meta && $indicadoresnotas->realizado < $indicadoresnotas->score120){
                $indicadoresnotas->nota = (((20/($indicadoresnotas->score120 - $indicadoresnotas->meta))*($indicadoresnotas->realizado - $indicadoresnotas->meta))+100);
            }

            if($indicadoresnotas->id_objetivo == 4){
                if($indicadoresnotas->realizado <= $indicadoresnotas->score120){
                    $indicadoresnotas->nota = 120;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score90){
                    $indicadoresnotas->nota = 0;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score120 && $indicadoresnotas->realizado <= $indicadoresnotas->meta){
                        $indicadoresnotas->nota = (((20/($indicadoresnotas->meta - $indicadoresnotas->score120))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);

                } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90 && $indicadoresnotas->realizado > $indicadoresnotas->meta){
                    $indicadoresnotas->nota = (((10/($indicadoresnotas->score90 - $indicadoresnotas->meta ))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);
                }
            }

            $indicadoresnotas->nota = number_format((float)$indicadoresnotas->nota, 2, '.', ''); 
            $count = $count+1;
            
        }       

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



    return view('Painel.Gestao.Controlador.NotaConsolidada.detalhar', compact('advogado_cpf','indicadoresnota', 'totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_notasconsolidada_exportarnotasocio($advogado_cpf) {

        $count = 0;
        $indicadoresnota = DB::select( DB::raw("
        SELECT a.advogado,
        u.nome as nome,
        a.nivel,
        a.id_objetivo,
        o.objetivo,
        a.ano_referencia,
        convert(numeric(5,2),sum(a.nota)/count(*)) AS realizado,
        m.score90,
        m.meta,
        m.score120
        ,m.peso,
        m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado_cpf%'
                AND a.id_objetivo not in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,m.score90,m.meta,m.score120,m.peso,m.uom
                UNION ALL
                SELECT a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota AS realizado,m.score90,m.meta,m.score120,m.peso,m.uom
                FROM web_indicadores_nota AS a
                INNER JOIN web_indicadores_objetivo o
                ON a.id_objetivo = o.id
                INNER JOIN web_indicadores_metas m
                ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                INNER JOIN web_usuario u
                ON a.advogado = u.cpf
                WHERE a.advogado like '%$advogado_cpf%'
                AND a.id_objetivo in (12,16,17,18)
                AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
                AND a.mes_referencia in (SELECT max(aux.mes_referencia) 
                                         FROM web_indicadores_nota AS aux 
                                         WHERE a.advogado = aux.advogado
                                         and aux.ano_referencia = a.ano_referencia)
                GROUP BY a.advogado,u.nome,a.nivel,a.id_objetivo,o.objetivo,a.ano_referencia,a.nota,m.score90,m.meta,m.score120,m.peso,m.uom
                ORDER BY a.id_objetivo ASC"));


           foreach ($indicadoresnota as $indicadoresnotas) {

            $indicadoresnotas->nota = ((100*$indicadoresnotas->realizado)/$indicadoresnotas->meta);

            if($indicadoresnotas->realizado >= $indicadoresnotas->score120 && $indicadoresnotas->id_objetivo <> 4){
                $indicadoresnotas->nota = 120;

            } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90){
                $indicadoresnotas->nota = 0;
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->score90 && $indicadoresnotas->realizado < $indicadoresnotas->meta){
                $indicadoresnotas->nota = (100-((10/($indicadoresnotas->meta-$indicadoresnotas->score90))*($indicadoresnotas->meta- $indicadoresnotas->realizado)));
            
            } else if ($indicadoresnotas->realizado >= $indicadoresnotas->meta && $indicadoresnotas->realizado < $indicadoresnotas->score120){
                $indicadoresnotas->nota = (((20/($indicadoresnotas->score120 - $indicadoresnotas->meta))*($indicadoresnotas->realizado - $indicadoresnotas->meta))+100);
            }

            if($indicadoresnotas->id_objetivo == 4){
                if($indicadoresnotas->realizado <= $indicadoresnotas->score120){
                    $indicadoresnotas->nota = 120;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score90){
                    $indicadoresnotas->nota = 0;

                } else if ($indicadoresnotas->realizado > $indicadoresnotas->score120 && $indicadoresnotas->realizado <= $indicadoresnotas->meta){
                        $indicadoresnotas->nota = (((20/($indicadoresnotas->meta - $indicadoresnotas->score120))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);

                } else if ($indicadoresnotas->realizado < $indicadoresnotas->score90 && $indicadoresnotas->realizado > $indicadoresnotas->meta){
                    $indicadoresnotas->nota = (((10/($indicadoresnotas->score90 - $indicadoresnotas->meta ))*($indicadoresnotas->meta - $indicadoresnotas->realizado))+100);
                }
            }

            $indicadoresnotas->nota = number_format((float)$indicadoresnotas->nota, 2, '.', ''); 
            $count = $count+1;
            
        }      

              $customer_array[] = array(
                           'nome', 
                           'objetivo',
                           'nivel',
                           'ano_referencia',
                           'uom',
                           'score90',
                           'meta',
                           'score120',
                           'realizado',
                           'nota',);
                  
                        foreach($indicadoresnota as $customer) {
                                $customer_array[] = array(
                                        'nome'  => $customer->nome,
                                        'objetivo' => $customer->objetivo,
                                        'nivel' => $customer->nivel,
                                        'ano_referencia' => $customer->ano_referencia,
                                        'uom' => $customer->uom,
                                        'score90' => $customer->score90,
                                        'meta' => $customer->meta,
                                        'score120' => $customer->score120,
                                        'realizado' => $customer->realizado,
                                        'nota' => $customer->nota,
                                );
                        }
                        ini_set('memory_limit','-1'); 
                        Excel::create('Nota consolidada sócio', function($excel) use ($customer_array){
                                $excel->setTitle('Nota consolidada sócio');
                                $excel->sheet('Nota consolidada sócio', function($sheet) use ($customer_array) {
                                $sheet->fromArray($customer_array, null, 'A1', false, false);
                        });
                })->download('xlsx');



    }

    public function controlador_notasconsolidada_detalharmes($cpf, $id_objetivo) {

        $notaDetalhadaPorObjetivo = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.descricao as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        WHERE a.advogado like '%$cpf%'
        AND a.id_objetivo = $id_objetivo
        AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')"));
        $notaacumulada = 0;
        $count = 0;
        $soma = 1;


        if ($id_objetivo == 12 || $id_objetivo == 16 ||  $id_objetivo == 17 ||  $id_objetivo == 18){

                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado)));
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = 0;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = $notaPorObjetivo->nota;
                    // Fim //
                    
                    // Converte variável para ter apenas duas casas decimais //
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    // Fim //
                    
                    $count = $count+1; 
                }
    
            } elseif ($id_objetivo != 12 || $id_objetivo == 16 || $id_objetivo != 17 || $id_objetivo != 18){
    
                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);

    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->realizado <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota = 120;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota = 0;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score120 && $notaPorObjetivo->realizado <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
    
                        } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90 && $notaPorObjetivo->realizado > $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((10/($notaPorObjetivo->score90-$notaPorObjetivo->meta))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
                        }
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = $notaacumulada/$soma;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = ((100*$notaPorObjetivo->nota_acumulada)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota_consolidada_acumulada = 120;
                    
                    } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota_consolidada_acumulada = 0;
                
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada)));
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->nota_acumulada-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota_consolidada_acumulada = 120;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota_consolidada_acumulada = 0;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score120 && $notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
    
                        } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
                        }
                    }
                    
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    
                    $count = $count+1;
                    $soma = $soma+1;
                    
                }
            
        }

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

        return view('Painel.Gestao.Controlador.NotaConsolidada.detalharmes', compact('notaDetalhadaPorObjetivo', 'totalNotificacaoAbertas', 'notificacoes', 'cpf', 'id_objetivo'));
    }

    public function controlador_notasconsolidada_exportardetalhamentomes($cpf, $id_objetivo) {

        $notaDetalhadaPorObjetivo = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.descricao as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        WHERE a.advogado like '%$cpf%'
        AND a.id_objetivo = $id_objetivo
        AND a.ano_referencia = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        ORDER BY Gestao_Mes.id ASC"));
        $notaacumulada = 0;
        $count = 0;
        $soma = 1;

        if ($id_objetivo == 12 || $id_objetivo == 16 ||  $id_objetivo == 17 ||  $id_objetivo == 18){

                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = 0;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = $notaPorObjetivo->nota;
                    // Fim //
                    
                    // Converte variável para ter apenas duas casas decimais //
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    // Fim //
                    
                    $count = $count+1; 
                }
    
            } elseif ($id_objetivo != 12 || $id_objetivo == 16 || $id_objetivo != 17 || $id_objetivo != 18){
    
                foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                    $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);

    
                    if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota = 120;
    
                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota = 0;
                
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->id_objetivo)));
                    
                    } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->realizado <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota = 120;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota = 0;
    
                        } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score120 && $notaPorObjetivo->realizado <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
    
                        } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90 && $notaPorObjetivo->realizado > $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota = (((10/($notaPorObjetivo->score90-$notaPorObjetivo->meta))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
                        }
                    }
    
                    $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                    $notaPorObjetivo->nota_acumulada = $notaacumulada/$soma;
                    $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;
    
                    // Calcula a nota consolidada através da media das notas por mês //
                    $notaPorObjetivo->nota_consolidada_acumulada = ((100*$notaPorObjetivo->nota_acumulada)/$notaPorObjetivo->meta);
    
                    if($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                        $notaPorObjetivo->nota_consolidada_acumulada = 120;
                    
                    } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90){
                    $notaPorObjetivo->nota_consolidada_acumulada = 0;
                
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada)));
                    
                    } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->nota_acumulada-$notaPorObjetivo->meta))+100);
                    }
    
                    if($notaPorObjetivo->id_objetivo == 4){
                        if($notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->score120){
                            $notaPorObjetivo->nota_consolidada_acumulada = 120;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score90){
                            $notaPorObjetivo->nota_consolidada_acumulada = 0;
    
                        } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score120 && $notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
    
                        } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta){
                            $notaPorObjetivo->nota_consolidada_acumulada = (((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
                        }
                    }
                    
                    $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                    $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                    $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                    
                    $count = $count+1;
                    $soma = $soma+1;
                    
                }       
        }
 
         $customer_array[] = array(
                           'objetivo', 
                           'mes',
                           'score90',
                           'meta',
                           'score120',
                           'realizado',
                           'nota_acumulada',
                           'consolidada_mes',
                           'nota_consolidada_acumulada');
                  
                        foreach($customer_data as $customer) {
                                $customer_array[] = array(
                                        'objetivo'  => $customer->objetivo,
                                        'mes' => $customer->mes,
                                        'score90' => $customer->score90,
                                        'meta' => $customer->meta,
                                        'score120' => $customer->score120,
                                        'realizado' => $customer->realizado,
                                        'nota_acumulada' => $customer->nota_acumulada,
                                        'consolidada_mes' => $customer->consolidada_mes,
                                        'nota_consolidada_acumulada' => $customer->nota_consolidada_acumulada,

                                );
                        }
                        ini_set('memory_limit','-1'); 
                        Excel::create('Detalhamento indicador', function($excel) use ($customer_array){
                                $excel->setTitle('Detalhamento indicador');
                                $excel->sheet('Detalhamento indicador', function($sheet) use ($customer_array) {
                                $sheet->fromArray($customer_array, null, 'A1', false, false);
                        });
                })->download('xlsx');

    }

    public function controlador_notasconsolidada_editar($id) {

          //Pega os dados deste registro
          $datas = DB::table('dbo.web_indicadores_notaconsolidada')
          ->select('dbo.web_indicadores_notaconsolidada.id as id',
                   'dbo.users.id as usuario_id',
                   'dbo.users.name as usuario_nome',
                   'dbo.web_indicadores_notaconsolidada.mes_referencia as mes_referencia',
                   'dbo.web_indicadores_notaconsolidada.ano_referencia as ano_referencia',
                   'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                   'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                   'dbo.web_indicadores_notaconsolidada.nivel as nivel',
                   'dbo.web_indicadores_notaconsolidada.nota_consolidada as notaconsolidada',
                   'dbo.web_indicadores_notaconsolidada.nota_acumulada as notaacumulada')
          ->leftjoin('dbo.users', 'dbo.web_indicadores_notaconsolidada.advogado', 'dbo.users.cpf')
          ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_notaconsolidada.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
          ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
          ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
          ->where('dbo.web_indicadores_notaconsolidada.id', '=', $id)
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

          
          return view('Painel.Gestao.Controlador.NotaConsolidada.editar', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }


    public function controlador_notasconsolidada_editado(Request $request) {

        $carbon= Carbon::now();

        $id = $request->get('id');
        $user_id = $request->get('usuario_id');
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $nivel = $request->get('nivel');
        $notaconsolidada = str_replace (',', '.', str_replace ('.', '.', $request->get('notaconsolidada')));
        $notaacumulada = str_replace (',', '.', str_replace ('.', '.', $request->get('notaacumulada')));

        //Atualiza na tabela
        $values = array(
            "nivel" => $nivel,
            'mes_referencia' => $mes,
            'ano_referencia' => $ano,
            "nota_consolidada" => $notaconsolidada,
            "nota_acumulada" => $notaacumulada);
        DB::table('dbo.web_indicadores_notaconsolidada')
       ->where('dbo.web_indicadores_notaconsolidada.id', '=', $id)
       ->update($values);

        //Envia notificação
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $user_id, 'tipo' => '6', 'obs' => 'Nota advogado editada no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        flash('Edição realizada com sucesso !')->success();
        return redirect()->route('Painel.Gestao.Controlador.NotasConsolidada.index');

    }

    public function controlador_hierarquia_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.web_hierarquia')
            ->select('dbo.users.id','dbo.users.name as Responsavel', 'dbo.users.email as email', 'dbo.users.cpf as cpf')       
            ->join('dbo.users', 'dbo.web_hierarquia.responsavel', 'dbo.users.cpf')
            ->where('dbo.web_hierarquia.ativo', 'S')
            ->groupBy('dbo.users.id','dbo.users.name', 'dbo.users.email', 'dbo.users.cpf', 'dbo.web_hierarquia.responsavel')
            ->orderBy('dbo.users.name', 'asc')
            ->get();

        $responsaveis =  DB::table('dbo.users')
            ->select('dbo.users.id', 'dbo.users.name')  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->whereIn('dbo.profile_user.profile_id', array(20,23,24,27,37,38))
            ->groupby('dbo.users.id', 'dbo.users.name')
            ->orderBy('dbo.users.name', 'asc')
            ->get();    
            
        $usuarios =  DB::table('dbo.users')
            ->select('dbo.users.id', 'dbo.users.name')  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->whereNotIn('dbo.profile_user.profile_id', array(1,20,23,24,27,37,38))
            ->groupby('dbo.users.id', 'dbo.users.name')
            ->orderBy('dbo.users.name', 'asc')
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

    
        return view('Painel.Gestao.Controlador.Hierarquia.index', compact('responsaveis','usuarios','datahoje','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_hierarquia_exportarresponsaveis() {

        $customer_data = DB::table('dbo.web_hierarquia')
        ->select('dbo.users.name as Responsavel', 'dbo.users.email as email', 'dbo.users.cpf as cpf')       
        ->join('dbo.users', 'dbo.web_hierarquia.responsavel', 'dbo.users.cpf')
        ->where('dbo.web_hierarquia.ativo', 'S')
        ->groupBy('dbo.users.id','dbo.users.name', 'dbo.users.email', 'dbo.users.cpf', 'dbo.web_hierarquia.responsavel')
        ->orderBy('dbo.users.name', 'asc')
        ->get();
  
           $customer_array[] = array(
                   'Responsavel', 
                   'email',
                   'cpf');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'Responsavel'  => $customer->Responsavel,
                                'email' => $customer->email,
                                'cpf' => $customer->cpf,
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Listagem responsáveis', function($excel) use ($customer_array){
                        $excel->setTitle('Listagem responsáveis');
                        $excel->sheet('Listagem responsáveis', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');

    }

    public function controlador_hierarquia_advogados($responsavel_cpf) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $responsavel_id = DB::table('dbo.users')->select('id')->where('cpf','=', $responsavel_cpf)->value('id');
        $responsavel_nome = DB::table('dbo.users')->select('name')->where('cpf','=', $responsavel_cpf)->value('name');

        $datas = DB::table('dbo.web_hierarquia')
            ->select(
                    'dbo.web_hierarquia.id as id',
                    'dbo.users.id as advogado_id',
                    'dbo.users.name as advogado_nome', 
                    'dbo.users.email as email', 
                    'dbo.users.cpf as cpf',
                    'dbo.web_hierarquia.ativo as ativo',
                    'dbo.web_hierarquia.datainicio as datainicio',
                    'dbo.web_hierarquia.datafim as datafim',
                    'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                    'PLCFULL.dbo.Jurid_Setor.Descricao as setor')       
            ->leftjoin('dbo.users', 'dbo.web_hierarquia.advogado', 'dbo.users.cpf')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
            ->where('dbo.web_hierarquia.responsavel', $responsavel_cpf)
            ->where('dbo.web_hierarquia.ativo', 'S')
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

        $usuarios =  DB::table('dbo.users')
            ->select('dbo.users.id', 'dbo.users.name')  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->where('dbo.profile_user.profile_id','!=', '1')
            ->groupby('dbo.users.id', 'dbo.users.name')
            ->orderBy('dbo.users.name', 'asc')
            ->get();     

    
        return view('Painel.Gestao.Controlador.Hierarquia.advogados', compact('usuarios','datahoje','datas','responsavel_id','responsavel_nome','responsavel_cpf','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_hierarquia_exportaradvogados($responsavel_cpf) {

        $responsavel_nome = DB::table('dbo.users')->select('name')->where('cpf','=', $responsavel_cpf)->value('name');


        $customer_data = DB::table('dbo.web_hierarquia')
        ->select(
                'dbo.users.name as socio', 
                'dbo.users.email as email', 
                'dbo.users.cpf as cpf',
                'dbo.web_hierarquia.ativo as ativo',
                'dbo.web_hierarquia.datainicio as datainicio',
                'dbo.web_hierarquia.datafim as datafim',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                'PLCFULL.dbo.Jurid_Setor.Descricao as setor')       
        ->leftjoin('dbo.users', 'dbo.web_hierarquia.advogado', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.users.cpf', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->where('dbo.web_hierarquia.responsavel', $responsavel_cpf)
        ->where('dbo.web_hierarquia.ativo', 'S')
        ->get();
  
           $customer_array[] = array(
                   'socio', 
                   'email',
                   'cpf',
                   'responsavel',
                   'unidade',
                   'setor',
                   'ativo',
                   'datainicio');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'socio'  => $customer->socio,
                                'email' => $customer->email,
                                'cpf' => $customer->cpf,
                                'responsavel' => $responsavel_nome,
                                'unidade'  => $customer->unidade,
                                'setor' => $customer->setor,
                                'ativo' => $customer->ativo,
                                'datainicio' => date('d/m/Y', strtotime($customer->datainicio)),

                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Listagem sócios', function($excel) use ($customer_array){
                        $excel->setTitle('Listagem sócios');
                        $excel->sheet('Listagem sócios', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');

    }

    public function controlador_hierarquia_gravar(Request $request) {

        $carbon= Carbon::now();
        $advogado_id = $request->get('usuario');
        $responsavel_id = $request->get('responsavel');
        $responsavel_cpf = $request->get('responsavel_cpf');
        $ativo = $request->get('ativo');
        $data = $request->get('data');

        $advogado_cpf = DB::table('dbo.users')->select('cpf')->where('id','=', $advogado_id)->value('cpf');
       
        $value1 = array(
            'advogado' => $advogado_cpf, 
            'responsavel' => $responsavel_cpf, 
            'ativo' => $ativo, 
            'datainicio' => $data);
        DB::table('dbo.web_hierarquia')->insert($value1);

        $id = DB::table('dbo.web_hierarquia')->select('id')->orderBy('id', 'desc')->value('id');

        //Envia notificação para o Responsável
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '6', 'obs' => 'Um usuário foi adicionado em sua hierarquia no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        //Envia notificação para o Advogado
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $advogado_id, 'tipo' => '6', 'obs' => 'Você foi adicionado em uma nova hierarquia no portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);
  
        flash('Relacionamento realizado com sucesso !')->success();
        return redirect()->route("Painel.Gestao.Controlador.Hierarquia.advogados", ["id" => $responsavel_cpf]);

    }

    public function controlador_hierarquia_editar($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.web_hierarquia')
        ->select(
                'dbo.web_hierarquia.id as id',
                'dbo.users.id as responsavel_id',
                'dbo.users.name as responsavel_nome', 
                'dbo.users.cpf as responsavel_cpf',
                'PLCFULL.dbo.Jurid_Advogado.Codigo as usuario_cpf',
                'PLCFULL.dbo.Jurid_Advogado.Nome as usuario_nome',
                'dbo.web_hierarquia.ativo as ativo',
                'dbo.web_hierarquia.datainicio as datainicio',
                'dbo.web_hierarquia.datafim as datafim',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                'PLCFULL.dbo.Jurid_Setor.Descricao as setor')       
        ->leftjoin('dbo.users', 'dbo.web_hierarquia.responsavel', 'dbo.users.cpf')
        ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_hierarquia.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->where('dbo.web_hierarquia.id', $id)
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.data', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.Hierarquia.editar', compact('datahoje','datas','totalNotificacaoAbertas', 'notificacoes'));



    }

    public function controlador_hierarquia_editado(Request $request) {

        $carbon= Carbon::now();
        $id = $request->get('id');
        $usuario_cpf = $request->get('usuario_cpf');
        $responsavel_id = $request->get('responsavel_id');
        $responsavel_cpf = $request->get('responsavel_cpf');
        $ativo = $request->get('ativo');
        $datafim = $request->get('datafim');

        $usuario_id = DB::table('dbo.users')->select('id')->where('cpf','=', $usuario_cpf)->value('id');

        //Atualiza na tabela
        $values = array(
            "ativo" => $ativo,
            'datafim' => $datafim);
        DB::table('dbo.web_hierarquia')
        ->where('dbo.web_hierarquia.id', '=', $id)
        ->update($values);

        //Envia notificação para o responsavel
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '6', 'obs' => 'Um usuario da sua hierarquia foi editado em nosso portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        //Envia notificação para o usuario
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $usuario_id, 'tipo' => '6', 'obs' => 'A sua hierarquia foi editada em nosso portal.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        flash('Edição realizada com sucesso !')->success();
        return redirect()->route("Painel.Gestao.Controlador.Hierarquia.advogados", ["id" => $responsavel_cpf]);

    }

    public function procedure_mediascore(Request $request) {


        $carbon= Carbon::now();
        $mes = $carbon->format('m');
    
        $mes_referencia = $mes - 1;

        //Limpa a tabela temp
        DB::table('dbo.temp_calculomediascore')->delete();      
        
        $notaacumulada = 0;
        $count = 0;
        $soma = 1;

        //Pego todas as notas do mês de referencia 
        $notaDetalhadaPorObjetivo = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.id as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        AND a.id_objetivo = '3'
        AND a.ano_referencia = '2021'"));

        $notaDetalhadaPorObjetivo6 = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.id as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        AND a.id_objetivo = '6'
        AND a.ano_referencia = '2021'"));
    
        $notaDetalhadaPorObjetivo7 = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.id as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        AND a.id_objetivo = '7'
        AND a.ano_referencia = '2021'"));

        $notaDetalhadaPorObjetivo10 = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.id as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        AND a.id_objetivo = '10'
        AND a.ano_referencia = '2021'"));

        $notaDetalhadaPorObjetivo20 = DB::select( DB::raw("
        SELECT a.advogado,a.nivel,a.id_objetivo,
        o.objetivo,a.ano_referencia,a.nota as realizado,
        m.score90,m.meta,m.score120,m.peso,m.uom,Gestao_Mes.id as mes
        FROM web_indicadores_nota AS a
        INNER JOIN web_indicadores_objetivo o
        ON a.id_objetivo = o.id
        INNER JOIN web_indicadores_metas m
        ON o.id = m.id_objetivo AND a.nivel = m.nivel AND year(m.prazo) = (SELECT max(ano_apuracao) FROM web_indicadores_anoapuracao WHERE apuracao_atual = 'S')
        INNER JOIN Gestao_Mes on a.mes_referencia = Gestao_Mes.id 
        AND a.id_objetivo = '20'
        AND a.ano_referencia = '2021'"));

    
    
        //////////////////////FIM INDICADOR 3//////////////////////
            foreach ($notaDetalhadaPorObjetivo as $notaPorObjetivo) {
    
                $notaPorObjetivo->nota = ((100*$notaPorObjetivo->realizado)/$notaPorObjetivo->meta);


                if($notaPorObjetivo->realizado >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                    $notaPorObjetivo->nota = 120;

                } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90){
                $notaPorObjetivo->nota = 0;
            
                } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->score90 && $notaPorObjetivo->realizado < $notaPorObjetivo->meta){
                    $notaPorObjetivo->nota = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado)));
                
                } else if ($notaPorObjetivo->realizado >= $notaPorObjetivo->meta && $notaPorObjetivo->realizado < $notaPorObjetivo->score120){
                    $notaPorObjetivo->nota = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->realizado-$notaPorObjetivo->meta))+100);
                }

                if($notaPorObjetivo->id_objetivo == 4){
                    if($notaPorObjetivo->realizado <= $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota = 120;

                    } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score90){
                        $notaPorObjetivo->nota = 0;

                    } else if ($notaPorObjetivo->realizado > $notaPorObjetivo->score120 && $notaPorObjetivo->realizado <= $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);

                    } else if ($notaPorObjetivo->realizado < $notaPorObjetivo->score90 && $notaPorObjetivo->realizado > $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota = (((10/($notaPorObjetivo->score90-$notaPorObjetivo->meta))*($notaPorObjetivo->meta-$notaPorObjetivo->realizado))+100);
                    }
                }

                $notaacumulada = $notaacumulada + $notaPorObjetivo->realizado;
                $notaPorObjetivo->nota_acumulada = $notaacumulada/$soma;
                $notaPorObjetivo->consolidada_mes = $notaPorObjetivo->nota;

                // Calcula a nota consolidada através da media das notas por mês //
                $notaPorObjetivo->nota_consolidada_acumulada = ((100*$notaPorObjetivo->nota_acumulada)/$notaPorObjetivo->meta);

                if($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score120 && $notaPorObjetivo->id_objetivo <> 4){
                    $notaPorObjetivo->nota_consolidada_acumulada = 120;
                
                } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90){
                $notaPorObjetivo->nota_consolidada_acumulada = 0;
            
                } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->meta){
                    $notaPorObjetivo->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada)));
                } else if ($notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta && $notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score120){
                    $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->score120-$notaPorObjetivo->meta))*($notaPorObjetivo->nota_acumulada-$notaPorObjetivo->meta))+100);
                }

                if($notaPorObjetivo->id_objetivo == 4){
                    if($notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->score120){
                        $notaPorObjetivo->nota_consolidada_acumulada = 120;

                    } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score90){
                        $notaPorObjetivo->nota_consolidada_acumulada = 0;

                    } else if ($notaPorObjetivo->nota_acumulada > $notaPorObjetivo->score120 && $notaPorObjetivo->nota_acumulada <= $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota_consolidada_acumulada = (((20/($notaPorObjetivo->meta-$notaPorObjetivo->score120))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);

                    } else if ($notaPorObjetivo->nota_acumulada < $notaPorObjetivo->score90 && $notaPorObjetivo->nota_acumulada >= $notaPorObjetivo->meta){
                        $notaPorObjetivo->nota_consolidada_acumulada = (((10/($notaPorObjetivo->meta-$notaPorObjetivo->score90))*($notaPorObjetivo->meta-$notaPorObjetivo->nota_acumulada))+100);
                    }
                }
                
                $notaPorObjetivo->consolidada_mes = number_format((float)$notaPorObjetivo->consolidada_mes, 2, '.', '');
                $notaPorObjetivo->nota_acumulada = number_format((float)$notaPorObjetivo->nota_acumulada, 2, '.', '');
                $notaPorObjetivo->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo->nota_consolidada_acumulada, 2, '.', '');
                $notaPorObjetivo->nota = number_format((float)$notaPorObjetivo->nota, 2, '.', ''); 
                
                $count = $count+1;
                $soma = $soma+1;

            // if($notaPorObjetivo->mes == $mes_referencia) {

            //Grava na tabela temp o usuario + nota
            $values = array(
            'advogado' => $notaPorObjetivo->advogado, 
            'id_objetivo' => $notaPorObjetivo->id_objetivo, 
            'mes_referencia' => $notaPorObjetivo->mes, 
            'ano_referencia' => '2021', 
            'nota' => $notaPorObjetivo->nota, 
            'nota_alterada' => $notaPorObjetivo->nota);
            DB::table('dbo.temp_calculomediascore')->insert($values);

            // }

        }
        //////////////////////FIM INDICADOR 3//////////////////////


        //////////////////////INDICADOR 6//////////////////////

        foreach ($notaDetalhadaPorObjetivo6 as $notaPorObjetivo6) {

            $notaacumulada6 = 0;
            $count6 = 0;
            $soma6 = 1;
    
            $notaPorObjetivo6->nota = ((100*$notaPorObjetivo6->realizado)/$notaPorObjetivo6->meta);


            if($notaPorObjetivo6->realizado >= $notaPorObjetivo6->score120 && $notaPorObjetivo6->id_objetivo <> 4){
                $notaPorObjetivo6->nota = 120;

            } else if ($notaPorObjetivo6->realizado < $notaPorObjetivo6->score90){
            $notaPorObjetivo6->nota = 0;

            } else if ($notaPorObjetivo6->realizado >= $notaPorObjetivo6->score90 && $notaPorObjetivo6->realizado < $notaPorObjetivo6->meta){
                $notaPorObjetivo6->nota = (100-((10/($notaPorObjetivo6->meta-$notaPorObjetivo6->score90))*($notaPorObjetivo6->meta-$notaPorObjetivo6->realizado)));
            
            } else if ($notaPorObjetivo6->realizado >= $notaPorObjetivo6->meta && $notaPorObjetivo6->realizado < $notaPorObjetivo6->score120){
                $notaPorObjetivo6->nota = (((20/($notaPorObjetivo6->score120-$notaPorObjetivo6->meta))*($notaPorObjetivo6->realizado-$notaPorObjetivo6->meta))+100);
            }

            if($notaPorObjetivo6->id_objetivo == 4){
                if($notaPorObjetivo6->realizado <= $notaPorObjetivo6->score120){
                    $notaPorObjetivo6->nota = 120;

                } else if ($notaPorObjetivo6->realizado > $notaPorObjetivo6->score90){
                    $notaPorObjetivo6->nota = 0;

                } else if ($notaPorObjenotaPorObjetivo6tivo7->realizado > $notaPorObjetivo6->score120 && $notaPorObjetivo6->realizado <= $notaPorObjetivo6->meta){
                    $notaPorObjetivo6->nota = (((20/($notaPorObjetivo6->meta-$notaPorObjetivo6->score120))*($notaPorObjetivo6->meta-$notaPorObjetivo6->realizado))+100);

                } else if ($notaPorObjetivo6->realizado < $notaPorObjetivo6->score90 && $notaPorObjetivo6->realizado > $notaPorObjetivo6->meta){
                    $notaPorObjetivo6->nota = (((10/($notaPorObjetivo6->score90-$notaPorObjetivo6->meta))*($notaPorObjetivo6->meta-$notaPorObjetivo6->realizado))+100);
                }
            }

            $notaacumulada6 = $notaacumulada6 + $notaPorObjetivo6->realizado;
            $notaPorObjetivo6->nota_acumulada = $notaacumulada6/$soma6;
            $notaPorObjetivo6->consolidada_mes = $notaPorObjetivo6->nota;

            // Calcula a nota consolidada através da media das notas por mês //
            $notaPorObjetivo6->nota_consolidada_acumulada = ((100*$notaPorObjetivo6->nota_acumulada)/$notaPorObjetivo6->meta);

            if($notaPorObjetivo6->nota_acumulada >= $notaPorObjetivo6->score120 && $notaPorObjetivo6->id_objetivo <> 4){
                $notaPorObjetivo6->nota_consolidada_acumulada = 120;
            
            } else if ($notaPorObjetivo6->nota_acumulada < $notaPorObjetivo6->score90){
            $notaPorObjetivo6->nota_consolidada_acumulada = 0;
            $notaPorObjetivo6->nota_consolidada_acumulada = 0;
        
            } else if ($notaPorObjetivo6->nota_acumulada >= $notaPorObjetivo6->score90 && $notaPorObjetivo6->nota_acumulada < $notaPorObjetivo6->meta){
                $notaPorObjetivo6->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo6->meta-$notaPorObjetivo6->score90))*($notaPorObjetivo6->meta-$notaPorObjetivo6->nota_acumulada)));
            } else if ($notaPorObjetivo6->nota_acumulada >= $notaPorObjetivo6->meta && $notaPorObjetivo6->nota_acumulada < $notaPorObjetivo6->score120){
                $notaPorObjetivo6->nota_consolidada_acumulada = (((20/($notaPorObjetivo6->score120-$notaPorObjetivo6->meta))*($notaPorObjetivo6->nota_acumulada-$notaPorObjetivo6->meta))+100);
            }

            if($notaPorObjetivo6->id_objetivo == 4){
                if($notaPorObjetivo6->nota_acumulada <= $notaPorObjetivo6->score120){
                    $notaPorObjetivo6->nota_consolidada_acumulada = 120;

                } else if ($notaPorObjetivo6->nota_acumulada > $notaPorObjetivo6->score90){
                    $notaPorObjetivo6->nota_consolidada_acumulada = 0;
                    $notaPorObjetivo6->nota_consolidada_acumulada = 0;
                    $notaPorObjetivo6->consolidada_mes = 0;

                } else if ($notaPorObjetivo6->nota_acumulada > $notaPorObjetivo6->score120 && $notaPorObjetivo6->nota_acumulada <= $notaPorObjetivo6->meta){
                    $notaPorObjetivo6->nota_consolidada_acumulada = (((20/($notaPorObjetivo6->meta-$notaPorObjetivo6->score120))*($notaPorObjetivo6->meta-$notaPorObjetivo6->nota_acumulada))+100);

                } else if ($notaPorObjetivo6->nota_acumulada < $notaPorObjetivo6->score90 && $notaPorObjetivo6->nota_acumulada >= $notaPorObjetivo6->meta){
                    $notaPorObjetivo6->nota_consolidada_acumulada = (((10/($notaPorObjetivo6->meta-$notaPorObjetivo6->score90))*($notaPorObjetivo6->meta-$notaPorObjetivo6->nota_acumulada))+100);
                }
            }
            
            $notaPorObjetivo6->consolidada_mes = number_format((float)$notaPorObjetivo6->consolidada_mes, 2, '.', '');
            $notaPorObjetivo6->nota_acumulada = number_format((float)$notaPorObjetivo6->nota_acumulada, 2, '.', '');
            $notaPorObjetivo6->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo6->nota_consolidada_acumulada, 2, '.', '');
            $notaPorObjetivo6->nota = number_format((float)$notaPorObjetivo6->nota, 2, '.', ''); 
            
            $count6 = $count6+1;
            $soma6 = $soma6+1;

        // if($notaPorObjetivo6->mes == $mes_referencia) {

        //Grava na tabela temp o usuario + nota
        $values = array(
        'advogado' => $notaPorObjetivo6->advogado, 
        'id_objetivo' => $notaPorObjetivo6->id_objetivo, 
        'mes_referencia' => $notaPorObjetivo6->mes, 
        'ano_referencia' => '2021', 
        'nota' => $notaPorObjetivo6->nota, 
        'nota_alterada' => $notaPorObjetivo6->nota);
        DB::table('dbo.temp_calculomediascore')->insert($values);

        // }

    }
        //////////////////////FIM INDICADOR 6//////////////////////




        //////////////////////INDICADOR 7//////////////////////

        foreach ($notaDetalhadaPorObjetivo7 as $notaPorObjetivo7) {

            $notaacumulada7 = 0;
            $count7 = 0;
            $soma7 = 1;
    
            $notaPorObjetivo7->nota = ((100*$notaPorObjetivo7->realizado)/$notaPorObjetivo7->meta);


            if($notaPorObjetivo7->realizado >= $notaPorObjetivo7->score120 && $notaPorObjetivo7->id_objetivo <> 4){
                $notaPorObjetivo7->nota = 120;

            } else if ($notaPorObjetivo7->realizado < $notaPorObjetivo7->score90){
            $notaPorObjetivo7->nota = 0;

            } else if ($notaPorObjetivo7->realizado >= $notaPorObjetivo7->score90 && $notaPorObjetivo7->realizado < $notaPorObjetivo7->meta){
                $notaPorObjetivo7->nota = (100-((10/($notaPorObjetivo7->meta-$notaPorObjetivo7->score90))*($notaPorObjetivo7->meta-$notaPorObjetivo7->realizado)));
            
            } else if ($notaPorObjetivo7->realizado >= $notaPorObjetivo7->meta && $notaPorObjetivo7->realizado < $notaPorObjetivo7->score120){
                $notaPorObjetivo7->nota = (((20/($notaPorObjetivo7->score120-$notaPorObjetivo7->meta))*($notaPorObjetivo7->realizado-$notaPorObjetivo7->meta))+100);
            }

            if($notaPorObjetivo7->id_objetivo == 4){
                if($notaPorObjetivo7->realizado <= $notaPorObjetivo7->score120){
                    $notaPorObjetivo7->nota = 120;

                } else if ($notaPorObjetivo7->realizado > $notaPorObjetivo7->score90){
                    $notaPorObjetivo7->nota = 0;

                } else if ($notaPorObjetivo7->realizado > $notaPorObjetivo7->score120 && $notaPorObjetivo7->realizado <= $notaPorObjetivo7->meta){
                    $notaPorObjetivo7->nota = (((20/($notaPorObjetivo7->meta-$notaPorObjetivo7->score120))*($notaPorObjetivo7->meta-$notaPorObjetivo7->realizado))+100);

                } else if ($notaPorObjetivo7->realizado < $notaPorObjetivo7->score90 && $notaPorObjetivo7->realizado > $notaPorObjetivo7->meta){
                    $notaPorObjetivo7->nota = (((10/($notaPorObjetivo7->score90-$notaPorObjetivo7->meta))*($notaPorObjetivo7->meta-$notaPorObjetivo7->realizado))+100);
                }
            }

            $notaacumulada7 = $notaacumulada7 + $notaPorObjetivo7->realizado;
            $notaPorObjetivo7->nota_acumulada = $notaacumulada7/$soma7;
            $notaPorObjetivo7->consolidada_mes = $notaPorObjetivo7->nota;

            // Calcula a nota consolidada através da media das notas por mês //
            $notaPorObjetivo7->nota_consolidada_acumulada = ((100*$notaPorObjetivo7->nota_acumulada)/$notaPorObjetivo7->meta);

            if($notaPorObjetivo7->nota_acumulada >= $notaPorObjetivo7->score120 && $notaPorObjetivo7->id_objetivo <> 4){
                $notaPorObjetivo7->nota_consolidada_acumulada = 120;
            
            } else if ($notaPorObjetivo7->nota_acumulada < $notaPorObjetivo7->score90){
            $notaPorObjetivo7->nota_consolidada_acumulada = 0;
            $notaPorObjetivo7->nota_consolidada_acumulada = 0;
        
            } else if ($notaPorObjetivo7->nota_acumulada >= $notaPorObjetivo7->score90 && $notaPorObjetivo7->nota_acumulada < $notaPorObjetivo7->meta){
                $notaPorObjetivo7->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo7->meta-$notaPorObjetivo7->score90))*($notaPorObjetivo7->meta-$notaPorObjetivo7->nota_acumulada)));
            } else if ($notaPorObjetivo7->nota_acumulada >= $notaPorObjetivo7->meta && $notaPorObjetivo7->nota_acumulada < $notaPorObjetivo7->score120){
                $notaPorObjetivo7->nota_consolidada_acumulada = (((20/($notaPorObjetivo7->score120-$notaPorObjetivo7->meta))*($notaPorObjetivo7->nota_acumulada-$notaPorObjetivo7->meta))+100);
            }

            if($notaPorObjetivo7->id_objetivo == 4){
                if($notaPorObjetivo7->nota_acumulada <= $notaPorObjetivo7->score120){
                    $notaPorObjetivo7->nota_consolidada_acumulada = 120;

                } else if ($notaPorObjetivo7->nota_acumulada > $notaPorObjetivo7->score90){
                    $notaPorObjetivo7->nota_consolidada_acumulada = 0;
                    $notaPorObjetivo7->nota_consolidada_acumulada = 0;
                    $notaPorObjetivo7->consolidada_mes = 0;

                } else if ($notaPorObjetivo7->nota_acumulada > $notaPorObjetivo7->score120 && $notaPorObjetivo7->nota_acumulada <= $notaPorObjetivo7->meta){
                    $notaPorObjetivo7->nota_consolidada_acumulada = (((20/($notaPorObjetivo7->meta-$notaPorObjetivo7->score120))*($notaPorObjetivo7->meta-$notaPorObjetivo7->nota_acumulada))+100);

                } else if ($notaPorObjetivo7->nota_acumulada < $notaPorObjetivo7->score90 && $notaPorObjetivo7->nota_acumulada >= $notaPorObjetivo7->meta){
                    $notaPorObjetivo7->nota_consolidada_acumulada = (((10/($notaPorObjetivo7->meta-$notaPorObjetivo7->score90))*($notaPorObjetivo7->meta-$notaPorObjetivo7->nota_acumulada))+100);
                }
            }
            
            $notaPorObjetivo7->consolidada_mes = number_format((float)$notaPorObjetivo7->consolidada_mes, 2, '.', '');
            $notaPorObjetivo7->nota_acumulada = number_format((float)$notaPorObjetivo7->nota_acumulada, 2, '.', '');
            $notaPorObjetivo7->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo7->nota_consolidada_acumulada, 2, '.', '');
            $notaPorObjetivo7->nota = number_format((float)$notaPorObjetivo7->nota, 2, '.', ''); 
            
            $count7 = $count7+1;
            $soma7 = $soma7+1;

        // if($notaPorObjetivo7->mes == $mes_referencia) {

        //Grava na tabela temp o usuario + nota
        $values = array(
        'advogado' => $notaPorObjetivo7->advogado, 
        'id_objetivo' => $notaPorObjetivo7->id_objetivo, 
        'mes_referencia' => $notaPorObjetivo7->mes, 
        'ano_referencia' => '2021', 
        'nota' => $notaPorObjetivo7->nota, 
        'nota_alterada' => $notaPorObjetivo7->nota);
        DB::table('dbo.temp_calculomediascore')->insert($values);

        // }

    }
        //////////////////////FIM INDICADOR 7//////////////////////

        //////////////////////INDICADOR 10//////////////////////
        foreach ($notaDetalhadaPorObjetivo10 as $notaPorObjetivo10) {

                $notaacumulada10 = 0;
                $count10 = 0;
                $soma10 = 1;
        
                $notaPorObjetivo10->nota = ((100*$notaPorObjetivo10->realizado)/$notaPorObjetivo10->meta);
    
    
                if($notaPorObjetivo10->realizado >= $notaPorObjetivo10->score120 && $notaPorObjetivo10->id_objetivo <> 4){
                    $notaPorObjetivo10->nota = 120;
    
                } else if ($notaPorObjetivo10->realizado < $notaPorObjetivo10->score90){
                $notaPorObjetivo10->nota = 0;
            
                } else if ($notaPorObjetivo10->realizado >= $notaPorObjetivo10->score90 && $notaPorObjetivo10->realizado < $notaPorObjetivo10->meta){
                   
                    $notaPorObjetivo10->nota = (100-((10/($notaPorObjetivo10->meta-$notaPorObjetivo10->score90))*($notaPorObjetivo10->meta-$notaPorObjetivo10->realizado)));

                } else if ($notaPorObjetivo10->realizado >= $notaPorObjetivo10->meta && $notaPorObjetivo10->realizado < $notaPorObjetivo10->score120){
                    $notaPorObjetivo10->nota = (((20/($notaPorObjetivo10->score120-$notaPorObjetivo10->meta))*($notaPorObjetivo10->realizado-$notaPorObjetivo10->meta))+100);
                }

    
                if($notaPorObjetivo10->id_objetivo == 4){
                    if($notaPorObjetivo10->realizado <= $notaPorObjetivo10->score120){
                        $notaPorObjetivo10->nota = 120;

                    } else if ($notaPorObjetivo10->realizado > $notaPorObjetivo10->score90){
                        $notaPorObjetivo10->nota = 0;

                    } else if ($notaPorObjetivo10->realizado > $notaPorObjetivo10->score120 && $notaPorObjetivo10->realizado <= $notaPorObjetivo10->meta){
                        $notaPorObjetivo10->nota = (((20/($notaPorObjetivo10->meta-$notaPorObjetivo10->score120))*($notaPorObjetivo10->meta-$notaPorObjetivo10->realizado))+100);

                    } else if ($notaPorObjetivo10->realizado < $notaPorObjetivo10->score90 && $notaPorObjetivo10->realizado > $notaPorObjetivo10->meta){
                        $notaPorObjetivo10->nota = (((10/($notaPorObjetivo10->score90-$notaPorObjetivo10->meta))*($notaPorObjetivo10->meta-$notaPorObjetivo10->realizado))+100);

                    }
                }
    
                $notaacumulada10 = $notaacumulada10 + $notaPorObjetivo10->realizado;
                $notaPorObjetivo10->nota_acumulada = $notaacumulada10/$soma10;
                $notaPorObjetivo10->consolidada_mes = $notaPorObjetivo10->nota;


    
                // Calcula a nota consolidada através da media das notas por mês //
                $notaPorObjetivo10->nota_consolidada_acumulada = ((100*$notaPorObjetivo10->nota_acumulada)/$notaPorObjetivo10->meta);
    
                if($notaPorObjetivo10->nota_acumulada >= $notaPorObjetivo10->score120 && $notaPorObjetivo10->id_objetivo <> 4){
                    $notaPorObjetivo10->nota_consolidada_acumulada = 120;
                
                } else if ($notaPorObjetivo10->nota_acumulada < $notaPorObjetivo10->score90){
                $notaPorObjetivo10->nota_consolidada_acumulada = 0;
            
                } else if ($notaPorObjetivo10->nota_acumulada >= $notaPorObjetivo10->score90 && $notaPorObjetivo10->nota_acumulada < $notaPorObjetivo10->meta){
                    $notaPorObjetivo10->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo10->meta-$notaPorObjetivo10->score90))*($notaPorObjetivo10->meta-$notaPorObjetivo10->nota_acumulada)));
                } else if ($notaPorObjetivo10->nota_acumulada >= $notaPorObjetivo10->meta && $notaPorObjetivo10->nota_acumulada < $notaPorObjetivo10->score120){
                    $notaPorObjetivo10->nota_consolidada_acumulada = (((20/($notaPorObjetivo10->score120-$notaPorObjetivo10->meta))*($notaPorObjetivo10->nota_acumulada-$notaPorObjetivo10->meta))+100);
                }
    
                if($notaPorObjetivo10->id_objetivo == 4){
                    if($notaPorObjetivo10->nota_acumulada <= $notaPorObjetivo10->score120){
                        $notaPorObjetivo10->nota_consolidada_acumulada = 120;
    
                    } else if ($notaPorObjetivo10->nota_acumulada > $notaPorObjetivo10->score90){
                        $notaPorObjetivo10->nota_consolidada_acumulada = 0;
    
                    } else if ($notaPorObjetivo10->nota_acumulada > $notaPorObjetivo10->score120 && $notaPorObjetivo10->nota_acumulada <= $notaPorObjetivo10->meta){
                        $notaPorObjetivo10->nota_consolidada_acumulada = (((20/($notaPorObjetivo10->meta-$notaPorObjetivo10->score120))*($notaPorObjetivo10->meta-$notaPorObjetivo10->nota_acumulada))+100);
    
                    } else if ($notaPorObjetivo10->nota_acumulada < $notaPorObjetivo10->score90 && $notaPorObjetivo10->nota_acumulada >= $notaPorObjetivo10->meta){
                        $notaPorObjetivo10->nota_consolidada_acumulada = (((10/($notaPorObjetivo10->meta-$notaPorObjetivo10->score90))*($notaPorObjetivo10->meta-$notaPorObjetivo10->nota_acumulada))+100);
                    }
                }
                
                $notaPorObjetivo10->consolidada_mes = number_format((float)$notaPorObjetivo10->consolidada_mes, 2, '.', '');
                $notaPorObjetivo10->nota_acumulada = number_format((float)$notaPorObjetivo10->nota_acumulada, 2, '.', '');
                $notaPorObjetivo10->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo10->nota_consolidada_acumulada, 2, '.', '');
                $notaPorObjetivo10->nota = number_format((float)$notaPorObjetivo10->nota, 2, '.', ''); 
                
                $count10 = $count10+1;
                $soma10 = $soma10+1;
    
            // if($notaPorObjetivo10->mes == $mes_referencia) {
    
            //Grava na tabela temp o usuario + nota
            $values = array(
            'advogado' => $notaPorObjetivo10->advogado, 
            'id_objetivo' => $notaPorObjetivo10->id_objetivo, 
            'mes_referencia' => $notaPorObjetivo10->mes, 
            'ano_referencia' => '2021', 
            'nota' => $notaPorObjetivo10->nota, 
            'nota_alterada' => $notaPorObjetivo10->nota);
            DB::table('dbo.temp_calculomediascore')->insert($values);
    
            // }
    
        }
        // //////////////////////FIM INDICADOR 10//////////////////////


        // //////////////////////INDICADOR 20//////////////////////
     foreach ($notaDetalhadaPorObjetivo20 as $notaPorObjetivo20) {

            $notaacumulada20 = 0;
            $count20 = 0;
            $soma20 = 1;
        
            $notaPorObjetivo20->nota = ((100*$notaPorObjetivo20->realizado)/$notaPorObjetivo20->meta);
    
    
            if($notaPorObjetivo20->realizado >= $notaPorObjetivo20->score120 && $notaPorObjetivo20->id_objetivo <> 4){
                $notaPorObjetivo20->nota = 120;
    
            } else if ($notaPorObjetivo20->realizado < $notaPorObjetivo20->score90){
               $notaPorObjetivo20->nota = 0;
            
            } else if ($notaPorObjetivo20->realizado >= $notaPorObjetivo20->score90 && $notaPorObjetivo20->realizado < $notaPorObjetivo20->meta){
                $notaPorObjetivo20->nota = (100-((10/($notaPorObjetivo20->meta-$notaPorObjetivo20->score90))*($notaPorObjetivo20->meta-$notaPorObjetivo20->realizado)));
                
            } else if ($notaPorObjetivo20->realizado >= $notaPorObjetivo20->meta && $notaPorObjetivo20->realizado < $notaPorObjetivo20->score120){
                    $notaPorObjetivo20->nota = (((20/($notaPorObjetivo20->score120-$notaPorObjetivo20->meta))*($notaPorObjetivo20->realizado-$notaPorObjetivo20->meta))+100);
            }
    
            if($notaPorObjetivo20->id_objetivo == 4){
                if($notaPorObjetivo20->realizado <= $notaPorObjetivo20->score120){
                $notaPorObjetivo20->nota = 120;
    
                } else if ($notaPorObjetivo20->realizado > $notaPorObjetivo20->score90){
                $notaPorObjetivo20->nota = 0;
    
                } else if ($notaPorObjetivo20->realizado > $notaPorObjetivo20->score120 && $notaPorObjetivo20->realizado <= $notaPorObjetivo20->meta){
                $notaPorObjetivo20->nota = (((20/($notaPorObjetivo20->meta-$notaPorObjetivo20->score120))*($notaPorObjetivo20->meta-$notaPorObjetivo20->realizado))+100);
    
                } else if ($notaPorObjetivo20->realizado < $notaPorObjetivo20->score90 && $notaPorObjetivo20->realizado > $notaPorObjetivo20->meta){
                $notaPorObjetivo20->nota = (((10/($notaPorObjetivo20->score90-$notaPorObjetivo20->meta))*($notaPorObjetivo20->meta-$notaPorObjetivo20->realizado))+100);
                }
            }
    
                $notaacumulada20 = $notaacumulada20 + $notaPorObjetivo20->realizado;
                $notaPorObjetivo20->nota_acumulada = $notaacumulada20/$soma20;
                $notaPorObjetivo20->consolidada_mes = $notaPorObjetivo20->nota;
    
                // Calcula a nota consolidada através da media das notas por mês //
                $notaPorObjetivo20->nota_consolidada_acumulada = ((100*$notaPorObjetivo20->nota_acumulada)/$notaPorObjetivo20->meta);
    
                if($notaPorObjetivo20->nota_acumulada >= $notaPorObjetivo20->score120 && $notaPorObjetivo20->id_objetivo <> 4){
                    $notaPorObjetivo20->nota_consolidada_acumulada = 120;
                
                } else if ($notaPorObjetivo20->nota_acumulada < $notaPorObjetivo20->score90){
                    $notaPorObjetivo20->nota_consolidada_acumulada = 0;
            
                } else if ($notaPorObjetivo20->nota_acumulada >= $notaPorObjetivo20->score90 && $notaPorObjetivo20->nota_acumulada < $notaPorObjetivo20->meta){
                    $notaPorObjetivo20->nota_consolidada_acumulada = (100-((10/($notaPorObjetivo20->meta-$notaPorObjetivo20->score90))*($notaPorObjetivo20->meta-$notaPorObjetivo20->nota_acumulada)));
                } else if ($notaPorObjetivo20->nota_acumulada >= $notaPorObjetivo20->meta && $notaPorObjetivo20->nota_acumulada < $notaPorObjetivo20->score120){
                    $notaPorObjetivo20->nota_consolidada_acumulada = (((20/($notaPorObjetivo20->score120-$notaPorObjetivo20->meta))*($notaPorObjetivo20->nota_acumulada-$notaPorObjetivo20->meta))+100);
                }
    
                if($notaPorObjetivo20->id_objetivo == 4){
                    if($notaPorObjetivo20->nota_acumulada <= $notaPorObjetivo20->score120){
                        $notaPorObjetivo20->nota_consolidada_acumulada = 120;
    
                    } else if ($notaPorObjetivo20->nota_acumulada > $notaPorObjetivo20->score90){
                        $notaPorObjetivo20->nota_consolidada_acumulada = 0;
    
                    } else if ($notaPorObjetivo20->nota_acumulada > $notaPorObjetivo20->score120 && $notaPorObjetivo20->nota_acumulada <= $notaPorObjetivo20->meta){
                        $notaPorObjetivo20->nota_consolidada_acumulada = (((20/($notaPorObjetivo20->meta-$notaPorObjetivo20->score120))*($notaPorObjetivo20->meta-$notaPorObjetivo20->nota_acumulada))+100);
    
                    } else if ($notaPorObjetivo20->nota_acumulada < $notaPorObjetivo20->score90 && $notaPorObjetivo20->nota_acumulada >= $notaPorObjetivo20->meta){
                        $notaPorObjetivo20->nota_consolidada_acumulada = (((10/($notaPorObjetivo20->meta-$notaPorObjetivo20->score90))*($notaPorObjetivo20->meta-$notaPorObjetivo20->nota_acumulada))+100);
                    }
                }
                
                $notaPorObjetivo20->consolidada_mes = number_format((float)$notaPorObjetivo20->consolidada_mes, 2, '.', '');
                $notaPorObjetivo20->nota_acumulada = number_format((float)$notaPorObjetivo20->nota_acumulada, 2, '.', '');
                $notaPorObjetivo20->nota_consolidada_acumulada = number_format((float)$notaPorObjetivo20->nota_consolidada_acumulada, 2, '.', '');
                $notaPorObjetivo20->nota = number_format((float)$notaPorObjetivo20->nota, 2, '.', ''); 
                
                $count20 = $count20+1;
                $soma20 = $soma20+1;
    
            // if($notaPorObjetivo20->mes == $mes_referencia) {
    
            //Grava na tabela temp o usuario + nota
            $values = array(
            'advogado' => $notaPorObjetivo20->advogado, 
            'id_objetivo' => $notaPorObjetivo20->id_objetivo, 
             'mes_referencia' => $notaPorObjetivo20->mes, 
            'ano_referencia' => '2021', 
            'nota' => $notaPorObjetivo20->nota, 
            'nota_alterada' => $notaPorObjetivo20->nota);
            DB::table('dbo.temp_calculomediascore')->insert($values);
    
            // }
    
        }
        // //////////////////////FIM INDICADOR 20//////////////////////

       // DB::statement('EXEC proc_calcula_indicadoresNota_mediaScore');

        flash('Procedure executada com sucesso !')->success();
        return redirect()->route("Painel.Gestao.Controlador.index");
    }

    public function procedure_notaconsolidada(Request $request) {

        // DB::select('SET NOCOUNT ON;  EXEC proc_calcula_indicadoresNotaConsolidada');
        DB::statement('EXEC proc_calcula_indicadoresNotaConsolidada');
        // $results = DB::select('EXEC proc_calcula_indicadoresNotaConsolidada');

        flash('Procedure executada com sucesso !')->success();
        return redirect()->route("Painel.Gestao.Controlador.index");

    }
    
    public function controlador_metas_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.web_indicadores_metas')
        ->select(
                'dbo.web_indicadores_metas.id as id',
                'dbo.web_indicadores_objetivo.objetivo as objetivo',
                'dbo.web_indicadores_metas.score90',
                'dbo.web_indicadores_metas.score120',
                'dbo.web_indicadores_metas.meta as meta',
                'dbo.web_indicadores_metas.uom',
                'dbo.web_indicadores_metas.peso',
                'dbo.web_indicadores_metas.prazo',
                'dbo.web_indicadores_metas.nivel')       
        ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_metas.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->where('dbo.web_indicadores_metas.prazo', '>', $datahoje)
        ->get();

        $objetivos = DB::table('dbo.web_indicadores_objetivo')
        ->where('ativo','=','S')
        ->orderBy('objetivo', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.Metas.index', compact('datahoje','objetivos','datas','totalNotificacaoAbertas', 'notificacoes'));


    }

    public function controlador_metas_exportar() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $customer_data = DB::table('dbo.web_indicadores_metas')
        ->select(
                'dbo.web_indicadores_metas.id as id',
                'dbo.web_indicadores_objetivo.objetivo as objetivo',
                'dbo.web_indicadores_metas.score90',
                'dbo.web_indicadores_metas.score120',
                'dbo.web_indicadores_metas.meta as meta',
                'dbo.web_indicadores_metas.uom',
                'dbo.web_indicadores_metas.peso',
                'dbo.web_indicadores_metas.prazo',
                'dbo.web_indicadores_metas.nivel')       
        ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_metas.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->where('dbo.web_indicadores_metas.prazo', '>', $datahoje)
        ->get();
  
           $customer_array[] = array(
                   'id', 
                   'objetivo',
                   'score90',
                   'score120',
                   'meta',
                   'uom',
                   'peso',
                   'prazo',
                   'nivel');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'id'  => $customer->id,
                                'objetivo' => $customer->objetivo,
                                'score90' => $customer->score90,
                                'score120' => $customer->score120,
                                'meta' => $customer->meta,
                                'uom' => $customer->uom,
                                'peso' => $customer->peso,
                                'prazo' => date('d/m/Y', strtotime($customer->prazo)),
                                'nivel' => $customer->nivel,

                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Listagem de metas', function($excel) use ($customer_array){
                        $excel->setTitle('Listagem de metas');
                        $excel->sheet('Listagem de metas', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');

    }

    public function controlador_metas_gravarregistro(Request $request) {

        $objetivo = $request->get('objetivo');
        $score90 = $request->get('score90');
        $meta = $request->get('meta');
        $score120 = $request->get('score120');
        $uom = $request->get('uom');
        $peso = $request->get('peso');
        $prazo = $request->get('prazo');
        $nivel = $request->get('nivel');

        $value1 = array(
            'id_objetivo' => $objetivo, 
            'score90' => $score90, 
            'meta' => $meta, 
            'score120' => $score120,
            'uom' => $uom,
            'peso' => $peso,
            'prazo' => $prazo,
            'nivel' => $nivel);
        DB::table('dbo.web_indicadores_metas')->insert($value1);
  
        flash('Nova meta cadastrada com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.Metas.index");

    }

    public function controlador_metas_editar($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.web_indicadores_metas')
        ->select(
                'dbo.web_indicadores_metas.id as id',
                'dbo.web_indicadores_objetivo.id as objetivo_id',
                'dbo.web_indicadores_objetivo.objetivo as objetivo',
                'dbo.web_indicadores_metas.score90',
                'dbo.web_indicadores_metas.score120',
                'dbo.web_indicadores_metas.meta as meta',
                'dbo.web_indicadores_metas.uom',
                'dbo.web_indicadores_metas.peso',
                'dbo.web_indicadores_metas.prazo',
                'dbo.web_indicadores_metas.nivel')       
        ->leftjoin('dbo.web_indicadores_objetivo', 'dbo.web_indicadores_metas.id_objetivo', 'dbo.web_indicadores_objetivo.id')
        ->where('dbo.web_indicadores_metas.id', $id)
        ->first();

        $objetivos = DB::table('dbo.web_indicadores_objetivo')
        ->where('ativo','=','S')
        ->orderBy('objetivo', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.Metas.editar', compact('datahoje','objetivos','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_metas_editado(Request $request) {

        $id = $request->get('id');
        $objetivo = $request->get('objetivo');
        $score90 = $request->get('score90');
        $meta = $request->get('meta');
        $score120 = $request->get('score120');
        $uom = $request->get('uom');
        $peso = $request->get('peso');
        $prazo = $request->get('prazo');
        $nivel = $request->get('nivel');


        $values = array(
            "id_objetivo" => $objetivo,
            "score90" => $score90,
            "meta" => $meta,
            "score120" => $score120,
            "uom" => $uom,
            "peso" => $peso,
            "prazo" => $prazo,
            "nivel" => $nivel);
        DB::table('dbo.web_indicadores_metas')
        ->where('dbo.web_indicadores_metas.id', '=', $id)
        ->update($values);


        flash('Meta atualizada com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.Metas.index");
    }

    public function controlador_objetivos_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.web_indicadores_objetivo')
        ->select(
                'dbo.web_indicadores_objetivo.id as id',
                'dbo.web_indicadores_objetivo.objetivo as objetivo',
                'dbo.web_indicadores_objetivo.ativo')       
        ->orderBy('dbo.web_indicadores_objetivo.id', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.Objetivos.index', compact('datahoje','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_objetivos_exportar() {

        $customer_data = DB::table('dbo.web_indicadores_objetivo')
        ->select(
                'dbo.web_indicadores_objetivo.id as id',
                'dbo.web_indicadores_objetivo.objetivo as objetivo',
                'dbo.web_indicadores_objetivo.ativo')       
        ->orderBy('dbo.web_indicadores_objetivo.id', 'asc')
        ->get();
  
           $customer_array[] = array(
                   'id', 
                   'objetivo',
                   'ativo');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'id'  => $customer->id,
                                'objetivo' => $customer->objetivo,
                                'ativo' => $customer->ativo,
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Listagem de indicadores', function($excel) use ($customer_array){
                        $excel->setTitle('Listagem de indicadores');
                        $excel->sheet('Listagem de indicadores', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');
    }

    public function controlador_objetivos_gravarregistro(Request $request) {

        $objetivo = $request->get('objetivo');
        $ativo = $request->get('ativo');

        $value1 = array(
            'objetivo' => $objetivo, 
            'ativo' => $ativo);
        DB::table('dbo.web_indicadores_objetivo')->insert($value1);
  
        flash('Novo objetivo cadastrado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.Objetivos.index");

    }

    public function controlador_objetivos_editar($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.web_indicadores_objetivo')
        ->select(
                'dbo.web_indicadores_objetivo.id as id',
                'dbo.web_indicadores_objetivo.objetivo as objetivo',
                'dbo.web_indicadores_objetivo.ativo')       
        ->where('id', $id)
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.Objetivos.editar', compact('datahoje','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_objetivos_editado(Request $request) {

        $id = $request->get('id');
        $objetivo = $request->get('objetivo');
        $ativo = $request->get('ativo');

        $values = array(
            "objetivo" => $objetivo,
            "ativo" => $ativo);
        DB::table('dbo.web_indicadores_objetivo')
        ->where('dbo.web_indicadores_objetivo.id', '=', $id)
        ->update($values);

        flash('Objetivo atualizado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.Objetivos.index");
    }

    public function controlador_valororcado_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.temp_valororcado')
        ->select(
                'PLCFULL.dbo.Jurid_Setor.Codigo as setor',
                'PLCFULL.dbo.Jurid_Setor.Descricao as descricao',
                'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                'dbo.temp_valororcado.valor')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.temp_valororcado.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')               
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->orderBy('dbo.temp_valororcado.setor', 'asc')
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('PLCFULL.dbo.Jurid_Setor.Codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao')  
        ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
        ->orderby('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.ValorOrcado.index', compact('datahoje','setores','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_valororcado_exportar() {

        $customer_data = DB::table('dbo.temp_valororcado')
        ->select(
                'PLCFULL.dbo.Jurid_Setor.Codigo as setor_codigo',
                'PLCFULL.dbo.Jurid_Setor.Descricao as setor_descricao',
                'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade_codigo',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                'dbo.temp_valororcado.valor')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.temp_valororcado.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')               
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->orderBy('dbo.temp_valororcado.setor', 'asc')
        ->get();
  
           $customer_array[] = array(
                   'setor_codigo', 
                   'setor_descricao',
                   'unidade_codigo',
                   'unidade_descricao',
                   'valor');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'setor_codigo'  => $customer->setor_codigo,
                                'setor_descricao' => $customer->setor_descricao,
                                'unidade_codigo' => $customer->unidade_codigo,
                                'unidade_descricao' => $customer->unidade_descricao,
                                'valor' => number_format($customer->valor,2,",","."),
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Relatório valor orçado anual', function($excel) use ($customer_array){
                        $excel->setTitle('Valor orçado anual');
                        $excel->sheet('Valor orçado anual', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');

    }

    public function controlador_valororcado_gravarregistro(Request $request) {

        $setor = $request->get('setor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));


        $value1 = array(
            'setor' => $setor, 
            'valor' => $valor);
        DB::table('dbo.temp_valororcado')->insert($value1);

        flash('Lançamento de valor orçado cadastrado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.ValorOrcado.index");
    }

    public function controlador_valororcado_editar($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.temp_valororcado')
        ->select(
                'PLCFULL.dbo.Jurid_Setor.Codigo as setor',
                'PLCFULL.dbo.Jurid_Setor.Descricao as descricao',
                'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                'dbo.temp_valororcado.valor')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.temp_valororcado.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')               
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->orderBy('dbo.temp_valororcado.setor', 'asc')
        ->where('dbo.temp_valororcado.setor', $id)
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
        'Hist_Notificacao.status', 
        'dbo.users.*')  
        ->limit(3)
        ->join('dbo.users','dbo.Hist_Notificacao.user_id','=','dbo.users.id')
        ->where('dbo.Hist_Notificacao.status','=','A')
        ->where('dbo.Hist_Notificacao.destino_id','=', Auth::user()->id)
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.ValorOrcado.editar', compact('datahoje','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_valororcado_editado(Request $request) {

        $setor = $request->get('setor');
        $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));

        $values = array(
            "valor" => $valor);
        DB::table('dbo.temp_valororcado')
        ->where('dbo.temp_valororcado.setor', '=', $setor)
        ->update($values);

        flash('Lançamento de valor orçado atualizado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.ValorOrcado.index");

    }

    public function controlador_valororcado_mensal_index() {

        $carbon= Carbon::now();
        $anoatual =  $carbon->format('Y');

        $datas = DB::table('dbo.Gestao_ValorOrcado_Mensal')
        ->select(
                'dbo.Gestao_ValorOrcado_Mensal.id',
                'PLCFULL.dbo.Jurid_Setor.Codigo as setor',
                'PLCFULL.dbo.Jurid_Setor.Descricao as descricao',
                'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                'dbo.Gestao_Mes.descricao as mes',
                'dbo.Gestao_ValorOrcado_Mensal.ano',
                'dbo.Gestao_ValorOrcado_Mensal.valor')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_ValorOrcado_Mensal.setor_id', 'PLCFULL.dbo.Jurid_Setor.Id')               
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_ValorOrcado_Mensal.mes', 'dbo.Gestao_Mes.id')
        ->where('dbo.Gestao_ValorOrcado_Mensal.ano', 2021)
        ->orderBy('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
        ->orderBy('dbo.Gestao_Mes.id', 'asc')
        ->get();

        $meses = DB::table('dbo.Gestao_Mes')
        ->orderby('id', 'asc')
        ->get();

        $setores = DB::table('PLCFULL.dbo.Jurid_Setor')
        ->select('PLCFULL.dbo.Jurid_Setor.Codigo', 'PLCFULL.dbo.Jurid_Setor.Descricao', 'PLCFULL.dbo.Jurid_Setor.Id')  
        ->where('PLCFULL.dbo.Jurid_Setor.Ativo','=','1')
        ->orderby('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.ValorOrcado.Mensal.index', compact('setores','meses','anoatual','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_valororcado_mensal_exportar() {

        $customer_data = DB::table('dbo.Gestao_ValorOrcado_Mensal')
        ->select(
                'PLCFULL.dbo.Jurid_Setor.Codigo as setor_codigo',
                'PLCFULL.dbo.Jurid_Setor.Descricao as setor_descricao',
                'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade_codigo',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                'dbo.Gestao_Mes.descricao as mes',
                'dbo.Gestao_ValorOrcado_Mensal.ano',
                'dbo.Gestao_ValorOrcado_Mensal.valor')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_ValorOrcado_Mensal.setor_id', 'PLCFULL.dbo.Jurid_Setor.Id')               
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_ValorOrcado_Mensal.mes', 'dbo.Gestao_Mes.id')
        ->where('dbo.Gestao_ValorOrcado_Mensal.ano', 2021)
        ->orderBy('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
        ->orderBy('dbo.Gestao_Mes.id', 'asc')
        ->get();
  
           $customer_array[] = array(
                   'setor_codigo', 
                   'setor_descricao',
                   'unidade_codigo',
                   'unidade_descricao',
                   'mes',
                   'ano',
                   'valor');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'setor_codigo'  => $customer->setor_codigo,
                                'setor_descricao' => $customer->setor_descricao,
                                'unidade_codigo' => $customer->unidade_codigo,
                                'unidade_descricao' => $customer->unidade_descricao,
                                'mes' => $customer->mes,
                                'ano' => $customer->ano,
                                'valor' => number_format($customer->valor,2,",","."),
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Relatório valor orçado mensal', function($excel) use ($customer_array){
                        $excel->setTitle('Valor orçado mensal');
                        $excel->sheet('Valor orçado mensal', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');

    }

    public function controlador_valororcado_mensal_gravarregistro(Request $request) {

        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $setor = $request->get('setor');
        $valor = str_replace (',', '.', str_replace ('.', '', $request->get('valor')));
        $ativo = $request->get('ativo');
        $carbon= Carbon::now();

        $value1 = array(
            'setor_id' => $setor, 
            'mes' => $mes,
            'ano' => $ano,
            'valor' => $valor,
            'ativo' => $ativo,
            'data' => $carbon);
        DB::table('dbo.Gestao_ValorOrcado_Mensal')->insert($value1);

        

        flash('Lançamento de valor orçado mensal cadastrado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.ValorOrcado.Mensal.index");
    }

    public function controlador_valororcado_mensal_editar($id) {

        $carbon= Carbon::now();

        $datas = DB::table('dbo.Gestao_ValorOrcado_Mensal')
        ->select(
                'dbo.Gestao_ValorOrcado_Mensal.id',
                'PLCFULL.dbo.Jurid_Setor.Codigo as setor',
                'PLCFULL.dbo.Jurid_Setor.Descricao as descricao',
                'PLCFULL.dbo.Jurid_Unidade.Codigo as unidade',
                'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade_descricao',
                'dbo.Gestao_Mes.id as mes_id',
                'dbo.Gestao_Mes.descricao as mes',
                'dbo.Gestao_ValorOrcado_Mensal.ano',
                'dbo.Gestao_ValorOrcado_Mensal.valor',
                'dbo.Gestao_ValorOrcado_Mensal.ativo')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.Gestao_ValorOrcado_Mensal.setor_id', 'PLCFULL.dbo.Jurid_Setor.Id')               
        ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
        ->leftjoin('dbo.Gestao_Mes', 'dbo.Gestao_ValorOrcado_Mensal.mes', 'dbo.Gestao_Mes.id')
        ->where('dbo.Gestao_ValorOrcado_Mensal.id', $id)
        ->first();

        $meses = DB::table('dbo.Gestao_Mes')
        ->orderby('id', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.ValorOrcado.Mensal.editar', compact('meses','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_valororcado_mensal_editado(Request $request) {

        $id = $request->get('id');
        $mes = $request->get('mes');
        $ano = $request->get('ano');
        $valor = str_replace (',', '.', str_replace ('.', '.', $request->get('valor')));
        $ativo = $request->get('ativo');

        $values = array(
            "mes" => $mes,
            "ano" => $ano,
            "valor" => $valor,
            "ativo" => $ativo);
        DB::table('dbo.Gestao_ValorOrcado_Mensal')
        ->where('dbo.Gestao_ValorOrcado_Mensal.id', '=', $id)
        ->update($values);

        flash('Lançamento de valor orçado mensal atualizado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.ValorOrcado.Mensal.index");

    }

    public function meritocracia_contrato_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $nivel = DB::table('dbo.web_indicadores_advogado')->select('nivel')->where('advogado','=', Auth::user()->cpf)->orderBy('id', 'desc')->value('nivel');


        $datas = DB::table('dbo.Gestao_Contrato')
        ->select(
                'dbo.Gestao_Contrato.id as id',
                'dbo.Gestao_Contrato_Tipo.descricao as tipo',
                'dbo.Gestao_Contrato_Status.id as status_id',
                'dbo.Gestao_Contrato_Status.descricao as status',
                'dbo.Gestao_Contrato.ano',
                'dbo.Gestao_Contrato.anexo')
        ->leftjoin('dbo.Gestao_Contrato_Tipo', 'dbo.Gestao_Contrato.tipo_id', 'dbo.Gestao_Contrato_Tipo.id')
        ->leftjoin('dbo.Gestao_Contrato_Status', 'dbo.Gestao_Contrato.status_id', 'dbo.Gestao_Contrato_Status.id')
        ->orderBy('dbo.Gestao_Contrato.id', 'asc')
        ->where('dbo.Gestao_Contrato.user_id', Auth::user()->id)
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Meritocracia.Contrato.index', compact('nivel','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function meritocracia_contrato_baixar($anexo) {

        $carbon= Carbon::now();

        return Storage::disk('gestao-sftp')->download($anexo);

    //     //Pego os dados do usuario com a Jurid_Advogado
    //     $datas = DB::table('PLCFULL.dbo.Jurid_Advogado')
    //     ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as codigo',
    //              'PLCFULL.dbo.Jurid_Advogado.Nome as nome',
    //              'PLCFULL.dbo.Jurid_Advogado.Endereco as endereco',
    //              'PLCFULL.dbo.Jurid_Advogado.Bairro as bairro',
    //              'PLCFULL.dbo.Jurid_Advogado.Cidade as cidade',
    //              'PLCFULL.dbo.Jurid_Advogado.Cep as cep',
    //              'PLCFULL.dbo.Jurid_Advogado.UF as uf',
    //              'PLCFULL.dbo.Jurid_Advogado.E_mail as email',
    //              'PLCFULL.dbo.Jurid_Advogado.OAB as oab')
    //    ->where('PLCFULL.dbo.Jurid_Advogado.Codigo','=', Auth::user()->cpf)
    //    ->first();  

    //    //Pegos dados do contrato que ele selecionou
    //    $contratos = DB::table('dbo.Gestao_Contrato')
    //    ->select('dbo.Gestao_Contrato.id as id',
    //             'dbo.users.name as controlador_nome',
    //             'dbo.users.cpf as controlador_cpf',
    //             'dbo.Gestao_Contrato.data_assinaturasocio as data',
    //             'dbo.Gestao_Contrato.anexo as anexo',
    //             'dbo.Gestao_Contrato.anexo_assinaturacontrolador',
    //             'dbo.Gestao_Contrato.anexo_assinaturaplc')
    //   ->leftjoin('dbo.users', 'dbo.Gestao_Contrato.controlador_id', 'dbo.users.id')          
    //   ->where('dbo.Gestao_Contrato.id','=', $id)
    //   ->first(); 

    //   $pdf = PDF::loadView('Painel.Gestao.Meritocracia.Contrato.visualizar', compact('datas', 'contratos', 'carbon'));
    //   return $pdf->download('contrato.pdf');

    }

    public function controlador_contrato_index() {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.Gestao_Contrato_Tipo')
        ->select(
                'dbo.Gestao_Contrato_Tipo.id as id',
                'dbo.Gestao_Contrato_Tipo.descricao as tipo',
                'dbo.Gestao_Contrato_Tipo.status as ativo')       
        ->orderBy('dbo.Gestao_Contrato_Tipo.id', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        return view('Painel.Gestao.Controlador.Contrato.index', compact('datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_contrato_exportar() {

        $customer_data = DB::table('dbo.Gestao_Contrato_Tipo')
        ->select(
                'dbo.Gestao_Contrato_Tipo.id as id',
                'dbo.Gestao_Contrato_Tipo.descricao as tipo',
                'dbo.Gestao_Contrato_Tipo.status as ativo')       
        ->orderBy('dbo.Gestao_Contrato_Tipo.id', 'asc')
        ->get();
  
           $customer_array[] = array(
                   'id', 
                   'tipo',
                   'ativo');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'id'  => $customer->id,
                                'tipo' => $customer->tipo,
                                'ativo' => $customer->ativo,
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Tipos de contrato', function($excel) use ($customer_array){
                        $excel->setTitle('Tipos de contrato');
                        $excel->sheet('Tipos de contrato', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');
    }

    public function controlador_contrato_gravarregistro(Request $request) {

        $descricao = $request->get('descricao');
        $ativo = $request->get('ativo');

        //Grava na tabela
        $values = array(
            'descricao' => $descricao, 
            'status' => $ativo);
            DB::table('dbo.Gestao_Contrato_Tipo')->insert($values);

        flash('Novo contrato criado com sucesso !')->success();

        return redirect()->route("Painel.Gestao.Controlador.Contrato.index");

    }

    public function controlador_contrato_detalhatipo($id) {

        $carbon= Carbon::now();
        $datahoje = $carbon->format('Y-m-d');

        $datas = DB::table('dbo.Gestao_Contrato')
        ->select(
                'dbo.Gestao_Contrato.id as id',
                'dbo.users.name as usuario',
                'dbo.Gestao_Contrato_Tipo.descricao as tipo',
                'dbo.Gestao_Contrato_Status.id as status_id',
                'dbo.Gestao_Contrato_Status.descricao as status',
                'dbo.Gestao_Contrato.ano')
        ->leftjoin('dbo.Gestao_Contrato_Tipo', 'dbo.Gestao_Contrato.tipo_id', 'dbo.Gestao_Contrato_Tipo.id')
        ->leftjoin('dbo.Gestao_Contrato_Status', 'dbo.Gestao_Contrato.status_id', 'dbo.Gestao_Contrato_Status.id')
        ->leftjoin('dbo.users', 'dbo.Gestao_Contrato.user_id', 'dbo.users.id')
        ->orderBy('dbo.users.name', 'asc')
        ->where('dbo.Gestao_Contrato_Tipo.id', $id)
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();

        $usuarios =  DB::table('dbo.users')
        ->select('dbo.users.id', 'dbo.users.name')  
        ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
        ->where('dbo.profile_user.profile_id','!=', '1')
        ->orderby('dbo.users.name', 'asc')
        ->get(); 

        return view('Painel.Gestao.Controlador.Contrato.detalhatipo', compact('id','usuarios','datas','totalNotificacaoAbertas', 'notificacoes'));

    }

    public function controlador_contrato_exportardetalhamento($tipo_id) {

        $customer_data =  DB::table('dbo.Gestao_Contrato')
        ->select(
                'dbo.Gestao_Contrato.id as id',
                'dbo.users.name as socio',
                'dbo.Gestao_Contrato_Tipo.descricao as tipo',
                'dbo.Gestao_Contrato_Status.descricao as status',
                'dbo.Gestao_Contrato.ano')
        ->leftjoin('dbo.Gestao_Contrato_Tipo', 'dbo.Gestao_Contrato.tipo_id', 'dbo.Gestao_Contrato_Tipo.id')
        ->leftjoin('dbo.Gestao_Contrato_Status', 'dbo.Gestao_Contrato.status_id', 'dbo.Gestao_Contrato_Status.id')
        ->leftjoin('dbo.users', 'dbo.Gestao_Contrato.user_id', 'dbo.users.id')
        ->orderBy('dbo.users.name', 'asc')
        ->where('dbo.Gestao_Contrato_Tipo.id', $id)
        ->get();
  
           $customer_array[] = array(
                   'id', 
                   'socio',
                   'tipo',
                   'status',
                   'ano');
          
                foreach($customer_data as $customer) {
                        $customer_array[] = array(
                                'id'  => $customer->id,
                                'socio' => $customer->socio,
                                'tipo' => $customer->tipo,
                                'status' => $customer->status,
                                'ano' => $customer->ano,
                        );
                }
                ini_set('memory_limit','-1'); 
                Excel::create('Contratos anexados', function($excel) use ($customer_array){
                        $excel->setTitle('Contratos anexados');
                        $excel->sheet('Contratos anexados', function($sheet) use ($customer_array) {
                        $sheet->fromArray($customer_array, null, 'A1', false, false);
                });
        })->download('xlsx');
    }

    public function controlador_contrato_anexado(Request $request) {

        $usuario_id = $request->get('usuario');
        $ano = $request->get('ano');
        $tipo_id = $request->get('tipo_id');
        $carbon= Carbon::now();
        $dataehora = $carbon->format('dmY_HHis');

        $anexo = $request->file('select_file');

        $new_name = $usuario_id . '_' . $dataehora . '.'  . $anexo->getClientOriginalExtension();

        $anexo->storeAs('gestao', $new_name);

        //Grava na Gestao_Contrato

        $values = array(
            'user_id' => $usuario_id,
            'controlador_id' => Auth::user()->id,
            'tipo_id' => $tipo_id,
            'ano' => $ano,
            'status_id' => '1',
            'anexo' => $anexo);
        DB::table('dbo.Gestao_Contrato')->insert($values);   

        flash('Contrato anexado com sucesso!')->success();   
        return redirect()->route("Painel.Gestao.Controlador.Contrato.detalhatipo", ["id" => $tipo_id]);

    }

    public function controlador_usuarios_index() {


        //Pego os dados do usuario com a Jurid_Advogado
        $datas = DB::table('dbo.web_indicadores_advogado')
        ->select('dbo.users.id as usuario_id',
                 'dbo.users.name as usuario_nome',
                 'dbo.users.cpf as usuario_codigo',
                 'dbo.users.email as usuario_email',
                 'dbo.web_indicadores_advogado.nivel',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.web_indicadores_advogado.datainicio',
                 'dbo.web_indicadores_advogado.datafim')
       ->join('dbo.users', 'dbo.web_indicadores_advogado.advogado', 'dbo.users.cpf')       
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_advogado.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')   
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->orderBy('dbo.users.name', 'asc')
       ->groupBy('dbo.users.id', 'dbo.users.name', 'dbo.users.cpf',
                'dbo.users.email', 'dbo.web_indicadores_advogado.nivel', 'PLCFULL.dbo.Jurid_Unidade.Descricao', 'PLCFULL.dbo.Jurid_Setor.Descricao', 'dbo.web_indicadores_advogado.datainicio', 'dbo.web_indicadores_advogado.datafim')
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
       ->orderBy('dbo.Hist_Notificacao.id', 'desc')
       ->get();

    return view('Painel.Gestao.Controlador.Usuarios.index', compact('datas','totalNotificacaoAbertas','notificacoes'));

    }

    public function controlador_usuarios_nivel_index() {

        $datas = DB::table('dbo.web_indicadores_advogado')
        ->select(
                 'dbo.web_indicadores_advogado.id as id', 
                 'dbo.users.name as usuario_nome',
                 'dbo.users.cpf as usuario_codigo',
                 'dbo.users.email as usuario_email',
                 'dbo.web_indicadores_advogado.nivel',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.web_indicadores_advogado.datainicio',
                 'dbo.web_indicadores_advogado.datafim')
       ->join('dbo.users', 'dbo.web_indicadores_advogado.advogado', 'dbo.users.cpf')       
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_advogado.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')   
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->wherenull('dbo.web_indicadores_advogado.datafim')
       ->orderBy('dbo.users.name', 'asc')
       ->groupBy('dbo.web_indicadores_advogado.id', 'dbo.users.name', 'dbo.users.cpf',
                'dbo.users.email', 'dbo.web_indicadores_advogado.nivel', 'PLCFULL.dbo.Jurid_Unidade.Descricao', 'PLCFULL.dbo.Jurid_Setor.Descricao', 'dbo.web_indicadores_advogado.datainicio', 'dbo.web_indicadores_advogado.datafim')
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
       ->orderBy('dbo.Hist_Notificacao.id', 'desc')
       ->get();

    return view('Painel.Gestao.Controlador.Usuarios.Nivel.index', compact('datas','totalNotificacaoAbertas','notificacoes'));

    }

    public function controlador_usuarios_nivel_atualizaregistro(Request $request) {

        $id = $request->get('id');
        $datafim = $request->get('datafim');
        $nivel = $request->get('nivel');

        if($datafim == null) {

            DB::table('dbo.web_indicadores_advogado')
            ->where('id', '=' ,$id) 
            ->limit(1) 
            ->update(array('nivel' => $nivel));

        } else {

            DB::table('dbo.web_indicadores_advogado')
            ->where('id', '=' ,$id) 
            ->limit(1) 
            ->update(array('nivel' => $nivel, 'datafim' => $datafim));

        }

        
        flash('Registro atualizado com sucesso!')->success();    

        return redirect()->route('Painel.Gestao.Controlador.Usuarios.Nivel.index');


    }

    public function controlador_usuarios_nivel_novorelacionamento() {

        //Busco usuarios
        $usuarios =  DB::table('dbo.users')
            ->select('dbo.users.cpf', 'dbo.users.name',)  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->where('dbo.profile_user.profile_id','!=', '1')
            ->orderby('dbo.users.name', 'asc')
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
        ->orderBy('dbo.Hist_Notificacao.id', 'desc')
        ->get();
 
     return view('Painel.Gestao.Controlador.Usuarios.Nivel.adicionar', compact('usuarios','totalNotificacaoAbertas','notificacoes'));
    
    }

    public function controlador_usuarios_nivel_relacionado(Request $request) {

        $advogado = $request->get('socio');
        $nivel = $request->get('nivel');
        $datainicio = $request->get('datainicio');

        //Grava na tabela
        $values = array('advogado' => $advogado, 'nivel' => $nivel, 'datainicio' => $datainicio);
        DB::table('dbo.web_indicadores_advogado')->insert($values);

        flash('Registro salvo com sucesso!')->success();    

        return redirect()->route('Painel.Gestao.Controlador.Usuarios.Nivel.index');
    }

    public function controlador_usuarios_setores_index() {

                $datas = DB::table('PLCFULL.dbo.Jurid_Setor')
                ->select('PLCFULL.dbo.Jurid_Setor.Id',
                         'PLCFULL.dbo.Jurid_Setor.Codigo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao',
                         'PLCFULL.dbo.Jurid_Unidade.Codigo as Unidade_Codigo',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade_Descricao')
               ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')   
               ->orderBy('PLCFULL.dbo.Jurid_Setor.Codigo', 'asc')
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
               ->orderBy('dbo.Hist_Notificacao.id', 'desc')
               ->get();
        
            return view('Painel.Gestao.Controlador.Usuarios.setores.index', compact('datas','totalNotificacaoAbertas','notificacoes'));

    }

    public function controlador_usuarios_setores_usuarios($setor_codigo) {

            $datas = DB::table('dbo.web_hierarquia_setor')
                ->select('dbo.users.name as usuario_nome',
                         'dbo.users.email as usuario_email',
                         'dbo.users.cpf as usuario_cpf',
                         'PLCFULL.dbo.Jurid_Setor.Codigo as Setor',
                         'dbo.web_hierarquia_setor.ativo',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as SetorDescricao')
               ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.web_hierarquia_setor.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')          
               ->leftjoin('dbo.users', 'dbo.web_hierarquia_setor.advogado', 'dbo.users.cpf')
               ->where('dbo.web_hierarquia_setor.setor', $setor_codigo)
               ->orderBy('dbo.users.name', 'asc')
               ->get();  

            $setor_descricao = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Descricao')->where('Codigo','=', $setor_codigo)->value('Descricao');   
        
            $usuarios =  DB::table('dbo.users')
            ->select('dbo.users.id', 'dbo.users.name')  
            ->leftjoin('dbo.profile_user','dbo.users.id','=','dbo.profile_user.user_id')
            ->where('dbo.profile_user.profile_id','!=', '1')
            ->orderby('dbo.users.name', 'asc')
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
               ->orderBy('dbo.Hist_Notificacao.id', 'desc')
               ->get();
        
            return view('Painel.Gestao.Controlador.Usuarios.setores.usuarios', compact('usuarios','setor_codigo','setor_descricao','datas','totalNotificacaoAbertas','notificacoes'));
    }

    public function controlador_usuarios_setores_relacionamentocriado(Request $request) {

        $setor = $request->get('setor');
        $usuario = $request->get('usuario');
        $ativo = $request->get('ativo');
        $carbon= Carbon::now();

        $usuario_cpf = DB::table('dbo.users')->select('cpf')->where('id','=', $usuario)->value('cpf');  
        
        //Grava na tabela web_hierarquia_setor
        $values= array('advogado' => $usuario_cpf, 
                       'setor' => $setor, 
                       'ativo' => $ativo);
        DB::table('dbo.web_hierarquia_setor')->insert($values);


        //Envia notificação ao usuário informando que ele foi relacionado
        $values3= array('data' => $carbon, 'id_ref' => $setor, 'user_id' => Auth::user()->id, 'destino_id' => $usuario, 'tipo' => '7', 'obs' => 'Você foi relacionado ao um novo setor de custo no modúlo de gestão.' ,'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        flash('Novo relacionamento cadastrado com sucesso !')->success();    

        return redirect()->route("Painel.Gestao.Controlador.Usuarios.Setores.usuarios", ["id" => $setor]);
    }

    public function controlador_usuarios_setores_relacionamentocancelado(Request $request) {

        $usuario_nome = $request->get('usuario_nome');
        $usuario_cpf = $request->get('usuario_cpf');
        $setor = $request->get('setor');
        $carbon= Carbon::now();

        DB::table('dbo.web_hierarquia_setor')
        ->where('advogado', $usuario_cpf) 
        ->where('setor', $setor)
        ->limit(1) 
        ->update(array('ativo' => 'N'));

        flash('Relacionamento desativado com sucesso !')->success();    

        return redirect()->route("Painel.Gestao.Controlador.Usuarios.Setores.usuarios", ["id" => $setor]);

    }

    public function controlador_usuarios_setores_relacionamentoativo(Request $request) {

        $usuario_nome = $request->get('usuario_nome');
        $usuario_cpf = $request->get('usuario_cpf');
        $setor = $request->get('setor');
        $carbon= Carbon::now();

        DB::table('dbo.web_hierarquia_setor')
        ->where('advogado', $usuario_cpf) 
        ->where('setor', $setor)
        ->limit(1) 
        ->update(array('ativo' => 'S'));

        flash('Relacionamento ativado novamente com sucesso !')->success();    

        return redirect()->route("Painel.Gestao.Controlador.Usuarios.Setores.usuarios", ["id" => $setor]);

    }

    public function controlador_usuarios_exportar() {

        $customer_data = DB::table('dbo.web_indicadores_advogado')
        ->select('dbo.users.id as usuario_id',
                 'dbo.users.name as usuario_nome',
                 'dbo.users.cpf as usuario_codigo',
                 'dbo.users.email as usuario_email',
                 'dbo.web_indicadores_advogado.nivel',
                 'PLCFULL.dbo.Jurid_Unidade.Descricao as unidade',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as setor',
                 'dbo.web_indicadores_advogado.datainicio')
       ->join('dbo.users', 'dbo.web_indicadores_advogado.advogado', 'dbo.users.cpf')       
       ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'dbo.web_indicadores_advogado.advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
       ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Advogado.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')   
       ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
       ->whereNull('dbo.web_indicadores_advogado.datafim')
       ->groupBy('dbo.users.id', 'dbo.users.name', 'dbo.users.cpf',
       'dbo.users.email', 'dbo.web_indicadores_advogado.nivel', 'PLCFULL.dbo.Jurid_Unidade.Descricao', 'PLCFULL.dbo.Jurid_Setor.Descricao', 'dbo.web_indicadores_advogado.datainicio')
       ->get();  
          
                   $customer_array[] = array(
                           'usuario_nome', 
                           'usuario_codigo',
                           'usuario_email',
                           'nivel',
                           'unidade',
                           'setor',
                           'datainicio');
                  
                        foreach($customer_data as $customer) {
                                $customer_array[] = array(
                                        'usuario_nome'  => $customer->usuario_nome,
                                        'usuario_codigo' => $customer->usuario_codigo,
                                        'usuario_email' => $customer->usuario_email,
                                        'nivel' => $customer->nivel,
                                        'unidade' => $customer->unidade,
                                        'setor' => $customer->setor,
                                        'datainicio' => date('d/m/Y', strtotime($customer->datainicio)),
                                );
                        }
                        ini_set('memory_limit','-1'); 
                        Excel::create('Relatorio usuarios', function($excel) use ($customer_array){
                                $excel->setTitle('Relatorio usuarios');
                                $excel->sheet('Relatorio usuarios', function($sheet) use ($customer_array) {
                                $sheet->fromArray($customer_array, null, 'A1', false, false);
                        });
                })->download('xlsx');


    }

    public function controlador_hierarquia_novoresponsavel(Request $request) {

        $responsavel_id = $request->get('responsavel');
        $advogado_id = $request->get('advogado');
        $status = $request->get('ativo');
        $datainicio = $request->get('data');
        $carbon= Carbon::now();
        

        $responsavel_cpf = DB::table('dbo.users')->select('cpf')->where('id','=',$responsavel_id)->value('cpf');  
        $advogado_cpf = DB::table('dbo.users')->select('cpf')->where('id','=',$advogado_id)->value('cpf');  

        //Grava na tabela hierarquia
        $values = array('advogado' => $advogado_cpf, 
                        'responsavel' => $responsavel_cpf, 
                        'ativo' => $status, 
                        'datainicio' => $datainicio);
        DB::table('dbo.web_hierarquia')->insert($values);

        $id = DB::table('dbo.web_hierarquia')->select('id')->where('responsavel','=',$responsavel_cpf)->where('advogado', $advogado_cpf)->orderBy('id', 'desc')->value('id');  

        //Envia notificação ao Responsavel
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $responsavel_id, 'tipo' => '7', 'obs' => 'Gestão: Você foi cadastrado como novo responsável em uma hierarquia.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        //Envia notificação ao Adv
        $values3= array('data' => $carbon, 'id_ref' => $id, 'user_id' => Auth::user()->id, 'destino_id' => $advogado_id, 'tipo' => '7', 'obs' => 'Gestão: Você foi cadastrado em uma nova hierarquia.', 'status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        flash('Nova hierarquia cadastrada com sucesso!')->success();

        return redirect()->route('Painel.Gestao.Controlador.Hierarquia.index');

    }


}