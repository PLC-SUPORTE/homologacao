<?php

namespace App\Mail\Correspondente;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NovaSolicitacaoServico extends Mailable
{
    use Queueable, SerializesModels;
    public $dado, $tiposervico;


    public function __construct($nome, $codigo, $pasta, $descricao, $tiposervico, $arquivos)
    {
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->pasta = $pasta;
        $this->descricao = $descricao;
        $this->tiposervico = $tiposervico;
        $this->arquivos = $arquivos;
    }

    public function build()
    {
        $query = $this->from('automacao@plcadvogados.com.br', 'PORTAL PL&C ADVOGADOS')
                ->subject('*****[PLC][Notificação][Solicitação de serviço]*****')
                ->view('Painel.Email.Correspondente.NovaSolicitacaoServico');
                
        foreach($this->arquivos as $index => $value){
            $value = str_replace('/', '\\', $value);
            $query = $query->attach(storage_path('app\\public\\correspondente\\contratacao\\'.$value));
        }

        $query->with([
            'nome' => $this->nome,
            'codigo' => $this->codigo,
            'pasta' => $this->pasta,
            'descricao' => $this->descricao,
            'tiposervico' => $this->tiposervico,
            'arquivos' => count($this->arquivos),
        ]);

        return $query;
    }
}
