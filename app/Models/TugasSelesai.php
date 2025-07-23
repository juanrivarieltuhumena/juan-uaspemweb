<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasSelesai extends Model
{
    use HasFactory;

    // Pastikan nama tabel cocok dengan migration SQLite-mu
    protected $table = 'tugas_selesais';

    protected $fillable = [
        'user_id',
        'tugas_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Tugas
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
