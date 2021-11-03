<?php

namespace App\Ecabs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForgotPassword extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'old_password',
        'new_password',
    ];
}
