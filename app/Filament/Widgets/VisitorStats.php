<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Redis;

class VisitorStats extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // حساب إجمالي الزوار الفريدين منذ البداية
        $totalUnique = Redis::pfcount('unique_visitors_all_time') ?? 0;

        // حساب زوار اليوم الفريدين حصراً
        $today = now()->format('Y-m-d');
        $todayUnique = Redis::pfcount("unique_visitors_day:$today") ?? 0;

        return [
            Stat::make('إجمالي الزوار الفريدين', number_format($totalUnique))
                ->description('مستخدمون حقيقيون زاروا المتجر')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('زوار اليوم', number_format($todayUnique))
                ->description('أشخاص جدد اليوم')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('primary'),
        ];
    }
}
