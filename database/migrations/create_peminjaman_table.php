<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->equipments;
            $table->id();
            $table->string('kode_pinjam', 30)->unique();
            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->cascadeOnDelete();
            $table->string('nim_peminjam', 10);
            $table->code ('code_barang', 100) -> unique();
            $table->string('nama_peminjam', 100);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'selesai',default:'diajukan'])->default('dipinjam');
            $table->int('total_estimated_fee', selisih hari*tarif harian);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};