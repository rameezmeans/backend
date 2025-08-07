<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandECUCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users, or add logic here
    }

    public function rules()
    {
        return [
            'brand' => 'required',
            'ecu'   => 'required',
            'type'     => 'required|in:download,upload',
            'comment'  => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'brand_id.required' => 'Brand is required.',
            'brand_id.not_in'   => 'Please select a valid brand.',
            'ecu_id.required'   => 'ECU is required.',
            'ecu_id.not_in'     => 'Please select a valid ECU.',
        ];
    }
}