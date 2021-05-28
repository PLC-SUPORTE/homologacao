<?php

namespace App\Mail\DPRH\Desligamento;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrepararDocumentacao extends Mailable
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
                ->subject('*****[PLC][Notificação][Enviar documentação de desligamento]*****')
                ->view('Painel.Email.DPRH.Desligamento.PrepararDocumentacao')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
