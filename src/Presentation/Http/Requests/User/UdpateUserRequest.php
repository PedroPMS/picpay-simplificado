<?php

namespace Picpay\Presentation\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Picpay\Domain\Enums\User\UserType;

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
