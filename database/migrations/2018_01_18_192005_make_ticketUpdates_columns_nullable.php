<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTicketUpdatesColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketupdates', function (Blueprint $table) {
            $table->text('comment')->nullable()->change();;
            $table->string('what',100)->nullable()->change();
            $table->string('newValue',100)->nullable()->change();
            $table->integer('attachment_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticketupdates', function (Blueprint $table) {
            //
        });
    }
}
