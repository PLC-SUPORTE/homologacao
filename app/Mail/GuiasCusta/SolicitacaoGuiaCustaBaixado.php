<?php

namespace App\Mail\GuiasCusta;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoGuiaCustaBaixado extends Mailable
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
                ->subject('[PLC][Notificação][Solicitação de pagamento de guia de custa baixado]')
                ->view('Painel.Email.GuiasCusta.FinanceiroBaixado')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
