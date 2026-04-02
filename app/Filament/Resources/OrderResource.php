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
public static function getNavigationGroup(): ?string
{
    return __('Orders');
}

public static function getModelLabel(): string
{
    return __('Order');
}

public static function getPluralModelLabel(): string
{
    return __('Orders');
}

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
            Forms\Components\Section::make(__('Customer & Location'))
                ->schema([
                    Forms\Components\Select::make('customer_id')
                        ->label(__('Customer'))
                        ->relationship('customer', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('governorate_id')
                        ->label(__('Governorate'))
                        ->relationship('governorate', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive() // هام لتحديث باقي الحقول
                        ->afterStateUpdated(fn ($set) => $set('district_id', null) ?? $set('shipping_branch_id', null)),

                    // حقول التوصيل داخل دمشق (تظهر فقط إذا كانت المحافظة دمشق)
                    Forms\Components\Select::make('district_id')
                        ->label(__('District (Delivery)'))
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
                        ->label(__('Detailed Address'))
                        ->required(fn ($get) => \App\Models\Governorate::find($get('governorate_id'))?->name === 'دمشق')
                        ->visible(fn ($get) => \App\Models\Governorate::find($get('governorate_id'))?->name === 'دمشق')
                        ->columnSpanFull(),

                    // حقل الشحن للمحافظات (يظهر فقط إذا كانت المحافظة ليست دمشق)
                    Forms\Components\Select::make('shipping_branch_id')
                        ->label(__('Shipping Branch'))
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

            Forms\Components\Section::make(__('Financial Details'))
                ->schema([
                    Forms\Components\TextInput::make('shipping_cost')
                        ->label(__('Shipping Cost'))
                        ->prefix('SYP')
                        ->readOnly() // لجعل الحساب آلياً من جدول المناطق/الأفرع
                        ->required(),

                    Forms\Components\Select::make('coupon_id')
                        ->label(__('Coupon'))
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
                        ->label(__('Discount Amount'))
                        ->numeric()
                        ->default(0.00)
                        ->readOnly(),

                    Forms\Components\TextInput::make('total_price')
                        ->label(__('Total Price'))
                        ->prefix('SYP')
                        ->required(),

                    Forms\Components\Select::make('status')

->label(__('Status'))
->options([
    'pending' => __('pending'),
    'shipping' => __('shipping'),
    'completed' => __('completed'),
    'cancelled' => __('cancelled'),
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
                    ->label(__('Customer'))
                    ->numeric()
                    ->sortable(),

Tables\Columns\TextColumn::make('governorate.name') // الوصول للاسم عبر العلاقة
    ->label(__('Governorate'))
    ->searchable()
    ->sortable(),



Tables\Columns\TextColumn::make('status')
    ->label(__('Status'))
    ->badge() // اختياري: لجعل الحالة تظهر كبطاقة ملونة
    ->formatStateUsing(fn (string $state): string => __($state))
    ->color(fn (string $state): string => match ($state) {
        'pending' => 'warning',
        'shipping' => 'info',
        'completed' => 'success',
        'cancelled' => 'danger',
        default => 'gray',
    })
    ->searchable(),

                Tables\Columns\TextColumn::make('shipping_cost')
                    ->label(__('Shipping Cost'))
                    ->money('SYP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label(__('Total Price'))
                    ->money('SYP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('coupon.code')
                    ->label(__('Coupon'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->label(__('Discount Amount'))
                    ->money('SYP')
                    ->sortable(),
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
