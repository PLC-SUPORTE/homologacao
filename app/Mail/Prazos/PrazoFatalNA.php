<?php

namespace App\Mail\Prazos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrazoFatalNA extends Mailable
{
    use Queueable, SerializesModels;

   public function build()
   {
       
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Prazos][Indicador cumprimento de prazos]')
                ->view('Painel.Email.Prazos.prazofatalnamail');
}
}
