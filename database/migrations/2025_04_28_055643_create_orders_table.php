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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("order_number")->unique();
            $table->string("user_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->string("manager_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->integer("manager_commission")->nullable();
            $table->integer("manager_rate")->nullable();
            $table->string("order_type");
            $table->string("price");
            $table->string("status")->default("pending");
            $table->string("user_order_status")->default("pending");
            $table->string("date")->nullable();
            $table->string("section")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
