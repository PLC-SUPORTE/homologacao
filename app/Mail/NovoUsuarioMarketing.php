<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovoUsuarioMarketing extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $email, $setordescricao, $unidadedescricao;


    public function __construct($name, $email, $setordescricao, $unidadedescricao)
    {
        $this->name = $name;
        $this->email = $email;
        $this->setordescricao = $setordescricao;
        $this->unidadedescricao = $unidadedescricao;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Criar assinatura e-mail]*****')
                ->view('Painel.Email.Usuarios.novousuariomarketing')
                ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'setordescricao' => $this->setordescricao,
                    'unidadedescricao' => $this->unidadedescricao,
                ]);
}
}
