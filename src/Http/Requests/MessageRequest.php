<?php

namespace Stilldesign\Messenger\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;

/**
 * Class MessageRequest
 * @package Stilldesign\Messenger\Http\Requests
 */
class MessageRequest extends FormRequest
{
    protected $conversationRepository;
    protected $guard;

    public function __construct(
        ConversationRepositoryInterface $conversationRepository,
        Guard $guard
    ) {
        parent::__construct();

        $this->conversationRepository = $conversationRepository;
        $this->guard = $guard;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->conversationRepository->isUsersInConversation(
            $this->input('conversationId'),
            [$this->guard->user()->id]
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string'
        ];
    }
}
