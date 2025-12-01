<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'supplier_id',
        'user_id',
        'invoice_number',
        'purchase_date',
        'total_amount',
    ];

    protected $dates = [
        'purchase_date', 
        'created_at',
        'updated_at'
    ];

    /**
     * Relasi ke Supplier
     * Setiap pembelian dapat memiliki satu supplier (boleh null)
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi ke User
     * Setiap pembelian dilakukan oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke purchase items
     * Setiap pembelian dapat memiliki banyak item pembelian
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
