<?php

$nilai = [
    ["nama" => "Andi", "mapel" => "Matematika", "nilai" => 80],
    ["nama" => "Budi", "mapel" => "Matematika", "nilai" => 60],
    ["nama" => "Citra", "mapel" => "Matematika", "nilai" => 90],
    ["nama" => "Andi", "mapel" => "Bahasa", "nilai" => 70],
    ["nama" => "Budi", "mapel" => "Bahasa", "nilai" => 75],
    ["nama" => "Citra", "mapel" => "Bahasa", "nilai" => 85],
];

// Helper: konversi nilai angka ke huruf
function nilaiHuruf(float $n): string {
    if ($n >= 85) return 'A';
    if ($n >= 70) return 'B';
    if ($n >= 60) return 'C';
    return 'D';
}

// ============================================================
// SOAL 1 — Rata-rata nilai tiap siswa + nilai huruf
// ============================================================
$totalPerSiswa = [];
$countPerSiswa = [];

foreach ($nilai as $row) {
    $totalPerSiswa[$row['nama']] = ($totalPerSiswa[$row['nama']] ?? 0) + $row['nilai'];
    $countPerSiswa[$row['nama']] = ($countPerSiswa[$row['nama']] ?? 0) + 1;
}

echo "=== SOAL 1: Rata-rata & Nilai Huruf per Siswa ===<br>";
foreach ($totalPerSiswa as $nama => $total) {
    $avg = $total / $countPerSiswa[$nama];
    echo "$nama => Rata-rata: $avg, Huruf: " . nilaiHuruf($avg) . "<br>";
}

// ============================================================
// SOAL 2 — Pivot: nama => [mapel => nilai]
// ============================================================
$pivot = [];
foreach ($nilai as $row) {
    $pivot[$row['nama']][$row['mapel']] = $row['nilai'];
}

echo "<br>=== SOAL 2: Pivot Data ===<br>";
print_r($pivot);

// ============================================================
// SOAL 3 — Nilai tengah Matematika (bukan terbaik & terburuk)
// ============================================================
$nilaiMatematika = [];
foreach ($nilai as $row) {
    if ($row['mapel'] === 'Matematika') {
        $nilaiMatematika[$row['nama']] = $row['nilai'];
    }
}

asort($nilaiMatematika);
$keys = array_keys($nilaiMatematika);
$tengah = $keys[intval(floor(count($keys) / 2))];

echo "<br><br>=== SOAL 3: Nilai Tengah Matematika ===<br>";
echo "Siswa nilai tengah: $tengah => " . $nilaiMatematika[$tengah] . "<br>";

// ============================================================
// SOAL 4 — Jumlah siswa per grade (A/B/C/D) per mapel
// ============================================================
$gradePerMapel = [];
foreach ($nilai as $row) {
    $huruf = nilaiHuruf($row['nilai']);
    $gradePerMapel[$row['mapel']][$huruf] = ($gradePerMapel[$row['mapel']][$huruf] ?? 0) + 1;
}

echo "<br>=== SOAL 4: Jumlah Siswa per Grade per Mapel ===<br>";
foreach ($gradePerMapel as $mapel => $grades) {
    echo "$mapel:<br>";
    foreach (['A', 'B', 'C', 'D'] as $g) {
        $count = $grades[$g] ?? 0;
        echo "  Grade $g: $count siswa<br>";
    }
}

// ============================================================
// SOAL 5 — Rata-rata per mapel + konversi ke huruf
// ============================================================
$totalPerMapel = [];
$countPerMapel = [];
foreach ($nilai as $row) {
    $totalPerMapel[$row['mapel']] = ($totalPerMapel[$row['mapel']] ?? 0) + $row['nilai'];
    $countPerMapel[$row['mapel']] = ($countPerMapel[$row['mapel']] ?? 0) + 1;
}

echo "<br>=== SOAL 5: Rata-rata per Mapel + Huruf ===<br>";
foreach ($totalPerMapel as $mapel => $total) {
    $avg = $total / $countPerMapel[$mapel];
    echo "$mapel => Rata-rata: $avg, Huruf: " . nilaiHuruf($avg) . "<br>";
}