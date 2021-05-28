<?php

namespace App\Mail\Contratacao;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CandidatoPreencherInformacoes extends Mailable
{
    use Queueable, SerializesModels;
    public $candidatotoken, $candidatonome;


    public function __construct($candidatotoken, $candidatonome)
    {
        $this->candidatotoken = $candidatotoken;
        $this->candidatonome = $candidatonome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação de cadastro informações]*****')
                ->view('Painel.Email.Contratacao.CandidatoPreencherInformacoes')
                ->with([
                    'candidatotoken' => $this->candidatotoken,
                    'candidatonome' => $this->candidatonome,
                ]);
}
}
