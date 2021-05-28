<?php

namespace App\Mail\Proposta;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AtualizadaProposta extends Mailable
{
    use Queueable, SerializesModels;
    public $proposta, $unidade, $setor, $cliente, $grupo, $valor, $solicitante_nome, $status_descricao, $carbon;


    public function __construct($proposta, $unidade, $setor, $cliente, $grupo, $valor, $solicitante_nome, $status_descricao, $carbon)
    {
        $this->proposta = $proposta;
        $this->unidade = $unidade;
        $this->setor = $setor;
        $this->cliente = $cliente;
        $this->grupo = $grupo;
        $this->valor = $valor;
        $this->solicitante_nome = $solicitante_nome;
        $this->status_descricao = $status_descricao;
        $this->carbon = $carbon;

    }

   public function build()
{
    return $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Propostas - Proposta atualizada]*****')
                ->view('Painel.Email.Proposta.propostaatualizada')
                ->with([
                    'proposta' => $this->proposta,
                    'unidade' => $this->unidade,
                    'setor' => $this->setor,
                    'cliente' => $this->cliente,
                    'grupo' => $this->grupo,
                    'valor' => $this->valor,
                    'status_descricao' => $this->status_descricao,
                    'solicitante_nome' => $this->solicitante_nome,
                    'carbon' => $this->carbon,
                ]);
}
}
