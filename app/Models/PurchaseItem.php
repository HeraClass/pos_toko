<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'expired_date',
        'quantity',
        'price',
    ];

    /**
     * Relasi ke Purchase
     * Setiap item pembelian dimiliki oleh satu pembelian
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Relasi ke Product
     * Setiap item pembelian mengacu ke satu produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
