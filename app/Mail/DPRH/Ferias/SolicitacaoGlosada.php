<?php

namespace App\Mail\DPRH\Ferias;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoGlosada extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $solicitante_nome;


    public function __construct($datas, $solicitante_nome)
    {
        $this->datas = $datas;
        $this->subcoordenador_nome = $solicitante_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação glosada]*****')
                ->view('Painel.Email.DPRH.Ferias.SolicitacaoGlosada')
                ->with([
                    'datas' => $this->datas,
                    'solicitante_nome' => $this->solicitante_nome,
                ]);
}
}
