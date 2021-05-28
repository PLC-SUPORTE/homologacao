<?php

namespace App\Mail\Usuario;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LembreteSenha extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $token;


    public function __construct($name, $token)
    {
        $this->name = $name;
        $this->token = $token;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Recuperar senha]*****')
                ->view('Painel.Email.Usuarios.lembretesenha')
                ->with([
                    'name' => $this->name,
                    'token' => $this->token,
                ]);
}
}
