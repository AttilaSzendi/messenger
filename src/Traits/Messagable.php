<?php

namespace Stilldesign\Messenger\Traits;

use Stilldesign\Messenger\Models\BlockedUser;
use Stilldesign\Messenger\Models\Message;

trait Messagable
{
    public function messengerBlockedUsers()
    {
        return $this->belongsToMany(BlockedUser::class);
    }

    public function messengerUnReadMessages()
    {
        return $this->hasMany(Message::class);
    }
}
