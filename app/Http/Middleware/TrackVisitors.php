<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class TrackVisitors
{
    public function handle($request, Closure $next)
    {
        // 1. تجاهل زيارات لوحة التحكم (Admin)
        if ($request->is('admin*')) {
            return $next($request);
        }

        $ip = $request->ip();
        $today = now()->format('Y-m-d');

        // 2. إضافة الـ IP لمجموعة الزوار الفريدين (العام)
        // تقنية HyperLogLog تضمن عدم التكرار تلقائياً
        //Redis::pfadd('unique_visitors_all_time', [$ip]);

        // 3. إضافة الـ IP لمجموعة زوار اليوم الفريدين
        //Redis::pfadd("unique_visitors_day:$today", [$ip]);

        return $next($request);
    }
}
