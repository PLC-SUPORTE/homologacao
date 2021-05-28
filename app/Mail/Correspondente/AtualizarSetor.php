<?php

namespace App\Mail\Correspondente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AtualizarSetor extends Mailable
{
    use Queueable, SerializesModels;
    public $correspondente, $pasta, $setor;


    public function __construct($correspondente, $pasta, $setor)
    {
        $this->correspondente = $correspondente;
        $this->pasta = $pasta;
        $this->setor = $setor;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Alteração de setor custo]*****')
                ->view('Painel.Email.Solicitacoes.atualizarsetor')
                ->with([
                    'correspondente' => $this->correspondente,
                    'pasta' => $this->pasta,
                    'setor' => $this->setor,
                ]);
}
}
