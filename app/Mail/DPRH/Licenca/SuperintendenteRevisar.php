<?php

namespace App\Mail\DPRH\Licenca;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuperintendenteRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $superintendente_nome;


    public function __construct($datas, $superintendente_nome)
    {
        $this->datas = $datas;
        $this->superintendente_nome = $superintendente_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de licença]*****')
                ->view('Painel.Email.DPRH.Licenca.SuperintendenteRevisar')
                ->with([
                    'datas' => $this->datas,
                    'superintendente_nome' => $this->superintendente_nome,
                ]);
}
}
