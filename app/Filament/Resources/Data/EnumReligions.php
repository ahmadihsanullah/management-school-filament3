<?php

namespace App\Filament\Resources\Data;

use Filament\Support\Contracts\HasLabel;

enum EnumReligions: string implements HasLabel
{
    case Islam = 'Islam';
    case Protestan = 'Protestan';
    case Katolik = 'Katolik';
    case Budha = 'Budha';
    case Hindu = 'Hindu';
    case Khonghucu = 'Khonghucu';  // Perbaikan typo
    
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Islam => 'Islam',
            self::Protestan => 'Protestan',
            self::Katolik => 'Katolik',
            self::Budha => 'Budha',
            self::Hindu => 'Hindu',
            self::Khonghucu => 'Khonghucu',
        };
    }
}
