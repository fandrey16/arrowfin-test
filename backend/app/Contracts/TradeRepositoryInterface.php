<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Trade;

interface TradeRepositoryInterface
{
 
    public function store(array $data): Trade;

}
