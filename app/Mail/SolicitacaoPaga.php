<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoPaga extends Mailable
{
    use Queueable, SerializesModels;
    public $numerodebite;


    public function __construct($numerodebite)
    {
        $this->numerodebite = $numerodebite;
    }

   public function build() {
       
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Correspondente][Pagamento realizado]*****')
                ->view('Painel.Email.Solicitacoes.pagamentorealizado')
                ->with([
                    'numerodebite' => $this->numerodebite,
                ]);
}
}
