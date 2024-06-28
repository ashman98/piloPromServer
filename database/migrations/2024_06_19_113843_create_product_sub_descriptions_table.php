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
        Schema::create('product_sub_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_description_id');

            $table->string('title');
            $table->string('description')->nullable();

            $table->tinyInteger('del_status')->default('0');
            $table->timestamps();

            $table->foreign('product_description_id')
                ->references('id')->on('product_descriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sub_descriptions');
    }
};
