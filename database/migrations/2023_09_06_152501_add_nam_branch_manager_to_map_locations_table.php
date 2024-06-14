<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamBranchManagerToMapLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('map_locations', function (Blueprint $table) {
            $table->string('name_branch_manager')->nullable();
            $table->string('cultural_level')->nullable();
            $table->decimal('price_program')->nullable();
            $table->string('specail_program')->nullable();
            $table->text('address')->nullable();
            $table->decimal('qaunity_staff')->nullable();
            $table->decimal('quantity_student')->nullable();
            $table->string('social_media')->nullable();
            $table->string('other')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('map_locations', function (Blueprint $table) {
            $table->dropColumn('name_branch_manager');
            $table->dropColumn('cultural_level');
            $table->dropColumn('price_program');
            $table->dropColumn('specail_program');
            $table->dropColumn('address');
            $table->dropColumn('qaunity_staff');
            $table->dropColumn('quantity_student');
            $table->dropColumn('social_media');
            $table->dropColumn('other');

        });
    }
}
