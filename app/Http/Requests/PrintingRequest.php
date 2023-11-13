<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PrintingRequest extends FormRequest
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
            'jobid' => 'required|integer',
            'pages' => 'required|integer',
            'copies' => 'required|integer',
            'filename' => 'required',
            'filesize' => 'required|integer',
            'user' => 'required',
            'host' => 'required',
            'printer' => 'required|alpha_dash',
        ];
    }

    /* 03/10/2023 - Thiago: Acho que não é mais necessário depois que saímos do tea4cups 
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'data' => $validator->errors(),
            ]));
    }
    */
