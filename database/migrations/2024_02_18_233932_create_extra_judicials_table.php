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
        Schema::create('extra_judicials', function (Blueprint $table) {
            $table->id();
            $table->string('documentRequest_id', 11);
            $table->foreign('documentRequest_id')->references('documentRequest_id')->on('document_requests')->onDelete('cascade');
            $table->string('death_cert');
            $table->string('heirship');
            $table->string('inv_estate');
            $table->string('tax_clearance');
            $table->string('deed_extrajudicial');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_judicials');
    }
};
