<?php

namespace App\Mail\Usuario;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovaSenha extends Mailable
{
    use Queueable, SerializesModels;
    public $novasenha;


    public function __construct($novasenha)
    {
        $this->novasenha = $novasenha;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Redefinição de senha]*****')
                ->view('Painel.Email.Usuarios.novasenha')
                ->with([
                    'novasenha' => $this->novasenha,
                ]);
}
}
