<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlah_siswa   = Siswa::count();
        $jumlah_kelas   = Siswa::distinct('kelas')->count('kelas');
        $rata_rata_nilai = Nilai::avg('nilai');
        $jumlah_mapel   = Nilai::distinct('mapel')->count('mapel');

        $rata_per_kelas = Nilai::selectRaw('kelas, ROUND(AVG(nilai)::numeric, 2) as rata_rata')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        $lokasi_siswa = Siswa::select('id', 'nama', 'kelas', 'alamat', 'coordinate')
            ->whereNotNull('coordinate')
            ->where('coordinate', '!=', '')
            ->get()
            ->map(function ($siswa) {
                [$lat, $lng] = explode(',', $siswa->coordinate);
                return [
                    'id'        => $siswa->id,
                    'nama'      => $siswa->nama,
                    'kelas'     => $siswa->kelas,
                    'alamat'    => $siswa->alamat,
                    'lat'       => (float) trim($lat),
                    'lng'       => (float) trim($lng),
                ];
            });

        return inertia('Dashboard', [
            'stats' => [
                'jumlah_siswa'   => $jumlah_siswa,
                'jumlah_kelas'   => $jumlah_kelas,
                'rata_rata_nilai' => round($rata_rata_nilai, 2),
                'jumlah_mapel'   => $jumlah_mapel,
            ],
            'rata_per_kelas' => $rata_per_kelas,
            'lokasi_siswa' => $lokasi_siswa,
        ]);
    }
}