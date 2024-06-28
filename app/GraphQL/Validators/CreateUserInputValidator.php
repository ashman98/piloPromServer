<?php declare(strict_types=1);

namespace App\GraphQL\Validators;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateUserInputValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255'
//                'sometimes',
//                Rule::unique('users', 'name')->ignore($this->arg('id'), 'id'),
            ],
            'surname' => [
                'required',
                'max:255'
            ],
            'email' => [
                'email',
                'required',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->arg('id'), 'id'),
            ],
            'password' => [
                'required',
                'max:255'
            ],
            'password_confirmation' => [
                'required',
                'max:255'
            ],
            'role' => [
                'required',
                'max:255'
            ],
            'address' => [
                'required',
                'max:255'
            ],
            'city' => [
                'required',
                'max:255'
            ],
            'state' => [
                'required',
                'max:255'
            ],
            'zip' => [
                'required'
            ],
            'phone' => [
                'required',
                'max:255'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required',
        ];
    }
}
