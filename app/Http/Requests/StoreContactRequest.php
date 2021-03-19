<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use App\Rules\SpecialCharacters;
use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                new SpecialCharacters()
            ],
            'birthday' => [
                'required',
                'date_format:Y-m-d'
            ],
            'phone' => [
                'required',
                new PhoneNumber()
            ],
            'address' => [
                'required'
            ],
            'credit_card' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                'unique:contacts,email,'.auth()->id()
            ]
        ];
    }
}
