<?php

namespace App\Filament\ShopPanel\Resources\OrderResource\Pages;

use App\Filament\ShopPanel\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
