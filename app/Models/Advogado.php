<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advogado extends Model
{
    protected $table = "PLCFULL.dbo.Jurid_Advogado";
    protected $primaryKey = 'Codigo';


    protected $fillable = [
        'Codigo',
        'Nome',
        'Status',
        'Correspondente',
    ];
    
     public function rules()
    {
        return [
            'Nome' => 'required|min:3|max:200',
        ];
    }


}
