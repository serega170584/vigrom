<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Wallet;
use App\Service\TransactionService;
use App\Validator\TransactionValidator;
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
    #[Route('/balance', name: 'wallet_balance')]
    public function index(
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
}
