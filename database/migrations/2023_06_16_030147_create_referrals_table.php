<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('parent_name');
            $table->string('phone');
            $table->string('parent2_name')->nullable();
            $table->string('phone2')->nullable();
            $table->foreignId('branch_id')->constraint();
            
            //Optional
            $table->string('student_name')->nullable();
            $table->foreignId('program_id')->nullable()->constraint('products');
            $table->string('level')->nullable();
            $table->string('shift')->nullable();
            $table->date('start_date')->nullable();
            $table->foreignId('referral_status_id')->nullable()->constraint();
            $table->foreignId('payment_status_id')->nullable()->constraint();
            $table->float('full_fee')->nullable();
            $table->float('fee')->nullable();
            $table->tinyInteger('discount_percentage')->default(0);
            $table->float('discount')->nullable();
            $table->tinyInteger('commission_percentage')->nullable();
            $table->float('commission')->nullable();
            $table->foreignId('created_by')->constraint('users');
            $table->foreignId('updated_by')->constraint('users');
            $table->timestamp('enrolled_at')->nullable();
            $table->foreignId('enrolled_by')->nullable()->constraint('users');
            $table->timestamp('paid_at')->nullable();

            
            $table->foreignId('parent_referral_id')->constraint('parent_referrals');
            $table->foreignId('weekly_report_referral_id')->nullable()->constraint('weekly_report_referrals');
            $table->string('weekly_report_comment')->nullable();
            $table->foreignId('commission_payment_id')->nullable()->constraint('commission_payments');
            $table->enum('commission_type', ['cash', 'point', 'coupon']);
            $table->float('wht')->default(0);
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
        Schema::dropIfExists('referrals');
    }
}
