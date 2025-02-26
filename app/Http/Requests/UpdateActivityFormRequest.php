<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule; 

class UpdateActivityFormRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('activities', 'name')->ignore($this->activity), 
            ],
            'description' => ['require', 'string'],
            'max_capacity' => ['required', 'integer', 'gt:0'],
            'start_date' => ['required', 'date'],
        ];
    }

/**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @return void
     *
     * @throws HttpResponseException
     */    protected function failedValidation(Validator $validator)
        {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }    
}
