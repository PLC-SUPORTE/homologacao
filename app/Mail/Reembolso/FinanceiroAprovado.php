<?php

namespace App\Mail\Reembolso;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinanceiroAprovado extends Mailable
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
                ->subject('[PLC][Notificação][Solicitação de Reembolso]')
                ->view('Painel.Email.Reembolso.FinanceiroAprovado')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
