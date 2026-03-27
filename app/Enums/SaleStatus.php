<?php

namespace App\Enums;

enum SaleStatus: string
{
    case Pending   = 'pending'; // In progress at terminal
    case Completed = 'completed'; // Fully paid
    case Voided    = 'voided'; // Cancelled after creation
    case Refunded  = 'refunded'; // Returned/refunded
}