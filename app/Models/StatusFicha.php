<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusFicha extends Model
{
    protected $table = "PLCFULL.dbo.Jurid_Status_Ficha";
    protected $primaryKey = 'Id';


    protected $fillable = [
        'Id',
        'Descricao',
    ];
    
     public function rules()
    {
        return [
            'Descricao' => 'required|min:3|max:200',
        ];
    }


}
