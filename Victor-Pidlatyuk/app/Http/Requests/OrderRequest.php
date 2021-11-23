<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        if ($this->method() == 'PATCH')
            return [
                'status' => 'required|in:active,canceled,complete'
            ];
        else
            return [
                'product_id' => 'required|exists:products,id',
                'status' => Rule::in(['active', 'canceled', 'complete']),
                'user_email' => 'required|max:255',
                'user_full_name' => 'required|max:255',
                'phone' => 'required|regex:/(\+380)[0-9]{9}/',
                'stripeToken' => 'required'
            ];
    }
}
