<?php

namespace App\Exports;

use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NilaiExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(
        private ?string $search = null
    ) {}

    public function query()
    {
        return Nilai::with('siswa')
            ->when($this->search, 
                fn($q) => $q->whereHas('siswa', 
                    fn($q) => $q->whereRaw('nama ILIKE ?', ["%{$this->search}%"])
                )
            )
            ->orderBy('id');
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Kelas', 'Mapel', 'Nilai'];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->siswa->nama ?? '-',
            $row->kelas,
            $row->mapel,
            $row->nilai,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}