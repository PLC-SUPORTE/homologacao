<?php

namespace App\Console\Commands\Usuarios;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Profile;
use App\Mail\Usuario\LembreteDesativacao;
use App\Mail\Usuario\UsuarioDesativado;
use App\Mail\Usuario\UsuarioPossuiDados;
use App\Mail\Usuario\UsuarioDesativadoApenasPortal;
use Excel;
use Illuminate\Support\Facades\Storage;

class VerificaUsuarios extends Command {


    protected $signature = 'usuarios_verifica:cron';


    protected $description = 'Verificação diaria as 00:00 de todos os usuarios cadastrados no Advwin que não entra a mais de 15 dias.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');
    $quinzedias = date('Y-m-d', strtotime('-15 days'));


    //Pego todos os usuarios que não acessam a mais de 15 dias
    $verifica = DB::select( DB::raw("select s.codigo,s.CPFCGC as cpf from PLCFULL.dbo.Jurid_Sistema s 
    INNER join PLCFULL.dbo.Jurid_Advogado adv on adv.Codigo = s.CPFCGC
    INNER join PLCFULL.dbo.Jurid_GrupoAdvogado grupoadv on grupoadv.Codigo = adv.GrupoAdv
    where s.Codigo not in ( select usuario from PLCFULL.dbo.Jurid_Audit where 
    dateadd(dd,datediff(dd,0,HoraServidor),0) >= (dateadd(dd,datediff(dd,0,GETDATE()),0) -15)
    and Arquivo='Log'     and Operacao='Logon' 
    )
     
    and adv.status ='Ativo'
    and grupoadv.Codigo <> 1
    and adv.Setor not in ('2.2.3.1', '9.1.3', '2.2.3.', '2.2.3.2')
    group by s.codigo,s.CPFCGC
    "));

    if($verifica != null) {

                //Gera Excel Usuario
                $customer_arrayusuarios[] = array(
                    'Nome',
                    'CPF',
                    'DataCadastro', 
                    'Setor',
                    'Unidade',
                    'GrupoAdvogado',
                    'Endereco',
                    'Bairro',
                    'Cidade',
                    'UF',
                    'Status',
                    'AdvwinAcesso',
                    'UltimoAcessoAdvwin',
                    'PortalAcesso',
                    'UltimoAcessoPortal');

                 //Grava no Excel das Pastas
                $customer_arraypastas[] = array(
                        'Nome',
                        'CPF',
                        'Codigo_Comp', 
                        'Cliente',
                        'Status',
                        'Unidade',
                        'Setor',
                        'Classificacao',
                        'NumeroProcesso');

                //Grava no Excel dos prazos
                $customer_arrayprazos[] = array(
                        'Solicitante',
                        'AdvogadoPara',
                        'CPF', 
                        'Pasta',
                        'NumeroProcesso',
                        'Setor',
                        'Unidade',
                        'Ident',
                        'Mov',
                        'TipoMovimentacao',
                        'DataSolicitacao',
                        'DataPrazo',
                        'DataPrazoFatal',
                        'DataFechamento');

        foreach($verifica as $index) {

            $usuario = $index->codigo;
            $cpf = $index->cpf;

            //Verifico no Portal qual foi a ultima vez que acessou
            $verificaportal = DB::table('dbo.TI_Usuarios_Auditoria')
            ->select('dbo.users.id', 'dbo.users.name', 'dbo.users.email', 'dbo.users.cpf', 'dbo.TI_Usuarios_Auditoria.alerta')
            ->join('dbo.users', 'dbo.TI_Usuarios_Auditoria.user_id', 'dbo.users.id')
            ->whereDate('dbo.TI_Usuarios_Auditoria.data', '<=', $quinzedias)
            ->where('dbo.users.status', 'Ativo')
            ->where('dbo.users.cpf', $cpf)
        
            ->orwherenull('dbo.TI_Usuarios_Auditoria.data')
            ->where('dbo.users.status', 'Ativo')
            ->where('dbo.users.cpf', $cpf)
            ->first();

            $ultimoacessoadvwin = DB::table('PLCFULL.dbo.Jurid_Audit')
                                  ->select('Hora')
                                  ->where('Usuario', $usuario)
                                  ->where('Arquivo', 'Log')
                                  ->where('Operacao', 'Logon')
                                  ->orderby('Hora', 'desc')
                                  ->value('Hora'); 

            $ultimoacessoportal = DB::table('dbo.TI_Usuarios_Auditoria')->select('data')->join('dbo.users', 'dbo.TI_Usuarios_Auditoria.user_id', 'dbo.users.id')->where('dbo.users.cpf', $cpf)->value('data');                           

            //Verifico se ele não entra no portal a mais de 15 dias também
            if($verificaportal != null) {

                $user_id = $verificaportal->id;
                $name = $verificaportal->name;
                $email = $verificaportal->email;
                $alerta = $verificaportal->alerta;

                $dadosusuario = DB::table('PLCFULL.dbo.Jurid_Advogado')
                ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                         'PLCFULL.dbo.Jurid_Advogado.Dt_Cad as DataCadastro',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as GrupoAdvogado',
                         'PLCFULL.dbo.Jurid_Advogado.Endereco',
                         'PLCFULL.dbo.Jurid_Advogado.Bairro',
                         'PLCFULL.dbo.Jurid_Advogado.Cidade',
                         'PLCFULL.dbo.Jurid_Advogado.UF',
                         'PLCFULL.dbo.Jurid_Advogado.Status')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'PLCFULL.dbo.Jurid_Advogado.GrupoAdv', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
                ->where('PLCFULL.dbo.Jurid_Advogado.Codigo', $cpf)
                ->get();

                if($ultimoacessoadvwin != null && $ultimoacessoportal != null) {
                        
                        foreach($dadosusuario as $customer) {
                                $customer_arrayusuarios[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'DataCadastro' => date('d/m/Y', strtotime($customer->DataCadastro)),
                                        'Setor' => $customer->Setor,
                                        'Unidade' => $customer->Unidade,
                                        'GrupoAdvogado' => $customer->GrupoAdvogado,
                                        'Endereco' => $customer->Endereco,
                                        'Bairro' => $customer->Bairro,
                                        'Cidade' => $customer->Cidade,
                                        'UF' => $customer->UF,
                                        'Status' => $customer->Status,
                                        'AdvwinAcesso' => 'Não',
                                        'UltimoAcessoAdvwin' => date('d/m/Y', strtotime($ultimoacessoadvwin)),
                                        'PortalAcesso' => 'Não',
                                        'UltimoAcessoPortal' => date('d/m/Y', strtotime($ultimoacessoportal)),
                                );
                        }
                 } elseif($ultimoacessoadvwin != null) {
                        foreach($dadosusuario as $customer) {
                                $customer_arrayusuarios[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'DataCadastro' => date('d/m/Y', strtotime($customer->DataCadastro)),
                                        'Setor' => $customer->Setor,
                                        'Unidade' => $customer->Unidade,
                                        'GrupoAdvogado' => $customer->GrupoAdvogado,
                                        'Endereco' => $customer->Endereco,
                                        'Bairro' => $customer->Bairro,
                                        'Cidade' => $customer->Cidade,
                                        'UF' => $customer->UF,
                                        'Status' => $customer->Status,
                                        'AdvwinAcesso' => 'Não',
                                        'UltimoAcessoAdvwin' => date('d/m/Y', strtotime($ultimoacessoadvwin)),
                                        'PortalAcesso' => 'Não',
                                        'UltimoAcessoPortal' => 'Não acessou.',
                                );
                        }

                 } else {
                        foreach($dadosusuario as $customer) {
                                $customer_arrayusuarios[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'DataCadastro' => date('d/m/Y', strtotime($customer->DataCadastro)),
                                        'Setor' => $customer->Setor,
                                        'Unidade' => $customer->Unidade,
                                        'GrupoAdvogado' => $customer->GrupoAdvogado,
                                        'Endereco' => $customer->Endereco,
                                        'Bairro' => $customer->Bairro,
                                        'Cidade' => $customer->Cidade,
                                        'UF' => $customer->UF,
                                        'Status' => $customer->Status,
                                        'AdvwinAcesso' => 'Não',
                                        'UltimoAcessoAdvwin' => 'Não acessou.',
                                        'PortalAcesso' => 'Não',
                                        'UltimoAcessoPortal' => 'Não acessou.',
                                );
                        }
                 }
                 //Fim Gravar Excel Usuario


                //Busca as pastas e prazos 
                $pastas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                         'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                         'PLCFULL.dbo.Jurid_Pastas.Status',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Pastas.IDForm',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Pastas.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->where('PLCFULL.dbo.Jurid_Pastas.Advogado', $cpf)
                ->get();

                $prazos = DB::table('PLCFULL.dbo.Jurid_agenda_table')
                ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as AdvogadoPara',
                         'PLCFULL.dbo.Jurid_Outra_Parte.Descricao as Solicitante',
                         'PLCFULL.dbo.Jurid_agenda_table.Pasta',
                         'PLCFULL.dbo.Jurid_agenda_table.Ident',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_agenda_table.Mov',
                         'PLCFULL.dbo.Jurid_CodMov.Descricao as TipoMovimentacao',
                         'PLCFULL.dbo.Jurid_agenda_table.Data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_agenda_table.Data_Prazo as DataPrazo',
                         'PLCFULL.dbo.Jurid_agenda_table.Data_prazoFim as DataPrazoFatal',
                         'PLCFULL.dbo.Jurid_agenda_table.Data_Fech as DataFechamento',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_agenda_table.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_agenda_table.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_agenda_table.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'PLCFULL.dbo.Jurid_agenda_table.Advogado_or', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_CodMov', 'PLCFULL.dbo.Jurid_agenda_table.CodMov', 'PLCFULL.dbo.Jurid_CodMov.Codigo')
                ->where('PLCFULL.dbo.Jurid_agenda_table.Advogado', $cpf)
                ->where('PLCFULL.dbo.Jurid_agenda_table.Status', '!=', '1')
                ->get();

           
                foreach($pastas as $customer) {
                        if($customer->IDForm == 0) {

                        } elseif($customer->IDForm == 3) {
                               $customer_arraypastas[] = array(
                                       'Nome'  => $customer->Nome,
                                       'CPF'  => $customer->CPF,
                                       'Codigo_Comp'  => $customer->Codigo_Comp,
                                       'Cliente' => $customer->Cliente,
                                       'Status' => $customer->Status,
                                       'Unidade' => $customer->Unidade,
                                       'Setor' => $customer->Setor,
                                       'Classificacao' => 'Contencioso',
                                       'NumeroProcesso' => $customer->NumeroProcesso,
                               );
                        }
                        $customer_arraypastas[] = array(
                                'Nome'  => $customer->Nome,
                                'CPF'  => $customer->CPF,
                                'Codigo_Comp'  => $customer->Codigo_Comp,
                                'Cliente' => $customer->Cliente,
                                'Status' => $customer->Status,
                                'Unidade' => $customer->Unidade,
                                'Setor' => $customer->Setor,
                                'Classificacao' => 'Consultivo',
                                'NumeroProcesso' => $customer->NumeroProcesso,
                        );
                }
                //Fim Gravar Excel das Pastas

           
                 foreach($prazos as $customer) {
                         $customer_arrayprazos[] = array(
                                 'Solicitante' => $customer->Solicitante,
                                 'Nome'  => $customer->AdvogadoPara,
                                 'CPF'  => $customer->CPF,
                                 'Pasta'  => $customer->Pasta,
                                 'NumeroProcesso' => $customer->NumeroProcesso,
                                 'Setor' => $customer->Setor,
                                 'Unidade' => $customer->Unidade,
                                 'Ident' => $customer->Ident,
                                 'Mov' => $customer->Mov,
                                 'TipoMovimentacao' => $customer->TipoMovimentacao,
                                 'DataSolicitacao' => date('d/m/Y', strtotime($customer->DataSolicitacao)),
                                 'DataPrazo' => date('d/m/Y', strtotime($customer->DataPrazo)),
                                 'DataPrazoFatal' => date('d/m/Y', strtotime($customer->DataPrazoFatal)),
                                 'DataFechamento' => date('d/m/Y', strtotime($customer->DataFechamento)),
                         );
                 }
                 //Fim Grava no Excel Prazos

                
            }
            //Se só não entra no Advwin
            else {

                $dadosusuario = DB::table('PLCFULL.dbo.Jurid_Advogado')
                ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                         'PLCFULL.dbo.Jurid_Advogado.Dt_Cad as DataCadastro',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_GrupoAdvogado.Descricao as GrupoAdvogado',
                         'PLCFULL.dbo.Jurid_Advogado.Endereco',
                         'PLCFULL.dbo.Jurid_Advogado.Bairro',
                         'PLCFULL.dbo.Jurid_Advogado.Cidade',
                         'PLCFULL.dbo.Jurid_Advogado.UF',
                         'PLCFULL.dbo.Jurid_Advogado.Status')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Advogado.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Setor.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_GrupoAdvogado', 'PLCFULL.dbo.Jurid_Advogado.GrupoAdv', 'PLCFULL.dbo.Jurid_GrupoAdvogado.Codigo')
                ->where('PLCFULL.dbo.Jurid_Advogado.Codigo', $cpf)
                ->get();

                if($ultimoacessoadvwin != null && $ultimoacessoportal != null) {
                        
                        foreach($dadosusuario as $customer) {
                                $customer_arrayusuarios[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'DataCadastro' => date('d/m/Y', strtotime($customer->DataCadastro)),
                                        'Setor' => $customer->Setor,
                                        'Unidade' => $customer->Unidade,
                                        'GrupoAdvogado' => $customer->GrupoAdvogado,
                                        'Endereco' => $customer->Endereco,
                                        'Bairro' => $customer->Bairro,
                                        'Cidade' => $customer->Cidade,
                                        'UF' => $customer->UF,
                                        'Status' => $customer->Status,
                                        'AdvwinAcesso' => 'Não',
                                        'UltimoAcessoAdvwin' => date('d/m/Y', strtotime($ultimoacessoadvwin)),
                                        'PortalAcesso' => 'Não',
                                        'UltimoAcessoPortal' => date('d/m/Y', strtotime($ultimoacessoportal)),
                                );
                        }
                 } elseif($ultimoacessoadvwin != null) {
                        foreach($dadosusuario as $customer) {
                                $customer_arrayusuarios[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'DataCadastro' => date('d/m/Y', strtotime($customer->DataCadastro)),
                                        'Setor' => $customer->Setor,
                                        'Unidade' => $customer->Unidade,
                                        'GrupoAdvogado' => $customer->GrupoAdvogado,
                                        'Endereco' => $customer->Endereco,
                                        'Bairro' => $customer->Bairro,
                                        'Cidade' => $customer->Cidade,
                                        'UF' => $customer->UF,
                                        'Status' => $customer->Status,
                                        'AdvwinAcesso' => 'Não',
                                        'UltimoAcessoAdvwin' => date('d/m/Y', strtotime($ultimoacessoadvwin)),
                                        'PortalAcesso' => 'Não',
                                        'UltimoAcessoPortal' => 'Não acessou.',
                                );
                        }

                 } else {
                        foreach($dadosusuario as $customer) {
                                $customer_arrayusuarios[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'DataCadastro' => date('d/m/Y', strtotime($customer->DataCadastro)),
                                        'Setor' => $customer->Setor,
                                        'Unidade' => $customer->Unidade,
                                        'GrupoAdvogado' => $customer->GrupoAdvogado,
                                        'Endereco' => $customer->Endereco,
                                        'Bairro' => $customer->Bairro,
                                        'Cidade' => $customer->Cidade,
                                        'UF' => $customer->UF,
                                        'Status' => $customer->Status,
                                        'AdvwinAcesso' => 'Não',
                                        'UltimoAcessoAdvwin' => 'Não acessou.',
                                        'PortalAcesso' => 'Não',
                                        'UltimoAcessoPortal' => 'Não acessou.',
                                );
                        }
                 }

                 //Fim Gravar Excel Usuario


                //Busca as pastas e prazos 
                $pastas = DB::table('PLCFULL.dbo.Jurid_Pastas')
                ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as Nome',
                         'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp',
                         'PLCFULL.dbo.Jurid_CliFor.Razao as Cliente',
                         'PLCFULL.dbo.Jurid_Pastas.Status',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor',
                         'PLCFULL.dbo.Jurid_Pastas.IDForm',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso')
                ->leftjoin('PLCFULL.dbo.Jurid_CliFor', 'PLCFULL.dbo.Jurid_Pastas.Cliente', 'PLCFULL.dbo.Jurid_CliFor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_Pastas.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_Pastas.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->where('PLCFULL.dbo.Jurid_Pastas.Advogado', $cpf)
                ->get();

                $prazos = DB::table('PLCFULL.dbo.Jurid_agenda_table')
                ->select('PLCFULL.dbo.Jurid_Advogado.Codigo as CPF',
                         'PLCFULL.dbo.Jurid_Advogado.Nome as AdvogadoPara',
                         'PLCFULL.dbo.Jurid_Outra_Parte.Descricao as Solicitante',
                         'PLCFULL.dbo.Jurid_agenda_table.Pasta',
                         'PLCFULL.dbo.Jurid_agenda_table.Ident',
                         'PLCFULL.dbo.Jurid_Pastas.NumPrc1_Sonumeros as NumeroProcesso',
                         'PLCFULL.dbo.Jurid_agenda_table.Mov',
                         'PLCFULL.dbo.Jurid_CodMov.Descricao as TipoMovimentacao',
                         'PLCFULL.dbo.Jurid_agenda_table.Data as DataSolicitacao',
                         'PLCFULL.dbo.Jurid_agenda_table.Data_Prazo as DataPrazo',
                         'PLCFULL.dbo.Jurid_agenda_table.Data_prazoFim as DataPrazoFatal',
                         'PLCFULL.dbo.Jurid_agenda_table.Data_Fech as DataFechamento',
                         'PLCFULL.dbo.Jurid_Unidade.Descricao as Unidade',
                         'PLCFULL.dbo.Jurid_Setor.Descricao as Setor')
                ->leftjoin('PLCFULL.dbo.Jurid_Unidade', 'PLCFULL.dbo.Jurid_agenda_table.Unidade', 'PLCFULL.dbo.Jurid_Unidade.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Advogado', 'PLCFULL.dbo.Jurid_agenda_table.Advogado', 'PLCFULL.dbo.Jurid_Advogado.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Pastas', 'PLCFULL.dbo.Jurid_agenda_table.Pasta', 'PLCFULL.dbo.Jurid_Pastas.Codigo_Comp')
                ->leftjoin('PLCFULL.dbo.Jurid_Setor', 'PLCFULL.dbo.Jurid_Pastas.Setor', 'PLCFULL.dbo.Jurid_Setor.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_Outra_Parte', 'PLCFULL.dbo.Jurid_agenda_table.Advogado_or', 'PLCFULL.dbo.Jurid_Outra_Parte.Codigo')
                ->leftjoin('PLCFULL.dbo.Jurid_CodMov', 'PLCFULL.dbo.Jurid_agenda_table.CodMov', 'PLCFULL.dbo.Jurid_CodMov.Codigo')
                ->where('PLCFULL.dbo.Jurid_agenda_table.Advogado', $cpf)
                ->where('PLCFULL.dbo.Jurid_agenda_table.Status', '!=', '1')
                ->get();

           
                 foreach($pastas as $customer) {
                         if($customer->IDForm == 0) {

                         } elseif($customer->IDForm == 3) {
                                $customer_arraypastas[] = array(
                                        'Nome'  => $customer->Nome,
                                        'CPF'  => $customer->CPF,
                                        'Codigo_Comp'  => $customer->Codigo_Comp,
                                        'Cliente' => $customer->Cliente,
                                        'Status' => $customer->Status,
                                        'Unidade' => $customer->Unidade,
                                        'Setor' => $customer->Setor,
                                        'Classificacao' => 'Contencioso',
                                        'NumeroProcesso' => $customer->NumeroProcesso,
                                );
                         }
                         $customer_arraypastas[] = array(
                                 'Nome'  => $customer->Nome,
                                 'CPF'  => $customer->CPF,
                                 'Codigo_Comp'  => $customer->Codigo_Comp,
                                 'Cliente' => $customer->Cliente,
                                 'Status' => $customer->Status,
                                 'Unidade' => $customer->Unidade,
                                 'Setor' => $customer->Setor,
                                 'Classificacao' => 'Consultivo',
                                 'NumeroProcesso' => $customer->NumeroProcesso,
                         );
                 }
                 //Fim Gravar Excel das Pastas

                
                 foreach($prazos as $customer) {
                         $customer_arrayprazos[] = array(
                                 'Solicitante' => $customer->Solicitante,
                                 'Nome'  => $customer->AdvogadoPara,
                                 'CPF'  => $customer->CPF,
                                 'Pasta'  => $customer->Pasta,
                                 'NumeroProcesso' => $customer->NumeroProcesso,
                                 'Setor' => $customer->Setor,
                                 'Unidade' => $customer->Unidade,
                                 'Ident' => $customer->Ident,
                                 'Mov' => $customer->Mov,
                                 'TipoMovimentacao' => $customer->TipoMovimentacao,
                                 'DataSolicitacao' => date('d/m/Y', strtotime($customer->DataSolicitacao)),
                                 'DataPrazo' => date('d/m/Y', strtotime($customer->DataPrazo)),
                                 'DataPrazoFatal' => date('d/m/Y', strtotime($customer->DataPrazoFatal)),
                                 'DataFechamento' => date('d/m/Y', strtotime($customer->DataFechamento)),
                         );


            }

        }


        }
        //Fim Foreach


    }

    //Deleta os arquivos atuais
    unlink(storage_path('excel/exports/usuarios.xlsx'));
    unlink(storage_path('excel/exports/pastas.xlsx'));
    unlink(storage_path('excel/exports/prazos.xlsx'));

    
    //Gera o Excel
    ini_set('memory_limit','-1'); 
    Excel::create('usuarios', function($excel) use ($customer_arrayusuarios){
            $excel->setTitle('Usuarios');
            $excel->sheet('Usuarios', function($sheet) use ($customer_arrayusuarios) {
            $sheet->fromArray($customer_arrayusuarios, null, 'A1', false, false);
    });
    })->save('xlsx', storage_path('excel/exports'), true);

    Excel::create('pastas', function($excel) use ($customer_arraypastas){
            $excel->setTitle('Pastas em aberto');
            $excel->sheet('Pastas em aberto', function($sheet) use ($customer_arraypastas) {
            $sheet->fromArray($customer_arraypastas, null, 'A1', false, false);
    });
    })->save('xlsx', storage_path('excel/exports'), true);

    Excel::create('prazos', function($excel) use ($customer_arrayprazos){
            $excel->setTitle('Prazos em aberto');
            $excel->sheet('Prazos em aberto', function($sheet) use ($customer_arrayprazos) {
            $sheet->fromArray($customer_arrayprazos, null, 'A1', false, false);
    });
    })->save('xlsx', storage_path('excel/exports'), true);
   
//     Mail::to('gumercindo.ribeiro@plcadvogados.com.br')
//     ->cc('ronaldo.amaral@plcadvogados.com.br')
//     ->send(new UsuarioPossuiDados());

    Mail::to('ronaldo.amaral@plcadvogados.com.br')
    ->send(new UsuarioPossuiDados());
    }

}

