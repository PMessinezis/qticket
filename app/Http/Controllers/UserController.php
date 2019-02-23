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
		$this->crud->orderBy('lastname');
		$this->crud->removeColumn('id');
		$this->crud->addField('uid')->beforeField('id');
		$this->crud->addColumn('uid')->beforeColumn('firstname');
		$this->crud->removeField('id');
		$this->crud->removeField('directorate');
		$this->crud->removeField('nomiko');
		$this->crud->removeField('description');
		$this->crud->removeField('topothesia');
		$this->crud->removeField('manager_uid');
		$this->crud->setDefaultPageLength(100);
		$this->crud->addField(
			[ // Select2Multiple = n-n relationship (with pivot table)
				'label' => "Viewer of Groups",
				'type' => 'select2_multiple',
				'name' => 'viewerOf', // the method that defines the relationship in your Model
				'entity' => 'viewerOf', // the method that defines the relationship in your Model
				'attribute' => 'gid', // foreign key attribute that is shown to user
				'model' => "App\ADGroup", // foreign key model
				'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
			],
			'update');

		// $fields = [
		// 	'uid', 'firstname', 'lastname', 'email', 'phone1', 'phone2',
		// 	[ // Select2Multiple = n-n relationship (with pivot table)
		// 		'label' => "Viewer of Groups",
		// 		'type' => 'select2_multiple',
		// 		'name' => 'viewerOf', // the method that defines the relationship in your Model
		// 		'entity' => 'viewerOf', // the method that defines the relationship in your Model
		// 		'attribute' => 'gid', // foreign key attribute that is shown to user
		// 		'model' => "App\ADGroup", // foreign key model
		// 		'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
		// 	],
		// ];
		// $cols = [
		// 	'uid', 'firstname', 'lastname', 'email', 'employeeid', 'phone1', 'phone2',
		// ];

		// $this->crud->addFields($fields);
		// $this->crud->setColumns($cols);
		$this->crud->query = $this->crud->query->orderBy('lastname', 'asc');
	}

	public function store(StoreRequest $request) {
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request) {
		return parent::updateCrud();
	}

}

?>