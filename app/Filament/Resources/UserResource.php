<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\Filter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';


    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->required()
                ->email()
                ->maxLength(255),

            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($record) => $record === null)
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => bcrypt($state)),

                Select::make('role')
                ->label('Assign Role')
                ->options(Role::pluck('name', 'name')->toArray())
                ->default('shop')
                ->searchable()
                ->preload()
                ->required()
                ->dehydrated(false) // Prevents Laravel from storing in users table
                ->afterStateHydrated(fn ($state, $record) => $state ??= $record?->roles->first()?->name)
                ->afterStateUpdated(fn ($state, $record) => $record?->syncRoles($state)),
            
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

            // Role Badge Column
            BadgeColumn::make('role')
                ->label('Role')
                ->getStateUsing(fn (User $user) => $user->hasRole('shop') ? 'Shop' : ucfirst($user->roles->first()?->name ?? 'User'))
                ->colors([
                    'admin' => 'primary',
                    'shop' => 'success',
                    'user' => 'secondary',
                ]),

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
