<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'status',
        'requested_at', // Changed from 'request_date' to 'requested_at'
        'approved_at', // Changed from 'approval_date' to 'approved_at'
        'estimated_return_at',
        'actual_return_at', // Changed from 'real_return_date' to 'actual_return_at'
        'observations',
    ];

    protected $casts = [
        'requested_at' => 'datetime', // Changed from 'request_date' to 'requested_at'
        'approved_at' => 'datetime', // Changed from 'approval_date' to 'approved_at'
        'estimated_return_at' => 'datetime',
        'actual_return_at' => 'datetime', // Changed from 'real_return_date' to 'actual_return_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
