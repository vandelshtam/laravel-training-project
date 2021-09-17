<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('image');
            $table->integer('user_id');
            $table->integer('post_id');
            $table->integer('imageable_id');
            $table->string('imageable_type');
            $table->integer('chat_id');
            $table->integer('info_id');
            $table->string('location');
            $table->string('favorites');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
