<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\BalanceUpdater;
use App\Repository\WalletRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:unoccupied-wallets',
    description: 'Add a short description for your command',
)]
class UnoccupiedWalletsCommand extends Command
{
    private WalletRepository $walletRepository;

    private MessageBusInterface $messageBus;

    public function __construct(
        WalletRepository $walletRepository,
        MessageBusInterface $messageBus,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->walletRepository = $walletRepository;
        $this->messageBus = $messageBus;
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

    protected function execute(InputInterface $input, OutputInterface $output): void
    {

        while (true) {
            $pendingWallets = $this->walletRepository->findPendingWallets();
            foreach ($pendingWallets as $pendingWallet) {
                $balanceUpdater = new BalanceUpdater($pendingWallet);

                $this->messageBus->dispatch($balanceUpdater);
            }
        }
    }
}
