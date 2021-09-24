<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('user_id');
            $table->string('text');
            $table->string('name_post');
            $table->string('avatar_post');
            $table->string('title_post')->unique();
            $table->integer('favorites');
            $table->integer('postable_id');
            $table->integer('banned');
            $table->integer('post_id');
            $table->integer('info_id');
            $table->integer('social_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
