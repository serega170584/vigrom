<?php

declare(strict_types=1);

namespace App\Entity;

enum TransactionType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
}