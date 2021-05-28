<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovaSolicitacaoNaoCobravel extends Mailable
{
    use Queueable, SerializesModels;
    public $id_matrix;


    public function __construct($id_matrix)
    {
        $this->id_matrix = $id_matrix;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Nova solicitação pesquisa não cobrável]')
                ->view('Painel.Email.PesquisaPatrimonial.novasolicitacaonaocobravel')
                ->with([
                    'id_matrix' => $this->id_matrix,
                ]);
}
}
