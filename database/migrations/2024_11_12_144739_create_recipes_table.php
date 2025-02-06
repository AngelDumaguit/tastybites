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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('recipeName');
            $table->string('cuisineType');
            $table->text('description');
            $table->text('ingredients');
            $table->text('procedure');
            $table->string('status')->default('active');
            $table->string('freePrem');
            $table->string('videoLink')->nullable();
            $table->string('imagePath')->nullable();
            $table->integer('price')->nullable();
            $table->string('gcashNumber')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
