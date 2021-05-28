<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoPagamentoBoleto extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $solicitacoes, $numdoc, $extenso, $carbon, $comarcas;


    public function __construct($datas, $solicitacoes, $numdoc, $valor, $extenso, $carbon, $comarcas)
    {
        $this->datas = $datas;
        $this->solicitacoes = $solicitacoes;
        $this->numdoc = $numdoc;
        $this->valor = $valor;
        $this->extenso = $extenso;
        $this->carbon = $carbon;
        $this->comarcas = $comarcas;
    }


   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Nota de Solicitação de Adiantamento para Pesquisa Patrimonial]')
                ->view('Painel.Email.PesquisaPatrimonial.pagamentoboleto')
                ->attach(storage_path('app/public/boletos/'.$this->numdoc.'.pdf'))
                // ->attach(storage_path('app/public/boletos/notadebito/'.$this->numdoc.'notadebito.pdf'))
                ->with([
                    'datas' => $this->datas,
                    'solicitacoes' => $this->solicitacoes,
                    'valor' => $this->valor,
                    'extenso' => $this->extenso,
                    'carbon' => $this->carbon,
                    'comarcas' => $this->comarcas,
                ]);
}
}
