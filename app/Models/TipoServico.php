<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServico extends Model
{
    protected $table = "dbo.Jurid_Nota_Tiposervico";
    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'descricao',
        'ativo',
    ];
    
     public function rules()
    {
        return [
            'descricao' => 'required|min:3|max:200',
        ];
    }


}
