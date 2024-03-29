<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';
    protected $guarded = [];
    protected $primaryKey = 'id_penjualan';

    public function penjualandetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_penjualan', 'id_penjualan_detail');
    }
}
