<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class DepartmentController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Department');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/department');
        $this->crud->setEntityNameStrings('department', 'departments');

        $this->crud->setFromDb();
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