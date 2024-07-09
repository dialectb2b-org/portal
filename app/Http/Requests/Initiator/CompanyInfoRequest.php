<?php

namespace App\Http\Requests\Initiator;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AtLeastOneCheckboxSelected;

class CompanyInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'name' => ['required','max:100'],
            'pobox' => ['nullable','max:100'],
            'address' => ['required','max:100'],
            'street' => ['nullable','max:100'],
            'building' => ['required','max:100'],
            'country_id' => ['required'],
            'region_id' => ['required','array',new AtLeastOneCheckboxSelected],
            'domain' => ['nullable','regex:/^(?!-)[A-Za-z0-9-]{1,63}(?<!-)(\.[A-Za-z]{2,})+$/','max:100'],
            'country_code' => ['required','max:100'],
            'landline' => ['nullable','max:100'],
            'fax' => ['nullable','max:100'],
            'document_no' => ['required','max:100'],
            'expiry_date' => ['required','max:100'],
            'document' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your company name',
            'pobox.required' => 'Please enter your PO Box No.',
            'country_id.required' => 'Please choose your country',
            'region_id.required' => 'Please choose atleast 1 region',
            'address.required' => 'Please enter your address',
            'street.required' => 'Please enter your street.',
            'building.required' => 'Please enter your city.',
            'domain.required' => 'Please enter your website address.',
            'country_code.required' => 'Please enter your country code.',
            'fax.required' => 'Please enter your fax no.',
            'mobile.required' => 'Please enter your mobile no.',
            'document_no.required' => 'Please enter your document no.',
            'expiry_date.required' => 'Please enter your document expiry date.',
            'document' => 'Please upload document file'
        ];
    }
}
