<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->charset = 'utf8mb4';

            $table->bigIncrements('id')->comment('ファイルID');
            $table->string('name', 100)->comment('ファイル名');
            $table->string('path', 1000)->comment('ファイルパス');
            $table->string('type', 100)->comment('ファイルタイプ');
            $table->dateTime('created_at')->comment('登録日時');
            $table->dateTime('updated_at')->comment('更新日時');
        });

        DB::statement("ALTER TABLE files COMMENT 'ファイルテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
