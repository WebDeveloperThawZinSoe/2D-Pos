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
        Schema::create('dine_head_limits', function (Blueprint $table) {
            $table->id();
            $table->string("manager_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->string("date")->nullable();
            $table->string("section")->nullable();
            $table->string("amount");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dine_head_limits');
    }
};
