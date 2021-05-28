<?php

namespace App\Mail\Usuario;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsuarioPossuiDados extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct()
    {

    }

   public function build()
   {
       
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Rotina verificação usuários]*****')
                ->attach(storage_path('excel/exports/usuarios.xlsx'))
                ->attach(storage_path('excel/exports/pastas.xlsx'))
                ->attach(storage_path('excel/exports/prazos.xlsx'))
                ->view('Painel.Email.Usuarios.dadosusuario')
                ->with([

                ]);
}
}
