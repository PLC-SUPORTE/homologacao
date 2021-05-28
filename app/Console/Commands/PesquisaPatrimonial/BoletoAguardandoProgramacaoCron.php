<?php

namespace App\Console\Commands\PesquisaPatrimonial;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PesquisaPatrimonial\BoletosAguardandoProgramacaoEmail;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;


class BoletoAguardandoProgramacaoCron extends Command {


    protected $signature = 'pesquisapatrimonial_boletosaguardandoprogramacao:cron';


    protected $description = 'Verificação diaria ás 17:00 de todos os boletos gerados aguardando programação do núcleo referente a pesquisa patrimonial que serão enviados ao solicitante/advogado e ao cliente.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();

    //Pego os Cliente que tiverem boletos gerados na Data Hoje
    
    $datas = DB::table('PLCFULL.dbo.Jurid_Boleto')
    ->select('PLCFULL.dbo.Jurid_Boleto.nosso_numero as NossoNumero',
             'PLCFULL.dbo.Jurid_Boleto.codigo_boleto as NumeroDocumento',
             'PLCFULL.dbo.Jurid_Boleto.num_doc as CPR',
             'PLCFULL.dbo.Jurid_Boleto.data_vencimento as DataVencimento',
             'PLCFULL.dbo.Jurid_Boleto.valor_boleto as Valor',
             'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo as Remessa',
             'PLCFULL.dbo.Jurid_CliFor.Razao as ClienteRazao', 
             'PLCFULL.dbo.Jurid_CliFor.Codigo as ClienteCodigo',
             )
    ->join('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Boleto.codigo_cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->join('dbo.PesquisaPatrimonial_Matrix', 'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', '=','dbo.PesquisaPatrimonial_Matrix.codigo_boleto')
    ->leftjoin('PLCFULL.dbo.Jurid_Boleto_Remessa_Lista', 'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', 'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo_boleto')
    ->whereIn('dbo.PesquisaPatrimonial_Matrix.status_id', array(21))
    ->orderBy('PLCFULL.dbo.Jurid_CliFor.Razao', 'asc')  
    ->groupby('PLCFULL.dbo.Jurid_Boleto.nosso_numero', 
              'PLCFULL.dbo.Jurid_Boleto.codigo_boleto', 
              'PLCFULL.dbo.Jurid_Boleto.num_doc',
              'PLCFULL.dbo.Jurid_Boleto.data_vencimento',
              'PLCFULL.dbo.Jurid_Boleto.valor_boleto',
              'PLCFULL.dbo.Jurid_Boleto_Remessa_Lista.codigo',
              'PLCFULL.dbo.Jurid_CliFor.Razao',
              'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->get();  
    
   if($datas->count() != 0) {

    Mail::to('vanessa.ferreira@plcadvogados.com.br')
    ->cc('felipe.rocha@especialistaresultados.com.br','ronaldo.ferreira@plcadvogados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br')
    ->send(new BoletosAguardandoProgramacaoEmail($datas));
   
   }


    }
}
