<?php

namespace App\Mail\Reembolso;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoReprovada extends Mailable
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
                ->subject('[PLC][Notificação][Solicitação de Reembolso]')
                ->view('Painel.Email.Reembolso.SolicitacaoReprovada')
                ->with([
                    'datas' => $this->datas,
                    'motivodescricao' => $this->motivodescricao,
                ]);
}
}
