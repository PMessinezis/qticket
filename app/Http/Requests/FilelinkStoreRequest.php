<?php

namespace App\Http\Requests;

use \Backpack\CRUD\app\Http\Requests\CrudRequest;

class FilelinkStoreRequest extends CrudRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'description' => 'required',
			'fileName' => 'required',
		];
	}

	public function messages() {
		return [
			'description.required' => 'Document Description is required!',
			'fileName.required' => 'A Document to upload is required!',
		];
	}

}
