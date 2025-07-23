<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @method bool isAdmin()
 * @method bool isMahasiswa()
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi: Mahasiswa memiliki banyak tugas.
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mahasiswa_id');
    }

    /**
     * Helper untuk cek apakah user adalah admin.
     */
    public function isAdmin(): bool
{
    return $this->role === 'admin';
}


    /**
     * Helper untuk cek apakah user adalah mahasiswa.
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function tugasSelesai()
{
    return $this->hasMany(TugasSelesai::class);
}

}
