<?php

namespace Tests\Integration;

use Stilldesign\Messenger\Models\Conversation;
use Stilldesign\Messenger\Models\Message;
use Stilldesign\Messenger\Repositories\ConversationRepository;
use Stilldesign\Messenger\Tests\TestCase;
use Stilldesign\Messenger\Models\User;

class ConversationRepositoryTest extends TestCase
{
    /**
     * @var ConversationRepository
     */
    public $underTest;

    public function setUp()
    {
        parent::setUp();

        $this->underTest = new ConversationRepository(new Conversation());
    }

    /**
     * @test
     */
    public function findById_should_return_conversation_by_id()
    {
        factory(Conversation::class)->create(['private' => true]);

        $response = $this->underTest->findById(1);

        $this->assertEquals(1, $response->id);
        $this->assertEquals(true, $response->private);
        $this->assertInstanceOf(Conversation::class, $response);
    }

    /**
     * @test
     */
    public function createConversation_should_create_conversation_with_participants()
    {
        $participantNumber = rand(2, 15);
        $users = factory(User::class, $participantNumber)->create();

        $response = $this->underTest->createConversation($users->pluck('id')->toArray());

        $this->assertDatabaseHas('conversations', ['id' => 1, 'private' => true]);
        $this->assertCount($participantNumber, $response->users()->get());
    }

    /**
     * @test
     */
    public function createMessage_should_create_message()
    {
        $conversation = factory(Conversation::class)->create();
        $senderId = factory(User::class)->create()->id;
        $message = "This is the message...";

        $this->underTest->createMessage($conversation, [
            'body' => $message,
            'user_id' => $senderId,
            'ip_address' => '125.43.0.1',
        ]);

        $this->underTest->createMessage($conversation, [
            'body' => $message,
            'user_id' => $senderId,
            'is_image' => true,
            'ip_address' => '125.43.0.1'
        ]);

        //THEN
        $this->assertDatabaseHas('messages', [
            'id' => 1,
            'body' => $message,
            'conversation_id' => $conversation->id,
            'user_id' => $senderId,
            'is_image' => false,
            'is_document' => false
        ]);

        $this->assertDatabaseHas('messages', [
            'id' => 2,
            'body' => $message,
            'conversation_id' => $conversation->id,
            'user_id' => $senderId,
            'is_image' => true,
            'is_document' => false
        ]);
    }

    /**
     * @test
     */
    public function notEmptyConversationsByUserId_should_return_a_collection_of_conversations_where_message_exists_with_last_message(
    )
    {
        $userId = factory(User::class)->create()->id;
        $message = 'This is the body of the message...';
        $conversations = factory(Conversation::class, 2)->create();

        factory(Message::class)->create(['body' => $message, 'conversation_id' => $conversations[0]->id]);

        $conversations[0]->users()->attach([$userId]);
        $conversations[1]->users()->attach([$userId]); //this will not received because it has no message

        $response = $this->underTest->notEmptyConversationsByUserId($userId);

        $this->assertCount(1, $response);
        $this->assertEquals($message, $response[0]->lastMessage->body);
    }

    /**
     * @test
     */
    public function findParticipantsConversation_finds_the_conversation_of_the_given_participants_if_they_have_one()
    {
        list($users, $conversation) = $this->makeUsersAndConversation();

        $conversation->users()->attach([$users[0]->id, $users[1]->id]);

        $response = $this->underTest->findParticipantsConversation([$users[0]->id, $users[1]->id]);

        $this->assertInstanceOf(Conversation::class, $response);
    }

    /**
     * @test
     */
    public function findParticipantsConversation_does_not_find_the_conversation_of_the_given_participants_if_they_do_not_have_one(
    )
    {
        list($users, $conversation) = $this->makeUsersAndConversation();

        $conversation->users()->attach([$users[0]->id, $users[1]->id, $users[2]->id]);

        $response = $this->underTest->findParticipantsConversation([$users[0]->id, $users[1]->id]);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function hasUsers_returns_true_if_all_users_is_in_the_conversation()
    {
        list($users, $conversation) = $this->makeUsersAndConversation();

        $conversation->users()->attach([$users[0]->id, $users[1]->id, $users[2]->id]);

        $response = $this->underTest->hasUsers($conversation, [$users[0]->id, $users[1]->id]);

        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function hasUsers_returns_false_if_not_all_users_is_in_the_conversation()
    {
        list($users, $conversation) = $this->makeUsersAndConversation();

        $conversation->users()->attach([$users[0]->id, $users[2]->id]);

        $response = $this->underTest->hasUsers($conversation, [$users[0]->id, $users[1]->id]);

        $this->assertFalse($response);
    }

    /**
     * @test
     */
    public function isUsersInConversation_returns_true_if_the_users_are_in_the_conversation()
    {
        list($users, $conversation) = $this->makeUsersAndConversation();

        $conversation->users()->attach([$users[1]->id, $users[2]->id]);

        $response = $this->underTest->isUsersInConversation($conversation->id, [$users[1]->id, $users[2]->id]);

        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function isUsersInConversation_returns_false_if_the_users_are_not_in_the_conversation()
    {
        list($users, $conversation) = $this->makeUsersAndConversation();

        $conversation->users()->attach([$users[1]->id, $users[2]->id]);

        $response = $this->underTest->isUsersInConversation($conversation->id, [$users[0]->id]);

        $this->assertFalse($response);
    }

    /**
     * @test
     */
    public function delete_method_soft_deletes_conversation()
    {
        $conversation = factory(Conversation::class)->create(['id' => 32]);

        $this->underTest->delete($conversation->id);

        $this->assertCount(0, Conversation::all());
        $this->assertNotNull(Conversation::withTrashed()->whereId(32)->first()->deleted_at);
    }

    /**
     * @test
     */
    public function conversation_should_marked_as_read()
    {
        $user = factory(User::class)->create();
        $conversation = factory(Conversation::class)->create();
        $conversation->users()->attach($user->id);

        $updatedRecordsInDB = $this->underTest->markAsRead($user->id, $conversation);

        $this->assertEquals(1, $updatedRecordsInDB);
    }

    /**
     * @test
     */
    public function conversation_should_marked_as_unread_for_the_recipients_except_the_sender_if_sender_sends_a_message()
    {
        $users = factory(User::class, 4)->create();
        $conversation = factory(Conversation::class)->create();
        $conversation->users()->attach([$users[0]->id, $users[1]->id, $users[2]->id, $users[3]->id]);

        $participantsExceptSender = [$users[1]->id, $users[2]->id, $users[3]->id];

        $updatedRecordsInDB = $this->underTest->markAsUnReadForRecipients($participantsExceptSender, $conversation);

        $this->assertEquals(3, $updatedRecordsInDB);
    }

    /**
     * @return array
     */
    public function makeUsersAndConversation(): array
    {
        $users = factory(User::class, 3)->create();

        $conversation = factory(Conversation::class)->create();
        return array($users, $conversation);
    }
}