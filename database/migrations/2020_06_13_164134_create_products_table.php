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
        Schema::create('product_standards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');

            $table->tinyInteger('del_status')->default('0');
            $table->timestamps();
        });

        Schema::create('wood_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');

            $table->tinyInteger('del_status')->default('0');
            $table->timestamps();
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->tinyInteger('del_status')->default('0');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_type_id');
            $table->unsignedBigInteger('wood_type_id');
            $table->unsignedBigInteger('product_standard_id');

            $table->string('name');
            $table->decimal('pieces_price');
            $table->decimal('cubic_meters_price');
            $table->integer('count_in_cubic');
            $table->integer('width');
            $table->integer('length ');
            $table->integer('thickness');
            $table->string('moisture');
            $table->tinyInteger('sort');

            $table->tinyInteger('del_status')->default('0');
            $table->timestamps();

            $table->foreign('product_type_id')
                ->references('id')->on('product_types')->onDelete('cascade');
            $table->foreign('wood_type_id')
                ->references('id')->on('wood_types')->onDelete('cascade');
            $table->foreign('product_standard_id')
                ->references('id')->on('product_standards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_standards');
        Schema::dropIfExists('wood_types');
        Schema::dropIfExists('product_types');


    }
};
