<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Currency::class, inversedBy: 'wallets')]
    #[ORM\JoinColumn(nullable: false)]
    private Currency $currency;

    #[ORM\OneToMany(mappedBy: 'wallet', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $balancedAt;

    #[ORM\Column(type: 'integer')]
    private int $balance;

    #[ORM\Column(type: 'boolean')]
    private bool $isOccupied;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setWallet($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getWallet() === $this) {
                $transaction->setWallet(null);
            }
        }

        return $this;
    }

    public function getBalancedAt(): ?\DateTimeImmutable
    {
        return $this->balancedAt;
    }

    public function setBalancedAt(\DateTimeImmutable $balancedAt): self
    {
        $this->balancedAt = $balancedAt;

        return $this;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function isIsOccupied(): bool
    {
        return $this->isOccupied;
    }

    public function setIsOccupied(bool $isOccupied): self
    {
        $this->isOccupied = $isOccupied;

        return $this;
    }

}
