<?php

namespace App\Mail\Contratacao\RH;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RHEnviaDocumentacao extends Mailable
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
                ->subject('*****[PLC][Notificação][Nova solicitação de contratação]*****')
                ->view('Painel.Email.Contratacao.RH.EnviaDocumentacao')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
