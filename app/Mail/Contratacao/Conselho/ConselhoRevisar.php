<?php

namespace App\Mail\Contratacao\Conselho;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConselhoRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $conselheiro_nome;


    public function __construct($datas, $conselheiro_nome)
    {
        $this->datas = $datas;
        $this->conselheiro_nome = $conselheiro_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de contratação]*****')
                ->view('Painel.Email.Contratacao.ConselhoRevisar')
                ->with([
                    'datas' => $this->datas,
                    'conselheiro_nome' => $this->conselheiro_nome,
                ]);
}
}
