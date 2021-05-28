<?php

namespace App\Models;

use App\Models\Posts;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['descricao', 'status', 'data'];
    protected $table = "dbo.Marketing_Categoria_Posts";

    public function rules($id = '')
    {
        return [
            'descricao'       => 'required|min:3|max:255',
            'status'          => "required|min:1|max:100|",
            'data'            => 'required|date',
        ];
    }
    
    public function posts()
    {
        return $this->hasMany(\App\Models\Posts::class);
    }
}