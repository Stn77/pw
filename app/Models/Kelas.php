<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $fillable = [
        'name',
        'jurusan_id',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
