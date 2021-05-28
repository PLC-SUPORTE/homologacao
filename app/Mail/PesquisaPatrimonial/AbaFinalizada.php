<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbaFinalizada extends Mailable
{
    use Queueable, SerializesModels;
    public $numero, $descricao;


    public function __construct($numero, $descricao)
    {
        $this->numero = $numero;
        $this->descricao = $descricao;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial]Aba finalizada')
                ->view('Painel.Email.PesquisaPatrimonial.abafinalizada')
                ->with([
                    'numero' => $this->numero,
                    'descricao' => $this->descricao,
                ]);
}
}
