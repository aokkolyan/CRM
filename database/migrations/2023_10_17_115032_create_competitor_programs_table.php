<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitorProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitor_programs', function (Blueprint $table) {
            $table->id();
            $table->string('promotion');
            $table->float('program_price');
            $table->integer('program_student');
            $table->foreignId('program_id')->constrained();
            $table->foreignId('map_location_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competitor_programs');
    }
}
