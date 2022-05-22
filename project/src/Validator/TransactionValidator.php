<?php

declare(strict_types=1);

namespace App\Validator;

use App\Dto\TransactionData;
use App\Entity\TransactionReason;
use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Entity\Wallet;
use App\Repository\CurrencyRepository;
use App\Traits\EmptyWalletValidatorTrait;
use Money\Converter as MoneyConverter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Money;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransactionValidator
{
    use EmptyWalletValidatorTrait;

    public function __construct(
        public readonly ISOCurrencies $currencies,
        public readonly CurrencyRepository $currencyRepository
    )
    {
    }

    public function map(?Wallet $wallet, Request $request): TransactionData
    {
        $this->validateWallet($wallet);

        $amount = $request->get('amount');
        if (null === $amount) {
            throw new BadRequestHttpException('Amount is null!');
        }

        $amount = (float)$amount;
        if (0.0 === $amount) {
            throw new BadRequestHttpException('Amount is zero!');
        }

        $currency = $request->get('currency');
        if (!$this->currencies->contains($currency)) {
            throw new BadRequestHttpException('Currency is not recognized!');
        }

        if (null === $this->currencyRepository->find($currency)) {
            throw new BadRequestHttpException('Currency is not recognized!');
        }

        $subunitDivider = 10 ** $this->currencies->subunitFor(new Currency($currency));
        $money = new Money($amount * $subunitDivider, new Currency($currency));

        $currencyRepository = $this->currencyRepository;

        $currencyRate = $currencyRepository->find($currency)->getRate();

        $walletCurrencyId = $wallet->getCurrencyId()->getId();
        $walletCurrencyRate = $currencyRepository->find($walletCurrencyId)->getRate();

        $currencyExchange = new FixedExchange([
            $currency => [
                $walletCurrencyId => $currencyRate / $walletCurrencyRate
            ]
        ]);

        $moneyConverter = new MoneyConverter($this->currencies, $currencyExchange);
        $money = $moneyConverter->convert($money, new Currency($walletCurrencyId));

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
            (int)$money->getAmount(),
            $type,
            $reason,
            TransactionStatus::NEW
        );
    }
}