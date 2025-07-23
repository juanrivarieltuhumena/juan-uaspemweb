<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasSelesai;
use Illuminate\Support\Facades\Auth;

class TugasSelesaiController extends Controller
{
    public function tandaiSelesai(Tugas $tugas)
    {
        $user = Auth::user();

        // Cek apakah sudah pernah menandai selesai
        $sudah = TugasSelesai::where('user_id', $user->id)
            ->where('tugas_id', $tugas->id)
            ->exists();

        if (!$sudah) {
            TugasSelesai::create([
                'user_id' => $user->id,
                'tugas_id' => $tugas->id,
            ]);
        }

        return back()->with('success', 'Tugas berhasil ditandai selesai!');
    }
}
