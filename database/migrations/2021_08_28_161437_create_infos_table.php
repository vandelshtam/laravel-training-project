<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('occupation')->nullable();
            $table->string('location')->nullable();
            $table->text('position')->nullable();
            $table->text('phone')->nullable();
            $table->integer('status')->default(0);
            $table->string('avatar', 'img/demo/avatars/avatar-f.png');
            $table->integer('user_id');
            $table->integer('infosable_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infos');
    }
}
