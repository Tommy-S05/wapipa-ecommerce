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
        Schema::create('variation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('type');
        });

        Schema::create('variation_type_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_type_id')
                ->constrained('variation_types')
                ->cascadeOnDelete();
            $table->string('name');
        });

        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();
//            $table->foreignId('variation_type_id')->constrained();
//            $table->foreignId('variation_type_option_id')->constrained();
            $table->json('variation_type_options_ids');
            $table->decimal('price', 20, 4)->nullable();
            $table->integer('quantity')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variation_types');
        Schema::dropIfExists('variation_type_options');
        Schema::dropIfExists('product_variations');
    }
};
