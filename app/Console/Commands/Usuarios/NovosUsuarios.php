<?php

namespace App\Console\Commands\Usuarios;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Profile;
use App\Mail\NovoUsuarioCron;
use App\Mail\Correspondente\NovoUsuarioCorrespondente;
use App\Mail\NovoUsuarioMarketing;
use App\Mail\Usuario\NovoUsuarioSemEmail;

class NovosUsuarios extends Command {


    protected $signature = 'usuarios_novosusuarios:cron';


    protected $description = 'Verificação diaria as 09:00, 12:00, 14:00 e 18:00 de todos os usuarios cadastrados no Advwin na data de hoje.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    $carbon= Carbon::now();
    $datahoje = $carbon->format('Y-m-d');

    //Pego todos os novos usuarios cadastrados no Advwin na data de hoje, exceto Correspondente
    $verifica = DB::table('PLCFULL.dbo.Jurid_Advogado')
    ->select('Codigo as Codigo',
             'Nome as Name',
             'E_mail as Email',
             'Setor as Setor',
             'Unidade as Unidade',
             'GrupoAdv as GrupoAdvogado',
             'Correspondente',
    )
    ->whereDate('PLCFULL.dbo.Jurid_Advogado.Dt_cad', 'like', '%' . $datahoje . '%')
    ->where('PLCFULL.dbo.Jurid_Advogado.Status', 'Ativo')
    ->get();
    
