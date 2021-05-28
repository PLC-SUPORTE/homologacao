<?php

namespace App\Mail\Prazos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrazoVencido extends Mailable
{
    use Queueable, SerializesModels;
    public $prazos, $totalprazos, $advogado_nome;


    public function __construct($prazos, $totalprazos, $advogado_nome)
    {
        $this->prazos = $prazos;
        $this->totalprazos = $totalprazos;
        $this->advogado_nome = $advogado_nome;
    }

   public function build()
   {
       
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Prazos][Indicador cumprimento de prazos]*****')
                ->view('Painel.Email.Prazos.prazofatal')
                ->with([
                    'prazos' => $this->prazos,
                    'totalprazos' => $this->totalprazos,
                    'advogado_nome' => $this->advogado_nome,
                ]);
}
}
