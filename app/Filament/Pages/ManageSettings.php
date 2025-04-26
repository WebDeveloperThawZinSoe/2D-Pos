<?php
namespace App\Filament\Resources\SettingsResource\Pages;

use App\Filament\Resources\SettingsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Models\Setting;

class ManageSettings extends ManageRecords
{
    protected static string $resource = SettingsResource::class;

    public function mount(): void
    {
        // Redirect to the only existing record, as there should only be one row
        $settings = Setting::first();
        if ($settings) {
            $this->record = $settings;
        }
    }

    public function getActions(): array
    {
        return [
            // Only show the edit action, no create or delete
            Actions\EditAction::make(),
        ];
    }
}
