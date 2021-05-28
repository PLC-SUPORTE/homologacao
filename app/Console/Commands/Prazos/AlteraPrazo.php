<?php

namespace App\Console\Commands\Prazos;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlteraPrazo extends Command {


    protected $signature = 'prazos_alteraprazo:cron';


    protected $description = 'Verificação a cada 5 minutos de todos os prazos alterados.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();

    //Pega todos os prazos que não foi dado update e nem enviado e-mail
    $datas = DB::table('dbo.Prazo_Sync')
    ->select('dbo.Prazo_Sync.ident',
             'dbo.Prazo_Sync.status',
             'dbo.Prazo_Sync.data_prazo')
    ->where('dbo.Prazo_Sync.status_interno', '=', '1')
    ->get();

   //Verifica se existe registros
   if($datas->count() == 0) {

    // //Grava na Hist informando que foi executado com sucesso mas não obteve registros
    // $date = date('Y-m-d H:i', strtotime('+1 Hours'));
    // $id_matrix = 4;
    // $observacao = 'Comando executado com sucesso, mas não teve registros.';

    // $values = array(
    //     'id_matrix' => $id_matrix, 
    //     'data' => $carbon, 
    //     'proximohorario' => $date,
    //     'observacao' => $observacao);
    // DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

    } 
    else {

        foreach($datas as $index) {

            $ident = $index->ident;
            $status = $index->status;
            $data_prazo = $index->data_prazo;


            //Update na Jurid_agenda_table
            DB::table('PLCFULL.dbo.Jurid_agenda_table')
            ->where('PLCFULL.dbo.Jurid_agenda_table.Ident', $ident)
            ->limit(1) 
            ->update(array('Data_prazo' => $data_prazo, 'Status' => $status, 'Ag_StatusExecucao' => ''));

            //Update na Prazo_Sync para enviar e-mail 
            DB::table('dbo.Prazo_Sync')
            ->where('dbo.Prazo_Sync.ident', $ident)
            ->limit(1) 
            ->update(array('status_interno' => '2'));
         }
         //Fim foreach

   }


    }

}
