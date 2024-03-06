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
        Schema::create('inquiry_messages', function (Blueprint $table) {
            $table->id();
            $table->string('inquiry_id', 11);
            $table->foreign('inquiry_id')->references('inquiry_id')->on('inquiries')->onDelete('cascade');
            $table->string('email');
            $table->string('staff_name')->nullable();
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiry_messages');
    }
};
