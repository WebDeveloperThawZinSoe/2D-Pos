<?php

namespace App\Filament\ShopPanel\Resources;

use App\Filament\ShopPanel\Resources\CloseNumberResource\Pages;
use App\Models\CloseNumber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Rules\UniqueCloseNumber;

class CloseNumberResource extends Resource
{
    protected static ?string $model = CloseNumber::class;

    protected static ?string $navigationIcon = 'heroicon-c-x-mark';
    protected static ?string $navigationGroup = 'အော်ဒါများ';
    protected static ?string $navigationLabel = 'ပိတ်သီးများ';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            DatePicker::make('date')
                ->required()
                ->minDate(now()->toDateString()),

                Select::make('section')
                ->options([
                    'AM' => 'AM',
                    'PM' => 'PM',
                ])
                ->required()
                ->rules(function (callable $get) {
                    return [
                        new UniqueCloseNumber($get('date'), auth()->id()),
                    ];
                }),

            TextInput::make('manager_id')
                ->required()
                ->default(Auth::user()->id)->hidden(),


            TextInput::make('number')
                ->required()
                ->maxLength(255)
                ->helperText('ဥပမာ - 23,15,67,12'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('section')
                    ->sortable(),

                Tables\Columns\TextColumn::make('number')
                    ->sortable()
                    ->searchable(),

               
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCloseNumbers::route('/'),
            'create' => Pages\CreateCloseNumber::route('/create'),
            'edit' => Pages\EditCloseNumber::route('/{record}/edit'),
        ];
    }
}
