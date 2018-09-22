<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusesTable extends Migration {

	public function up()
	{
		Schema::create('statuses', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 32)->unique();
		});
	}

	public function down()
	{
		Schema::drop('statuses');
	}
}