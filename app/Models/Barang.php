<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'nama',
        'stok',
        'harga',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function item_penjualans()
    {
        return $this->hasMany(ItemTransaksiPenjualan::class);
    }
}
