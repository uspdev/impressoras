<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrinterRequest extends FormRequest
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
        $rules = [
            'name'         => 'required',
            'machine_name' => ['required'],
            'rule_id'      => 'nullable',
        ];
        
        if ($this->method() == 'PATCH' || $this->method() == 'PUT'){
            array_push($rules['machine_name'], 'unique:printers,machine_name,' .$this->printer->id);
        }
        else{
            array_push($rules['machine_name'], 'unique:printers');
        }
        
        return $rules;
           
     }

    public function messages()
    {
        return [
            'name.required'         => 'O nome não pode ficar em branco',
            'machine_name.required' => 'O nome de máquina não pode ficar em branco',
	        'machine_name.unique'   => 'O nome da máquina deve ser único.',
        ];
    }
}
