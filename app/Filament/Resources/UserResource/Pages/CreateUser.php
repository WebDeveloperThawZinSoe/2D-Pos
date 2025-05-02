<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract the password from the form state manually
        $password = $this->form->getState()['password'] ?? null;

        if (!empty($password)) {
            $data['plain_password'] = $password;
            $data['password'] = Hash::make($password);
        } else {
            throw new \Exception('Password is required.');
        }

        return $data;
    }
    

    protected function afterCreate(): void
    {
        $this->record->syncRoles($this->data['role'] ?? 'shop');
    }


    


}
