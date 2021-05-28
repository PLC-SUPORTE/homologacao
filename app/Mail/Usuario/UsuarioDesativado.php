<?php

namespace App\Mail\Usuario;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UsuarioDesativado extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $email, $cpf;


    public function __construct($name, $email, $cpf)
    {
        $this->name = $name;
        $this->email = $email;
        $this->cpf = $cpf;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Credenciais desativados]*****')
                ->view('Painel.Email.Usuarios.usuariodesativado')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'cpf' => $this->cpf,
                ]);
}
}
