<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PesquisaFinalizada extends Mailable
{
    use Queueable, SerializesModels;
    public $numero;


    public function __construct($numero)
    {
        $this->numero = $numero;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial]Solicitação de pesquisa patrimonial finalizada')
                ->view('Painel.Email.PesquisaPatrimonial.pesquisafinalizada')
                ->with([
                    'numero' => $this->numero,
                ]);
}
}
