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
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->date("join_date")->nullable();
            $table->date("end_date")->nullable();
            $table->integer("status")->default(1);
            $table->string("commission")->nullable();
            $table->string("rate")->nullable();
            $table->string("max_limit")->nullalbe();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
