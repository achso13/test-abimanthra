<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nama', 'kelas', 'alamat', 'coordinate'
    ];

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }
}
