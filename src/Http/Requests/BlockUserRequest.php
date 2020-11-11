<?php

namespace Stilldesign\Messenger\Http\Requests;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MessageRequest
 * @package Stilldesign\Messenger\Http\Requests
 */
class BlockUserRequest extends FormRequest
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
            'userId' => 'required|numeric'
        ];
    }
}
