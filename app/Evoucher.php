<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Evoucher extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'e_voucher';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['n_voucher', 'price'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['_token'];
}
