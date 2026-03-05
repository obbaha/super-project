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
    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 1;

    // إلغاء زر الإنشاء من الواجهة
public static function canCreate(): bool
{
    return false;
}

// لإخفاء رابط "Create" من القائمة الجانبية أيضاً (اختياري)
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Customer & Location')
                ->schema([
                    Forms\Components\Select::make('customer_id')
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('governorate_id')
                        ->label('Governorate')
                        ->relationship('governorate', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive() // هام لتحديث باقي الحقول
                        ->afterStateUpdated(fn ($set) => $set('district_id', null) ?? $set('shipping_branch_id', null)),

                    // حقول التوصيل داخل دمشق (تظهر فقط إذا كانت المحافظة دمشق)
                    Forms\Components\Select::make('district_id')
                        ->label('District (Delivery)')
                        ->relationship('district', 'name', fn ($query, $get) =>
                            $query->where('governorate_id', $get('governorate_id'))
                        )
                        ->searchable()
                        ->preload()
                        ->required(fn ($get) => \App\Models\Governorate::find($get('governorate_id'))?->name === 'دمشق')
                        ->visible(fn ($get) => \App\Models\Governorate::find($get('governorate_id'))?->name === 'دمشق')
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            $district = \App\Models\District::find($state);
                            if ($district) {
                                $set('shipping_cost', $district->shipping_cost);
                            }
                        }),

                    Forms\Components\Textarea::make('detailed_address')
                        ->label('Detailed Address')
                        ->required(fn ($get) => \App\Models\Governorate::find($get('governorate_id'))?->name === 'دمشق')
                        ->visible(fn ($get) => \App\Models\Governorate::find($get('governorate_id'))?->name === 'دمشق')
                        ->columnSpanFull(),

                    // حقل الشحن للمحافظات (يظهر فقط إذا كانت المحافظة ليست دمشق)
                    Forms\Components\Select::make('shipping_branch_id')
                        ->label('Shipping Branch')
                        ->relationship('shippingBranch', 'branch_name', fn ($query, $get) =>
                            $query->where('governorate_id', $get('governorate_id'))
                        )
                        ->searchable()
                        ->preload()
                        ->required(fn ($get) => $get('governorate_id') && \App\Models\Governorate::find($get('governorate_id'))?->name !== 'دمشق')
                        ->visible(fn ($get) => $get('governorate_id') && \App\Models\Governorate::find($get('governorate_id'))?->name !== 'دمشق')
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            $branch = \App\Models\ShippingBranch::find($state);
                            if ($branch) {
                                $set('shipping_cost', $branch->shipping_cost);
                            }
                        }),
                ])->columns(2),

            Forms\Components\Section::make('Financial Details')
                ->schema([
                    Forms\Components\TextInput::make('shipping_cost')
                        ->numeric()
                        ->prefix('SYP')
                        ->readOnly() // لجعل الحساب آلياً من جدول المناطق/الأفرع
                        ->required(),

                    Forms\Components\Select::make('coupon_id')
                        ->relationship('coupon', 'code')
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $coupon = \App\Models\Coupon::find($state);
                            if ($coupon) {
                                $set('discount_amount', $coupon->value);
                            }
                        }),

                    Forms\Components\TextInput::make('discount_amount')
                        ->numeric()
                        ->default(0.00)
                        ->readOnly(),

                    Forms\Components\TextInput::make('total_price')
                        ->numeric()
                        ->required()
                        ->helperText('Total including shipping and discounts'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
                        ->disabled(fn ($record) => $record && $record->inventory_updated)
                        ->native(false),
                ])->columns(2),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),

Tables\Columns\TextColumn::make('governorate.name') // الوصول للاسم عبر العلاقة
    ->label('Governorate')
    ->searchable()
    ->sortable(),



                Tables\Columns\TextColumn::make('shippingBranch.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupon.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\IconColumn::make('inventory_updated')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
