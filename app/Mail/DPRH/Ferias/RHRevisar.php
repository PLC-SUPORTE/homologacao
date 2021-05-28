<?php

namespace App\Mail\DPRH\Ferias;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RHRevisar extends Mailable
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
                ->subject('*****[PLC][Notificação][Nova Solicitação de férias]*****')
                ->view('Painel.Email.DPRH.Ferias.RHRevisar')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
