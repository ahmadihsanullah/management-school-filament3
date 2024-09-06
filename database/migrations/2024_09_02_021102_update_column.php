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
        Schema::table('home_rooms', function (Blueprint $table) {
            $table->renameColumn('teacher_id', 'teachers_id');
            $table->renameColumn('classroom_id', 'classrooms_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_rooms', function (Blueprint $table) {
            $table->dropColumn('teachers_id');
            $table->dropColumn('classrooms_id');
        });
    }
};
