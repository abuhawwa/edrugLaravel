<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //#: Admin Gate only
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
            'firstName' => 'required|string|max:25',
            'lastName' => 'required|string|max:25',
            'email' => 'required|string|email|max:50|unique:users',
            'mobile' => 'required|numeric|unique:users,mobile|regex:/^[0-9]{10}$/',
            'dob' => 'required|date|before:-18 years',
            'gender' => 'required|in:Female,Male',
            'password' => 'required|string|min:7|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[@#$&*]).{7,15}$/',
        ];
    }
}
