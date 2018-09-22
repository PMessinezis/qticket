<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class ResolverController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Resolver');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/resolver');
        $this->crud->setEntityNameStrings('resolver', 'resolvers');

        $fields=[ 
                  [  // Select2
                   'label' => "User",
                   'type' => 'select2',
                   'name' => 'user_uid', // the db column for the foreign key
                   'entity' => 'user', // the method that defines the relationship in your Model
                   'attribute' => 'name', // foreign key attribute that is shown to user
                   'model' => "App\User" ,// foreign key model
                   'model_key' => "uid" // foreign key model key
                  ],
                  [       // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Groups",
                    'type' => 'select2_multiple',
                    'name' => 'groups', // the method that defines the relationship in your Model
                    'entity' => 'groups', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => "App\Group", // foreign key model
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                ],
                [
                    'label' => 'Active',
                    'type' => 'checkbox',
                    'name' => 'isActive',
                    'default' => 1
                  ],
                ];

       $this->crud->addFields($fields);


        $columns=[ 
                  [  // Select2
                   'label' => "User",
                   'type' => 'text',
                   'name' => 'userWithUID'
                  ],
                  [       // Select2Multiple = n-n relationship (with pivot table)
                    'label' => "Groups",
                    'type' => 'select_multiple',
                    'name' => 'groups', // the method that defines the relationship in your Model
                    'entity' => 'groups', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    'model' => "App\Group", // foreign key model
                    'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                ],
                  [
                    'label' => 'Active',
                    'type' => 'check',
                    'name' => 'isActive',
                  ],
                ];


       $this->crud->setColumns($columns);
    
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