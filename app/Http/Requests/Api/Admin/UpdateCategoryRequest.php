<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Support\Str;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends ApiRequest
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
            'name' => [
                'required',
                'min:3',
                'max:191',
                Rule::unique('categories')->ignore($this->category),
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique('categories')->ignore($this->category),
            ],
            'parent_id' => 'exists:categories,id|nullable'
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
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'parent_id' => $this->parent_id == 0 ? null : $this->parent_id,
        ]);
    }
}
