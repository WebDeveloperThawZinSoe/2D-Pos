<?php

namespace App\Filament\ShopPanel\Resources\UserResource\Pages;

use App\Filament\ShopPanel\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set manager_id to the current logged-in user's id
        $data['manager_id'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        // Assign the "user" role to the newly created user
        $this->record->assignRole('user');
    }
}
