<?php

namespace Modules\YrpDashboard\Entities;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class YrpUser extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'yrp_users';

    protected $fillable = [
        'name',
        'prefix_code',
        'first_name',
        'last_name',
        'role',
        'position',
        'department',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        // ตรวจสอบว่าค่าที่ส่งมาไม่ได้เข้ารหัสแล้ว
        if ($value && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * ตรวจสอบว่าผู้ใช้สามารถเข้าถึง Filament panel ได้หรือไม่
     */
    public function canAccessFilament(): bool
    {
        // เพิ่มตรรกะการตรวจสอบที่นี่ เช่น ตรวจสอบอีเมลโดเมน หรือฟิลด์ is_admin
        return true;
        
        // ตัวอย่างการตรวจสอบโดเมนอีเมล
        // return str_ends_with($this->email, '@yourdomain.com');
        
        // ตัวอย่างการตรวจสอบสถานะแอดมิน (ต้องเพิ่มฟิลด์ is_admin ในฐานข้อมูล)
        // return $this->is_admin;
    }

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
