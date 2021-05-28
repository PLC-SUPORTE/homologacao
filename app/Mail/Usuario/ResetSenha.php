<?php

namespace App\Mail\Usuario;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetSenha extends Mailable
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
                ->subject('*****[PLC][Notificação][Credenciais de acesso]*****')
                ->view('Painel.Email.Usuarios.resetsenha')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'senha' => $this->senha,
                ]);
}
}
