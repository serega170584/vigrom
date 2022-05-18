<?php

declare(strict_types=1);

namespace App\Entity;

enum TransactionReason: string
{
    case STOCK = 'stock';
    case REFUND = 'refund';
}