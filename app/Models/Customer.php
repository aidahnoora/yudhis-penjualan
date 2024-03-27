<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'no_hp',
        'alamat',
    ];

    public function penjualans()
    {
        return $this->hasMany(TransaksiPenjualan::class);
    }
}
