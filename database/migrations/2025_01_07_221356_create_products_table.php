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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->string('title', 2000);
            $table->string('slug', 2000);
            $table->longText('description');
            $table->decimal('price', 20, 4);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('quantity')->nullable()->default(0);
            $table->foreignIdFor(\App\Models\User::class, 'created_by');
            $table->foreignIdFor(\App\Models\User::class, 'updated_by');
            $table->softDeletes();
//            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
