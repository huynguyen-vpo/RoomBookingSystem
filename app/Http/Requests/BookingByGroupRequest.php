<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingByGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'groupId' => 'bail|required|string',
            'userId' => 'bail|required|string',
            'checkInDate' => 'bail|required|date',
            'checkOutDate' => 'bail|required|date',
            'numberOfPeople' => 'bail|required|numeric',
            'singleNumber' => 'bail|required|numeric',
            'doubleNumber' => 'bail|required|numeric',
            'tripleNumber' => 'bail|required|numeric',
            'quarterNumber' => 'bail|required|numeric',
        ];
    }
}
