<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notificacao extends Model
{
    protected $table = "dbo.Hist_Notificacao";
    protected $primaryKey = 'id';


    protected $fillable = [
        'data',
        'id_ref',
        'user_id',
        'tipo',
        'status',
    ];
    
     public function rules()
    {
        return [
        ];
    }


}
