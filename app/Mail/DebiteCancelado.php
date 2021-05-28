<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DebiteCancelado extends Mailable
{
    use Queueable, SerializesModels;
    public $numero;


    public function __construct($numero)
    {
        $this->numero = $numero;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Correspondente][Solicitação Cancelada]*****')
                ->view('Painel.Email.Solicitacoes.emailCancelado')
                ->with([
                    'numero' => $this->numero,
                ]);
}
}
