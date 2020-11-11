<?php

namespace Tests\Integration;

use Stilldesign\Messenger\Models\User;
use Stilldesign\Messenger\Repositories\ConversationRepository;
use Stilldesign\Messenger\Repositories\UserRepository;
use Stilldesign\Messenger\Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /**
     * @var ConversationRepository
     */
    public $underTest;

    public function setUp()
    {
        parent::setUp();

        $this->underTest = new UserRepository(new User());
    }

    /**
     * @test
     */
    public function hasUserBlockedUser_should_return_null_if_the_user_has_not_blocked_that_person()
    {
        $user = factory(User::class)->create();
        $userToBlock = factory(User::class)->create();

        $response = $this->underTest->hasUserBlockedUser($user->id, $userToBlock->id);

        $this->assertFalse($response);
    }

    /**
     * @test
     */
    public function hasUserBlockedUser_should_return_user_model_if_the_user_has_blocked_that_person()
    {
        $user = factory(User::class)->create();
        $userToBlock = factory(User::class)->create();

        $user->messengerBlockedUsers()->attach([$userToBlock->id]);

        $response = $this->underTest->hasUserBlockedUser($user->id, $userToBlock->id);

        $this->assertTrue($response);
    }
}