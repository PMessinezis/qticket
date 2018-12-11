<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class SubcategoryController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Subcategory');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/subcategory');
        $this->crud->setEntityNameStrings('subcategory', 'subcategories');

        $columns=[
                  [ 'name' => 'name' , 'label' => 'Sub-Category Name'],
                  [
                   'label' => "Category",
                   'type' => 'select',
                   'name' => 'Category_id', // the db column for the foreign key
                   'entity' => 'Category', // the method that defines the relationship in your Model
                   'attribute' => 'name', // foreign key attribute that is shown to user
                   'model' => "App\Category" // foreign key model
                  ],
                 
                ];

        $this->crud->addColumns($columns);



        
        $fields=[
                  [ 'name' => 'name',
                    'label' => 'Sub-Category Name'],
                  [
                   'label' => "Category (optional)",
                   'type' => 'select2',
                   'name' => 'Category_id', // the db column for the foreign key
                   'entity' => 'Category', // the method that defines the relationship in your Model
                   'attribute' => 'name', // foreign key attribute that is shown to user
                   'model' => "App\Category" // foreign key model
                  ],
                 
                ];

        $this->crud->addFields($fields,'both');
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