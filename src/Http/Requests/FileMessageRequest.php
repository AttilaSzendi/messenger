<?php

namespace Stilldesign\Messenger\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;
use Stilldesign\Messenger\Contracts\ConversationRepositoryInterface;
use Stilldesign\Messenger\Contracts\FileExtensionManagerInterface;

class FileMessageRequest extends FormRequest
{
    protected $conversationRepository;
    protected $fileExtensionManager;
    protected $guard;

    public function __construct(
        ConversationRepositoryInterface $conversationRepository,
        FileExtensionManagerInterface $fileExtensionManager,
        Guard $guard
    ) {
        parent::__construct();

        $this->conversationRepository = $conversationRepository;
        $this->fileExtensionManager = $fileExtensionManager;
        $this->guard = $guard;
    }

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
            'file' => "required|file|mimes:{$this->fileExtensionManager->forRequestRule()}"
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'Please include a file!',
            'file.file' => 'Please include a file!',
            'file.mimes' => 'The file type is not supported!',
        ];
    }
}
