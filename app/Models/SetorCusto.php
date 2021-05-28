<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SetorCusto extends Model
{
    protected $fillable = ['Codigo', 'Descricao'];
    protected $table = "PLCFULL.dbo.Jurid_Setor";
    protected $primaryKey = 'Id';
    public $timestamps = false;

    
    public function rules()
    {
        return [
            'Codigo' => 'required|min:3|max:60',
            'Descricao' => 'required|min:3|max:200',
            'Ativo' => 'required|min:1|max:200'
        ];
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}