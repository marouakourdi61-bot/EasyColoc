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
        Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('colocation_id')->nullable()->after('id');

        $table->foreign('colocation_id')
              ->references('id')
              ->on('colocations')
              ->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['colocation_id']);
        $table->dropColumn('colocation_id');
    });
    }
};
