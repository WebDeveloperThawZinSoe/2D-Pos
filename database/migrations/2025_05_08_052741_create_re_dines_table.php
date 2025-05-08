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
        Schema::create('re_dines', function (Blueprint $table) {
            $table->id();
            $table->string("manager_id")->nullable()->constrained('users')->onDelete('cascade');
            $table->string("name");
            $table->integer("commission")->default(0);
            $table->integer("rate")->default(85);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('re_dines');
    }
};
