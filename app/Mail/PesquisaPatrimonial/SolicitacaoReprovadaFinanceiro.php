<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoReprovadaFinanceiro extends Mailable
{
    use Queueable, SerializesModels;
    public $id_matrix, $motivodescricao, $observacaoedicao;


    public function __construct($id_matrix, $motivodescricao, $observacaoedicao)
    {
        $this->id_matrix = $id_matrix;
        $this->motivodescricao = $motivodescricao;
        $this->observacaoedicao = $observacaoedicao;

    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Solicitação pesquisa patrimonial reprovada]')
                ->view('Painel.Email.PesquisaPatrimonial.SolicitacaoReprovadaFinanceiro')
                ->with([
                    'id_matrix' => $this->id_matrix,
                    'motivodescricao' => $this->motivodescricao,
                    'observacaoedicao' => $this->observacaoedicao,
                ]);
}
}
