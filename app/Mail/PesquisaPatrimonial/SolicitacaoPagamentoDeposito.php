<?php

namespace App\Mail\PesquisaPatrimonial;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SolicitacaoPagamentoDeposito extends Mailable
{
    use Queueable, SerializesModels;
    public $datas, $clientecodigo, $extenso, $carbon, $comarcas;


    public function __construct($datas, $clientecodigo, $extenso, $carbon, $comarcas)
    {
        $this->datas = $datas;
        $this->clientecodigo = $clientecodigo;
        $this->extenso = $extenso;
        $this->carbon = $carbon;
        $this->comarcas = $comarcas;
    }


   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('[PLC][Notificação][Pesquisa Patrimonial][Solicitação pagamento pesquisa patrimonial]')
                ->view('Painel.Email.PesquisaPatrimonial.pagamentodeposito')
                ->with([
                    'datas' => $this->datas,
                    'extenso' => $this->extenso,
                    'carbon' => $this->carbon,
                    'comarcas' => $this->comarcas,
                ]);
}
}
