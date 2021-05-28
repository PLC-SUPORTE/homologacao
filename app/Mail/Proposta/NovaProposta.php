<?php

namespace App\Mail\Proposta;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovaProposta extends Mailable
{
    use Queueable, SerializesModels;
    public $proposta, $unidade, $setor, $cliente, $grupo, $valor, $carbon;


    public function __construct($proposta, $unidade, $setor, $cliente, $grupo, $valor, $carbon)
    {
        $this->proposta = $proposta;
        $this->unidade = $unidade;
        $this->setor = $setor;
        $this->cliente = $cliente;
        $this->grupo = $grupo;
        $this->valor = $valor;
        $this->carbon = $carbon;

    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Propostas - Nova proposta]*****')
                ->view('Painel.Email.Proposta.novaproposta')
                ->with([
                    'proposta' => $this->proposta,
                    'unidade' => $this->unidade,
                    'setor' => $this->setor,
                    'cliente' => $this->cliente,
                    'grupo' => $this->grupo,
                    'valor' => $this->valor,
                    'carbon' => $this->carbon,
                ]);
}
}
