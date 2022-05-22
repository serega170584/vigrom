<?php

declare(strict_types=1);

namespace App\Message;

class BalanceUpdater
{
    public function __construct(
        public readonly int $walletId
    )
    {
    }

    /**
     * @return int
     */
    public function getWalletId(): int
    {
        return $this->walletId;
    }
}