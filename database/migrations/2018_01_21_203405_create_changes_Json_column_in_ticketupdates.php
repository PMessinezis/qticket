<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangesJsonColumnInTicketupdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketupdates', function (Blueprint $table) {
            $table->dropColumn('what');
            $table->dropColumn('newValue');

            $table->text('changeslog')->after('comment')->nullable();
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
            $table->string('what');
            $table->string('newValue');

            $table->dropColumn('changes');
        });
    }
}
