<?php

namespace App\Console\Commands\GuiasCusta;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Profile;
use App\Mail\GuiasCusta\EnviaNotificacaoFinanceiro;

class EnviaNotificacao extends Command {


    protected $signature = 'guiascusta_envianotificacao:cron';


    protected $description = 'Verificação diaria se existe alguma solicitação de pagamento de guia de custa criada pelo Advwin.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $data = date('m');

    //Pego todos os novos usuarios cadastrados no Advwin na data de hoje, exceto Correspondente
    $verifica = DB::table('PLCFULL.dbo.Jurid_Debite')
    ->select('PLCFULL.dbo.Jurid_Debite.Pasta',
             'PLCFULL.dbo.Jurid_Debite.Advogado',
             DB::raw("SUM(ValorT) as ValorTotal"))
    ->join('dbo.Financeiro_Reembolso_Matrix', 'PLCFULL.dbo.Jurid_Debite.Numero', 'dbo.Financeiro_Reembolso_Matrix.numerodebite')         
    ->whereMonth('PLCFULL.dbo.Jurid_Debite.Data', $data)
    ->groupby('PLCFULL.dbo.Jurid_Debite.Pasta', 'PLCFULL.dbo.Jurid_Debite.Advogado')
    ->get();
     
   if($verifica->count() != 0) {

    foreach($verifica as $index) {


        $solicitantecodigo = $index->Advogado;
        $valortotal = $index->ValorTotal;
        $pasta = $index->Pasta;

          //Verifico se o valor é acima de R$ 1500,00

          if($valortotal >= 1500) {

             $solicitantenome = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Nome')->where('Codigo', $solicitantecodigo)->value('Nome'); 
             $setor = DB::table('PLCFULL.dbo.Jurid_Pastas')->select('Setor')->where('Codigo_Comp', $pasta)->value('Setor'); 

             $coordenadores = DB::table('dbo.users')
             ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
             ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
             ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
             ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
             ->select('dbo.users.id', 'dbo.users.name as nome', 'dbo.users.email') 
             ->where('dbo.profiles.id', '=', '20')
             ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
             ->first();   

            //Envia e-mail para o Gerente Finaceiro e Revisão Tecnica
            Mail::to($coordenadores->email)
            ->cc('ronaldo.amaral@plcadvogados.com.br')
            ->send(new EnviaValorAcima($solicitantenome, $pasta, $valortotal));

          }

    }
    //Fim Foreach

    }

}

}

