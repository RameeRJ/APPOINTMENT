<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string',
            'schedule_id' => 'required|exists:schedules,id',
            'age' => 'required|integer|min:1',
            'phone' => 'required|string|max:15',
            'place' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'schedule_id.required' => 'Schedule ID is required.',
            'schedule_id.exists' => 'Selected schedule does not exist.',
            'age.required' => 'Age is required.',
            'phone.required' => 'Phone number is required.',
            'place.required' => 'Place is required.',
        ];
    }
}