<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->index('fk_order_service_to_users');
            $table->foreignId('freelancer_id')->nullable()->index('fk_order_freelancer_to_users');
            $table->foreignId('buyer_id')->nullable()->index('fk_order_buyer_to_users');
            $table->longText('file');
            $table->longText('note')->nullable();
            $table->date('expired');
            $table->foreignId('order_status_id')->nullable()->index('fk_order_to_order_status');
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
        Schema::dropIfExists('order');
    }
};
