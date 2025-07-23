<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel baru tanpa kolom mahasiswa_id
        Schema::create('tugas_temp', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamps();
        });

        // 2. Salin data dari tabel tugas lama ke tugas_temp
        DB::statement('INSERT INTO tugas_temp (id, judul, deskripsi, deadline, created_at, updated_at)
                       SELECT id, judul, deskripsi, deadline, created_at, updated_at FROM tugas');

        // 3. Hapus tabel tugas lama
        Schema::drop('tugas');

        // 4. Ganti nama tugas_temp menjadi tugas
        Schema::rename('tugas_temp', 'tugas');
    }

    public function down(): void
    {
        // Tambahkan kembali kolom mahasiswa_id jika di-rollback
        Schema::table('tugas', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
        });
    }
};
