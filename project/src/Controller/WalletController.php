<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Wallet;
use App\Repository\TransactionRepository;
use App\Repository\WalletRepository;
use App\Validator\TransactionValidator;
use App\Validator\WalletValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    #[Route('/refill/{id}', name: 'wallet_refill', methods: ['POST'])]
    public function refill(
        ?Wallet $wallet,
        Request $request,
        TransactionValidator $transactionValidator,
        TransactionRepository $transactionRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $transactionData = $transactionValidator->map($wallet, $request);

        $entity = $transactionRepository->createEntity(Transaction::class);
        $entity->setWallet($transactionData->getWallet());
        $entity->setAmount($transactionData->getAmount());
        $entity->setType($transactionData->getType());
        $entity->setReason($transactionData->getReason());
        $entity->setStatus($transactionData->getStatus());

        $entityManager->persist($entity);
        $entityManager->flush();

        return $this->json($entity->getId());
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/balance/{id}', name: 'wallet_balance')]
    public function balance(
        ?Wallet $wallet,
        WalletValidator $walletValidator,
        WalletRepository $walletRepository
    ): JsonResponse
    {
        $walletValidator->validate($wallet);

        return $this->json($wallet->getBalance());
    }
}
