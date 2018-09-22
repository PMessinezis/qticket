<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVendorsTable extends Migration {

	public function up()
	{
		Schema::create('vendors', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100)->unique();
			$table->longText('notes');
			$table->boolean('isActive')->index()->default(1);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('vendors');
	}
}