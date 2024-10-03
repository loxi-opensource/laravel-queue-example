<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('trade_no')->unique()->comment('订单号');
            $table->float('order_amount', 2)->comment('订单金额');
            $table->tinyInteger('status')->comment('订单状态');
            $table->string('payment_no')->nullable()->comment('支付渠道单号');
            $table->float('pay_amount', 2)->nullable()->comment('支付金额');
            $table->timestamp('pay_time')->nullable()->comment('支付时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
