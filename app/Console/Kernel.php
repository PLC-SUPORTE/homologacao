<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        Commands\FaturamentoAutomatico::class,
        Commands\Faturamento_VerificaStatus::class,
        Commands\PesquisaPatrimonial\PesquisaPatrimonial::class,
        Commands\FaturamentoInicioMes::class,
        Commands\PesquisaPatrimonial\EnvioBoleto::class,
        Commands\PesquisaPatrimonial\DebitosAberto::class,
        Commands\Usuarios\NovosUsuarios::class,
        Commands\Correspondente\AlertaPrazo::class,
        commands\Prazos\AlteraPrazo::class,
        commands\Usuarios\VerificaUsuarios::class,
        commands\Reembolso\VerificaValores::class,
        Commands\PesquisaPatrimonial\BoletosPendentes::class,
        Commands\Prazos\PrazoFatal::class,
        Commands\PesquisaPatrimonial\BoletoAguardandoProgramacaoCron::class,
        Commands\Financeiro\BuscaBaixaJuros::class,

    ];

    protected function schedule(Schedule $schedule)
    {

        //Verifica se houve alteração nas pastas para gravar
        $schedule->command('faturamentoautomatico:cron')->dailyAt('07:00', '12:00', '23:00'); 
       
        //Verifica status na Faturamento Temp 
        $schedule->command('faturamentoverificastatus:cron')->dailyAt('18:00');
        
        //Verifica se é o 1ª Dia do mês para gravar os registros na Matrix
        $schedule->command('faturamentoiniciomes:cron')->monthly();

        //Verificação dos boletos pagos na data de hoje
        $schedule->command('pesquisapatrimonial:cron')->hourly();

        //Verificação diaria 23:00 de todos os boletos que ainda não foram pagos e foram gerados na data de hoje
        $schedule->command('pesquisapatrimonial_envioboleto:cron')->dailyAt('08:00');

        //Verificação diaria de todos os debitos abertos da pesquisa patrimonial e envia para o Financeiro
        $schedule->command('pesquisapatrimonial_debitosaberto:cron')->dailyAt('12:00');

        //Verificação diaria as 09:00, 12:00, 14:00 e 18:00 de todos os usuarios cadastrados no Advwin na data de hoje.
        $schedule->command('usuarios_novosusuarios:cron')->dailyAt('09:00', '12:00', '14:00', '18:00'); 

        //Verifica todos os dias 12:00 se tem solicitação pendente da Revisão Tecnica no projeto: Correspondente
        $schedule->command('correspondente_alertaprazo:cron')->dailyAt('12:00');
        
        //Busco todas os Advs. que não acessam login a mais de 15 dias buscando as pastas e prazos 
        $schedule->command('usuarios_verifica:cron')->dailyAt('00:00');

        //Verificação a cada 5 minutos de todos os prazos na tabela que tenham sidos alterados e Status = 1
        // $schedule->command('prazos_alteraprazo:cron')->everyFiveMinutes();

        $schedule->command('reembolso_verificavalores:cron')->dailyAt('01:00');

        $schedule->command('pesquisapatrimonial_boletospendentes:cron')->dailyAt('17:00');
        $schedule->command('pesquisapatrimonial_boletosaguardandoprogramacao:cron')->dailyAt('17:00');


        $schedule->command('prazofatal_envianotificacao:cron')->dailyAt('05:00','16:00'); 

        $schedule->command('financeiro_buscabaixasjuros:cron')->dailyAt('05:00','18:00'); 

    }

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
