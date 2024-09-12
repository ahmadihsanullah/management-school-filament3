<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;

use App\Models\Periode;
use App\Models\Student;
use App\Models\HomeRoom;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use App\Filament\Resources\StudentHasClassResource;
use App\Models\Classroom;
use App\Models\StudentHasClass;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Concerns\InteractsWithFormActions;

class FormStudentClass extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = StudentHasClassResource::class;

    protected static string $view = 'filament.resources.student-has-class-resource.pages.form-student-class';

    public $students = [];
    public $classrooms = '';
    public $periode = '';

    public function mount()
    {
        $this->form->fill();
    }

    public function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Select::make("students")
                        ->searchable()
                        ->multiple()
                        ->options(Student::all()->pluck('name', 'id'))
                        ->label('Student')
                        ->columnSpan(3),
                    Select::make('classrooms')
                        ->searchable()
                        ->options(Classroom::all()->pluck('name', 'id'))
                        ->label('Classroom'),
                    Select::make('periode')
                        ->searchable()
                        ->options(Periode::all()->pluck('name', 'id'))
                        ->label('Periode')
                ])->columns(3)
        ];
    }

    public function save()
    {
        $students = $this->students;
        $data = [];

        foreach ($students as $row) {
            // Cek apakah student sudah ada di kelas ini dengan periode yang sama
            $existingRecord = StudentHasClass::where('students_id', $row)
                ->where('classrooms_id', $this->classrooms)
                ->where('periode_id', $this->periode)
                ->first();

            // Jika belum ada, tambahkan ke dalam array data
            if (!$existingRecord) {
                array_push($data, [
                    'students_id' => $row,
                    'classrooms_id' => $this->classrooms,
                    'periode_id' => $this->periode,
                    'is_open' => 1
                ]);
            }
        }

        // Simpan semua data yang sudah diproses
        foreach ($data as $record) {
            StudentHasClass::create($record);
        }

        // Redirect setelah penyimpanan
        return redirect()->to('admin/student-has-classes');
    }
}
