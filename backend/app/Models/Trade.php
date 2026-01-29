<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Trade extends Model
{
    protected $table = 'trades';

    protected $fillable = [
        'user_id',
        'symbol',
        'side',
        'entry_price',
        'exit_price',
        'size',
        'opened_at',
        'closed_at',
    ];

    protected $casts = [
        'entry_price' => 'float',
        'exit_price' => 'float',
        'size' => 'float',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
