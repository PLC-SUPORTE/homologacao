<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoPesquisaPatrimonial extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $equipe_nome;


    public function __construct($datas, $equipe_nome)
    {

        $this->datas= $datas;
        $this->equipe_nome = $equipe_nome;

    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Nova solicitação de pesquisa patrimonial]')
                ->view('Painel.Email.PesquisaPatrimonial.solicitacao')
                ->with([
                    'datas' => $this->datas,
                    'equipe_nome' => $this->equipe_nome,
                ]);
}
}
