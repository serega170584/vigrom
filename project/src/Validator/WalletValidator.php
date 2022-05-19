<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Wallet;
use App\Traits\EmptyWalletValidatorTrait;

class WalletValidator
{
    use EmptyWalletValidatorTrait;

    public function validate(?Wallet $wallet): void
    {
        $this->validateWallet($wallet);
    }
}