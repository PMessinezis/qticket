<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilelinksTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('filelinks', function (Blueprint $table) {
			$table->increments('id');
			$table->string('description', 60);
			$table->string('originalName', 512);
			$table->string('fileName', 512);
			$table->string('fileExt', 50);
			$table->string('mimeType', 512);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('filelinks');
	}
}
