<?php

namespace Stilldesign\Messenger\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'user_id',
        'blocked_user_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
