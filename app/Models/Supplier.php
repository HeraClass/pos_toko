<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'avatar'
    ];

    /**
     * Relasi many-to-many dengan Product
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier')
                    ->withTimestamps();
    }

    /**
     * Accessor untuk nama lengkap
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAvatarUrl()
    {
        return Storage::url($this->avatar);
    }
}
