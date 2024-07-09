<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\Models\Company;
use App\Models\CompanyDocument;

class GuestInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
    **/
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        
        return [
            'company_id' => ['required','max:255'],
            'country_id' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'company_id.required' => 'Please enter your company id',
            'country_id.required' => 'Please choose your country',
        ];
    }
}
