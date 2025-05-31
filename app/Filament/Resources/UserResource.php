<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'ဒိုင်များ';
    protected static ?string $navigationLabel = 'ဒိုင်များအားလုံး';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(255),

                            TextInput::make('password')
                            ->label('Password')
                            ->maxLength(255)
                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord), // <- Prevent automatic save
                        

                            Select::make('role')
                            ->label('Assign Role')
                            ->options([
                                'admin' => 'Admin',
                                'shop' => 'ဒိုင်',
                            ])
                            ->default('shop') // Set default to 'shop'
                            ->searchable()
                            ->hidden(fn () => true), // Always hide the field
                        

 

                        DatePicker::make('join_date')
                            ->label('Join Date')
                            ->required(fn (callable $get) => $get('role') === 'shop'),

                        DatePicker::make('end_date')
                            ->label('End Date')
                            ->required(fn (callable $get) => $get('role') === 'shop'),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                1 => 'Active',
                                0 => 'Inactive',
                            ])
                            ->default(1)
                            ->required(fn (callable $get) => $get('role') === 'user'),

                      
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('plain_password')->sortable()->searchable(),
            TextColumn::make('end_date')
                ->label('End Date')
                ->badge() // Enables visible color
                ->date()
                ->color(function ($state) {
                    if (!$state) return null;

                    // Make sure $state is a Carbon instance
                    $date = $state instanceof \Carbon\Carbon ? $state : Carbon::parse($state);

                    return $date->isBetween(now(), now()->addMonth())
                        ? 'danger' // Red
                        : 'success'; // Green or default
                }),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn (User $user) => $user->status == 1 ? 'Active' : 'Inactive')
                    ->colors([
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    ])
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('end_date', 'asc')
            ->filters([
                Filter::make('Shop')
                    ->query(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'shop'))),

                Filter::make('Admin')
                    ->query(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'admin'))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->url(fn (User $record): string => route('filament.admin.resources.users.view', ['record' => $record->id]))
                    ->visible(fn (User $record) => $record->hasRole('shop')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('roles', fn ($q) => $q->where('name', 'shop'));
    }
}