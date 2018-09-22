<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAttachmentsTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {

            $table->dropColumn('originalPath');
            $table->dropColumn('fileType');
            $table->dropColumn('file');
            $table->dropColumn('filePath');
        
            $table->string('originalName', 512)->after('filename');
            $table->string('filePath', 512)->after('filename');
            $table->string('fileExt', 50)->after('filename');
            $table->string('mimeType', 512)->after('filename');
            $table->integer('size')->after('mimeType');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropColumn('originalName');
            $table->dropColumn('filePath');
            $table->dropColumn('fileExt');
            $table->dropColumn('mimeType');
            $table->dropColumn('size');
        });
    }
}
