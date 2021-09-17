<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('author_user_id');
            $table->string('name_chat')->unique();
            $table->integer('status_chat');
            $table->string('chat_avatar');
            $table->string('location');
            $table->string('favorites');
            $table->string('name');
            $table->integer('user_id');
            $table->integer('chat_id');
            $table->integer('banned')->nullable();
            $table->string('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
