<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\TransactionStatus;
use App\Entity\TransactionType;
use App\Message\BalanceUpdater;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class BalanceUpdaterHandler
{
    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(
        BalanceUpdater $balanceUpdater,
        TransactionRepository $transactionRepository,
        WalletRepository $walletRepository,
        EntityManagerInterface $entityManager
    )
    {
        $wallet = $walletRepository->findPendingWallet($balanceUpdater->getWalletId());

        $transactions = $wallet->getTransactions();

        $balance = $wallet->getBalance();

        foreach ($transactions as $transaction) {
            if ($transaction->getType() === TransactionType::DEBIT) {
                $balance += $transaction->getAmount();
                $transaction->setStatus(TransactionStatus::APPROVED);
            } else {
                $walletBalance = $balance;
                $balance -= $transaction->getAmount();
                $transaction->setStatus(TransactionStatus::APPROVED);
                if ($balance < 0) {
                    $balance = $walletBalance;
                    $transaction->setStatus(TransactionStatus::ERROR);
                }
            }
        }

        $wallet->setBalance($balance);
        $wallet->setIsOccupied(false);

        $entityManager->flush();

    }
}