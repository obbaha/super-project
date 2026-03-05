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

    public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Variation Details')
                ->schema([
                    Forms\Components\TextInput::make('full_sku')
                        ->label('Variation SKU')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g., PROD-RED-XL'),

                    Forms\Components\TextInput::make('attribute_name')
                        ->label('Attribute (Color/Size)')
                        ->required()
                        ->placeholder('e.g., Red, XL, 42'),

                    Forms\Components\TextInput::make('additional_price')
                        ->numeric()
                        ->prefix('+ SYP')
                        ->default(0.00)
                        ->helperText('Extra cost added to base product price'),
                ])->columns(3),

            Forms\Components\Section::make('Inventory Management')
                ->schema([
                    Forms\Components\TextInput::make('stock_quantity')
                        ->label('Initial Stock')
                        ->numeric()
                        ->required()
                        ->default(0)
                        ->minValue(0),

                    Forms\Components\TextInput::make('reserved_quantity')
                        ->label('Reserved (Manual Adjustment)')
                        ->numeric()
                        ->default(0)
                        ->disabledOn('create') // يفضل عدم تعديله يدوياً عند الإنشاء
                        ->helperText('Current reservations from orders'),

                    Forms\Components\Toggle::make('is_available')
                        ->label('Active Variation')
                        ->default(true)
                        ->inline(false),
                ])->columns(3),

            Forms\Components\Section::make('Media')
                ->schema([
                    CuratorPicker::make('featured_image_id')
                        ->label('Variation Image')
                        ->relationship('featuredImage', 'id') // الربط مع علاقة الوسائط
                        ->constrained(true)
                        ->columnSpanFull(),
                ]),
        ]);
}

public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('sku')
        // توجيه الضغط على السطر لصفحة تعديل التنوع (Variation)
        ->recordUrl(fn ($record): string => url("/admin/product-variations/{$record->id}/edit"))
        ->columns([
            Tables\Columns\TextColumn::make('sku')
                ->searchable()
                ->sortable(),
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
