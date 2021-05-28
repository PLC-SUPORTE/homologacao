<?php

namespace App\Mail\Agendamento;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AgendamentoCancelado extends Mailable
{
    use Queueable, SerializesModels;
    public $id, $mesa, $sala, $localizacao, $andar, $observacao, $datainicio, $datafim;


    public function __construct($id, $mesa, $sala, $localizacao, $andar, $datainicio, $datafim)
    {
        $this->id = $id;
        $this->mesa = $mesa;
        $this->sala = $sala;
        $this->localizacao = $localizacao;
        $this->andar = $andar;
        $this->datainicio = $datainicio;
        $this->datafim = $datafim;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Agendamento cancelado]*****')
                ->view('Painel.Email.Agendamento.cancelado')
                ->with([
                    'id' => $this->id,
                    'mesa' => $this->mesa,
                    'sala' => $this->sala,
                    'localizacao' => $this->localizacao,
                    'andar' => $this->andar,
                    'datainicio' => $this->datainicio,
                    'datafim' => $this->datafim,
                ]);
}
}
