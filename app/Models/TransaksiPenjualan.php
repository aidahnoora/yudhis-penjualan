<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nota',
        'tgl_transaksi',
        'total',
        'bayar',
        'kembalian',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item_penjualans()
    {
        return $this->hasMany(ItemTransaksiPenjualan::class);
    }
}
