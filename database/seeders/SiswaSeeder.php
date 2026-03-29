<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswa = [
            [
                'nama'       => 'Andi',
                'kelas'      => '7A',
                'alamat'     => 'Jl. Mawar No. 12, Depok, Jawa Barat',
                'coordinate' => '-6.402484,106.794243',
            ],
            [
                'nama'       => 'Budi',
                'kelas'      => '7A',
                'alamat'     => 'Jl. Melati No. 5, Depok, Jawa Barat',
                'coordinate' => '-6.412050,106.812340',
            ],
            [
                'nama'       => 'Citra',
                'kelas'      => '7B',
                'alamat'     => 'Jl. Anggrek No. 8, Depok, Jawa Barat',
                'coordinate' => '-6.389120,106.823450',
            ],
        ];

        foreach ($siswa as $data) {
            Siswa::create($data);
        }
    }
}