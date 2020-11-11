<?php

namespace Stilldesign\Messenger\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Http\Request;
use Stilldesign\Messenger\Models\Conversation;

interface ConversationRepositoryInterface
{
    public function notEmptyConversationsByUserId($userId, $nameFilter = null);

    public function findById($id);

    public function createConversation(array $participantIds = []);

    public function createMessage(Conversation $conversation, array $data);

    public function hasUsers(Conversation $conversation, array $userIds): bool;

    public function isUsersInConversation($conversationId, array $userIds): bool;

    public function findParticipantsConversation(array $participantIds);

    public function delete($conversationId);

    public function messagesByConversation(
        Conversation $conversation,
        Request $request,
        int $userId
    ): LengthAwarePaginator;

    public function markAsRead($userId, $conversation): int;

    public function markAsUnReadForRecipients(array $usersId, $conversation): int;

    public function messageRecipients(Conversation $conversation, $senderId): Collection;

    public function findWhereBodyLike(Conversation $conversation, $searchString): SupportCollection;

    public function findMessagesAroundSearchResult(Conversation $conversation, array $range): Collection;
}
