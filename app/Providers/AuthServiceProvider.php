<?php

namespace App\Providers;

use App\Models\Mahasiswa;
use App\Models\Tugas;
use App\Policies\MahasiswaPolicy;
use App\Policies\TugasPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Tugas::class => TugasPolicy::class,
        Mahasiswa::class => MahasiswaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
