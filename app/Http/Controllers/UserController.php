<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class UserController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\User');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/user');
        $this->crud->setEntityNameStrings('user', 'users');

        $this->crud->denyAccess(['update', 'delete']);

        $this->crud->setFromDb();
        $this->crud->addColumn(['name'=> 'uid' , 'label' => 'UserID'])->beforeColumn('firstname');

        $this->crud->removeColumns(["remember_token", "isTempEntry"]);
        $this->crud->removeFields(["remember_token", "isTempEntry"],'both');
        $this->crud->orderBy('uid');
        $this->crud->removeColumn('id');
        $this->crud->addField('uid')->beforeField('id');
        $this->crud->removeField('id');
        $this->crud->setDefaultPageLength(100);
    }

  public function store(StoreRequest $request)
  {
    return parent::storeCrud();
  }

  public function update(UpdateRequest $request)
  {
    return parent::updateCrud();
  }

}


?>