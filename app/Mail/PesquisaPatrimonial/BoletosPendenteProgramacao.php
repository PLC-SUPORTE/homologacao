<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BoletosPendenteProgramacao extends Mailable
{
    use Queueable, SerializesModels;
    public $datas;


    public function __construct($datas)
    {
        $this->datas = $datas;
    }


   public function build() {
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Boletos aguardando geração do arquivo de remessa para Pesquisa Patrimonial]')
                ->view('Painel.Email.PesquisaPatrimonial.boletospendenteprogramacao')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
