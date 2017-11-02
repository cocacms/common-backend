<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use auth;
    use SoftDeletes;


}
