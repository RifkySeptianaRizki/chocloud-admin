<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Perintah untuk menghapus kolom 'category'
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Perintah untuk mengembalikan kolom jika migrasi di-rollback
            $table->string('category')->nullable()->after('price');
        });
    }
};