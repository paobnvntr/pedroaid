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
        Schema::create('affidavit_of_no_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('documentRequest_id', 11);
            $table->foreign('documentRequest_id')->references('documentRequest_id')->on('document_requests')->onDelete('cascade');
            $table->string('aoni_name');
            $table->string('aoni_age');
            $table->string('aoni_address');
            $table->string('certificate_of_indigency');
            $table->string('previous_employer_name')->nullable();
            $table->string('previous_employer_contact')->nullable();
            $table->string('business_name');
            $table->string('registration_number');
            $table->string('business_address');
            $table->string('business_period');
            $table->string('no_income_period');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affidavit_of_no_incomes');
    }
};
