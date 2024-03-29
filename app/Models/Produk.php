<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $guarded = [];
    protected $primaryKey = 'id_produk';

    public function penjualandetail()
    {
        return $this->belongsTo(PenjualanDetail::class, 'id_produk', 'id_penjualan_detail');
    }
}
