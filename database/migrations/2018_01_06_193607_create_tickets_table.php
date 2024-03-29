<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration {

	public function up()
	{
		Schema::create('tickets', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('source_id')->unsigned()->index();
			$table->string('requestedBy_uid', 16)->index();
			$table->string('onBehalfOf_uid', 16)->index();
			$table->integer('category_id')->unsigned()->index();
			$table->integer('subcategory_id')->unsigned()->index();
			$table->longText('description');
			$table->integer('status_id')->unsigned()->index();
			$table->string('priority', 16)->index()->default('Normal');
			$table->integer('parentTicket_id')->unsigned()->nullable()->index();
			$table->integer('assignedGroup_id')->unsigned()->index();
			$table->integer('assignedResolver_id')->unsigned()->nullable()->index();
			$table->integer('assignedVendor_id')->unsigned()->index();
			$table->string('vendorRef', 50);
			$table->datetime('vendorOpenedDate')->nullable();
			$table->datetime('vendorClosedDate')->nullable();
			$table->text('resolution')->nullable();
			$table->integer('rootCause_id')->unsigned()->nullable()->index();
			$table->datetime('openedDateTime')->index();
			$table->datetime('closedDateTime')->nullable()->index();
			$table->timestamps();
		});
		DB::statement('ALTER TABLE `tickets` ADD INDEX `tickets_description_idx` (`description`(191));');
		DB::statement('ALTER TABLE `tickets` ADD INDEX `tickets_resolution_idx` (`resolution`(191));');
	}

	public function down()
	{
		Schema::drop('tickets');
	}
}