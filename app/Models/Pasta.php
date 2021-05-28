<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasta extends Model
{
    protected $table = "PLCFULL.dbo.Jurid_Pastas";
    protected $primaryKey = 'Codigo';


    protected $fillable = [
        'Codigo',
        'Codigo_Comp',
        'Cliente',
        'Descricao',
        'Advogado',
        'UF',
        'Tribunal',
        'OutraParte',
        'Setor',
        'Unidade',
        'PRConta',
        'Moeda',
        'NumPrc1_Sonumeros',   
        'Status',
    ];
    
     public function rules()
    {
        return [
            'Nome' => 'required|min:3|max:200',
        ];
    }


}
