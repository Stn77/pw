<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatAbsen extends Model
{
    protected $fillable = ['user_id', 'tanggal', 'hari', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
