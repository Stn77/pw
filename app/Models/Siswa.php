<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'name',
        'nisn',
        'kelas_id',
        'jurusan_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
