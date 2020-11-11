<?php

namespace Stilldesign\Messenger\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Stilldesign\Messenger\Traits\Messagable;

/**
 * Class User
 * @package Stilldesign\Messenger\Models
 * @property int id
 * @property string name
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Messagable;

    protected $visible = ['id', 'name'];
}
