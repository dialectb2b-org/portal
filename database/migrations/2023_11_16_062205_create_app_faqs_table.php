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
        Schema::create('app_faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->text('question')->nullable();
            $table->text('answer')->nullable();
            $table->integer('status')->default(1);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_faqs');
    }
};
