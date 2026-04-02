<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
public static function getNavigationGroup(): ?string
{
    return __('Orders');
}

public static function getModelLabel(): string
{
    return __('Customer');
}

public static function getPluralModelLabel(): string
{
    return __('Customers');
}

    protected static ?int $navigationSort = 2;

    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('name')
            ->label(__('Name'))
            ->required(),
            Forms\Components\TextInput::make('phone')
                ->label(__('Phone'))
                ->tel()
                ->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
            ->label(__('Name'))
            ->searchable(),
            Tables\Columns\TextColumn::make('phone')
            ->label(__('Phone'))
            ->searchable(),
            // سنضيف عدد الطلبات لاحقاً عند العمل على جدول Orders
            Tables\Columns\TextColumn::make('orders_count')
            ->label(__('Orders Count'))
            ->counts('orders'),
            Tables\Columns\TextColumn::make('created_at')
            ->label(__('Created At'))
            ->dateTime()
            ->sortable(),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
