<?php

namespace Stilldesign\Messenger\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ConversationRequest
 * @package Stilldesign\Messenger\Http\Requests
 */
class ConversationRequest extends FormRequest
{
    protected $guard;

    public function __construct(Guard $guard)
    {
        parent::__construct();

        $this->guard = $guard;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->guard->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'addresseeId' => 'required'
        ];
    }
}
