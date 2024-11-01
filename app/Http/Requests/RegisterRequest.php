<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Set to true to allow the request.
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|regex:/^\d{10}$/|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ];
    }

    /**
     * Customize the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Your name is required.',
            'email.required' => 'An email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'The phone number must be exactly 10 digits.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'A password is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.same' => 'The password confirmation does not match.',
            
        ];
    }
}