<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacoesPagas extends Mailable
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
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Solicitação de pesquisa patrimonial pagas]')
                ->view('Painel.Email.PesquisaPatrimonial.solicitacoespagas')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
