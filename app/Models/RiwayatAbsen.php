<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatAbsen extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'tanggal', 'hari', 'is_late'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
