<?php

namespace App\Console\Commands\Correspondente;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Correspondente\AlertaPrazoMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlertaPrazo extends Command
{

    protected $signature = 'correspondente_alertaprazo:cron';
    protected $description = 'Verifica as 00:00 de todos os dias, se existe registro pendente há mais de 3 dias para a Revisão Tecnica';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $diasatras = date('Y-m-d', strtotime('-3 days'));

        $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
                ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('dbo.users', 'PLCFULL.dbo.Jurid_Advogado.Codigo', 'dbo.users.cpf')
                ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite', 
                         'PLCFULL.dbo.Jurid_Debite.Setor',
                         'dbo.users.email as E_mail')
                ->where('dbo.Jurid_Situacao_Ficha.status_id','=','6')
                ->whereDate('PLCFULL.dbo.Jurid_Debite.Data', '<', $diasatras)
                ->get();
        
        foreach($datas as $index) {

            $numerodebite = $index->NumeroDebite;
            $setor = $index->Setor;
            $advogadosolicitante_email = $index->E_mail;

            $datas = DB::table('PLCFULL.dbo.Jurid_Debite')
            ->leftjoin('dbo.Jurid_Situacao_Ficha','PLCFULL.dbo.Jurid_Debite.Numero','=','dbo.Jurid_Situacao_Ficha.numerodebite')
            ->leftjoin('dbo.Jurid_Nota_Tiposervico', 'dbo.Jurid_Situacao_Ficha.tiposervico_id', '=', 'dbo.Jurid_Nota_Tiposervico.id')
            ->leftjoin('PLCFULL.dbo.Jurid_Advogado','PLCFULL.dbo.Jurid_Debite.Advogado','=','PLCFULL.dbo.Jurid_Advogado.Codigo')
            ->leftjoin('dbo.users','PLCFULL.dbo.Jurid_Debite.AdvServ','=','dbo.users.cpf')
            ->select('PLCFULL.dbo.Jurid_Debite.Numero as NumeroDebite',
                  'PLCFULL.dbo.Jurid_Debite.Pasta',
                  DB::raw('CAST(PLCFULL.dbo.Jurid_Debite.Data AS Date) as DataFicha'),
                  DB::raw('CAST(dbo.Jurid_Situacao_Ficha.data_servico AS Date) as DataServico'), 
                  DB::raw('CAST(ValorT AS NUMERIC(15,2)) as ValorTotal'), 
                 'PLCFULL.dbo.Jurid_Debite.Setor as Setor',
                 'PLCFULL.dbo.Jurid_Advogado.Nome as Solicitante',
                 'dbo.Jurid_Nota_Tiposervico.descricao as Tiposervico',
                 'dbo.users.name')
          ->where('dbo.Jurid_Situacao_Ficha.status_id','=','6')
          ->where('PLCFULL.dbo.Jurid_Debite.Numero', $numerodebite)
          ->get();

            $coordenadores = DB::table('dbo.users')
            ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
            ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
            ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
            ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
            ->select('dbo.users.id', 'dbo.users.email') 
            ->where('dbo.profiles.id', '=', '20')
            ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
            ->get();     
            
            //Loop para enviar a todos Coodenador/SubCoodenador do setor em CC para o Gumercindo/Douglas/Danielle
            foreach ($coordenadores as $data) {
     
             $coordenador_id = $data->id;
             $coordenador_email = $data->email;
     
             if($advogadosolicitante_email != null) {
             Mail::to($coordenador_email)
             ->cc($advogadosolicitante_email, 'gumercindo.ribeiro@plcadvogados.com.br', 'douglas.figueiredo@plcadvogados.com.br', 'daniele.oliveira@plcadvogados.com.br')
             ->send(new AlertaPrazoMail($datas));
             } else {
             Mail::to($coordenador_email)
             ->cc('gumercindo.ribeiro@plcadvogados.com.br', 'douglas.figueiredo@plcadvogados.com.br', 'daniele.oliveira@plcadvogados.com.br')
             ->send(new AlertaPrazoMail($datas));
             }
           
            }

            
            
        }    


}


}
