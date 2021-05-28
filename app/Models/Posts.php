<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Posts extends Model
{
    protected $fillable = [
        'titulo',
        'image',
        'user_id',
        'categoria_id',
        'descricao',
        'link',
        'data',
        'status'
    ];
    protected $table = "dbo.Marketing_Posts";
    public $timestamps = false;

    
    public function rules($id = '')
    {
        return [
            'titulo'         => "required|min:3|max:250,unique:posts,titulo,{$id},id",
            'categoria_id'   => 'required',
            'descricao'      => 'required|min:50|max:6000',
            'link'           => 'max:6000',
            'data'           => 'required|date',
            'status'         => 'required|in:A,R',
            'image'          => "file",
        ];
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
   // public function views()
    //{
      //  return $this->hasMany(PostView::class);
   // }
    
  //  public function comments()
  //  {
  //      return $this->hasMany(Comment::class)
    //            ->join('users', 'users.id', '=', 'comments.user_id')
    ///            ->select('comments.id', 'comments.description', 'comments.name', 'users.image as image_user')
    //            ->where('comments.status', 'A');
   // }
}