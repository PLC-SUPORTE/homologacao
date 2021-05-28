<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BoletosAguardandoProgramacaoEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $datas;


    public function __construct($datas)
    {
        $this->datas = $datas;
    }


   public function build() {
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Boletos aguardando programação]')
                ->view('Painel.Email.PesquisaPatrimonial.boletosaguardandoprogramacao')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
