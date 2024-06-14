<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_kh');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->string('color')->default('#bbbbbb');
            
            $table->integer('created_by')->default('0');
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
        Schema::dropIfExists('sources');
    }
}
