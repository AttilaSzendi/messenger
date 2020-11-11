<?php

namespace Stilldesign\Messenger\Contracts;

interface UserRepositoryInterface
{
    public function blockUser($userId, $targetId);

    public function unBlockUser($userId, $targetId);

    /**
     * @param int $userId
     * @param int $targetId
     * @return bool
     */
    public function hasUserBlockedUser(int $userId, int $targetId);
}
