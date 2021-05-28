<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Automatico\AdvogadoMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaturamentoAutomatico extends Command {

    protected $signature = 'faturamentoautomatico:cron';
    protected $description = 'Verificação automatica 07:00 da manha e 18:00 se teve alteração no status das pastas.';


    public function __construct()
    {
        parent::__construct();
    }

 
    public function handle() {

        $carbon= Carbon::now();
        $diaatual = $carbon->format('Y-m-d');
        $primeirodiadomes = $carbon->format('Y-m-t');


       //Verifico se possui uma nova pasta na Jurid_Pasta com a data de cadastro hoje para insert na Matrix
       $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
       ->select('id_pasta','status')
       ->where('Dt_Cad', '=', date("Y-m-d"))
       ->count();

       //Se encontrar alguma pasta nova
       if($datas >= 1) {

        $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
        ->select('id_pasta','status')
        ->where('Dt_Cad', '=', $diaatual)
        ->get();   

        foreach($datas as $index) {

        $id_pasta = $index->id_pasta;
        $status = $index->status;
               
        //Gravo na Tabela Matrix 
        $values = array(
          'id_pasta' => $id_pasta,
          'status_inicial' => $status,
          'status_atual' => $status,
          'dt_update' => $carbon);
         DB::table('dbo.Financeiro_Faturamento_Matrix')->insert($values);  
        }
        //Fim Foreach
    }

    //Verifico se possui alguma alteração na Jurid_Audit 
    $datas = DB::table('PLCFULL.dbo.Jurid_Audit')
             ->where('Hora', '=', $diaatual)
             ->where('Operacao', '=', "Alt_Stat_Manual")
             ->where('Operacao', '=', "Alt_Stat_Auto")
             ->count();

    if($datas >= "1")  { 
     
        $datas = DB::table('PLCFULL.dbo.Jurid_Audit')
        ->select('Par1','Cliente')
        ->where('Hora', '=', $diaatual)
        ->where('Operacao', '=', "Alt_Stat_Manual")
        ->where('Operacao', '=', "Alt_Stat_Auto")
        ->get();  

     foreach($datas as $index) {

         $codigo_pasta = $index->Par1;
         $id_pasta = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('id_pasta')->where('Codigo_comp','=', $codigo_pasta)->value('id_pasta'); 
         $status = $index->Cliente;

         //Comparação das datas com a matrix
         $diaatual = $carbon->format('d');
         $ultimaatualizacao = DB::table('dbo.Financeiro_Faturamento_Matrix')->select('dt_update')->where('id_pasta','=', $id_pasta)->value('dt_update'); 
         $diaatualizacao = date('d', strtotime($ultimaatualizacao));
         $diferenca = $diaatual- $diaatualizacao;

        //Gravo na Tabela Hist informando alteração no status da pasta
         if($status == "Ativa") {
            $values = array(
                'id_pasta' => $id_pasta,
                'status_atual' => $status,
                'dias_count' => $diferenca,
                'dt_update' => $carbon);
                DB::table('dbo.Financeiro_Faturamento_Hist')->insert($values);  
         } else {
            $values = array(
                'id_pasta' => $id_pasta,
                'status_atual' => $status,
                'dt_update' => $carbon);
                DB::table('dbo.Financeiro_Faturamento_Hist')->insert($values);  
         }

          //Update Jurid_Matrix informando status atual
          DB::table('dbo.Financeiro_Faturamento_Matrix')
         ->where('id_pasta', $id_pasta)  
         ->limit(1) 
         ->update(array('status_atual' => $status, 'dt_update' => $carbon));
          }
         //Fim Foreach

         $date = date('Y-m-d H:i', strtotime('+24 Hours'));
         $id_matrix = 1;
         $observacao = 'Comando executado com sucesso. Registros gravadas na tabela.';
     
         $values = array(
             'id_matrix' => $id_matrix, 
             'data' => $carbon, 
             'proximohorario' => $date,
             'observacao' => $observacao);
         DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

    }
    //Se não existir nova alteração fim do CRON
    else {
        $date = date('Y-m-d H:i', strtotime('+24 Hours'));
        $id_matrix = 1;
        $observacao = 'Comando executado com sucesso. Não houve registros.';
    
        $values = array(
            'id_matrix' => $id_matrix, 
            'data' => $carbon, 
            'proximohorario' => $date,
            'observacao' => $observacao);
        DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);
    }

    
}


}

