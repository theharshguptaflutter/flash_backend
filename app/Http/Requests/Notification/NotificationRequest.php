<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @todo This is not used for now. Need to implement this in AuthController class
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @todo This is not used for now. Need to implement this in AuthController class
     * @return array
     */
    public function rules()
    {
        return [
            'id'      => ['sometimes'],
            'message' => ['sometimes']
        ];
    }
}