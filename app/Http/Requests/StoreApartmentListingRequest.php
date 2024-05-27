<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApartmentListingRequest extends FormRequest
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
            'title' => 'required|max:30',
            'category' => 'required',
            'number_of_guest' => 'required|integer',
            'number_of_bedrooms' => 'required|integer',
            'number_of_kitchens' => 'required|integer',
            'amount' => 'required|numeric',
        ];
    }
}
