<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Awcodes\Curator\Components\Forms\CuratorPicker;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';



public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
{
    return __('Product Variations');
}



    public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make(__('Variation Details'))
                ->schema([
                    Forms\Components\TextInput::make('full_sku')
                        ->label(__('Full SKU'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g., PROD-RED-XL'),

                    Forms\Components\TextInput::make('attribute_name')
                        ->label(__('Attribute Name'))
                        ->required()
                        ->placeholder('e.g., Red, XL, 42'),

                    Forms\Components\TextInput::make('additional_price')
                        ->label(__('Additional Price'))
                        ->prefix('SYP')
                        ->default(0.00)
                        ->helperText('Extra cost added to base product price'),
                ])->columns(3),

            Forms\Components\Section::make(__('Inventory Management'))
                ->schema([
                    Forms\Components\TextInput::make('stock_quantity')
                        ->label(__('Stock Quantity'))
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0),

                    Forms\Components\TextInput::make('reserved_quantity')
                        ->label(__('Reserved Quantity'))
                        ->numeric()
                        ->default(0)
                        ->disabledOn('create'),

                    Forms\Components\Toggle::make('is_available')
                        ->label(__('Is Available'))
                        ->default(true)
                        ->inline(false),
                ])->columns(3),

            Forms\Components\Section::make(__('Media'))
                ->schema([
                    CuratorPicker::make('featured_image_id')
                        ->label(__('Image'))
                        ->relationship('featuredImage', 'id') // الربط مع علاقة الوسائط
                        ->constrained(true)
                        ->columnSpanFull(),
                ]),
        ]);
}

public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('full_sku')
        // توجيه الضغط على السطر لصفحة تعديل التنوع (Variation)
        ->recordUrl(fn ($record): string => url("/admin/product-variations/{$record->id}/edit"))
        ->columns([
            Tables\Columns\TextColumn::make('full_sku')
                ->label(__('Full SKU'))
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('attribute_name')
                ->label(__('Attribute Name')),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            // يبقى الزر مخصصاً للتعديل أيضاً لزيادة وضوح تجربة المستخدم
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
}
