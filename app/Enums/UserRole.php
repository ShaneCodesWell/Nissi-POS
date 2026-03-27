<?php

namespace App\Enums;

enum UserRole: string
{
    case Cashier    = 'cashier';
    case Supervisor = 'supervisor';
    case Manager    = 'manager';
}