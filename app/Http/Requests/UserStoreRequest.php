<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UserStoreRequest extends FormRequest
{

 

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()->all()
            ], 422)
        );
    }
    
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
            'name' => ['required'],
            'lastname' => ['required'],
            'email' => ['required','unique:users'],
            'idnumber' => ['required','unique:users']
        ];
    }

    /**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
public function messages()
{
    
    return [
        'name.required' => 'El nombre es requerido.',
        'lastname.required' => 'El apellido es requerido.',
        'email.unique' => 'El :attribute  ya existe en otra cuenta.',
        'email.required' => 'El :attribute es requerido.',
        'idnumber.required' => 'El número de documento es requerido.',
        'idnumber.unique' => 'El número de documento ya existe en otra cuenta.'
    ];
}
}
