<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert([
            'id_setting' => 1,
            'nama_perusahaan' => 'Toko Ku',
            'alamat' => 'Jln. Raya Genteng Ds Alasmalang',
            'telepon' => '087736548080',
            'tipe_nota' => 1,
            'path_logo' => '/img/logo.png',
            'path_kartu_member' => '/img/member.png',
        ]);
    }
}
