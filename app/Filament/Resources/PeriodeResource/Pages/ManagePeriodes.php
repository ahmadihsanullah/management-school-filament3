<?php

namespace App\Filament\Resources\PeriodeResource\Pages;

use Filament\Actions;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Resources\PeriodeResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePeriodes extends ManageRecords
{
    protected static string $resource = PeriodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return "Periode";
    }
}
