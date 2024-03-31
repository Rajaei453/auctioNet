<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
class registerRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'city' => ['required', 'string'],
        ];

    }
    public function messages()
    {
        return [
            'name.required'=>'The Name IS Required',
            'name.max'=>'The Name Is Too Long',
            'email.required'=>'The Email Is Required',
            'email.string'=>'The Email Shl=ould Be Required',
            'email.email'=>'This is not Email',
        ];

    }

}
