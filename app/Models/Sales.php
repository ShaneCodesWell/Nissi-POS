<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SaleStatus;

class Sales extends Model
{
    /** @use HasFactory<\Database\Factories\SalesFactory> */
    use HasFactory;

    protected $casts = [
        'status' => SaleStatus::class,
    ];
}
