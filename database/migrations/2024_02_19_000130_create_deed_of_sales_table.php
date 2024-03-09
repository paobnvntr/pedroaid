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
        Schema::create('deed_of_sales', function (Blueprint $table) {
            $table->id();
            $table->string('documentRequest_id', 11);
            $table->foreign('documentRequest_id')->references('documentRequest_id')->on('document_requests')->onDelete('cascade');
            $table->string('name_of_vendor');
            $table->string('vendor_civil_status');
            $table->string('vendor_address');
            $table->string('property_document');
            $table->string('property_price');
            $table->string('vendor_valid_id_front');
            $table->string('vendor_valid_id_back');
            $table->string('name_of_vendee');
            $table->string('vendee_valid_id_front');
            $table->string('vendee_valid_id_back');
            $table->string('name_of_witness');
            $table->string('witness_valid_id_front');
            $table->string('witness_valid_id_back');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deed_of_sales');
    }
};
