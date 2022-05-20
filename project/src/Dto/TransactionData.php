<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\TransactionReason;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Entity\Wallet;

class TransactionData
{
    public function __construct(
        public readonly Wallet $wallet,
        public readonly int $amount,
        public readonly TransactionType $type,
        public readonly TransactionReason $reason,
        public readonly TransactionStatus $status
    )
    {
    }

    /**
     * @return Wallet
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return TransactionType
     */
    public function getType(): TransactionType
    {
        return $this->type;
    }

    /**
     * @return TransactionReason
     */
    public function getReason(): TransactionReason
    {
        return $this->reason;
    }

    /**
     * @return TransactionStatus
     */
    public function getStatus(): TransactionStatus
    {
        return $this->status;
    }
}