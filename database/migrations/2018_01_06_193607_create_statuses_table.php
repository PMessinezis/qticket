<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatusesTable extends Migration {

	public function up()
	{
		Schema::create('statuses', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 32)->unique();
			$table->boolean('isTerminal')->nullable()->index()->default(0);//
			$table->timestamps();
		});

		App\Status::create(['name' => 'Open'] );
		App\Status::create(['name' => 'Closed'    , 'isTerminal' => 1 ] );
		App\Status::create(['name' => 'Cancelled' , 'isTerminal' => 1 ] );
	}

	public function down()
	{
		Schema::drop('statuses');
	}
}