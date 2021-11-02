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
            $table->string('avatar_post', 'img/demo/avatars/avatar-f.png');
            $table->string('title_post');
            $table->integer('favorites')->nullable();
            $table->integer('postable_id');
            $table->integer('banned')->nullable();
            $table->integer('post_id')->nullable();
            $table->integer('info_id');
            $table->integer('social_id');
            $table->string('c');
            $table->string('search_post');
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
