<?php

namespace App\Filament\Resources\StudentHasClassResource\Pages;

use App\Models\Periode;
use App\Models\Student;
use App\Models\HomeRoom;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use App\Filament\Resources\StudentHasClassResource;
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
    public $homerooms = '';
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
                    Select::make('homerooms')
                        ->searchable()
                        ->options(HomeRoom::all()->pluck('classroom.name', 'id'))
                        ->label('Homeroom'),
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
            array_push($data,  [
                'student_id' => $row,
                'home_room_id' => $this->homerooms,
                'periode_id' => $this->periode,
                'is_open' => 1
            ]);
        }
        foreach ($data as $record) {
            StudentHasClass::create($record);
        }
    
        return redirect()->to('admin/student-has-classes');
    }
}
