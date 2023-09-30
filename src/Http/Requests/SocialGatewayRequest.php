<?php

namespace Leafwrap\SocialConnects\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Leafwrap\SocialConnects\Traits\Helper;

class SocialGatewayRequest extends FormRequest
{
    use Helper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'gateway'     => 'sometimes|required',
            'credentials' => 'sometimes|array',
            'additional'  => 'sometimes|array',
            'status'      => 'sometimes|required|boolean',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException($this->leafwrapValidateError($validator->errors()));
        }
        parent::failedValidation($validator);
    }
}
