<?php

namespace App\Mail\Contratacao\Gerente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GerenteRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $gerente_nome;


    public function __construct($datas, $gerente_nome)
    {
        $this->datas = $datas;
        $this->gerente_nome = $gerente_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de contratação]*****')
                ->view('Painel.Email.Contratacao.Gerente.SolicitacaoRevisar')
                ->with([
                    'datas' => $this->datas,
                    'gerente_nome' => $this->gerente_nome,
                ]);
}
}
