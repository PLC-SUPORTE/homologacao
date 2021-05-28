<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TiposDoc extends Model
{
    protected $table = "PLCFULL.dbo.Jurid_TipoDoc";
    protected $primaryKey = 'Codigo';


    protected $fillable = [
        'Codigo',
        'Descricao',
    ];
    
     public function rules()
    {
        return [
        ];
    }


}
