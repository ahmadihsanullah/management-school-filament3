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
            $table->renameColumn('student_id', 'students_id');
            $table->renameColumn('home_room_id', 'homerooms_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_has_classes', function (Blueprint $table) {
            $table->dropColumn('students_id');
            $table->dropColumn('homerooms_id');
        });
    }
};
