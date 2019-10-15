<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstitutionInternalDivition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'description',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Institution()
    {
       return $this->hasOne('App\Institution');
    }

    function Publication()
    {
       return $this->belongsTo('App\Publication');
    }

}