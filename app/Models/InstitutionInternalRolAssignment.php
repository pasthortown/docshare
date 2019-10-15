<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstitutionInternalRolAssignment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function InstitutionInternalRol()
    {
       return $this->hasOne('App\InstitutionInternalRol');
    }

    function Person()
    {
       return $this->hasOne('App\Person');
    }

}