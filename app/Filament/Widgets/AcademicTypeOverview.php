<?php

namespace App\Filament\Widgets;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AcademicTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        $teacherId = Teacher::where('id', $user->id); // Atau ambil ID guru yang relevan dari user atau parameter lain
        $subjectId = 1;
        if($user->hasRole('admin')){
            return [
                Stat::make('Guru', Teacher::query()->count()),
                Stat::make('Siswa', Student::query()->count()),
                Stat::make('Kelas', Classroom::query()->count()),
            ];
        }
        return [
               
        ];
    }
}
