<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSourcesTable extends Migration {

	public function up()
	{
		Schema::create('sources', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 32)->unique();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('sources');
	}
}