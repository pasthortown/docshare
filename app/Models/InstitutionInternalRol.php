<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstitutionInternalRol extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','description',
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

    function InstitutionInternalRolAssignment()
    {
       return $this->belongsTo('App\InstitutionInternalRolAssignment');
    }

}