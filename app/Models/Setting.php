<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Ambil nilai pengaturan, dengan nilai cadangan jika belum ada
    public static function get($key, $default = null)
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    // Simpan atau perbarui pengaturan
    public static function set($key, $value)
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}