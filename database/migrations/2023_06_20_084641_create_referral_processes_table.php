<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_processes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('referral_id')->nullable();
            $table->foreignId('parent_referral_id')->constraint();
            $table->boolean('is_contact')->default(false);
            $table->text('remark')->nullable();
            $table->foreignId('created_by')->constraint('users');
            $table->foreignId('referral_status_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_processes');
    }
}
