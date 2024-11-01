<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ScheduleRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_bookings' => 'required|integer|between:3,60',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages()
    {
        return [
            'doctor_id.required' => 'Doctor is required.',
            'doctor_id.exists' => 'The selected doctor does not exist.',
            'title.required' => 'Schedule title is required.',
            'date.required' => 'Schedule date is required.',
            'date.after_or_equal' => 'Schedule date cannot be before today.', 
            'start_time.required' => 'Start time is required.',
            'end_time.required' => 'End time is required and must be after the start time.',
            'max_bookings.required' => 'Maximum bookings are required.',
            'max_bookings.between' => 'Maximum bookings must be between 20 and 60.',
        ];
    }

   
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $date = Carbon::parse($this->input('date'));
            $start_time = Carbon::parse($this->input('start_time'));
            $end_time = Carbon::parse($this->input('end_time'));
            $current_time = Carbon::now(); 

            
            if ($date->isToday() && $start_time->lt($current_time)) {
                $validator->errors()->add('start_time', 'The start time cannot be in the past for today\'s schedule.');
            }

            
            $hoursDifference = $start_time->diffInHours($end_time);

            if ($hoursDifference < 5) {
                $validator->errors()->add('end_time', 'The time difference between start time and end time must be at least 5 hours.');
            }

            if ($hoursDifference > 10) {
                $validator->errors()->add('end_time', 'The time difference between start time and end time cannot exceed 10 hours.');
            }
        });
    }

}
