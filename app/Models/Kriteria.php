<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = ['kode_kriteria', 'nama_kriteria', 'tipe', 'bobot'];

    protected $casts = ['bobot' => 'float'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
}