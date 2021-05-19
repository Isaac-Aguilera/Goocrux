<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Notifications', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('noti_desc');
            $table->integer('user_id');
            $table->integer('video_id');
            $table->tinyInteger('state');
            $table->string('type');
            $table->dateTime('updated_at')->useCurrent();
            $table->dateTime('created_at')->useCurrent();
            $table->foreign('user_id', 'Notifications_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('video_id', 'Notifications_ibfk_2')->references('id')->on('Videos')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Notifications');
    }
}
