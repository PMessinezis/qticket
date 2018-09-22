<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	public function up()
	{
		Schema::create('categories', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100)->unique();
			$table->integer('type_id')->unsigned()->index();
			$table->integer('defaultGroup_id')->unsigned()->nullable()->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('categories');
	}
}