<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilelinkStoreRequest as StoreRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;

class FilelinkController extends CrudController {

	public function setup() {
		$this->crud->setModel('App\Filelink');
		$this->crud->setRoute(config('backpack.base.route_prefix') . '/filelink');
		$this->crud->setEntityNameStrings('How-To Document', 'How-To Documents');

		$this->crud->denyAccess('update');

		$columns = [
			[
				'label' => "Document Description",
				'name' => 'description',
			],
			[
				'label' => "Document",
				'name' => 'fileName',
				'type' => 'model_function',
				'function_name' => 'linkhtml',
				'limit' => 300,
			],
		];

		$this->crud->addColumns($columns);

		$fields = [
			[
				'label' => "Document Description",
				'name' => 'description',
				'attributes' => [
					'placeholder' => 'The description of the file',
				],
			],
			[
				'name' => 'fileName',
				'label' => 'Document to upload',
				'type' => 'upload',
				'upload' => true,
			],
		];

		$this->crud->addFields($fields, 'both');

		$this->crud->setRequiredFields(StoreRequest::class, 'create');
		$this->crud->setRequiredFields(UpdateRequest::class, 'edit');
	}

	public function store(StoreRequest $request) {

		return parent::storeCrud();
	}

	public function update(UpdateRequest $request) {
		return parent::updateCrud();
	}

}

?>