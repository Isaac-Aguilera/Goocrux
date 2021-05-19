<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComentarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Comentaris', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user_id');
            $table->integer('video_id');
            $table->text('contingut');
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('user_id', 'Comentaris_ibfk_1')->references('id')->on('users')->onUpdate('cascade');
            $table->foreign('video_id', 'Comentaris_ibfk_2')->references('id')->on('Videos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Comentaris');
    }
}
