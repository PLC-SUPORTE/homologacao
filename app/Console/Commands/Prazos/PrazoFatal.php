<?php

namespace App\Console\Commands\Prazos;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Profile;
use App\Mail\Prazos\PrazoFatalMail;
use App\Mail\Prazos\PrazoFatalNA;



class PrazoFatal extends Command {


    protected $signature = 'prazofatal_envianotificacao:cron';


    protected $description = 'Verificação a cada 3 horas dos prazos fatais pra hoje.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d H:i:s');

    //Pego todos os os usuarios que possuem prazo fatal na data de hoje e agrupo

    $verifica = DB::select( DB::raw("
    Select
    a.Advogado
    From PLCFULL.dbo.jurid_agenda_table a

    Where
    
    dateadd(dd,datediff(dd,0,a.prazo_fatal),0) = (dateadd(dd,datediff(dd,0,GETDATE()),0) -0)

    and a.status not in ('1')
    
    GROUP BY a.Advogado"));

     
   if($verifica != null) {

    foreach($verifica as $index) {


        $solicitante_codigo = $index->Advogado;

        $datas = DB::select( DB::raw("
        Select
        a.ident,
        convert(varchar,a.data_prazo,103) as 'DataPrazo',
        convert(varchar,a.prazo_fatal,103) as 'DataPrazoFatal',
        adv.nome as 'AdvogadoPara',
        adv1.nome as 'Solicitante',
        PLCFULL.dbo.RetornaProcessoAtual(p.codigo_comp) as 'NumeroProcesso',
        p.codigo_comp as 'Pastas',
        c.nome as 'Cliente',
        p.outraparte as 'Outra Parte',
        tp.Descricao as 'Tipo de Processo',
        p.esfera as 'Esfera',
        u.descricao as 'Unidade',
        s.descricao as 'Setor de Custo',
        v.descricao as 'Vara',
        t.descricao as 'Local',
        i.Descicao as 'Instância',
        p.comarca as 'Comarca',
        p.uf as 'UF',
        cm.descricao as 'TipoMovimentação',
        cast(a.obs as varchar(8000)) as 'Observação',
        case
        When a.status = 0 then 'Ativo'
        Else 'A confirmar'
        End as 'Status',
        convert(varchar,a.data_prazo,108) as 'Hora do Prazo',
        CONVERT(DATETIME,a.data,103) as 'Data de Inserção',
        a.ident as 'Número da OS',
        Case
        when p.PrcEstrategico = 1 then 'Sim'
        Else 'Não'
        End as 'Processo Estratégico',
        adv2.nome as [Com Cópia Para]
        
        From PLCFULL.dbo.jurid_agenda_table a
        left outer join PLCFULL.dbo.jurid_pastas p on a.pasta = p.codigo_comp
        left outer join PLCFULL.dbo.jurid_clifor c on p.cliente = c.codigo
        left outer join PLCFULL.dbo.jurid_varas v on p.varas = v.codigo
        left outer join PLCFULL.dbo.jurid_tribunais t on p.tribunal = t.codigo
        left outer join PLCFULL.dbo.jurid_setor s on p.setor = s.codigo
        left outer join PLCFULL.dbo.jurid_advogado adv on a.advogado = adv.codigo
        left outer join PLCFULL.dbo.jurid_advogado adv1 on a.advogado_or = adv1.codigo
        left outer join PLCFULL.dbo.jurid_codmov cm on a.codmov = cm.codigo
        left outer join PLCFULL.dbo.jurid_unidade u on a.unidade = u.codigo
        left outer join PLCFULL.dbo.jurid_distribuicao d on p.codigo_comp=d.codigo_comp and d.d_atual =1
        left outer join PLCFULL.dbo.jurid_tipovara tv on tv.codigo=v.tipo
        left outer join PLCFULL.dbo.Jurid_TipoP tp on p.TipoP = tp.Codigo
        left outer join PLCFULL.dbo.Jurid_instancia i on p.instancia = i.codigo
        left outer join PLCFULL.dbo.Jurid_Copia_Prazo cp ON a.ident = cp.Ident_Agenda
        left outer join PLCFULL.dbo.Jurid_advogado adv2 ON cp.Advogado = adv2.codigo
        Where
        
        dateadd(dd,datediff(dd,0,a.prazo_fatal),0) = (dateadd(dd,datediff(dd,0,GETDATE()),0) -0)

        and a.advogado like '%$solicitante_codigo%'
        and a.CodMov not in('MOV001','MOV002','MOV015','MOV223','MOV202','MOV201','MOV213','MOV206','MOV212','MOV205','MOV207','MOV210','MOV214',
        'MOV216','MOV209','MOV211','MOV215','MOV208','MOV055','MOV043','MOV046','MOV156','MOV215','NA05',
        'NA38','MOV081','MOV144','MOV116','MOV285','MOV126')
        and a.status not in ('1')"));


        //Pego o Coordenador do setor
        $setor = DB::table('PLCFULL.dbo.Jurid_Advogado')->select('Setor')->where('Codigo','=', $solicitante_codigo)->value('Setor');
        $solicitante_email = DB::table('dbo.users')->select('email')->where('cpf','=', $solicitante_codigo)->value('email');

        $coordenadores = DB::table('dbo.users')
        ->leftjoin('dbo.setor_custo_user', 'dbo.users.id', '=', 'dbo.setor_custo_user.user_id')
        ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'dbo.setor_custo_user.setor_custo_id', '=', 'PLCFULL.dbo.Jurid_Setor.Id')
        ->leftjoin('dbo.profile_user', 'dbo.users.id', '=', 'dbo.profile_user.user_id')
        ->leftjoin('dbo.profiles', 'dbo.profile_user.profile_id', '=', 'dbo.profiles.id')
        ->select('dbo.users.id', 'dbo.users.email') 
        ->where('dbo.profiles.id', '=', '20')
        ->where('PLCFULL.dbo.Jurid_Setor.Codigo','=',$setor)
        ->first();     

        if($coordenadores != null) {
            Mail::to($solicitante_email)
            ->cc($coordenadores->email)
            ->send(new PrazoFatalMail($datas));


            Mail::to('gumercindo.ribeiro@plcadvogados.com.br')
            ->cc('alessandra.lopes@plcadvogados.com.br')
            ->send(new PrazoFatalMail($datas));

            
            Mail::to('controladoria@plcadvogados.com.br')
            ->cc('controladoriasp@plcadvogados.com.br')
            ->send(new PrazoFatalMail($datas));
        
        } else {
            Mail::to($solicitante_email)
            ->send(new PrazoFatalMail($datas));

            Mail::to('gumercindo.ribeiro@plcadvogados.com.br')
            ->cc('alessandra.lopes@plcadvogados.com.br')
            ->send(new PrazoFatalMail($datas));

            
            Mail::to('controladoria@plcadvogados.com.br')
            ->cc('controladoriasp@plcadvogados.com.br')
            ->send(new PrazoFatalMail($datas));
        }
        
 
        //Envia e-mail para o Gumercindo,Controladoria,ControladoriaSP,Adv.
   

    }
    //Fim Foreach

    }
    //Se não tiver nada, envia e-mail para a controladoria e gumercindo
    Mail::to('alessandra.lopes@plcadvogados.com.br')
    ->cc('gumercindo.ribeiro@plcadvogados.com.br')
    ->send(new PrazoFatalNA());

    Mail::to('controladoria@plcadvogados.com.br')
    ->cc('controladoriasp@plcadvogados.com.br')
    ->send(new PrazoFatalNA());


}

}

