<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketUpdatesTable extends Migration {

	public function up()
	{
		Schema::create('ticketupdates', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('ticket_id')->unsigned()->index();
			$table->text('comment');
			$table->boolean('isSensitive')->default(0);
			$table->integer('fromStatus_id')->unsigned()->index();
			$table->integer('toStatus_id')->unsigned()->index();
			$table->string('updatedBy_uid',16)->index();
			$table->timestamps();
		});
		DB::statement('ALTER TABLE `ticketupdates` ADD INDEX `ticketupdates_comment_idx` (`comment`(191));');

	}

	public function down()
	{
		Schema::drop('ticketupdates');
	}
}