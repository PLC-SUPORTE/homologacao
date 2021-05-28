<?php

namespace App\Mail\Compras\Solicitante;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitanteEnvio extends Mailable
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
                ->subject('*****[PLC][Notificação][Nova Solicitação de compra]*****')
                ->view('Painel.Email.Compras.Solicitante.solicitanteEnvio')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
