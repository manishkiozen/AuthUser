<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCatlog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catlog';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'catlog_id', 'icon_file'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['_token'];
}
