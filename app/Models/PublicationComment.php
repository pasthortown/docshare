<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicationComment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'content',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Publication()
    {
       return $this->hasOne('App\Publication');
    }

    function Person()
    {
       return $this->hasOne('App\Person');
    }

}