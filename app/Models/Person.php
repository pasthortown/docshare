<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'identification','name','last_name','mobile_number','home_number','birthday','email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function PublicationComment()
    {
       return $this->belongsTo('App\PublicationComment');
    }

    function User()
    {
       return $this->hasOne('App\User');
    }

    function InstitutionInternalRolAssignment()
    {
       return $this->belongsTo('App\InstitutionInternalRolAssignment');
    }

}