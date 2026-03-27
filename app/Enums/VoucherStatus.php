<?php

namespace App\Enums;

enum VoucherStatus: string
{
    case Assigned = 'assigned'; // Issued to customer, not yet used
    case Redeemed = 'redeemed'; // Used at checkout
    case Expired  = 'expired'; // Past expiry without being used
    case Revoked  = 'revoked'; // Manually cancelled by staff
}