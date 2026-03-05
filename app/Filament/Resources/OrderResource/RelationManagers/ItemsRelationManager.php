<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

public function form(Form $form): Form
{
    $isProcessed = $this->getOwnerRecord()->inventory_updated;
    return $form
        ->schema([
            // اختيار المنتج والتنوع في قائمة واحدة احترافية
            Forms\Components\Select::make('product_variation_id')
                ->disabled($isProcessed)
                ->label('Product & Variation')
                ->relationship('variation', 'id') // نستخدم العلاقة المباشرة
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->product->name} - {$record->attribute_name}")
                ->searchable()
                ->preload()
                ->required()
                ->reactive() // لجعل الحقول الأخرى تتفاعل معه
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // جلب السعر عند اختيار المنتج
                    $variation = \App\Models\ProductVariation::with('product')->find($state);
                    if ($variation) {
                        // السعر الإجمالي للوحدة = سعر المنتج الأساسي + السعر الإضافي للتنوع
                        $unitPrice = $variation->product->price + $variation->additional_price;
                        $set('unit_price', $unitPrice);
                        
                        // تحديث المجموع الفرعي بناءً على الكمية الموجودة
                        $quantity = $get('quantity') ?? 1;
                        $set('subtotal', $unitPrice * $quantity);
                    }
                })
                ->columnSpan(2),

            Forms\Components\TextInput::make('quantity')
                ->disabled($isProcessed)
                ->label('Quantity')
                ->numeric()
                ->default(1)
                ->minValue(1)
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // تحديث المجموع الفرعي عند تغيير الكمية
                    $unitPrice = $get('unit_price') ?? 0;
                    $set('subtotal', (float)$state * (float)$unitPrice);
                }),

            Forms\Components\TextInput::make('subtotal')
                ->label('Subtotal')
                ->numeric()
                ->readOnly() // لا يمكن للمستخدم تعديله يدوياً لضمان الدقة
                ->prefix('$') // أو "ر.س" حسب رغبتك
                ->required(),

            // حقل مخفي لتخزين سعر الوحدة واستخدامه في الحسابات
            Forms\Components\Hidden::make('unit_price'),
        ])
        ->columns(4);
}

public function table(Table $table): Table
{
    // جلب سجل الطلب الأب [cite: 35]
    $order = $this->getOwnerRecord();
    // التحقق هل الحالة نهائية (تمت المعالجة) [cite: 55, 56]
    $isProcessed = $order->inventory_updated; 

    return $table
        ->recordTitleAttribute('product_variation_id')
        ->columns([
            Tables\Columns\TextColumn::make('variation.product.name')
                ->label('Product'),
            Tables\Columns\TextColumn::make('variation.attribute_name')
                ->label('Variation'),
            Tables\Columns\TextColumn::make('quantity')
                ->label('Qty'),
            Tables\Columns\TextColumn::make('subtotal')
                ->money('SAR'), // أو العملة التي تفضلها
        ])
        ->headerActions([
            // منع إنشاء عنصر جديد إذا كان الطلب مكتملاً أو ملغياً [cite: 48]
            Tables\Actions\CreateAction::make()
                ->disabled($isProcessed)
                ->hidden($isProcessed),
        ])
        ->actions([
            // منع التعديل والحذف [cite: 48]
            Tables\Actions\EditAction::make()
                ->disabled($isProcessed)
                ->hidden($isProcessed),
            Tables\Actions\DeleteAction::make()
                ->disabled($isProcessed)
                ->hidden($isProcessed),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->disabled($isProcessed),
            ])->visible(!$isProcessed),
        ]);
}
}
