<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Automatico\AdvogadoMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaturamentoInicioMes extends Command {

    protected $signature = 'faturamentoiniciomes:cron';
    protected $description = 'Verificação automatica todo dia 01 do mes para gravar as pastas na Matrix.';


    public function __construct()
    {
        parent::__construct();
    }

 
    public function handle() {

        $carbon= Carbon::now();
        $diaatual = $carbon->format('Y-m-d');
        $primeirodiadomes = $carbon->format('Y-m-t');

    //Verifico se hoje é o 1 dia do mês
    if($diaatual == $primeirodiadomes) {

       $datas = DB::table('PLCFULL.dbo.Jurid_Pastas')
             ->select('id_pasta','status')
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

        $date = date('Y-m-d H:i', strtotime('+24 Hours'));
        $id_matrix = 3;
        $observacao = 'Comando executado com sucesso. Registros gravados na tabela Matrix.';
    
        $values = array(
            'id_matrix' => $id_matrix, 
            'data' => $carbon, 
            'proximohorario' => $date,
            'observacao' => $observacao);
        DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

    } 
    //Se não é o 1 dia do mês
    else {
        $date = date('Y-m-d H:i', strtotime('+24 Hours'));
        $id_matrix = 3;
        $observacao = 'Comando executado com sucesso. Não é o 1ª dia.';
    
        $values = array(
            'id_matrix' => $id_matrix, 
            'data' => $carbon, 
            'proximohorario' => $date,
            'observacao' => $observacao);
        DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);
    }
       
}

}

