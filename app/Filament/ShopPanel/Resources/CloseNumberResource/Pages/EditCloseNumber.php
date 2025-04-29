<?php

namespace App\Filament\ShopPanel\Resources\CloseNumberResource\Pages;

use App\Filament\ShopPanel\Resources\CloseNumberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCloseNumber extends EditRecord
{
    protected static string $resource = CloseNumberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
