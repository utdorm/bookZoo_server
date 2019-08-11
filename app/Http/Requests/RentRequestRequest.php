<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RentRequestRequest extends FormRequest
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
            // case route('newRequest'):
            //     return $this->newRequestRules();
            case route('adminNewRentRequest'):
                return $this->adminNewRequestRules();
            case route('getRequestByDate'):
                return $this->getRequestByDateRule();
            default:
                return [];
        }
    }
    public function newRequestRules() {
        return [
            'book_id' => 'required',
            'user_id' => 'required'
        ];
    }
    public function adminNewRequestRules() {
        return [
            'book_id' => 'required',
            'renter_name' => 'required|string',
            'phone_number' => 'required|string',
            'deposit' => 'numeric'
        ];
    }
    public function getRequestByDateRule(){
        return [
            'date' => 'string|required'
        ];
    }
}
