<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsViewerOfADGroupTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('ticketsViewerOfADGroup', function (Blueprint $table) {
			$table->increments('id');
			$table->string('adgroup_gid', 160)->index();
			$table->string('user_uid', 60)->index();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('ticketsViewerOfADGroup');
	}
}
