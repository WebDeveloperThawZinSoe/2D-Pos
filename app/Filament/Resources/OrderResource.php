<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'အော်ဒါများ';
    protected static ?string $navigationLabel = 'တင်ကွက်များ';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
                ->columns([
                
                Tables\Columns\TextColumn::make('date')
                    ->label('တင်ချိန်')
                    ->searchable()
                    ->sortable(),
                
                    Tables\Columns\TextColumn::make('section')
                    ->label('တင်ချိန်')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('manager.name')
                    ->label('ဒိုင်')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('ထိုးသား')
                    ->searchable()
                    ->sortable(),



                Tables\Columns\TextColumn::make('order_type')
                    ->label('တင်ကွက်များ')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('စုစုပေါင်းတင်‌ငွေ')
                    ->money('MMK')
                    ->sortable(),



                Tables\Columns\TextColumn::make('date')
                    ->label('Order Date')
                    ->date()
                    ->sortable(),
            ])->defaultSort('id', 'desc')

          
            ->filters([
                //
            ])
      
                ->actions([
                    Tables\Actions\ViewAction::make()
                        ->label('View Detail')
                        ->modalHeading('Order Detail')
                        ->modalSubheading(fn (Order $record) => "Order ID: {$record->id}")
                        ->modalContent(function (Order $record) {
                            return view('filament.shop-panel.order.view-detail', [
                                'record' => $record,
                            ]);
                        }),
                ])
                
     
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListOrders::route('/'),
            // 'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }



}
