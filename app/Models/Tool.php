<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = [
        'tool_name',
        'category_id',
        'stock',
        'condition',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke peminjaman
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
}
