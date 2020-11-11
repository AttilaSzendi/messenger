<?php

namespace Stilldesign\Messenger\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Conversation
 * @property int $id
 * @property bool $private
 * @property string $data
 * @property Message $lastMessage
 * @property Collection $users
 * @property Collection $messages
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package Stilldesign\Messenger\Models
 */
class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = ['private', 'data'];

    public function users()
    {
        return $this->belongsToMany(config('messenger.user'))
            ->withPivot('is_seen', 'unread_count')
            ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->with('sender');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)
            ->orderBy('messages.id', 'desc')
            ->with('sender');
    }
}
