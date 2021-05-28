<?php

namespace App\Mail\Gestao;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrazoContestacao extends Mailable
{
    use Queueable, SerializesModels;
    public $ident, $pasta, $mov, $unidade, $data, $dataprazo, $dataprazofatal, $datafechamento, $justificativa;


    public function __construct($ident, $pasta, $mov, $unidade, $data, $dataprazo, $dataprazofatal, $datafechamento, $justificativa)
    {
        $this->ident = $ident;
        $this->pasta = $pasta;
        $this->mov = $mov;
        $this->unidade = $unidade;
        $this->data= $data;
        $this->dataprazo = $dataprazo;
        $this->dataprazofatal = $dataprazofatal;
        $this->datafechamento = $datafechamento;
        $this->justificativa = $justificativa;

    }

   public function build()
   {
       
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Prazo][Nova contestação cadastrada no portal]*****')
                ->view('Painel.Email.Gestao.prazocontestacao')
                ->with([
                    'ident' => $this->ident,
                    'pasta' => $this->pasta,
                    'mov' => $this->mov,
                    'unidade' => $this->unidade,
                    'data' => $this->data,
                    'dataprazo' => $this->dataprazo,
                    'dataprazofatal' => $this->dataprazofatal,
                    'datafechamento' => $this->datafechamento,
                    'justificativa' => $this->justificativa,
                ]);
}
}
