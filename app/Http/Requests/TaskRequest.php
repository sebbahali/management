<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id',
        ];
    }
}
