<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateResolversTable extends Migration {

	public function up()
	{
		Schema::create('resolvers', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unique()->unsigned();
			$table->boolean('isActive')->default(1);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('resolvers');
	}
}