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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('product_slug')->nullable();
            $table->string('product_image')->nullable();
            $table->string('currency_code', 8)->default('TRY');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
