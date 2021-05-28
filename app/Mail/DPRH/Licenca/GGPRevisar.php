<?php

namespace App\Mail\DPRH\Licenca;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GGPRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $ggp_nome;


    public function __construct($datas, $ggp_nome)
    {
        $this->datas = $datas;
        $this->ggp_nome = $ggp_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de licença]*****')
                ->view('Painel.Email.DPRH.Licenca.GGPRevisar')
                ->with([
                    'datas' => $this->datas,
                    'ggp_nome' => $this->ggp_nome,
                ]);
}
}
