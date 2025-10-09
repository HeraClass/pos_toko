<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'barcode',
        'price',
        'quantity',
        'status',
        'category_id',
    ];

    /**
     * Relasi ke kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi many-to-many dengan Supplier
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
                    ->withTimestamps();
    }

    /**
     * Ambil URL gambar produk
     */
    public function getImageUrl(): string
    {
        if ($this->image && Storage::exists($this->image)) {
            return Storage::url($this->image);
        }
        return asset('images/img-placeholder.jpg');
    }
}
