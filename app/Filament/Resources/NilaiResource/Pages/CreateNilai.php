<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use Filament\Actions;
use App\Models\Periode;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms\Form;
use App\Models\Classroom;
use App\Models\CategoryNilai;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\NilaiResource;
use App\Models\Nilai;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateNilai extends CreateRecord
{
    protected static string $resource = NilaiResource::class;

    protected static string $view = 'filament.resources.nilai-resource.pages.form-nilai';

    public function form(Form $form): Form
    {
        return $form
        
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('classroom')
                            ->options(Classroom::query()->pluck('name', 'id'))
                            ->required()
                            ->label('Class'),
                        Select::make('periode')
                            ->label('Periode')
                            ->options(Periode::query()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('subject')
                            ->label('Subject')
                            ->options(Subject::query()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('category_nilai')
                            ->label('Category Nilai')
                            ->options(CategoryNilai::query()->pluck('name', 'id'))
                            ->searchable()
                            ->columnSpan(3),
                    ])->columns(3),
                Repeater::make('nilaistudents')
                    ->label('Grade')
                    ->schema([
                        Select::make('student')
                            ->options(Student::query()->pluck('name', 'id'))
                            ->label('students'),

                        TextInput::make('nilai')
                            ->numeric() // Tambahkan validasi numerik
                            ->required(), // Pastikan nilai wajib diisi
                    ])->columnSpan(3)
                    ->columns(2)
            ]);
    }

    public function save()
    {
        $get = $this->form->getState();

        // Menggunakan collect() untuk memanipulasi data
        $insert = collect($get['nilaistudents'])->map(function ($row) use ($get) {
            return [
                "class_id" => $get['classroom'],
                "student_id" => $row['student'],
                "periode_id" => $get['periode'],
                "teacher_id" => Auth::user()->id,
                "subject_id" => $get['subject'],
                "category_nilai_id" => $get['category_nilai'],
                "nilai" => $row['nilai'],
            ];
        })->toArray();

        Nilai::insert($insert);

        // Menambahkan flash message untuk feedback
        session()->flash('success', 'Nilai berhasil disimpan.');

        return redirect()->to('/admin/nilais');
    }
}
