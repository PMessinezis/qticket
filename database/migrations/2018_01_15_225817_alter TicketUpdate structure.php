<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTicketUpdateStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketupdates', function (Blueprint $table) {
            $table->dropColumn('fromStatus_id');
            $table->dropColumn('toStatus_id');
            $table->string('what',100)->after('comment')->index();
            $table->string('newValue',100)->after('what')->index();
            $table->integer('attachment_id')->after('newValue')->unsigned()->index();
        });

        Schema::table('attachments', function(Blueprint $table) {
            $table->integer('ticket_id')->unsigned()->after('id')->index();
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
            // $table->integer('fromStatus_id');
            // $table->integer('toStatus_id');
            // $table->dropColumn('what');
            // $table->dropColumn('newValue');
            // $table->dropColumn('attachment_id');
        });

        Schema::table('attachments', function(Blueprint $table) {
            // $table->dropColumn('ticket_id');
        });
    }
}
