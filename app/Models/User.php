<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser; // موجودة مسبقاً
use Filament\Panel; // موجودة مسبقاً

// اضفنا implements FilamentUser هنا
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
    ];

    // أضف هذه الدالة في نهاية الكلاس قبل القوس الأخير }
    public function canAccessPanel(Panel $panel): bool
    {
        // هذا السطر يسمح لأي مستخدم مسجل كأدمن بالدخول
        return true;
    }
}
