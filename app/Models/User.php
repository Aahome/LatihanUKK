<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relasi ke peminjaman
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    // Relasi ke log aktivitas
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
