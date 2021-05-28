<?php

namespace App\Mail\Prazos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrazoFatalMail extends Mailable
{
    use Queueable, SerializesModels;
    public $datas;


    public function __construct($datas)
    {
        $this->datas = $datas;
    }

   public function build()
   {
       
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Prazos][Indicador cumprimento de prazos]')
                ->view('Painel.Email.Prazos.prazofatalmail')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
