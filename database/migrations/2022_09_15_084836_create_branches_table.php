<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_kh');
            $table->string('code')->nullable();
            $table->string('description',100)->nullable();
            $table->softDeletes();
            
            $table->timestamps();
            $table->foreignId('school_id');
            $table->integer('commission')->default('5');
            $table->integer('created_by')->default('0');
            $table->integer('deleted_by')->nullable();
            $table->tinyInteger('max_discount')->default(15);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
