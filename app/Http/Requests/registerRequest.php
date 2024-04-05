<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
class registerRequest extends FormRequest
{

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];

    }
    public function messages()
    {
        return [
            'first_name.required'=>'The Name IS Required',
            'first_name.max'=>'The Name Is Too Long',
            'last_name.required'=>'The Name IS Required',
            'last_name.max'=>'The Name Is Too Long',
            'email.required'=>'The Email Is Required',
            'email.string'=>'The Email Shl=ould Be Required',
            'email.email'=>'This is not Email',
        ];

    }

}
