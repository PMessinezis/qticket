<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 16)->unique();
            $table->string('firstname', 100)->index();
            $table->string('lastname', 100)->index();
            $table->string('directorate', 100)->nullable()->index();
            $table->string('tmhma', 100)->nullable()->index();
            $table->string('nomiko', 100)->nullable()->index();
            $table->string('email', 100)->nullable()->index();
            $table->string('phone1', 100)->nullable()->index();
            $table->string('phone2', 100)->nullable()->index();
            $table->string('topothesia', 100)->nullable()->index();
            $table->boolean('isTempEntry')->nullable()->index()->default(0);
            $table->string('manager_uid', 16)->nullable()->index();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
