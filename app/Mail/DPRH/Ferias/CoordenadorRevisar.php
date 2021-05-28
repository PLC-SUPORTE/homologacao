<?php

namespace App\Mail\DPRH\Ferias;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CoordenadorRevisar extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $coordenador_nome;


    public function __construct($datas, $coordenador_nome)
    {
        $this->datas = $datas;
        $this->coordenador_nome = $coordenador_nome;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Nova Solicitação de férias]*****')
                ->view('Painel.Email.DPRH.Ferias.CoordenadorRevisar')
                ->with([
                    'datas' => $this->datas,
                    'coordenador_nome' => $this->coordenador_nome,
                ]);
}
}
