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
            // عرض كود الخصم بشكل بارز
            Tables\Columns\TextColumn::make('code')
                ->label('Coupon Code')
                ->searchable()
                ->fontFamily('mono')
                ->weight('bold')
                ->copyable(), // يسمح للمدير بنسخ الكود بنقرة واحدة

            // عرض قيمة الخصم كنسبة مئوية
            Tables\Columns\TextColumn::make('value')
                ->label('Discount (%)')
                ->suffix('%')
                ->sortable()
                ->color('primary'),

            // حدود الاستخدام
            Tables\Columns\TextColumn::make('usage_limit')
                ->label('Limit')
                ->placeholder('Unlimited')
                ->sortable(),

            // تاريخ الانتهاء
            Tables\Columns\TextColumn::make('expiry_date')
                ->label('Expiry Date')
                ->date()
                ->sortable()
                ->color(fn ($record): string => $record->expiry_date < now() ? 'danger' : 'gray'),

            // حالة التفعيل (تبديل مباشر من الجدول)
            Tables\Columns\IconColumn::make('is_active')
                ->label('Status')
                ->boolean()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Created At')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            // إضافة فلتر لتصفية الكوبونات النشطة فقط
            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Active Status'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
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
