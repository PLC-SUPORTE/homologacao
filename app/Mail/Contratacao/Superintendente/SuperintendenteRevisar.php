<?php

namespace App\Mail\Contratacao\Superintendente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuperintendenteRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $superintendente_nome;


    public function __construct($datas, $superintendente_name)
    {
        $this->datas = $datas;
        $this->superintendente_name = $superintendente_name;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de contratação]*****')
                ->view('Painel.Email.Contratacao.Superintendente.SolicitacaoRevisar')
                ->with([
                    'datas' => $this->datas,
                    'superintendente_name' => $this->superintendente_name,
                ]);
}
}
