<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovoUsuarioCron extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $usuario, $email, $senha;


    public function __construct($name, $usuario, $email, $senha)
    {
        $this->name = $name;
        $this->usuario = $usuario;
        $this->email = $email;
        $this->senha = $senha;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Seja Bem Vindo]*****')
                ->view('Painel.Email.Usuarios.bemvindocron')
                ->with([
                    'name' => $this->name,
                    'usuario' => $this->usuario,
                    'email' => $this->email,
                    'senha' => $this->senha,
                ]);
}
}
