<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\UserRole;
use BenSampo\Enum\Rules\EnumValue;

class UpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
            'address' => ['string', 'min:3', 'max:255'],
            'phone' => [
                'required',
                'regex:/0[1-9]{9}/',
                Rule::unique('users')->ignore($this->user),
            ],
            'role' => [new EnumValue(UserRole::class)],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'role' => empty($this->role) ? 0 : intval($this->role),
        ]);
    }
}
