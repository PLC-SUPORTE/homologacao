<?php

namespace App\Console\Commands\PesquisaPatrimonial;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PesquisaPatrimonial\DebitosPagos;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;


class DebitosAberto extends Command {


    protected $signature = 'pesquisapatrimonial_debitosaberto:cron';


    protected $description = 'Verificação diaria ás 00:00 de todos os debitos abertos da pesquisa patrimonial ate a data de hoje.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();

    //Pega todos os debitos abertos
    $datas = DB::table('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados')
    ->join('PLCFULL.dbo.Jurid_Debite', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.numerodebite', '=', 'PLCFULL.dbo.Jurid_Debite.Numero')
    ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Debite.Cliente', '=', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
    ->leftjoin('PLCFULL.dbo.Jurid_TipoDebite', 'PLCFULL.dbo.Jurid_Debite.Tipodeb', '=', 'PLCFULL.dbo.Jurid_TipoDebite.Codigo')
    ->leftjoin('dbo.PesquisaPatrimonial_Servicos_UF', 'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', '=', 'dbo.PesquisaPatrimonial_Servicos_UF.id')
    ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_matrix as NumeroSolicitacao',
             'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
             'dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.data as Data',
             'PLCFULL.dbo.Jurid_Debite.Pasta as Pasta',
             'PLCFULL.dbo.Jurid_TipoDebite.Descricao as TipoDebite',
             'dbo.PesquisaPatrimonial_Servicos_UF.descricao as TipoSolicitacao',
             DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.ValorT AS NUMERIC(15,2)) as Valor'),
    )
    ->where('PLCFULL.dbo.Jurid_Debite.Fatura', '=', '')
    ->where('PLCFULL.dbo.Jurid_Debite.datapag', '=', null)
    ->where('PLCFULL.dbo.Jurid_Debite.DebPago', '=', 'N')
    ->where('dbo.PesquisaPatrimonial_Solicitacao_ServicosSolicitados.id_tiposolicitacao', '!=', '13')
    ->get();

   //Verifica se existe registros
   if($datas->count() == 0) {

    //Grava na Hist informando que foi executado com sucesso mas não obteve registros
    $date = date('Y-m-d H:i', strtotime('+1 Hours'));
    $id_matrix = 4;
    $observacao = 'Comando executado com sucesso, mas não teve registros.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

    } 
    else {

  
    Mail::to('daniele.oliveira@plcadvogados.com.br')
      ->cc('roberta.povoa@plcadvogados.com.br')
      ->send(new DebitosPagos($datas));

    
    //Grava na Hist informando que foi executado com sucesso e encontrou registros
    $date = date('Y-m-d H:i', strtotime('+1 Hours'));
    $id_matrix = 4;
    $observacao = 'Comando executado com sucesso, e registros enviados via email.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

   }


   }



}
