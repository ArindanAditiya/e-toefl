<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(request()->isMethod('PUT')) {
            $email = 'required|string|email|max:191|unique:users,email,'.request()->id;
            $password = 'sometimes|confirmed';
        } else {
            $email = 'required|string|email|max:191|unique:users';
            $password = 'required|confirmed';
        }

        return [
            'name' => 'required|string|max:191',
            'email' => $email,
            'password' => $password,
            'level' => 'required',
            'is_active' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('user.name'),
            'email' => __('user.email'),
            'password' => __('user.password'),
            'level' => __('user.level'),
            'is_active' => __('user.is_active'),
        ];
    }
}
