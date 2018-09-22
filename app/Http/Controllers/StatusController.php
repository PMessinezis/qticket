<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class StatusController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Status');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/status');
        $this->crud->setEntityNameStrings('status', 'statuses');

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