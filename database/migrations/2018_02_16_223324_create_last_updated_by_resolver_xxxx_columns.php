<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLastUpdatedByResolverXxxxColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('lastUpdatedByResolver_at')->nullable()->after('closedDateTime')->index();
            $table->string('lastUpdatedByResolver_uid', 16)->nullable()->after('closedDateTime')->index();    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
