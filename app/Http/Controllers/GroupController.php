<?php 

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

use Illuminate\Http\Request;

use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;



class GroupController extends CrudController  
{

  public function setup() {
        $this->crud->setModel('App\Group');
        $this->crud->setRoute(config('backpack.base.route_prefix')  . '/group');
        $this->crud->setEntityNameStrings('group', 'groups');

        $columns=[
                  [ 'name' => 'name'],
                  [
                   'label' => "Department",
                   'type' => 'select',
                   'name' => 'department_id', // the db column for the foreign key
                   'entity' => 'department', // the method that defines the relationship in your Model
                   'attribute' => 'name', // foreign key attribute that is shown to user
                   'model' => "App\Department" // foreign key model
                  ],
                  [                                    
                    'label' => 'Notifications',
                    'type' => 'check',
                    'name' => 'notifyMembers',
                    'default' => 0,
                  ],
                  [                                    
                    'label' => 'Active',
                    'type' => 'check',
                    'name' => 'isActive',
                    'default' => 1,
                  ],
                ];

        $this->crud->addColumns($columns);



        $this->crud->addField('name','both');

        $fields=[
                  [
                   'label' => "Department",
                   'type' => 'select2',
                   'name' => 'department_id', // the db column for the foreign key
                   'entity' => 'department', // the method that defines the relationship in your Model
                   'attribute' => 'name', // foreign key attribute that is shown to user
                   'model' => "App\Department" // foreign key model
                  ],
                  [                                    
                    'label' => 'Notifications',
                    'type' => 'checkbox',
                    'name' => 'notifyMembers',
                    'default' => 0,
                  ],
                  [                                    
                    'label' => 'Active',
                    'type' => 'checkbox',
                    'name' => 'isActive',
                    'default' => 1,
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