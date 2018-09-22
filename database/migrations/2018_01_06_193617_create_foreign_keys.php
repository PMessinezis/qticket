<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
	    DB::raw('SET FOREIGN_KEY_CHECKS = 0;');

		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('requestedBy_uid')->references('uid')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});
		Schema::table('tickets', function(Blueprint $table) {
			$table->foreign('onBehalfOf_uid')->references('uid')->on('users')
						->onDelete('no action')
						->onUpdate('no action');
		});


	}

	public function down()
	{

		 DB::raw('SET FOREIGN_KEY_CHECKS = 0;');
	
	}
}