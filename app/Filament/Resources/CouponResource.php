<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Tables\Filters\TernaryFilter;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?int $navigationSort = 3;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Coupon Details')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('E.g., SAVE20'),

                    Forms\Components\TextInput::make('value')
                        ->label('Discount Percentage (%)')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(100)
                        ->suffix('%'), // توضيح أنها نسبة مئوية
                ])->columns(2),

            Forms\Components\Section::make('Usage Limits & Expiry')
                ->schema([
                    Forms\Components\TextInput::make('usage_limit')
                        ->numeric()
                        ->label('Total Usage Limit')
                        ->helperText('Leave empty for unlimited'),

                    Forms\Components\DatePicker::make('expiry_date')
                        ->label('Expiration Date')
                        ->native(false),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Is Active')
                        ->default(true),
                ])->columns(3),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // عرض اسم الزبون
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer')
                ->searchable()
                ->sortable(),

            // عرض اسم المحافظة من العلاقة
            Tables\Columns\TextColumn::make('governorate.name')
                ->label('Governorate')
                ->sortable(),

            // تكلفة الشحن
            Tables\Columns\TextColumn::make('shipping_cost')
                ->label('Shipping')
                ->money('SYP')
                ->sortable(),

            // الخصم
            Tables\Columns\TextColumn::make('discount_amount')
                ->label('Discount')
                ->money('SYP')
                ->color('danger'),

            // السعر النهائي - الأهم
            Tables\Columns\TextColumn::make('total_price')
                ->label('Total Amount')
                ->money('SYP')
                ->weight('bold')
                ->color('success')
                ->sortable(),

            // حالة الطلب مع ألوان تمييزية
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                }),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->dateTime()
                ->sortable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
