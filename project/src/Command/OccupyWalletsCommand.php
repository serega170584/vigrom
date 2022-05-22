<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Wallet;
use App\Message\BalanceUpdater;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:occupy-wallets',
    description: 'Add a short description for your command',
)]
class OccupyWalletsCommand extends Command
{
    private WalletRepository $walletRepository;

    private MessageBusInterface $messageBus;
    private EntityManagerInterface $entityManager;

    public function __construct(
        WalletRepository $walletRepository,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->walletRepository = $walletRepository;
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
    }

    /**
     * @return WalletRepository
     */
    public function getWalletRepository(): WalletRepository
    {
        return $this->walletRepository;
    }

    /**
     * @return MessageBusInterface
     */
    public function getMessageBus(): MessageBusInterface
    {
        return $this->messageBus;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        while (true) {
            $pendingWallets = $this->walletRepository->findPendingWallets();
            foreach ($pendingWallets as $pendingWallet) {
                /**
                 * @var Wallet $pendingWallet
                 */
                $balanceUpdater = new BalanceUpdater($pendingWallet->getId());
                $pendingWallet->setIsOccupied(true);
                $this->messageBus->dispatch($balanceUpdater);
            }
            $this->entityManager->flush();
        }
    }
}
