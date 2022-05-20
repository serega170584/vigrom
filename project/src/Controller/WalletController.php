<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Wallet;
use App\Repository\WalletRepository;
use App\Service\TransactionService;
use App\Validator\TransactionValidator;
use App\Validator\WalletValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Exception\ORMException
     */
    #[Route('/refill/{id}', name: 'wallet_refill', methods: ['POST'])]
    public function refill(
        ?Wallet $wallet,
        Request $request,
        TransactionValidator $transactionValidator,
        TransactionService $transactionService
    ): JsonResponse
    {
        $transactionData = $transactionValidator->map($wallet, $request);

        $transactionService->saveTransaction($transactionData);

        return $this->json([]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    #[Route('/balance/{id}', name: 'wallet_balance')]
    public function balance(?Wallet $wallet, WalletValidator $walletValidator, WalletRepository $walletRepository): JsonResponse
    {
        $walletValidator->validate($wallet);

        return $this->json($walletRepository->getBalance($wallet));
    }
}
