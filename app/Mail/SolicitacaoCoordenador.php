<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoCoordenador extends Mailable
{
    use Queueable, SerializesModels;
    public $notas;


    public function __construct($notas)
    {
        $this->notas = $notas;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Correspondente][Nova Solicitação de Pagamento]*****')
                ->view('Painel.Email.Solicitacoes.coordenador')
                ->with([
                    'notas' => $this->notas,
                ]);
}
}
