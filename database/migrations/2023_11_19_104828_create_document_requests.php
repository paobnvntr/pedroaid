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
        Schema::create('document_requests', function (Blueprint $table) {
            $table->string('documentRequest_id', 11)->unique()->primary();
            $table->string('document_type');
            $table->string('name');
            $table->string('address');
            $table->string('email');
            $table->string('cellphone_number');
            $table->string('documentRequest_status')->default('Pending');
            $table->timestamp('date_claimed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
