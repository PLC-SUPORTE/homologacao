<?php

namespace App\Console\Commands\DPRH\Desligamento;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Profile;
use App\Mail\DPRH\Desligamento\PrepararDocumentacao;

class NovosUsuarios extends Command {


    protected $signature = 'dprh_desligamento_enviarh:cron';


    protected $description = 'Verificação diaria as 00:00 de todas as solicitações que estejam com o status 2(Em Andamento) e envia para o RH.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $data = date('Y-m-d', strtotime('-1 day'));

    //Pego todos os novos usuarios cadastrados no Advwin na data de hoje, exceto Correspondente
    $verifica = DB::table('dbo.DPRH_Desligamento_Matrix')
    ->select('dbo.DPRH_Desligamento_Matrix.id'
    )
    ->whereDate('data_saida', 'like', '%' . $data . '%')
    ->where('status_id', '2')
    ->get();
    
   if($verifica->count() != 0) {


    foreach($verifica as $index) {

        $id = $index->id;

        $datas = DB::table('dbo.DPRH_Desligamento_Matrix')
        ->select('dbo.DPRH_Desligamento_Matrix.id',
                 'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                 'dbo.DPRH_Desligamento_Motivos.descricao as Motivo',
                 'dbo.DPRH_Desligamento_Status.descricao as Status',
                 'dbo.DPRH_Desligamento_Matrix.data_solicitacao as DataSolicitacao',
                 'dbo.DPRH_Desligamento_Matrix.data_saida as DataSaida',
                 'dbo.DPRH_Desligamento_Matrix.data_edicao as DataModificacao')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.DPRH_Desligamento_Matrix.setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
        ->leftjoin('dbo.DPRH_Desligamento_Status','dbo.DPRH_Desligamento_Matrix.status_id', 'dbo.DPRH_Desligamento_Status.id')
        ->leftjoin('dbo.DPRH_Desligamento_Motivos', 'dbo.DPRH_Desligamento_Matrix.motivo_id', 'dbo.DPRH_Desligamento_Motivos.id')
        ->where('dbo.DPRH_Desligamento_Matrix.id', $id)
        ->get();

        //Update Matrix 
        DB::table('dbo.DPRH_Desligamento_Matrix')
        ->where('id', $id)  
        ->limit(1) 
        ->update(array('status_id' => '4','data_edicao' => $carbon));

        //Grava na Hist
        $values3= array('id_matrix' => $id,
                      'user_id' => '25', 
                      'status' => '4', 
                      'data' => $carbon);
        DB::table('dbo.DPRH_Desligamento_Hist')->insert($values3); 

        //Envia e-mail para o RH
        Mail::to('ronaldo.amaral@plcadvogados.com.br')
         ->send(new PrepararDocumentacao($datas));


        // $values4= array('data' => $carbon, 'id_ref' => $userid, 'user_id' => '25', 'destino_id' => '241', 'tipo' => '3', 'obs' => 'O usuario: ' .$name .' foi cadastrado no portal pelo CRON.','status' => 'A');
        // DB::table('dbo.Hist_Notificacao')->insert($values4);

       }
       
    }
    //Fim Foreach

}

}

