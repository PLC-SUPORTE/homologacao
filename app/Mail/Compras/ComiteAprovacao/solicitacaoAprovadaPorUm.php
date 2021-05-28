<?php

namespace App\Mail\Compras\ComiteAprovacao;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class solicitacaoAprovadaPorUm extends Mailable
{
    use Queueable, SerializesModels;
    public $datas;


    public function __construct($datas)
    {
        $this->datas = $datas;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação aguardando segunda aprovação]*****')
                ->view('Painel.Email.Compras.ComiteAprovacao.solicitacaoAprovadaPorUm')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
