<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoBaixadaFinanceiro extends Mailable
{
    use Queueable, SerializesModels;
    public $id_matrix, $carbon;


    public function __construct($id_matrix, $carbon)
    {
        $this->id_matrix = $id_matrix;
        $this->carbon = $carbon;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Baixa boletos]')
                ->view('Painel.Email.PesquisaPatrimonial.solicitacaobaixadafinanceiro')
                ->with([
                    'id_matrix' => $this->id_matrix,
                    'carbon' => $this->carbon,
                ]);
}
}
