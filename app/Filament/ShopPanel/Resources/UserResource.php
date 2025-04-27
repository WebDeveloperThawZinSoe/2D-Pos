<?php

namespace App\Filament\ShopPanel\Resources;

use App\Filament\ShopPanel\Resources\UserResource\Pages;
use App\Filament\ShopPanel\Resources\UserResource\RelationManagers;
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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden; 
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Users';
    protected static ?string $navigationLabel = 'ထိုးသားများ';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Grid::make(2)->schema([
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
                    ->password()
                    ->label('Password')
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state)),

                Hidden::make('role')
                    ->default('user'),

                Hidden::make('manager_id')
                    ->default(fn () => auth()->id()),

                // DatePicker::make('join_date')
                //     ->label('Join Date')
                //     ->required(),

                // DatePicker::make('end_date')
                //     ->label('End Date')
                //     ->required(),

                TextInput::make('rate')
                    ->label('Rate')
                    ->numeric()
                    ->required(),

                TextInput::make('max_limit')
                    ->label('Max Limit')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ])
                    ->default(1)
                    ->required(),
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

            BadgeColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn (User $user) => $user->status == 1 ? 'Active' : 'Inactive')
                ->colors([
                    'Active' => 'success',
                    'Inactive' => 'danger',
                ])
                ->sortable(),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('Created At'),
        ])
        ->defaultSort('created_at', 'desc')
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
        ])
        ->modifyQueryUsing(function (Builder $query) {
            $user = Filament::auth()->user();

            // Only show users who have role 'user' and manager_id equals current logged in shop user ID
            return $query
                ->whereHas('roles', fn ($q) => $q->where('name', 'user'))
                ->where('manager_id', $user->id);
        });
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
