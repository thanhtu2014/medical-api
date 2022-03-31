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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('type', 24);
            $table->string('email', 255);
            $table->string('name', 128);
            $table->string('password', 1024);
            $table->string('key', 1024);
            $table->string('token', 1024);
            $table->dateTime('expired', $precision = 0)->nullable();
            $table->string('plan', 24);
            $table->string('code', 6)->nullable();
            $table->string('status', 24);
            $table->char('chg', 1)->default('Y');
            $table->string('new_by', 128);
            $table->dateTime('new_ts', $precision = 0)->useCurrent();
            $table->string('upd_by', 128)->default(null);
            $table->dateTime('upd_ts', $precision = 0)->default(null);

            $table->engine = 'InnoDB';
            $table->unique('email');
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
};
