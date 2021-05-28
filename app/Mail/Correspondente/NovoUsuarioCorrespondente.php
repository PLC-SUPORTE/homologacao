<?php

namespace App\Mail\Correspondente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovoUsuarioCorrespondente extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $email, $senha;


    public function __construct($name, $email, $senha)
    {
        $this->name = $name;
        $this->email = $email;
        $this->senha = $senha;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Seja Bem Vindo]*****')
                ->attach(storage_path('app/public/correspondente/manual.pdf'))
                ->view('Painel.Email.Usuarios.bemvindo')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'senha' => $this->senha,
                ]);
}
}
