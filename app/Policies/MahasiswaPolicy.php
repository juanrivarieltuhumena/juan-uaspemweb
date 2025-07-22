<?php

namespace App\Policies;

use App\Models\Mahasiswa;
use App\Models\User;

class MahasiswaPolicy
{
    public function viewAny(User $user): bool
    {
        // Hanya admin yang boleh lihat daftar mahasiswa
        return $user->isAdmin();
    }

    public function view(User $user, Mahasiswa $mahasiswa): bool
    {
        // Admin boleh lihat semua
        // Mahasiswa hanya bisa lihat dirinya sendiri
        return $user->isAdmin() || $user->id === $mahasiswa->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Mahasiswa $mahasiswa): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Mahasiswa $mahasiswa): bool
    {
        return $user->isAdmin();
    }
}
