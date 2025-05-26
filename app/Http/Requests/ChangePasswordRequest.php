<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Old Password didn\'t match');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'regex:/[0-9]/'],
            'confirm_password' => ['required', 'same:password', 'string', 'min:8', 'regex:/[0-9]/'],
        ];
    }
}
