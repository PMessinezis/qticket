<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class TypeController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Type');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/type');
        $this->crud->setEntityNameStrings('ticket category type', 'ticket category types');

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