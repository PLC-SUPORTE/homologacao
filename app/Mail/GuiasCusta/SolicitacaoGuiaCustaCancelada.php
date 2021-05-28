<?php

namespace App\Mail\GuiasCusta;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoGuiaCustaCancelada extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $motivodescricao;


    public function __construct($datas, $motivodescricao)
    {
        $this->datas = $datas;
        $this->motivodescricao = $motivodescricao;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Solicitação de pagamento de guia de custa cancelada]')
                ->view('Painel.Email.GuiasCusta.SolicitacaoCancelada')
                ->with([
                    'datas' => $this->datas,
                    'motivodescricao' => $this->motivodescricao,
                ]);
}
}
