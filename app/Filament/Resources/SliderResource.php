<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SliderResource\Pages;
use App\Filament\Resources\SliderResource\RelationManagers;
use App\Models\Slider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Awcodes\Curator\Components\Forms\CuratorPicker;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Awcodes\Curator\Components\Tables\CuratorColumn;

class SliderResource extends Resource
{
    protected static ?string $model = Slider::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('title')
            ->label(__('Title')), // سيقرأ من ملفات اللغات: العنوان / Title

        Forms\Components\TextInput::make('link')
            ->label(__('Redirect Link')), // رابط التوجيه / Redirect Link

        CuratorPicker::make('media_id')
            ->label(__('Slider Image')) // صورة الإعلان / Slider Image
            ->required()
            ->directory('sliders'),

        Forms\Components\TextInput::make('order')
            ->numeric()
            ->default(0)
            ->label(__('Sort Order')), // الترتيب / Order

        Forms\Components\Toggle::make('is_active')
            ->label(__('Active')) // تفعيل / Active
            ->default(true),
    ]);
}

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                CuratorColumn::make('media_id')
                    ->label(__('Image'))
                    ->size(80)
                    ->rounded(),

                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('link')
                    ->label(__('Redirect Link'))
                    ->color('gray')
                    ->limit(30),

                TextColumn::make('order')
                    ->label(__('Sort Order'))
                    ->sortable()
                    ->badge(),

                ToggleColumn::make('is_active')
                    ->label(__('Active')),
            ])
            // تم إصلاح السطر الذي كان يسبب المشكلة (إزالة أي تعليقات Blade)
            ->reorderable('order')
            ->defaultSort('order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active Status'))
                    ->placeholder(__('All'))
                    ->trueLabel(__('Active'))
                    ->falseLabel(__('Inactive')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSliders::route('/'),
            'create' => Pages\CreateSlider::route('/create'),
            'edit' => Pages\EditSlider::route('/{record}/edit'),
        ];
    }
}
