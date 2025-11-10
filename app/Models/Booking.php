<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'lapangan_id',
        'nama_pemesan',
        'status',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function jadwal()
    {
        return $this->hasOne(Jadwal::class);
    }
}
