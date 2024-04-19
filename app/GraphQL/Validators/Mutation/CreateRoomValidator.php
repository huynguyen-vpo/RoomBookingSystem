<?php declare(strict_types=1);

namespace App\GraphQL\Validators\Mutation;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateRoomValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'room_number' => [
                'required', 
                Rule::exists('rooms', 'room_number')
            ],
        ];
    }
    public function messages(): array{
        return [
            'room_number.required' => 'Room Number Is Required!!!',
            'room_number.exists' => 'Room Number Is Duplicated!!!',
        ];
    }
}
