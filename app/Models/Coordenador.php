<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;

class Coordenador extends Model 

{
    protected $table = "PLCFULL.dbo.jurid_debite";
    protected $primaryKey = 'Numero';
    public $timestamps = false;
    
     public function getDateFormat()
    {
        return 'd-m-Y H:i:s.v';
    }

    protected $fillable = [
        'Numero',
        'Advogado',
        'Cliente',
        'Data',
        'Tipo',
        'Obs',
        'Status',
        'Hist',
        'ValorT',
        'Usuario',
        'DebPago',
        'TipoDeb',
        'AdvServ',
        'Setor',
        'Pasta',
        'Unidade',
        'PRConta',
        'Valor_Adv',
        'Quantidade',
        'ValorUnitario_Adv',
        'ValorUnitarioCliente',
        'Revisado_DB',
        'moeda'
    ];
    

    
public function build()
{
    return $this->from('ronaldo.amaral@plcadvogados.com.br')
                ->view('Painel.Nota.email');
}


}
