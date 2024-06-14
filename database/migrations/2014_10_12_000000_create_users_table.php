<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('user_name')->unique();
            $table->string('phone')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->rememberToken();
            
            $table->foreignId('created_by')->default('0');
            $table->foreignId('school_id')->default('1');
            $table->foreignId('branch_id')->default('1');
            $table->foreignId('user_type_id')->default('1');
            $table->timestamps();
            $table->timestamp('last_used_at')->nullable();
            $table->softDeletes();

            
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
