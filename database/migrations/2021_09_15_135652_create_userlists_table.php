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
            $table->integer('userlistable_id');
            $table->string('userlistable_type');
            $table->integer('status_chat')->nullable();
            $table->string('name');
            $table->integer('favorites')->nullable();
            $table->string('role', 'participant');
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
