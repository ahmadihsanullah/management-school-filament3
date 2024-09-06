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
        Schema::table('student_has_classes', function (Blueprint $table) {
            $table->foreignId("classrooms_id")->constrained("classrooms")->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_has_classes', function (Blueprint $table) {
            $table->dropColumn("classrooms_id");
        });
    }
};
