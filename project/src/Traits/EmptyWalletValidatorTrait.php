<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entity\Wallet;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait EmptyWalletValidatorTrait
{
    public function validateWallet(?Wallet $wallet)
    {
        if (null === $wallet) {
            throw new NotFoundHttpException('Wallet is not found!');
        }
    }
}