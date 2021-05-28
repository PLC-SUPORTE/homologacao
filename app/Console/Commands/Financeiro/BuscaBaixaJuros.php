<?php

namespace App\Console\Commands\Financeiro;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
// use App\Mail\PesquisaPatrimonial\SolicitacaoLiberada;


class BuscaBaixaJuros extends Command {


    protected $signature = 'financeiro_buscabaixasjuros:cron';


    protected $description = 'Verificação automatica a cada 12 horas das baixas do tipolan 16.14.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    //Verifico se houve possui alguma baixa de boleto referente a uma solicitação de pesquisa patrimonial na data de hoje
    
    $datas = DB::table('PLCFULL.dbo.Jurid_ContPrBX')
    ->select('Numdoc', 'Ident_Cpr' ,'Valor')
    ->where('PLCFULL.dbo.Jurid_ContPrBX.TipoLan', '=', '14.02')
    ->where('PLCFULL.dbo.Jurid_ContPrBX.Tipo', '=', 'R')
    ->where('Numdoc', '181935')
    ->get();    
    
   //Se possuir registro para atualizar
   if($datas->count() != 0) {

    foreach($datas as $index) {  

     //Busca as solicitações com este codigo de boleto
     $numdoc = $index->Numdoc;
     $ident_cpr = $index->Ident_Cpr;
     $valor = $index->Valor;

     $valor_formato = number_format($valor, 2,',', '.');

     //Pego qual a baixa e do update no valor somando
     $valor_cpr = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Valor')->where('Cpr_ident', $ident_cpr)->where('Tipo', 'R')->value('Valor');
     $cpr = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Numdoc')->where('Cpr_ident', $ident_cpr)->where('Tipo', 'R')->value('Numdoc');
     $observacao_cpr = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Obs')->where('Cpr_ident', $ident_cpr)->where('Tipo', 'R')->value('Obs');
     $hist_cpr = DB::table('PLCFULL.dbo.Jurid_ContaPr')->select('Historico')->where('Cpr_ident', $ident_cpr)->where('Tipo', 'R')->value('Historico');
     $valor_atualizado = $valor + $valor_cpr;

     $hist_atualizado = $hist_cpr . ' Integrado o valor da CPR: ' . $numdoc . ' referente a Juros no tipo de lançamento 14.02 no valor de: ' . $valor_formato . ' no dia: ' . $carbon->format('d/m/Y H:i:s') . '. Processo realizado automático no portal.';
     
     //Grava na Tabela Auditoria
     $values3= array('cpr' => $cpr, 
                     'numdoc_juros' => $numdoc, 
                     'valor_cpr' => $valor_cpr, 
                     'valor_juros' => $valor, 
                     'valor_atualizado' => $valor_atualizado, 
                     'data' => $carbon);
     DB::table('dbo.Financeiro_Auditoria_BaixasJuro')->insert($values3);

    //Atualizo a CPR
    DB::table('PLCFULL.dbo.Jurid_ContaPr')
    ->where('Cpr_ident', $ident_cpr)
    ->update(array('Valor' => $valor_atualizado, 'Historico' => $hist_atualizado));

    //Deleto a baixo do Juros
    DB::table('PLCFULL.dbo.Jurid_ContPrBX')->where('Numdoc', $numdoc)->where('Tipolan', '14.02')->delete();    

    

   }
   //Fim Foreach


   }
   //Se não tiver registro


    }
}
