<?php

namespace Tests\Integration;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Stilldesign\Messenger\Models\Conversation;
use Stilldesign\Messenger\Models\Message;
use Stilldesign\Messenger\Models\User;
use Stilldesign\Messenger\Repositories\ConversationRepository;
use Stilldesign\Messenger\Tests\TestCase;
use Stilldesign\Messenger\Transformers\MessageListTransformer;
use Stilldesign\Messenger\Transformers\MessageTransformer;

class MessageListTransformerTest extends TestCase
{
    /**
     * @var ConversationRepository
     */
    public $underTest;

    public function setUp()
    {
        parent::setUp();

        $this->underTest = new MessageListTransformer(
            new MessageTransformer(new FilesystemAdapter(new Filesystem(new Local('/'))))
        );
    }

    /**
     * @test
     */
    public function transform_should_transform_the_paginated_messages()
    {
        $user = factory(User::class)->create([
            'name' => 'John Doe'
        ]);
        $this->actingAs($user);

        $conversation = factory(Conversation::class)->create();

        $messages = factory(Message::class, 2)->create([
            'conversation_id' => $conversation->id,
            'body' => 'test text',
            'user_id' => $user->id,
            'ip_address' => '127.0.0.1'
        ]);

        $paginatedMessages = Message::paginate();

        $response = $this->underTest->transform($paginatedMessages, $user->id)['data'];

        $this->assertCount(2, $response);
        $this->assertEquals([
            'id' => 2,
            'body' => 'test text',
            'conversationId' => $conversation->id,
            'attachmentOriginalName' => null,
            'createdAt' => $messages[1]->created_at,
            'sender' => [
                'name' => 'John Doe',
                'isMe' => true
            ],
            'isImage' => false,
            'isDocument' => false,
            'ipAddress' => '127.0.0.1'
        ], $response[0]);
    }
}
