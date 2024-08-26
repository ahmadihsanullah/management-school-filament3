<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
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
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // protected static ?string $navigationLabel = 'Siswa/i';

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
                                'male'=> 'Male',
                                'female'=> 'Female',
                            ]),
                        DatePicker::make('birthday'),
                        Select::make('religion')
                            ->options([
                                'Islam' => 'Islam',
                                'Katolik' => 'Katolik',
                                'Protestan' => 'Protestan',
                                'Hindu' => 'Hindu',
                                'Budha' => 'Budha',
                                'Khonghucu' => 'Khonghucu',
                            ]),
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
                ImageColumn::make('profile')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
        if($locale == "id"){
            return "Murid";
        }else{
            return "Students";
        }
    }
}
