<?php

namespace App\Mail\Contratacao\CEO;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CEORevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $ceo_nome;


    public function __construct($datas, $ceo_nome)
    {
        $this->datas = $datas;
        $this->ceo_nome = $ceo_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de contratação]*****')
                ->view('Painel.Email.Contratacao.CEO.SolicitacaoRevisar')
                ->with([
                    'datas' => $this->datas,
                    'ceo_nome' => $this->ceo_nome,
                ]);
}
}
