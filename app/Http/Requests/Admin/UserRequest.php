<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId  = $this->route('user')?->id;
        $isStore = $this->isMethod('POST');

        return [
            'name'      => ['required', 'string', 'max:191'],
            'email'     => ['required', 'email', 'max:191',
                Rule::unique('users', 'email')->ignore($userId)],
            'role'      => ['required', 'in:admin,editor,viewer'],
            'is_active' => ['boolean'],
            // Password required on create, optional on update
            'password'  => [$isStore ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'       => 'This email address is already registered.',
            'password.required'  => 'A password is required when creating a user.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
