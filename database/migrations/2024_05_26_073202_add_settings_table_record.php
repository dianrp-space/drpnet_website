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
        // Migrasi ini hanya untuk mencatat bahwa tabel settings sudah ada di database
        // Tabel settings ditambahkan secara manual melalui SQL untuk menghindari kehilangan data
        
        // Jangan buat tabel settings dari Laravel, karena sudah dibuat melalui SQL langsung
        // This migration is empty on purpose
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jangan lakukan apa-apa ketika rollback - tabel settings akan dikelola secara manual
        // This migration is empty on purpose
    }
};
