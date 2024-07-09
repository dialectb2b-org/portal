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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->date('billing_date');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('billing_name')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_location')->nullable();
            $table->string('billing_pobox')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->decimal('bill_amount', 8, 2)->nullable();
            $table->integer('status')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_reference_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
