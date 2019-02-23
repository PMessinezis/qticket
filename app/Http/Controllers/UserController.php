<?php

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;

class UserController extends CrudController {

	public function setup() {
		$this->crud->setModel('App\User');
		$this->crud->setRoute(config('backpack.base.route_prefix') . '/user');
		$this->crud->setEntityNameStrings('user', 'users');

		$this->crud->denyAccess(['delete']);

		$this->crud->setFromDb();
		$this->crud->addColumn(['name' => 'uid', 'label' => 'UserID'])->beforeColumn('firstname');

		$this->crud->removeColumns(["remember_token", "isTempEntry"]);
		$this->crud->removeFields(["remember_token", "isTempEntry"], 'both');
		$this->crud->orderBy('uid');
		$this->crud->removeColumn('id');
		$this->crud->addField('uid')->beforeField('id');
		$this->crud->removeField('id');
		$this->crud->setDefaultPageLength(100);
		$this->crud->addField(
			[ // Select2Multiple = n-n relationship (with pivot table)
				'label' => "Viewer of Groups",
				'type' => 'select_multiple',
				'name' => 'viewerOf', // the method that defines the relationship in your Model
				'entity' => 'viewerOf', // the method that defines the relationship in your Model
				'attribute' => 'gid', // foreign key attribute that is shown to user
				'model' => "App\ADGroup", // foreign key model
				'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
			],
			'update')->beforeField('topothesia');
	}

	public function store(StoreRequest $request) {
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request) {
		return parent::updateCrud();
	}

}

?>