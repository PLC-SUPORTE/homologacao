<?php

namespace App\Mail\Compras\ComiteCompras;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ComiteComprasEnvio extends Mailable
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
                ->subject('*****[PLC][Notificação][Nova solicitação pendente para aprovação]*****')
                ->view('Painel.Email.Compras.ComiteCompras.comiteComprasEnvio')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
