<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariationResource\Pages;
use App\Filament\Resources\ProductVariationResource\RelationManagers;
use App\Models\ProductVariation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Stock;
use Awcodes\Curator\Components\Forms\CuratorPicker;

class ProductVariationResource extends Resource
{
    protected static ?int $navigationSort = 3;

public static function getNavigationGroup(): ?string
{
    return __('Stock');
}

public static function getModelLabel(): string
{
    return __('Product Variation');
}

public static function getPluralModelLabel(): string
{
    return __('Product Variations');
}

    protected static ?string $model = ProductVariation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label(__('Product'))
                    ->required(),
                Forms\Components\TextInput::make('full_sku')
                    ->label(__('Full SKU'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('attribute_name')
                    ->label(__('Attribute Name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('additional_price')
                    ->label(__('Additional Price'))
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('stock_quantity')
                    ->label(__('Stock Quantity'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('reserved_quantity')
                    ->label(__('Reserved Quantity'))
                    ->required()
                    ->numeric()
                    ->default(0),

Forms\Components\Toggle::make('is_available')
                ->label(__('Is Available')) // متاح للعرض
                ->default(true)
                ->columnSpanFull(), // ليأخذ عرضاً كاملاً أو وضعه بجانب حقل آخر

CuratorPicker::make('images') // استخدم اسم العلاقة التي عرفناها في الموديل
    ->label(__('Product Gallery'))
    ->relationship('images', 'id') // الربط بالعلاقة
    ->multiple() // تفعيل التعدد
    ->orderColumn('order') // تفعيل حفظ الترتيب في العمود الذي أنشأناه في الـ Migration
    ->directory('product-variations')
    ->lazyLoad()
    ->listDisplay()
    ->columnSpanFull(),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_sku')
                    ->label(__('Full SKU'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('attribute_name')
                    ->label(__('Attribute Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('additional_price')
                    ->label(__('Additional Price'))
                    ->money('SYP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label(__('Stock Quantity'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reserved_quantity')
                    ->label(__('Reserved Quantity'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


Tables\Columns\IconColumn::make('is_available')
                ->label(__('Status'))
                ->boolean()
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
            'index' => Pages\ListProductVariations::route('/'),
            'create' => Pages\CreateProductVariation::route('/create'),
            'edit' => Pages\EditProductVariation::route('/{record}/edit'),
        ];
    }
}
