<?php

declare(strict_types=1);

namespace App\Entity;

enum TransactionStatus: string
{
    case NEW = 'new';
    case APPROVED = 'approved';
    case ERROR = 'error';
}