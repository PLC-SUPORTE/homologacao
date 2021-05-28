<?php

namespace App\Mail\DPRH\Licenca;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubCoordenadorRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $subcoordenador_nome;


    public function __construct($datas, $subcoordenador_nome)
    {
        $this->datas = $datas;
        $this->subcoordenador_nome = $subcoordenador_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de licença]*****')
                ->view('Painel.Email.DPRH.Licenca.SubCoordenadorRevisar')
                ->with([
                    'datas' => $this->datas,
                    'subcoordenador_nome' => $this->subcoordenador_nome,
                ]);
}
}
