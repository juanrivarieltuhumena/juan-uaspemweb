<?php

namespace App\Policies;

use App\Models\Tugas;
use App\Models\User;

class TugasPolicy
{
    public function viewAny(User $user): bool
    {
        // Semua user (admin dan mahasiswa) bisa akses daftar tugas
        return true;
    }

    public function view(User $user, Tugas $tugas): bool
{
    // Admin boleh lihat semua
    if ($user->isAdmin()) {
        return true;
    }

    // Mahasiswa hanya boleh lihat tugas yang sudah ditugaskan padanya
    return $tugas->tugasSelesai()->where('user_id', $user->id)->exists();
}


    public function create(User $user): bool
    {
        // Hanya admin yang bisa membuat tugas
        return $user->isAdmin();
    }

    public function update(User $user, Tugas $tugas): bool
    {
        // Hanya admin yang bisa mengedit tugas
        return $user->isAdmin();
    }

    public function delete(User $user, Tugas $tugas): bool
    {
        // Hanya admin yang bisa menghapus tugas
        return $user->isAdmin();
    }
}
