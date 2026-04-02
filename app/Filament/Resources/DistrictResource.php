<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistrictResource\Pages;
use App\Filament\Resources\DistrictResource\RelationManagers;
use App\Models\District;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistrictResource extends Resource
{
public static function getNavigationGroup(): ?string
{
    return __('Shipping Settings');
}

public static function getModelLabel(): string
{
    return __('District');
}

public static function getPluralModelLabel(): string
{
    return __('Districts');
}

    protected static ?int $navigationSort = 10;

    protected static ?string $model = District::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('governorate_id')
                ->label(__('Governorate'))
                ->relationship('governorate', 'name')
                ->required()
                ->searchable()
                ->preload(),
            Forms\Components\TextInput::make('name')
                ->label(__('Name'))
                ->required(),
            Forms\Components\TextInput::make('shipping_cost')
                ->label(__('Shipping Cost'))
                ->prefix('SYP')
                ->default(0),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label(__('Name'))
                ->searchable(),
                Tables\Columns\TextColumn::make('governorate.name')
                ->label(__('Governorate'))
                ->sortable(),
                Tables\Columns\TextColumn::make('shipping_cost')
                ->label(__('Shipping Cost'))
                ->money('SYP')
                ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDistricts::route('/'),
            'create' => Pages\CreateDistrict::route('/create'),
            'edit' => Pages\EditDistrict::route('/{record}/edit'),
        ];
    }
}
