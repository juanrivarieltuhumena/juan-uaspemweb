<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TugasSelesai;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'deadline',
        'mahasiswa_id', // Harus disertakan jika digunakan di relasi dan form
    ];

    /**
     * Relasi ke mahasiswa (user yang mengerjakan tugas).
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Relasi ke status tugas selesai.
     */
    public function tugasSelesai()
    {
        return $this->hasMany(TugasSelesai::class, 'tugas_id');
    }

    /**
     * Cek apakah tugas ini sudah diselesaikan oleh user tertentu.
     */
    public function isSelesaiOleh(User $user)
    {
        return $this->tugasSelesai()->where('user_id', $user->id)->exists();
    }

    public function selesais()
{
    return $this->hasMany(TugasSelesai::class);
}

}
