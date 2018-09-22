<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Group;
use Backpack\CRUD\app\Http\Controllers\CrudController;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class CategoryController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Category');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/category');
        $this->crud->setEntityNameStrings('category', 'categories');

        $fields=[
                  [
                    'name' => 'name',
                  ],
                  [
                    'label' => 'Category Type',
                    'type' => 'select2',
                    'name' => 'type_id',
                    'attribute' => 'name',
                    'entity' => 'type',
                    'model' => 'App\Type'
                  ],
                  [
                    'label' => 'Default Resolver Group',
                    'type' => 'select2',
                    'name' => 'defaultGroup_id',
                    'attribute' => 'name',
                    'entity' => 'defaultGroup',
                    'model' => 'App\Group',
                    'default' => Group::where('name','HelpDesk')->first() ? Group::where('name','HelpDesk')->first()->id : '' ,
                  ], [  'name'=> 'instructions' , 'type' => 'textarea' , 'label' => 'User Instructions (when opening ticket of this category)'],
                  [
                    'label' => 'Active',
                    'type' => 'checkbox',
                    'name' => 'isActive',
                  ],

        ];

        $columns=[
                  [
                    'name' => 'name',
                  ],
                  [
                    'label' => 'Category Type',
                    'type' => 'select',
                    'name' => 'type_id',                   
                    'attribute' => 'name',
                    'entity' => 'type',
                    'model' => 'App\Type'                  
                  ],
                  [
                    'label' => 'Default Group',
                    'type' => 'select',
                    'name' => 'defaultGroup_id',
                    'attribute' => 'name',
                    'entity' => 'defaultGroup',
                    'model' => 'App\Group',
                  ],
                  [
                    'label' => 'Active',
                    'type' => 'check',
                    'name' => 'isActive',
                  ],
        ];

        $this->crud->addFields($fields,'both');
        $this->crud->setColumns($columns,'both');
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