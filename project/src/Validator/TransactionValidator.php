<?php

declare(strict_types=1);

namespace App\Validator;

use App\Dto\TransactionData;
use App\Entity\TransactionReason;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Entity\Wallet;
use App\Traits\EmptyWalletValidatorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TransactionValidator
{
    use EmptyWalletValidatorTrait;

    public function map(?Wallet $wallet, Request $request): TransactionData
    {
        $this->validateWallet($wallet);

        $amount = $request->get('amount');
        if (null === $amount) {
            throw new BadRequestHttpException('Amount is null!');
        }

        $amount = (int)$amount;
        if (0 === $amount) {
            throw new BadRequestHttpException('Amount is zero!');
        }

        $type = $request->get('type');
        if (!($type instanceof TransactionType)) {
            throw new BadRequestHttpException('Type is not recognized!');
        }

        $reason = $request->get('reason');
        if (!($reason instanceof TransactionReason)) {
            throw new BadRequestHttpException('Reason is not recognized!');
        }

        return new TransactionData(
            $wallet,
            $amount,
            $type,
            $reason,
            TransactionStatus::NEW
        );
    }
}