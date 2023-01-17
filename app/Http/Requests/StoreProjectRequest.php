<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type_id' => ['nullable', 'exists:types,id'],
            // indichiamo quali regole devono essere validate oppure 'title' =>'required|max:50|unique:projects'
            'title' => ['required', 'max:150', 'unique:projects'],
            'cover_image' => ['nullable', 'image', 'max:512'],
            'description' => ['nullable'],
            'technologies' => ['exist:technologies,id']
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'il titolo è richiesto!',
            'description.required' => 'il contenuto della descrizione è richiesto!'
        ];
    }
}
