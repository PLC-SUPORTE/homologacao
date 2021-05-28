<?php

namespace App\Mail\Reembolso;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoDuplicada extends Mailable
{
    use Queueable, SerializesModels;


   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Tentativa de solicitação de reembolso duplicada]')
                ->view('Painel.Email.Reembolso.SolicitacaoDuplicada');
}
}
