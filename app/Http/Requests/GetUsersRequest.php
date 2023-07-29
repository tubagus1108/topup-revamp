<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUsersRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'draw' => 'required|integer',
            'start' => 'required|integer',
            'length' => 'required|integer',
            'order.0.column' => 'required|string',
            'order.0.dir' => 'required|in:asc,desc',
        ];
    }
}
