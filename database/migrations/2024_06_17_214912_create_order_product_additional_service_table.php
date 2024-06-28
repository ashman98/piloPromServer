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
        Schema::create('order_product_additional_service', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_product_id');
            $table->unsignedBigInteger('additional_service_id');

            $table->tinyInteger('del_status')->default(0);

            $table->foreign('order_product_id')
                ->references('id')->on('order_product')->onDelete('cascade');
            $table->foreign('additional_service_id')
                ->references('id')->on('additional_services')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_additional_service');
    }
};
