<?php

namespace App\Mail\Correspondente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RevisarNovoPrestador extends Mailable
{
    use Queueable, SerializesModels;
    public $descricao;


    public function __construct($descricao)
    {
        $this->descricao = $descricao;
    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação de novo usuário]*****')
                ->view('Painel.Email.Correspondente.RevisarNovoPrestador')
                ->with([
                    'descricao' => $this->descricao,
                ]);
}
}
