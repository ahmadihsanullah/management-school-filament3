<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\StudentResource;
use App\Imports\ImportStudent;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "Siswa/i";
    }

    public function getHeader(): ?View
    {
        $data =  Actions\CreateAction::make();
        return view('filament.custom.upload-file', compact('data'));
    }
    public $file = '';

    public function save(){
       if($this->file != ''){
            Excel::import(new ImportStudent, $this->file);
       }
    }
}
