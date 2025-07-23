<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tugas Terbaru</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9f9;
            padding: 40px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
        }

        .task-card {
            background: white;
            border-left: 5px solid #facc15;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0,0,0,0.1);
        }

        .task-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .task-desc {
            font-size: 16px;
            margin-bottom: 6px;
        }

        .task-deadline {
            font-size: 15px;
            font-weight: bold;
            color: #b91c1c;
        }

        .task-status {
            margin-top: 10px;
            font-weight: bold;
            color: green;
        }

        .task-form {
            margin-top: 10px;
        }

        .footer-note {
            margin-top: 30px;
            font-style: italic;
            font-size: 14px;
            color: #666;
            text-align: center;
        }

        .button-selesai {
            padding: 8px 16px;
            background-color: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .button-selesai:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>
    <h1>Daftar Tugas Terbaru</h1>

    @foreach ($tugas as $item)
        <div class="task-card">
            <div class="task-title">{{ $item->judul }}</div>
            <div class="task-desc">{{ $item->deskripsi }}</div>
            <div class="task-deadline">Deadline: {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y') }}</div>

            @auth
                @if (auth()->user()->isMahasiswa())
                @php
                $selesai = $item->isSelesaiOleh(auth()->user());
            @endphp
            

                    @if (!$selesai)
                        <form action="{{ route('tugas.selesai', $item->id) }}" method="POST" class="task-form">
                            @csrf
                            <button type="submit" class="button-selesai">Tandai Selesai</button>
                        </form>
                    @else
                        <div class="task-status">✅ Tugas Selesai</div>
                    @endif

                @elseif (auth()->user()->isAdmin())
                    @php
                        $jumlahSelesai = \App\Models\TugasSelesai::where('tugas_id', $item->id)->count();
                    @endphp
                    <div class="task-status">📊 {{ $jumlahSelesai }} mahasiswa telah menyelesaikan tugas ini.</div>
                @endif
            @endauth
        </div>
    @endforeach

    <div class="footer-note">
        Data di atas berasal dari input dosen melalui admin dashboard (Filament).
    </div>
</body>
</html>
