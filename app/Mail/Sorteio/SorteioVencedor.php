<?php

namespace App\Mail\Sorteio;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SorteioVencedor extends Mailable
{
    use Queueable, SerializesModels;

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Parabéns, você acaba de ganhar um sorteio!]')
                ->view('Painel.Email.Sorteio.sorteiovencedor');
}
}
