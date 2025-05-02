<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\UserResource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\UserResource\RelationManagers\UsersRelationManager::class,
        ];
    }
}
