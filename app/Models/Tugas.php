<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $fillable = ['judul', 'deskripsi', 'deadline', 'mahasiswa_id'];

    public function mahasiswa()
{
    return $this->belongsTo(User::class, 'mahasiswa_id');
}
}
