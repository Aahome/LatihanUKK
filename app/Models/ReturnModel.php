<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'borrowing_id',
        'return_date',
        'fine',
    ];

    // Relasi ke peminjaman
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
