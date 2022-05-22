<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private float $rate;

    public function getId(): string
    {
        return $this->id;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
