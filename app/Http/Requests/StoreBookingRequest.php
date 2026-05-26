<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tutor_id' => 'required|exists:tutors,id',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'location' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}