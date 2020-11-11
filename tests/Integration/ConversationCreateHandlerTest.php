<?php

namespace Tests\Integration;

use Stilldesign\Messenger\Handlers\ConversationCreateHandler;
use Stilldesign\Messenger\Models\Conversation;
use Stilldesign\Messenger\Models\User;
use Stilldesign\Messenger\Repositories\ConversationRepository;
use Stilldesign\Messenger\Tests\TestCase;

class ConversationCreateHandlerTest extends TestCase
{
    /**
     * @var ConversationCreateHandler
     */
    public $underTest;

    public function setUp()
    {
        parent::setUp();

        $conversationRepository = new ConversationRepository(new Conversation());

        $this->underTest = new ConversationCreateHandler(
            $conversationRepository
        );
    }

    /**
     * @test
     */
    public function findOrCreateConversation_returns_an_existing_or_a_new_conversation()
    {
        //GIVEN
        $users = factory(User::class, 2)->create();

        $participants = [$users[0], $users[1]];

        //WHEN
        $response = $this->underTest->findOrCreateConversation($participants);

        //THEN
        $this->assertInstanceOf(Conversation::class, $response);
    }
}