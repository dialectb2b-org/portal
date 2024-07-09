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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('subscription_type');
            $table->unsignedBigInteger('package_id');
            $table->integer('period_type');
            $table->decimal('rate', 8, 2);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->string('payment_mode')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('card_no')->nullable();
            $table->string('card_user')->nullable();
            $table->string('description')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_location')->nullable();
            $table->string('billing_pobox')->nullable();
            $table->string('card_token')->nullable();
            $table->integer('payment_status')->nullable();
            $table->string('reference_no')->nullable();
            $table->unsignedBigInteger('billing_id')->nullable();
            $table->text('remarks')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
