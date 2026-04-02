<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use App\Models\Product;


class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';


public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
{
    return __('Products');
}



public function form(Form $form): Form
{
    return $form
        ->schema([
            // قسم البيانات الأساسية
            Forms\Components\Section::make(__('Basic Information'))
                ->schema([
                    TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true), // لجعل الواجهة تفاعلية

                    TextInput::make('sku')
                        ->label(__('SKU'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50),
                ])->columns(2),

            // قسم السعر والوصف
            Forms\Components\Section::make(__('Details'))
                ->schema([
                    TextInput::make('price')
                        ->label(__('Price'))
                        ->prefix('SYP')
                        ->required()
                        ->default(0),

                    Toggle::make('is_available')
                        ->label(__('Is Available'))
                        ->default(true),

                    Textarea::make('description')
                        ->label(__('Description'))
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
}

public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('name')
        // هذا السطر هو المسؤول عن توجيه الضغط على السطر لصفحة المنتج في المتجر
        ->recordUrl(fn (Product $record): string => url("/admin/products/{$record->id}/edit"))
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('Name'))
                ->searchable()
                ->sortable(),


            Tables\Columns\TextColumn::make('price')
                ->label(__('Price'))
                ->money('SYP')
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            // يبقى هذا الزر مخصصاً للانتقال لصفحة التعديل داخل لوحة التحكم
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
