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
        // Admin boleh melihat semua, mahasiswa hanya tugas miliknya
        return $user->isAdmin() || $tugas->mahasiswa_id === $user->id;
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
