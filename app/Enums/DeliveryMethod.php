<?php

namespace App\Enums;

enum DeliveryMethod: string
{
    case Print = 'print';
    case Email = 'email';
    case SMS = 'sms';
    case None = 'none';
}