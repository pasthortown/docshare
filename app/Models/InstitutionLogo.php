<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstitutionLogo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'institution_logo_file_type','institution_logo_file_name','institution_logo_file',
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

}