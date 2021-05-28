<?php

namespace App\Mail\Correspondente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovaSolicitacaoAcesso extends Mailable
{
    use Queueable, SerializesModels;
    public $token, $dado, $tiposervico;


    public function __construct($token,$dado, $tiposervico)
    {
        $this->token = $token;
        $this->dado = $dado;
        $this->tiposervico = $tiposervico;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação de acesso]*****')
                ->view('Painel.Email.Correspondente.NovaSolicitacaoAcesso')
                ->with([
                    'token' => $this->token,
                    'dado' => $this->dado,
                    'tiposervico' => $this->tiposervico,
                ]);
}
}
