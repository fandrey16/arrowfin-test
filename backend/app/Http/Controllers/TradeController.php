<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\TradeRepositoryInterface;
use App\Services\TradeAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class TradeController extends Controller
{
    public function __construct(
        private readonly TradeRepositoryInterface $tradeRepository,
        private readonly TradeAnalyticsService $analyticsService,
    ) {}

    public function storeTrade(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'symbol' => ['required', 'string'],
            'side' => ['required', 'in:long,short'],
            'entry_price' => ['required', 'numeric'],
            'exit_price' => ['required', 'numeric'],
            'size' => ['required', 'numeric'],
        ]);

        $trade = $this->tradeRepository->store($validated);

        $pnl = $this->analyticsService->calculatePnL($trade);
        $this->analyticsService->updateDayStreak($trade->user, $pnl);

        return response()->json([
            'trade_id' => $trade->id,
            'pnl' => $pnl,
        ], 201);
    }
}
