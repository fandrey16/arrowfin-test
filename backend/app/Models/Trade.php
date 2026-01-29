<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $table = 'trades';

    protected $fillable = [
        'user_id',
        'symbol',
        'side',
        'entry_price',
        'exit_price',
        'quantity',
        'profit_loss',
        'status',
    ];

    protected $casts = [
        'entry_price' => 'decimal:2',
        'exit_price' => 'decimal:2',
        'profit_loss' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
