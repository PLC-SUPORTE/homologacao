<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PesquisaCorrespondente extends Model

{
    protected $table = "dbo.Hist_Form_Ficha";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_ref',
        'data',
        'user_id',
        'pergunta1',
        'pergunta2',
        'pergunta2_obs',
        'pergunta3',
        'pergunta3_obs',
        'pergunta4',
        'pergunta4_obs',
        'pergunta5',
        'pergunta6',
        'pergunta7',
        'pergunta8',
        'pergunta9',
        'pergunta9',
        'pergunta10',
    ];
    
     public function rules()
    {
        return [
        'id_ref' => 'required',
        'data' => 'required',
        'user_id' => 'required',
        'pergunta1' => 'required',
        'pergunta2' => 'required',
        'pergunta3' => 'required',
        'pergunta4' => 'required',
        'pergunta5' => 'required',
        'pergunta6' => 'required',
        'pergunta7' => 'required',
        'pergunta8' => 'required',
        'pergunta9' => 'required',
        'pergunta9' => 'required',
        'pergunta10' => 'required',
           
            
        ];
    }
    
     


}
