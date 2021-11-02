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
            $table->string('name_chat');
            $table->integer('status_chat')->nullable();
            $table->string('chat_avatar', 'img/demo/avatars/avatar-f.png');
            $table->string('location')->nullable();
            $table->string('favorites')->nullable();
            $table->string('name');
            $table->integer('user_id');
            $table->integer('chat_id')->nullable();
            $table->integer('banned')->nullable();
            $table->string('role', 'author');
            $table->string('c');
            $table->string('search_chat');
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
