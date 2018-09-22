<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeSomeTicketsTableColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *

     * @return void
     */
    public function up()
    {

        DB::raw('SET FOREIGN_KEY_CHECKS = 0;');

        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('source_id')->nullable()->change();
            $table->integer('category_id')->nullable()->change();
            $table->integer('subcategory_id')->nullable()->change();
            $table->longText('description')->nullable()->change();
            $table->integer('status_id')->nullable()->change();
            $table->integer('assignedGroup_id')->nullable()->change();
            $table->integer('assignedVendor_id')->nullable()->change();
            $table->string('vendorRef', 50)->nullable()->change();;
            $table->datetime('openedDateTime')->nullable()->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
        });
    }
}
