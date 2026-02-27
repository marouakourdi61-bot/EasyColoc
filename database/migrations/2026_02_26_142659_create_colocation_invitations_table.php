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
    Schema::create('colocation_invitations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('colocation_id')->constrained()->onDelete('cascade'); // relation avec colocation
        $table->string('email');
        $table->string('token')->unique();
        $table->boolean('used')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocation_invitations');
    }
};
