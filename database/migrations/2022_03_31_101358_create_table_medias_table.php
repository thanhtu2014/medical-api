<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('record_item')->nullable()->default(null);
            $table->string('fpath', 1024)->nullable()->default(null);
            $table->string('fname', 128)->nullable()->default(null);
            $table->string('fdisk', 1024)->nullable()->default(null);
            $table->string('name', 1024)->nullable()->default(null);
            $table->string('mime', 128)->nullable()->default(null);
            $table->string('fext', 24)->nullable()->default(null);
            $table->char('chg', 1)->default('Y');
            $table->string('new_by', 128);
            $table->dateTime('new_ts', $precision = 0)->useCurrent();
            $table->string('upd_by', 128)->default(null);
            $table->dateTime('upd_ts', $precision = 0)->default(null);

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
};
