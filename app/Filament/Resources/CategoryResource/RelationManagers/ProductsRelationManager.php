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

public function form(Form $form): Form
{
    return $form
        ->schema([
            // قسم البيانات الأساسية
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true), // لجعل الواجهة تفاعلية

                    TextInput::make('sku')
                        ->label('SKU (Stock Keeping Unit)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50),
                ])->columns(2),

            // قسم السعر والوصف
            Forms\Components\Section::make('Details')
                ->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('SYP')
                        ->required()
                        ->default(0),

                    Toggle::make('is_available')
                        ->label('Available for sale')
                        ->default(true),

                    Textarea::make('description')
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
