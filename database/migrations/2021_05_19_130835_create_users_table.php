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
            $table->integer('id')->primary();
            $table->string('name');
            $table->string('surname');
            $table->string('nick')->unique('nick');
            $table->string('email')->unique('email');
            $table->string('password');
            $table->rememberToken();
            $table->string('image')->nullable();
            $table->string('role', 20);
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
            $table->text('channel_desc')->nullable();
            $table->string('banner')->nullable();
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
