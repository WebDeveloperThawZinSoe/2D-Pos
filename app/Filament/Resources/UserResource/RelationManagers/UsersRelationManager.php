<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users'; // assumes manager_id relation

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'Active' => 'success',
                        'Inactive' => 'danger',
                    ])
                    ->getStateUsing(fn ($record) => $record->status ? 'Active' : 'Inactive'),
                Tables\Columns\TextColumn::make('plain_password'),
                Tables\Columns\TextColumn::make('rate'),
                Tables\Columns\TextColumn::make('commission'),
                Tables\Columns\TextColumn::make('max_limit'),
                Tables\Columns\TextColumn::make('end_am'),
                Tables\Columns\TextColumn::make('end_pm'),
                // Tables\Columns\TextColumn::make('open_time_am'),
                // Tables\Columns\TextColumn::make('close_time_am'),
                // Tables\Columns\TextColumn::make('open_time_pm'),
                // Tables\Columns\TextColumn::make('close_time_pm'),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }
}
