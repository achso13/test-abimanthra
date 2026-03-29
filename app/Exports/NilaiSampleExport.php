<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiSampleExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            [1, 'Andi',  '7A', 'Matematika', 80],
            [2, 'Andi',  '7A', 'Bahasa',     70],
            [3, 'Budi',  '7A', 'Matematika', 60],
            [4, 'Budi',  '7A', 'Bahasa',     75],
            [5, 'Citra', '7B', 'Matematika', 90],
            [6, 'Citra', '7B', 'Bahasa',     85],
        ];
    }

    public function headings(): array
    {
        return ['No', 'nama', 'kelas', 'mapel', 'nilai'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}