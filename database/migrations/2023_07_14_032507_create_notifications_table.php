<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constraint('users');
            $table->foreignId('user_id')->constraint('users');
            $table->foreignId('role_id')->constraint('roles');
            $table->string('message');
            $table->string('title');
            $table->timestamps();
            $table->string('link');
            $table->softDeletes();
            $table->tinyInteger('status')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
