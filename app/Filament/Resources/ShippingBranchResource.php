<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingBranchResource\Pages;
use App\Filament\Resources\ShippingBranchResource\RelationManagers;
use App\Models\ShippingBranch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingBranchResource extends Resource
{
    protected static ?string $navigationGroup = 'Shipping Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $model = ShippingBranch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('governorate_id')
                ->relationship('governorate', 'name')
                ->required(),
            Forms\Components\TextInput::make('branch_name')
                ->required(),
            Forms\Components\TextInput::make('shipping_cost')
                ->numeric()
                ->default(0.00),
            Forms\Components\Toggle::make('is_active')
                ->default(true),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('branch_name')->searchable(),
            Tables\Columns\TextColumn::make('governorate.name'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
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
            'index' => Pages\ListShippingBranches::route('/'),
            'create' => Pages\CreateShippingBranch::route('/create'),
            'edit' => Pages\EditShippingBranch::route('/{record}/edit'),
        ];
    }
}
