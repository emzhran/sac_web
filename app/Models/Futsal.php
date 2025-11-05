<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Futsal extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'jadwal', 'status', 'jam_mulai', 'jam_selesai'];
}