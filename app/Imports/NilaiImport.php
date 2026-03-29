<?php

namespace App\Imports;

use App\Models\Nilai;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class NilaiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $siswa = Siswa::whereRaw('LOWER(nama) = ?', [strtolower($row['nama'])])->first();

        if (!$siswa) return null;

        return new Nilai([
            'siswa_id' => $siswa->id,
            'kelas'    => $row['kelas'],
            'mapel'    => $row['mapel'],
            'nilai'    => $row['nilai'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama'  => 'required|string',
            'kelas' => 'required|string',
            'mapel' => 'required|string',
            'nilai' => 'required|numeric|min:0|max:100',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required'  => 'Kolom nama wajib diisi.',
            'kelas.required' => 'Kolom kelas wajib diisi.',
            'mapel.required' => 'Kolom mapel wajib diisi.',
            'nilai.required' => 'Kolom nilai wajib diisi.',
            'nilai.numeric'  => 'Kolom nilai harus berupa angka.',
            'nilai.max'      => 'Kolom nilai tidak boleh lebih dari 100.',
        ];
    }
}