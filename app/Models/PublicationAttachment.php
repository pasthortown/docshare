<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicationAttachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'publication_attachment_file_type','publication_attachment_file_name','publication_attachment_file',
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

}