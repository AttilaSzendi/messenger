<?php

namespace Stilldesign\Messenger\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message
 * @property int $id
 * @property string $body
 * @property int $conversation_id
 * @property int $user_id
 * @property bool $is_image
 * @property bool $is_document
 * @property string $attachment_original_name
 * @property string $ip_address
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $sender
 * @property Conversation $conversation
 * @package Stilldesign\Messenger\Models
 */
class Message extends Model
{
    use SoftDeletes;

    protected $touches = ['conversation'];

    protected $fillable = [
        'body',
        'conversation_id',
        'user_id',
        'is_image',
        'is_document',
        'attachment_original_name',
        'ip_address'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(config('messenger.user'), 'user_id');
    }
}
