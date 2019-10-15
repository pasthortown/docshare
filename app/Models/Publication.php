<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'title','abstract','written_date','published_date','keywords',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
       
    ];

    function Authors()
    {
       return $this->belongsToMany('App\Author')->withTimestamps();
    }

    function PublicationComment()
    {
       return $this->belongsTo('App\PublicationComment');
    }

    function PublicationType()
    {
       return $this->hasOne('App\PublicationType');
    }

    function InstitutionInternalDivition()
    {
       return $this->hasOne('App\InstitutionInternalDivition');
    }

    function PublicationAttachment()
    {
       return $this->belongsTo('App\PublicationAttachment');
    }

}