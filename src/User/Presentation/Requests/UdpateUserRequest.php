<?php

namespace Picpay\User\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Picpay\User\Domain\Enums\UserType;

class UdpateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'cpf' => ['required', 'string'],
            'type' => ['required', new Enum(UserType::class)],
        ];
    }
}
