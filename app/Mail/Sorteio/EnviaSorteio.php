<?php

namespace App\Mail\Sorteio;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviaSorteio extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $numerosorteio;


    public function __construct($name, $numerosorteio)
    {
        $this->name = $name;
        $this->numerosorteio = $numerosorteio;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Confirmação de inscrição no sorteio]')
                ->view('Painel.Email.Sorteio.sorteio')
                ->with([
                    'name' => $this->name,
                    'numerosorteio' => $this->numerosorteio,
                ]);
}
}
