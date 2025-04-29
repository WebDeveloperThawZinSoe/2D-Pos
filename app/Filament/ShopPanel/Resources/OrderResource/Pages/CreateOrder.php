<?php

namespace App\Filament\ShopPanel\Resources\OrderResource\Pages;

use App\Filament\ShopPanel\Resources\OrderResource;
use App\Models\CloseNumber; // ✅ Make sure you import this
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $exists = CloseNumber::where('date', $data['date'])
            ->where('section', $data['section'])
            ->where('manager_id', $data['manager_id'])
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('ပိတ်သီးရှိပြီးသား') // Burmese title
                ->body('သင်ရွေးထားသော Section အတွက် ပိတ်သီးအစတည်းကပိတ်ပြီးပါပြီ')
                ->danger()
                ->send();

            // ❗ Instead of returning redirect, throw ValidationException (more Filament way)
            throw \Filament\Forms\Components\ValidationException::withMessages([
                'section' => 'သင်ရွေးထားသော Section အတွက် ပိတ်သီးအစတည်းကပိတ်ပြီးပါပြီ',
            ]);
        }

        return $data;
    }
}
