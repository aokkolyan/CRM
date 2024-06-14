<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyReportReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_report_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constraint('branches');
            $table->foreignId('created_by')->constraint('users');
            $table->foreignId('prepared_by')->nullable()->constraint('users');
            $table->string('prepared_remark')->nullable();
            $table->foreignId('checked_by')->nullable()->constraint('users');
            $table->string('checked_remark')->nullable();
            $table->foreignId('approved_by')->nullable()->constraint('users');
            $table->string('approved_remark')->nullable();
            $table->dateTime('prepared_at')->nullable();
            $table->dateTime('checked_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('from');
            $table->dateTime('to');
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
        Schema::dropIfExists('weekly_report_referrals');
    }
}
