<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'user_id',
        'tool_id',
        'quantity',
        'borrow_date',
        'due_date',
        'status',
        'rejection_reason',
    ];

    // Relasi ke user (peminjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke alat
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    // Relasi ke pengembalian
    public function returnData()
    {
        return $this->hasOne(ReturnModel::class);
    }
}
