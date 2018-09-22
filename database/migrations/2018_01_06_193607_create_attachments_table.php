<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachmentsTable extends Migration {

	public function up()
	{
		Schema::create('attachments', function(Blueprint $table) {
			$table->increments('id');
			$table->string('filename', 512);
			$table->string('originalPath', 512);
			$table->string('fileType', 6)->index();
			$table->string('uploadedByUser_uid',16)->index();
			$table->binary('file');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('attachments');
	}
}