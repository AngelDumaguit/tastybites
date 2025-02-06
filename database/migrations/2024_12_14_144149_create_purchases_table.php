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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');  // Link to the recipes table
            $table->foreignId('user_id')->constrained('accounts')->onDelete('cascade');  // Link to the accounts (users) table
            $table->string('status')->default('pending');  // Default status as 'pending'
            $table->string('receipt_path');  // Store the path of the uploaded receipt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
