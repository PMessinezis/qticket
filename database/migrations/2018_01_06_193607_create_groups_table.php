<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupsTable extends Migration {

	public function up()
	{
		Schema::create('groups', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 40)->unique();
			$table->integer('department_id')->unsigned()->nullable()->index();
			$table->boolean('notifyMembers')->default(0);
			$table->boolean('isActive')->default(1);
			$table->timestamps();
		});

		App\Group::create(['name' => 'Helpdesk']);
	}

	public function down()
	{
		Schema::drop('groups');
	}
}