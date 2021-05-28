<?php

namespace App\Mail\Escritorio\Solicitacoes;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GerenteFinanceiroRevisar extends Mailable
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
                ->subject('*****[PLC][Notificação][Revisar solicitação de compra]*****')
                ->view('Painel.Email.Escritorio.Solicitacoes.gerentefinanceirorevisar')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
