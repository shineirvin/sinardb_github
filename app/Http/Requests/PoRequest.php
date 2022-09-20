<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoRequest extends FormRequest
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
            'po' => 'required',
            'do' => 'required',
            'date' => 'required',
            'customerID' => 'required',
            'poDetailsID' => 'required',
            'grandTotal' => 'required',
            'terbilang' => 'required',
        ];
    }
}
