<?php

namespace App\Mail\Reembolso;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviaValorAcima extends Mailable
{
    use Queueable, SerializesModels;
    public $solicitantenome, $pasta, $valortotal;


    public function __construct($solicitantenome, $pasta, $valortotal)
    {
        $this->solicitantenome = $solicitantenome;
        $this->pasta = $pasta;
        $this->valortotal = $valortotal;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Valor limite ultrapassado da pasta]')
                ->view('Painel.Email.Reembolso.EnviaValorAcima')
                ->with([
                    'solicitantenome' => $this->solicitantenome,
                    'pasta' => $this->pasta,
                    'valortotal' => $this->valortotal,
                ]);
}
}
