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
        Schema::create('appointments', function (Blueprint $table) {
            $table->string('appointment_id', 11)->unique()->primary();
            $table->string('name');
            $table->string('address');
            $table->string('cellphone_number');
            $table->string('email');
            $table->string('appointment_date');
            $table->string('appointment_time');
            $table->string('date_finished')->nullable();
            $table->string('appointment_status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
