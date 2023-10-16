<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterviewRequest extends FormRequest
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
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'csv' => 'required|max:204800',
			// 'csv.*' => 'mimes:text/csv',
			'csv.*' => 'mimetypes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt',
		];
	}

	public function attributes(): array
	{
		return [

			'csv' => 'CSV file/s',
		];
	}

	public function messages(): array
	{
		return [
			// 'csv' => '',
		];
	}

}
