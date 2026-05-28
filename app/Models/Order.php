<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'total_amount',
        'status',
        'dispatch_id',
        'failure_reason'
    ];

    /**
     * Cast de atributos nativos de Laravel 11/12
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'total_amount' => 'decimal:2',
        ];
    }
}