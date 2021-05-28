<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moeda extends Model
{
    protected $table = "PLCFULL.dbo.Jurid_Moeda";
    protected $primaryKey = 'Codigo';


    protected $fillable = [
        'Codigo',
        'Descricao',
        'Simbolo',
        'created_at',
    ];
    
     public function rules()
    {
        return [
            'Descricao' => 'required|min:3|max:200',
        ];
    }


}
