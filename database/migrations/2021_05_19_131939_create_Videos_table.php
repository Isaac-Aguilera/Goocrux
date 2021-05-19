<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Videos', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user_id');
            $table->integer('categoria_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('video_path');
            $table->integer('views')->default(0);
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('categoria_id', 'categoria_id')->references('id')->on('Categories')->onDelete('set NULL')->onUpdate('cascade');
            $table->foreign('user_id', 'user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Videos');
    }
}
