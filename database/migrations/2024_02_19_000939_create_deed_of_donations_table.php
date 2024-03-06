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
        Schema::create('deed_of_donations', function (Blueprint $table) {
            $table->id();
            $table->string('documentRequest_id', 11);
            $table->foreign('documentRequest_id')->references('documentRequest_id')->on('document_requests')->onDelete('cascade');
            $table->string('donor_name');
            $table->string('donor_age');
            $table->string('donor_address');
            $table->string('donee_name');
            $table->string('donee_age');
            $table->string('donee_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deed_of_donations');
    }
};
