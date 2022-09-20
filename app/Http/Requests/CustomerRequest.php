<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'address' => 'required|min:5',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama barang harus diisi!',
            'name.min' => 'Panjang nama barang minimal 3 karakter!',
            'address.required' => 'Nama barang harus diisi!',
            'address.min' => 'Panjang nama barang minimal 5 karakter!',
        ];
    }

}
