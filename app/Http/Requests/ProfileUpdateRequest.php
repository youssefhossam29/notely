<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp|max:2048'],
            'city' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:0,1'],
            'bio' => ['nullable', 'string'],
        ];
    }


    public function messages(){
        return [
            'gender.boolean' => 'The gender field must be male or female.',
        ];
    }

}
