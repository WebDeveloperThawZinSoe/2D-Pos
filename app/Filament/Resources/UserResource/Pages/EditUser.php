<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Get the new password from form state
        $newPassword = $this->form->getState()['password'] ?? null;

        if (!empty($newPassword)) {
            $data['plain_password'] = $newPassword;
            $data['password'] = Hash::make($newPassword);
        } else {
            // Keep existing password if no new password is provided
            $data['password'] = $this->record->password;
            $data['plain_password'] = $this->record->plain_password;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Always assign 'shop' role
        $this->record->syncRoles('shop');
    }
}