   if($verifica->count() == 0) {

    //Grava na Hist informando que foi executado com sucesso mas não obteve registros
    $date = date('Y-m-d H:i', strtotime('+4 Hours'));
    $id_matrix = 7;
    $observacao = 'Comando executado com sucesso, mas não teve registros.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

   } else {
    foreach($verifica as $index) {

     $name = $index->Name;
     $setor = $index->Setor;
     $unidade = $index->Unidade;
     $correspondente= $index->Correspondente;

    //  $email = str_replace(' ', '',$index->Email);
     $email = preg_replace("/\s+/", "", $index->Email);

     $codigo = str_replace(' ', '',$index->Codigo);


     $padraoplc = 'plc@'.substr($codigo,-4);
     $senhacriptografada = bcrypt($padraoplc);

     $grupoadvogado = $index->GrupoAdvogado;

     //Verifico se já não esta cadastrado
     $verificacadastro = DB::table('dbo.users')->select('id')->where('cpf', '=', $codigo)->value('id');

     if($verificacadastro == NULL) {

        //Se for da PL&c
        if($correspondente == 0) {

        $values= array('name' => $name,
                      'email' => $email, 
                      'password' => $senhacriptografada, 
                      'cpf' => $codigo, 
                      'created_at' => $carbon, 
                      'updated_at' => $carbon);
        DB::table('dbo.users')->insert($values);

        $userid = DB::table('dbo.users')->select('id')->where('cpf', '=', $codigo)->value('id'); 

       //Se for da PLC, ele verifica se é Coordenador, Controladoria, Financeiro ou Advogado

         //Se for Coordenador
         if($grupoadvogado == 11) {

            $values = array(
                'profile_id' => '20', 
                'user_id' => $userid);
            DB::table('dbo.profile_user')->insert($values); 

         }
         //Se for Controladoria
         else if($grupoadvogado == 19) {

            $values = array(
                'profile_id' => '38', 
                'user_id' => $userid);
            DB::table('dbo.profile_user')->insert($values); 

         }
         //Se for Financeiro
         else if($grupoadvogado == 15) {

            $values = array(
                'profile_id' => '21', 
                'user_id' => $userid);
            DB::table('dbo.profile_user')->insert($values); 

         }
         //Se for Advogado
         else {

            $values = array(
                'profile_id' => '17', 
                'user_id' => $userid);
            DB::table('dbo.profile_user')->insert($values); 
         }

         //Relaciona o usuario da PLC com o setor de custo
         $setorcusto_id = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Id')->where('Codigo', '=', $setor)->value('Id'); 
         $setordescricao = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Descricao')->where('Codigo', '=', $setor)->value('Descricao'); 
         $unidadecodigo = DB::table('PLCFULL.dbo.Jurid_Setor')->select('Unidade')->where('Codigo', '=', $setor)->value('Unidade'); 
         $unidadedescricao = DB::table('PLCFULL.dbo.Jurid_Unidade')->select('Descricao')->where('Codigo', '=', $unidadecodigo)->value('Descricao'); 

         $values = array(
            'setor_custo_id' => $setorcusto_id, 
            'user_id' => $userid);
        DB::table('dbo.setor_custo_user')->insert($values); 

        //Relaciona na web_hierarquia_setor
        $values = array(
            'advogado' => $codigo,
            'setor' => $setor,
            'ativo' => 'S');
        DB::table('dbo.web_hierarquia')->insert($values); 

         //Pego o usuario dele no Advwin
         $usuario = DB::table('PLCFULL.dbo.Jurid_Sistema')->select('Codigo')->where('CPFCGC', '=', $codigo)->value('Codigo'); 


         //Se o e-mail não estiver correto envia para o suporte
         if($email == '.') {

            Mail::to('suporte@plcadvogados.com.br')
            ->cc('suporte.sp@plcadvogados.com.br', 'ronaldo.amaral@plcadvogados.com.br', 'gumercindo.ribeiro@plcadvogados.com.br')
            ->send(new NovoUsuarioSemEmail($name, $usuario, $email, $padraoplc));
         } else {

           //Envia email para o usuario informando do cadastro
           Mail::to($email)
           ->cc('suporte@plcadvogados.com.br', 'suporte.sp@plcadvogados.com.br')
           ->send(new NovoUsuarioCron($name, $usuario, $email, $padraoplc));
         }

         //Envia e-mail para o Marketing 
         Mail::to('marketing@plcadvogados.com.br')
         ->cc('andrea.guimaraes@plcadvogados.com.br','eduardo.araujo@plcadvogados.com.br' ,'suporte@plcadvogados.com.br')
         ->send(new NovoUsuarioMarketing($name, $email, $setordescricao, $unidadedescricao));


        //Manda notificação para o Gumercindo e Isabela Silveira
        $values3= array('data' => $carbon, 'id_ref' => $userid, 'user_id' => '25', 'destino_id' => '1', 'tipo' => '3', 'obs' => 'O usuario: ' .$name .' foi cadastrado no portal pelo CRON.','status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        $values4= array('data' => $carbon, 'id_ref' => $userid, 'user_id' => '25', 'destino_id' => '241', 'tipo' => '3', 'obs' => 'O usuario: ' .$name .' foi cadastrado no portal pelo CRON.','status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

       }
       
       //Se for correspondente
       else {

        $values= array('name' => $name,
                      'email' => $email, 
                      'password' => $senhacriptografada, 
                      'cpf' => $codigo, 
                      'created_at' => $carbon, 
                      'updated_at' => $carbon);
        DB::table('dbo.users')->insert($values);

        $userid = DB::table('dbo.users')->select('id')->where('cpf', '=', $codigo)->value('id'); 

        $values = array(
            'profile_id' => '1', 
            'user_id' => $userid);
        DB::table('dbo.profile_user')->insert($values); 

        //Manda o e-mail de boas vindas copiando Isabella Silveira
        Mail::to($email)
        ->cc('isabela.silveira@plcadvogados.com.br')
        ->send(new NovoUsuarioCorrespondente($name, $email, $padraoplc));

        //Manda notificação para o Gumercindo e Isabela Silveira
        $values3= array('data' => $carbon, 'id_ref' => $userid, 'user_id' => '25', 'destino_id' => '1', 'tipo' => '3', 'obs' => 'O usuario: ' .$name .' foi cadastrado no portal pelo CRON.','status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values3);

        $values4= array('data' => $carbon, 'id_ref' => $userid, 'user_id' => '25', 'destino_id' => '241', 'tipo' => '3', 'obs' => 'O usuario: ' .$name .' foi cadastrado no portal pelo CRON.','status' => 'A');
        DB::table('dbo.Hist_Notificacao')->insert($values4);

       }

    }
    //Se já estiver cadastrado
    }
    //Fim Foreach

}
//Fim 
    //Grava na Hist informando que foi executado com sucesso
    $date = date('Y-m-d H:i', strtotime('+4 Hours'));
    $id_matrix = 7;
    $observacao = 'Comando executado com sucesso, e registros enviados por email.';

    $values = array(
        'id_matrix' => $id_matrix, 
        'data' => $carbon, 
        'proximohorario' => $date,
        'observacao' => $observacao);
    DB::table('dbo.SetorTI_TarefasAgendadas_Hist')->insert($values);

    }
}

