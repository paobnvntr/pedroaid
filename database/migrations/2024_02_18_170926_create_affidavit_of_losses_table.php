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
        Schema::create('affidavit_of_losses', function (Blueprint $table) {
            $table->id();
            $table->string('documentRequest_id', 11);
            $table->foreign('documentRequest_id')->references('documentRequest_id')->on('document_requests')->onDelete('cascade');
            $table->string('aol_name');
            $table->string('aol_age');
            $table->string('aol_address');
            $table->string('valid_id_front');
            $table->string('valid_id_back');
            $table->string('cedula');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affidavit_of_losses');
    }
};
