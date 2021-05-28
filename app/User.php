<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Profile;
use App\Models\Permission;
use App\Models\SetorCusto;

class User extends Authenticatable
{
    use Notifiable;
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'cpf', 'image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function profiles()
    {
        return $this->belongsToMany(Profile::class);
    }
    
    public function setorcustos()
    {
        return $this->belongsToMany(SetorCusto::class);
    }
    
    
    
    public function hasPermission(Permission $permission)
    {
        return $this->hasProfile($permission->profiles);
    }
    
    public function hasProfile($profile)
    {
        if(is_string($profile) ) {
            return $this->profiles->contains('name', $profile);
        }

        return !! $profile->intersect($this->profiles)->count(); 
    }
    
   public function hasSetorCusto($setorcusto)
    {
        if(is_string($setorcusto) ) {
            return $this->setorcustos->contains('Descricao', $setorcusto);
        }

        return !! $setorcusto->intersect($this->setorcustos)->count(); 
    }
    
    
}
