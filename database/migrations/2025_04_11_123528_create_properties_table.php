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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('currency', ['USD', 'ILS', 'JOD'])->default('ILS');
            $table->text('location')->nullable();
            $table->enum('status', ['available', 'finished'])->default('available');
            $table->enum('type', ['rent', 'sale']);
            $table->string('main_image');
            $table->integer('rooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->float('area')->nullable(); // in square meters
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
