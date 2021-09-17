<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userlists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('info_id');
            $table->integer('user_id');
            $table->integer('chat_id');
            $table->integer('imageable_id');
            $table->string('imageable_type');
            $table->integer('status_chat');
            $table->string('name');
            $table->integer('favorites');
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
        Schema::dropIfExists('userlists');
    }
}
