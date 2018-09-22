<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatqticketUpdatesTable extends Migration {

	public function up()
	{
		Schema::create('ticketUpdates', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('ticket_id')->unsigned()->index();
			$table->text('comment')->index();
			$table->boolean('isSensitive')->default(0);
			$table->integer('fromStatus_id')->unsigned()->index();
			$table->integer('toStatus_id')->unsigned()->index();
			$table->integer('updatedBy_id')->unsigned()->index();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('ticketUpdates');
	}
}