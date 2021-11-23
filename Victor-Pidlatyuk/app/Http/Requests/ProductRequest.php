<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                'title'=>'max:100|unique:products,title',
                'pictures.*' => 'mimes:jpg,png|dimensions:min_width=1280',
                'category_id' => 'exists:categories,id',
                'description'=>'max:500',
                'price' => 'numeric|between:0.1,9999999.99'
            ];
        else
            return [
                'title'=>'required|max:100|unique:products,title',
                'pictures'=> 'required',
                'pictures.*' => 'mimes:jpg,png|dimensions:min_width=1280',
                'category_id' => 'required|exists:categories,id',
                'description'=>'required|max:500',
                'price' => 'required|numeric|between:0.1,9999999.99'
            ];
    }
}
