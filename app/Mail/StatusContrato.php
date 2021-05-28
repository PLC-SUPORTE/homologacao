<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusContrato extends Mailable
{
    use Queueable, SerializesModels;
    public $contrato;


    public function __construct($contrato)
    {
        $this->datas = $contrato;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Alteração status contraot]*****')
                ->view('Painel.Email.Solicitacoes.emailStatusContrato')
                ->with([
                    'contrato' => $this->contrato,
                ]);
}
}
