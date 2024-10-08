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
        Schema::create('home_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_open')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_rooms');
    }
};
