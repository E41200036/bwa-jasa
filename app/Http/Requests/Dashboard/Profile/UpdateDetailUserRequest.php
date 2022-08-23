<?php

namespace App\Http\Requests\Dashboard\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'photo'          => ['nullable', 'file', 'max:1024'],
            'role'           => ['nullable'],
            'contact_number' => ['required', 'regex:/^[0-9]+$/', 'max:12'],
            'biography'      => ['nullable', 'string', 'max:5000'],
        ];
    }
}
