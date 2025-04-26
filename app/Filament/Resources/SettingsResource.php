<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages;
use App\Filament\Resources\SettingsResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon; 

class SettingsResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationLabel = 'Settings';
    protected static ?string $title = 'Settings';
    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 51;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TimePicker::make('open_time_am')
                ->label('Open Time AM')
                ->nullable(),
            Forms\Components\TimePicker::make('close_time_am')
                ->label('Close Time AM')
                ->nullable(),
            Forms\Components\TimePicker::make('open_time_pm')
                ->label('Open Time PM')
                ->nullable(),
            Forms\Components\TimePicker::make('close_time_pm')
                ->label('Close Time PM')
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->columns([
            TextColumn::make('open_time_am')
                ->label('Open Time AM')
                ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('H:i') : ''),
            TextColumn::make('close_time_am')
                ->label('Close Time AM')
                ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('H:i') : ''),
            TextColumn::make('open_time_pm')
                ->label('Open Time PM')
                ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('H:i') : ''),
            TextColumn::make('close_time_pm')
                ->label('Close Time PM')
                ->formatStateUsing(fn ($state) => $state ? Carbon::parse($state)->format('H:i') : ''),
        ])
        ->filters([
            // Add filters if needed
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
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSettings::route('/{record}/edit'),
        ];
    }

    // Prevent creating more than one record
    public static function canCreate(): bool
    {
        return Setting::count() === 0;
    }
}
