<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubcategoriesTable extends Migration {

	public function up()
	{
		Schema::create('subcategories', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100)->unique();
			$table->integer('category_id')->unsigned()->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('subcategories');
	}
}