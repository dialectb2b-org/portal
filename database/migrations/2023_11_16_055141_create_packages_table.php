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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->integer('sales_enquiry_receive_limit')->nullable();
            $table->integer('sales_respond_enquiry_limit')->nullable();
            $table->integer('sales_limited_enquiry_participation_limit')->nullable();
            $table->integer('sales_faq_option')->nullable();
            $table->integer('sales_inbox_validity')->nullable();
            $table->integer('procurement_enquiry_receive_limit')->nullable();
            $table->integer('procurement_proposal_receiving_limit')->nullable();
            $table->integer('procurement_limited_enquiry_limit')->nullable();
            $table->integer('procurement_review_quote_limit')->nullable();
            $table->integer('procurement_inbox_validity')->nullable();
            $table->integer('member_enquiry_limit')->nullable();
            $table->integer('member_proposal_receive_limit')->nullable();
            $table->integer('member_limited_enquiry_limit')->nullable();
            $table->integer('member_review_quote_limit')->nullable();
            $table->integer('member_inbox_validity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
