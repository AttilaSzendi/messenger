<?php

namespace Stilldesign\Messenger\Repositories;

use Stilldesign\Messenger\Contracts\UserRepositoryInterface;
use Stilldesign\Messenger\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User $model
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function blockUser($userId, $targetId)
    {
        return $this->model->findOrFail($userId)->messengerBlockedUsers()->attach([$targetId]);
    }

    public function unBlockUser($userId, $targetId)
    {
        return $this->model->findOrFail($userId)->messengerBlockedUsers()->detach([$targetId]);
    }

    /**
     * @param int $userId
     * @param int $targetId
     * @return bool
     */
    public function hasUserBlockedUser(int $userId, int $targetId)
    {
        return !! $this->model->newQuery()->findOrFail($userId)->messengerBlockedUsers()->where('id', $targetId)->first();
    }
}
