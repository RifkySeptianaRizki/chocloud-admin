<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengubah kolom menjadi tipe TEXT
            $table->text('shopee_link')->nullable()->change();
            $table->text('whatsapp_link')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Mengembalikan kolom ke tipe STRING jika di-rollback
            $table->string('shopee_link')->nullable()->change();
            $table->string('whatsapp_link')->nullable()->change();
        });
    }
};