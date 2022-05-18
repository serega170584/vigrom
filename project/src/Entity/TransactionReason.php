<?php

namespace App\Entity;

enum TransactionReason: string
{
    case STOCK = 'stock';
    case REFUND = 'refund';
}