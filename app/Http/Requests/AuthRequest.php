<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        switch ($this->url()) {
            case route('adminLogin'):
                return $this->adminLoginRule();
            case route('changePassword', ['id' => $this->route('id')]):
                return $this->changePasswordRule();
            default:
                return [];
        }
    }
    public function adminLoginRule(){
        return [
            'email'=> 'string|required',
            'password' => 'string|required'
        ];
    }
    public function changePasswordRule(){
        return [
            'password' => 'string|required',
            'newPassword' => 'string|confirmed|required'
        ];
    }
}
