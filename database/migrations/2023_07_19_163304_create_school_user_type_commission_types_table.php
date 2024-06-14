<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolUserTypeCommissionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_user_type_commission_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id');
            $table->foreignId('user_type_id');
            // Add other columns to the table
            $table->enum('commission_type', ['cash', 'point', 'coupon']);
            $table->softDeletes();
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
        Schema::dropIfExists('school_user_type_commission_types');
    }
}
