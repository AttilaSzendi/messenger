<?php

namespace Stilldesign\Messenger\Repositories;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Models\Conversation;

/**
 * Class ConversationRepository
 * @package Stilldesign\Messenger\Repositories
 */
class ConversationRepository implements ConversationRepositoryInterface
{
    protected $model;
    protected $userId;

    public function __construct(Conversation $model)
    {
        $this->model = $model;
    }

    public function notEmptyConversationsByUserId($userId, $nameFilter = null)
    {
        $this->userId = $userId;

        return $this->model
            ->select('conversations.*', 'message_orders.order')
            ->whereHas('users', function ($query) use ($nameFilter) {
                $query->where('users.id', $this->userId);
            })
            ->when($nameFilter, function ($query) use ($nameFilter) {
                return $query->whereHas('users', function ($query) use ($nameFilter) {
                    $query->where('users.name', 'LIKE', "%$nameFilter%");
                });
            })
            ->wherehas('lastMessage')
            ->with(['lastMessage', 'users'])
            ->join(
                DB::raw(
                    '(
                        SELECT max(id) \'order\', conversation_id 
                        FROM messages 
                        GROUP BY conversation_id
                    ) as message_orders'
                ),
                'conversations.id',
                '=',
                'message_orders.conversation_id'
            )->orderBy('message_orders.order', 'desc')
            ->paginate();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function createConversation(array $participantIds = [])
    {
        $conversation = $this->model->create();

        if (!empty($participantIds)) {
            $conversation->users()->attach($participantIds);
        }

        return $conversation;
    }

    public function createMessage(Conversation $conversation, array $data)
    {
        return $conversation->messages()->create($data);
    }

    public function findParticipantsConversation(array $participantIds)
    {
        $conversations = $this->model->withCount('users')->with('users')->get();

        return $conversations->first(function ($conversation) use ($participantIds) {
            return $this->hasUsers($conversation, $participantIds)
                && $conversation->users_count == count($participantIds);
        });
    }

    public function hasUsers(Conversation $conversation, array $userIds): bool
    {
        $contains = false;

        foreach ($userIds as $userId) {
            $contains = $conversation->users->contains(function ($user) use ($userId) {
                return $user->id === $userId;
            });
        }
        return $contains;
    }

    public function isUsersInConversation($conversationId, array $userIds): bool
    {
        return $this->model->where('id', $conversationId)
                ->whereHas('users', function ($user) use ($userIds) {
                    $user->whereIn('id', $userIds);
                })->count() > 0;
    }

    public function delete($conversationId)
    {
        try {
            return $this->model->destroy($conversationId);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function messagesByConversation(
        Conversation $conversation,
        Request $request,
        int $userId
    ): LengthAwarePaginator {
        return $conversation->messages()
            ->when($request->has('q'), function ($query) use ($request) {
                $query->where('body', 'like', "%{$request->get('q')}%");
            })
            ->when($request->has('withTrashed'), function ($query) use ($userId, $conversation) {
                $query->withTrashed()
                    ->whereNull('deleted_at')
                    ->orWhere('deleted_at', '<>', null)
                    ->where('user_id', '=', $userId);
            })
            ->orderBy('id', 'desc')
            ->paginate();
    }

    public function markAsRead($userId, $conversation): int
    {
        return $conversation->users()->newPivotStatement()->where('user_id', $userId)->update([
            'is_seen' => true,
            'unread_count' => 0,
            'updated_at' => Carbon::now()
        ]);
    }

    public function markAsUnReadForRecipients(array $usersId, $conversation): int
    {
        $updated = 0;

        foreach ($usersId as $userId) {
            $isSuccess = $conversation->users()->newPivotStatement()->where('user_id', $userId)->update([
                'is_seen' => false,
                'unread_count' => $this->getUnreadCount($conversation, $userId),
            ]);

            if ($isSuccess) {
                $updated++;
            }
        }
        return $updated;
    }

    public function messageRecipients(Conversation $conversation, $senderId): Collection
    {
        return $conversation->users()->where('id', '<>', $senderId)->get();
    }

    protected function getUnreadCount($conversation, $userId)
    {
        $lastSeen = $conversation->users->where('id', $userId)->first()->pivot->updated_at;

        return $conversation->messages()
            ->where('created_at', '>=', $lastSeen)
            ->where('user_id', '<>', $userId)
            ->count();
    }

    public function findWhereBodyLike(Conversation $conversation, $searchString): SupportCollection
    {
        return $conversation->messages()->where('body', 'LIKE', "%$searchString%")->pluck('id');
    }

    public function findMessagesAroundSearchResult(Conversation $conversation, array $range): Collection
    {
        return $conversation->messages()->whereBetween('id', $range)->get();
    }
}
