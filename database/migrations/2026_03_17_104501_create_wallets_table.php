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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            
            // Link to users table (foreign key)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Wallet name (e.g., "Business", "Personal")
            $table->string('name');
            
            // Balance with 2 decimal places
            $table->decimal('balance', 10, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};