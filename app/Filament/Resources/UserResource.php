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
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Users';
    protected static ?string $navigationLabel = 'All Users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2) // 2 column grid
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->email()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->maxLength(255)
                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord) // Required only when creating
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null) // Hash if entered
                            ->dehydrated(fn ($state) => filled($state)), // Only send to controller if filled
                        

                        Forms\Components\Select::make('role')
                            ->label('Assign Role')
                            ->options([
                                'admin' => 'Admin',
                                'shop' => 'ဒိုင်',
                                'user' => 'ထိုးသား',
                            ])
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateHydrated(function (Forms\Components\Select $component, $state, $record) {
                                if ($record) {
                                    $component->state($record->roles->first()?->name ?? 'shop'); // auto select role
                                }
                            })
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state !== 'user') {
                                    // Clear related fields if not user
                                    $set('manager_id', null);
                                    $set('join_date', null);
                                    $set('end_date', null);
                                    $set('status', 1);
                                    $set('commission', null);
                                    $set('rate', null);
                                    $set('max_limit', null);
                                }
                            }),

                        Forms\Components\Select::make('manager_id')
                            ->label('Manager (ဒိုင်)')
                            ->options(User::role('shop')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(fn (callable $get) => $get('role') === 'user')
                            ->visible(fn (callable $get) => $get('role') === 'user'),

                        Forms\Components\DatePicker::make('join_date')
                            ->label('Join Date')
                            ->required(fn (callable $get) => $get('role') === 'shop')
                            ->visible(fn (callable $get) => $get('role') === 'shop'),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->required(fn (callable $get) => $get('role') === 'shop')
                            ->visible(fn (callable $get) => $get('role') === 'shop'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                1 => 'Active',
                                0 => 'Inactive',
                            ])
                            ->default(1)
                            ->required(fn (callable $get) => $get('role') === 'user')
                            ->visible(fn (callable $get) => $get('role') === 'user'),

                        Forms\Components\TextInput::make('commission')
                            ->label('Commission')
                            ->numeric()
                            ->required(fn (callable $get) => $get('role') === 'shop')
                            ->visible(fn (callable $get) => $get('role') === 'shop'),

                        Forms\Components\TextInput::make('rate')
                            ->label('Rate')
                            ->numeric()
                            ->required(fn (callable $get) => $get('role') === 'user')
                            ->visible(fn (callable $get) => $get('role') === 'user'),

                        Forms\Components\TextInput::make('max_limit')
                            ->label('Max Limit')
                            ->numeric()
                            ->required(fn (callable $get) => $get('role') === 'user')
                            ->visible(fn (callable $get) => $get('role') === 'user'),
                    ]),
            ]);
    }

        

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('email')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('role')
                    ->label('Role')
                    ->getStateUsing(function (User $user) {
                        $role = $user->roles->first()?->name ?? 'user';
                        return match ($role) {
                            'admin' => 'admin',
                            'shop' => 'ဒိုင်',
                            'user' => 'ထိုးသား',
                            default => ucfirst($role),
                        };
                    })
                    ->colors([
                        'admin' => 'primary',
                        'shop' => 'success',
                        'user' => 'secondary',
                    ]),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn (User $user) => $user->status == 1 ? 'Active' : 'Inactive')
                    ->colors([
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    ])
                    ->sortable(),

                TextColumn::make('manager.name')
                    ->label('ဒိုင်')
                    ->sortable()
                    ->searchable()
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Created At'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Filter::make('Shop')
                    ->query(fn (Builder $query): Builder => $query->whereHas('roles', fn ($q) => $q->where('name', 'shop'))),
                Filter::make('Users')
                    ->query(fn (Builder $query): Builder => $query->whereHas('roles', fn ($q) => $q->where('name', 'user'))),
                Filter::make('Admins')
                    ->query(fn (Builder $query): Builder => $query->whereHas('roles', fn ($q) => $q->where('name', 'admin'))),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}