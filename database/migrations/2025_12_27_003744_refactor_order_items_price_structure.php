<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        // 1. Tambah kolom baru
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->nullable()->after('product_id');
            $table->decimal('subtotal', 10, 2)->nullable()->after('quantity');
        });

        // 2. Migrasi data lama
        DB::statement("
            UPDATE order_items
            SET unit_price = price / NULLIF(quantity, 0),
                subtotal = price
            WHERE quantity > 0
        ");

        // 3. DROP kolom price
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->after('product_id');
            $table->dropColumn(['unit_price', 'subtotal']);
        });
    }
};
