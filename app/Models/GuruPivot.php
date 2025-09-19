<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GuruPivot extends Pivot
{
    //
    protected $table = 'guru_kelas';
    protected $fillable = ['guru_id', 'kelas_id', 'jurusan_id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
