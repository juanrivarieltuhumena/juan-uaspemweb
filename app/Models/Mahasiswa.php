<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'nim', 'prodi', 'angkatan', 'jenis_kelamin', 'email'];

    public function tugas()
{
    return $this->hasMany(Tugas::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}


}
