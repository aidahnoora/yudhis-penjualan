<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'nota',
        'tgl_transaksi',
        'total',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function item_pembelians()
    {
        return $this->hasMany(ItemTransaksiPembelian::class);
    }
}
