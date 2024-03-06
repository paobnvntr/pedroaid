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
        Schema::create('affidavit_of_guardianships', function (Blueprint $table) {
            $table->id();
            $table->string('documentRequest_id', 11);
            $table->foreign('documentRequest_id')->references('documentRequest_id')->on('document_requests')->onDelete('cascade');
            $table->string('guardian_name');
            $table->string('guardian_age');
            $table->string('guardian_address');
            $table->string('guardian_occupation');
            $table->string('guardian_brgy_clearance');
            $table->string('guardian_relationship');
            $table->string('minor_name');
            $table->string('minor_age');
            $table->string('minor_address');
            $table->string('minor_relationship');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affidavit_of_guardianships');
    }
};
