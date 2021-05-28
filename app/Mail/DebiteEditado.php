<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DebiteEditado extends Mailable
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
                ->subject('*****[PLC][Notificação][Correspondente][Solicitação Editada]*****')
                ->cc('portal.financeiro@plcadvogados.com.br')
                ->view('Painel.Email.Solicitacoes.emailEditado')
                ->with([
                    'datas' => $this->datas,
                ]);
}
}
