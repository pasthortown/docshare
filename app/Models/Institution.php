<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name','address','address_map_latitude','address_map_longitude','phone_number','web',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function InstitutionLogo()
    {
       return $this->belongsTo('App\InstitutionLogo');
    }

    function InstitutionInternalDivition()
    {
       return $this->belongsTo('App\InstitutionInternalDivition');
    }

    function InstitutionInternalRol()
    {
       return $this->belongsTo('App\InstitutionInternalRol');
    }

}