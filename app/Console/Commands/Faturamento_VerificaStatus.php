<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Automatico\AdvogadoMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Faturamento_VerificaStatus extends Command
{

    protected $signature = 'faturamentoverificastatus:cron';
    protected $description = 'Verifica as 18:00 de todos os dias, se existe registro na tabela Faturamento Temp, se sim alterar status na Jurid_Pastas para Inativa e deletar registro Temp';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        //Verifico se existe registro gravado na tabela_temp
        $total= DB::table('dbo.Financeiro_Faturamento_Temp')->count();
        if($total >= 1 ) {

        $datas = DB::table('dbo.Financeiro_Faturamento_Temp')->select('id_pasta', 'status_atual')->get();     
         
       foreach($datas as $index) {

        $id_pasta = $index->id_pasta;
        $statusatual = $index->status_atual;

        //Passo o status de volta
        if($statusatual == "Inativa") {
        
            DB::table('PLCFULL.dbo.Jurid_Pastas')
            ->where('id_pasta', $id_pasta) 
            ->limit(1) 
            ->update(array('status' => 'Ativa'));
    
            DB::table('dbo.Financeiro_Faturamento_Temp')->where('id_pasta', $id_pasta)->delete(); 
        } 
        else {
                    
        DB::table('PLCFULL.dbo.Jurid_Pastas')
        ->where('id_pasta', $id_pasta) 
        ->limit(1) 
        ->update(array('status' => 'Inativa'));

        DB::table('dbo.Financeiro_Faturamento_Temp')->where('id_pasta', $id_pasta)->delete(); 

        }

        }
        //Fim Foreach
        $date = date('Y-m-d H:i', strtotime('+24 Hours'));
        $id_matrix = 2;
        $observacao = 'Comando executado com sucesso. Status da(s) pasta(s) alterados com sucesso.';
    
        $values = array(
            'id_matrix' => $id_matrix, 
            'data' => $carbon, 
            'proximohorario' => $date,
            'observacao' => $observacao);
        DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);
    
    }
    //Informe que não houve registros na tabela temporaria
    else {
    $date = date('Y-m-d H:i', strtotime('+24 Hours'));
    $id_matrix = 2;
    $observacao = 'Comando executado com sucesso, mas não teve registros.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);
    }


}


}
