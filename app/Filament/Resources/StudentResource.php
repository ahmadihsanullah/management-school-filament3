<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Data\EnumReligions;
use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms\Components\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Fieldset;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Validation\Rules\Enum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationLabel = 'Siswa/i';

    protected static ?string $navigationGroup = 'Academic';

    protected static ?int $navigationSort = 21;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('nis'),
                        TextInput::make('name'),
                        Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                            ]),
                        DatePicker::make('birthday'),
                        Select::make('religion')
                            ->options(EnumReligions::class),
                        TextInput::make('contact'),
                        FileUpload::make('profile')
                            ->directory('students'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->rowIndex()
                    ->label('No'),
                TextColumn::make('nis'),
                TextColumn::make('name'),
                TextColumn::make('gender'),
                TextColumn::make('birthday'),
                TextColumn::make('religion'),
                TextColumn::make('contact'),
                ImageColumn::make('profile'),
                TextColumn::make('status')
                    ->formatStateUsing(fn(String $state) => ucwords("{$state}"))
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ]),
                Filter::make('birthday')
                    ->label('Filter by Birthday')
                    ->form([
                        DatePicker::make('from')
                            ->label('From'),
                        DatePicker::make('to')
                            ->label('To'),
                        Select::make('sort_order')
                            ->label('Sort Order')
                            ->options([
                                'asc' => 'Ascending',
                                'desc' => 'Descending',
                            ])
                            ->default('asc'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Apply the date range filtering
                        $query->when(
                            $data['from'] ?? null,
                            fn(Builder $query, $date) => $query->whereDate('birthday', '>=', $date)
                        )->when(
                            $data['to'] ?? null,
                            fn(Builder $query, $date) => $query->whereDate('birthday', '<=', $date)
                        );

                        // Apply sorting based on the selected sort order
                        if (!empty($data['sort_order'])) {
                            $query->orderBy('birthday', $data['sort_order']);
                        }

                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Accept')
                        ->icon('heroicon-m-check')
                        ->requiresConfirmation()
                        ->action(function (Collection $record) {
                            return $record->each->update(['status' => 'accept']);
                        }),
                    BulkAction::make('Off')
                        ->icon('heroicon-m-x-circle')
                        ->requiresConfirmation()
                        ->action(function (Collection $record) {
                            return $record->each->update(['status' => 'off']);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        $locale = App::getLocale();
        if ($locale == "id") {
            return "Murid";
        } else {
            return "Students";
        }
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Fieldset::make('Biodata')
                            ->schema([
                                Components\Split::make([
                                    Components\ImageEntry::make('profile')
                                        ->hiddenLabel()
                                        ->lazy()
                                        ->grow(false),
                                    Components\Grid::make(2)
                                        ->schema([
                                            Components\Group::make([
                                                Components\TextEntry::make('nis'),
                                                Components\TextEntry::make('name'),
                                                Components\TextEntry::make('gender'),
                                                Components\TextEntry::make('birthday'),

                                            ])
                                                ->inlineLabel()
                                                ->columns(1),

                                            Components\Group::make([
                                                Components\TextEntry::make('religion'),
                                                Components\TextEntry::make('contact'),
                                                Components\TextEntry::make('status')
                                                    ->badge()
                                                    ->color(fn(string $state): string => match ($state) {
                                                        'accept' => 'success',
                                                        'off' => 'danger',
                                                        'grade' => 'success',
                                                        'move' => 'warning',
                                                        'wait' => 'gray'
                                                    }),
                                                Components\ViewEntry::make('QRCode')
                                                    ->view('filament.resources.students.qrcode'),
                                            ])
                                                ->inlineLabel()
                                                ->columns(1),
                                        ])

                                ])->from('lg')
                            ])->columns(1)
                    ])->columns(2)
            ]);
    }
}
