<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactSellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:2000'],
            'first_name' => ['nullable', 'string', 'max:100', 'required_without:last_name'],
            'last_name' => ['nullable', 'string', 'max:100', 'required_without:first_name'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'message.required' => 'Le message est obligatoire.',
            'first_name.required_without' => 'Veuillez indiquer un prenom ou un nom.',
            'last_name.required_without' => 'Veuillez indiquer un nom ou un prenom.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email n'est pas valide.",
        ];
    }
}
