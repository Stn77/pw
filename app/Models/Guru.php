<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'nip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pivot()
    {
        return $this->hasMany(GuruPivot::class);
    }
}
