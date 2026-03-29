<?php

namespace Database\Seeders;

use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Andi',  'kelas' => '7A', 'mapel' => 'Matematika', 'nilai' => 80],
            ['nama' => 'Andi',  'kelas' => '7A', 'mapel' => 'Bahasa',     'nilai' => 70],
            ['nama' => 'Budi',  'kelas' => '7A', 'mapel' => 'Matematika', 'nilai' => 60],
            ['nama' => 'Budi',  'kelas' => '7A', 'mapel' => 'Bahasa',     'nilai' => 75],
            ['nama' => 'Citra', 'kelas' => '7B', 'mapel' => 'Matematika', 'nilai' => 90],
            ['nama' => 'Citra', 'kelas' => '7B', 'mapel' => 'Bahasa',     'nilai' => 85],
        ];

        foreach ($data as $row) {
            $siswa = Siswa::where('nama', $row['nama'])->first();

            if (!$siswa) continue;

            Nilai::create([
                'siswa_id' => $siswa->id,
                'kelas'    => $row['kelas'],
                'mapel'    => $row['mapel'],
                'nilai'    => $row['nilai'],
            ]);
        }
    }
}