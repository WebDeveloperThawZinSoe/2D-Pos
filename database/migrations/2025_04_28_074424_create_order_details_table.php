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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string("order_number")->unique();
            $table->string("order_id")->nullable()->constrained('orders')->onDelete('cascade');
            $table->string("user_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->string("manager_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->string("number")->nullable();
            $table->string("order_type");
            $table->string("price");
            $table->string("user_order_status")->default("pending");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
