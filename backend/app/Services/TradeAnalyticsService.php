<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Trade;
use App\Models\User;

final class TradeAnalyticsService
{
    public function calculatePnL(Trade $trade): float
    {
        // Example: (exit - entry) * size for long, reverse for short
        $multiplier = $trade->side === 'short' ? -1 : 1;

        return ($trade->exit_price - $trade->entry_price) * $trade->size * $multiplier;
    }

    public function updateDayStreak(User $user, float $pnl): void
    {
        // Example streak logic: increment on profitable day, reset on loss
        if ($pnl > 0) {
            $user->day_streak = ($user->day_streak ?? 0) + 1;
        } else {
            $user->day_streak = 0;
        }

        $user->save();
    }
}
