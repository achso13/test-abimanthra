<?php

namespace App\Http\Controllers;

use App\Exports\NilaiExport;
use App\Exports\NilaiSampleExport;
use App\Imports\NilaiImport;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nilai = Nilai::query()
            ->with('siswa')
            ->when($request->search, 
                fn($query, $search) => $query->whereHas('siswa', fn($q) => $q->where('nama', 'like', "%$search%"))
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return inertia('Nilai/Index', [
            'nilai' => $nilai,
            'filters'  => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = Siswa::select('id', 'nama')->get();

        return inertia('Nilai/Create', [
            'siswa' => $siswa,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas' => 'required|string|max:255',
            'mapel' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);
        
        Nilai::create([
            'siswa_id' => $request->siswa_id,
            'kelas' => $request->kelas,
            'mapel' => $request->mapel,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $siswa = Siswa::select('id', 'nama')->get();
        $nilai = Nilai::with('siswa')->findOrFail($id);

        return inertia('Nilai/Edit', [
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $nilai = Nilai::findOrFail($id);

        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kelas' => 'required|string|max:255',
            'mapel' => 'required|string|max:255',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);
        
        $nilai->update([
            'siswa_id' => $request->siswa_id,
            'kelas' => $request->kelas,
            'mapel' => $request->mapel,
            'nilai' => $request->nilai,
        ]);

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus.');
    }

    public function export(Request $request)
    {
        return Excel::download(new NilaiExport($request->search), 'nilai-siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $import = new NilaiImport;
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        if ($failures->count() > 0) {
            $errorMessages = $failures
                ->map(fn($f) => "Baris {$f->row()}: " . implode(', ', $f->errors()))
                ->implode(' | ');

            return redirect()->route('nilai.index')->with('error', "Import selesai dengan beberapa error: {$errorMessages}");
        }

        return redirect()->route('nilai.index')->with('success', 'Import nilai berhasil.');
    }

    public function sample()
    {
        return Excel::download(new NilaiSampleExport, 'nilai-sample.xlsx');
    }
}
