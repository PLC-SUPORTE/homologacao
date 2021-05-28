<?php

namespace App\Mail\Contratacao;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoCancelada extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $solicitante_nome;


    public function __construct($datas, $solicitante_nome)
    {
        $this->datas = $datas;
        $this->solicitante_nome = $solicitante_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação glosada]*****')
                ->view('Painel.Email.Contratacao.SolicitacaoCancelada')
                ->with([
                    'datas' => $this->datas,
                    'solicitante_nome' => $this->solicitante_nome,
                ]);
}
}
