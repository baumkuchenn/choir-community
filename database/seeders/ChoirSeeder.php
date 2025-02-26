<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChoirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('choirs')->insert([
            [
                'nama'          => 'Ubaya Choir',
                'nama_singkat'  => 'Ubaya',
                'logo'          => 'https://picsum.photos/id/4/2000/1000',
                'profil'        => 'https://picsum.photos/id/4/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Surabaya',
                'alamat'        => 'Universitas Surabaya, Surabaya, Jawa Timur',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
            [
                'nama'          => 'Coro Semplice Indonesia',
                'nama_singkat'  => 'CSI',
                'logo'          => 'https://picsum.photos/id/5/2000/1000',
                'profil'        => 'https://picsum.photos/id/5/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Surabaya',
                'alamat'        => 'Surabaya, Jawa Timur',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
            [
                'nama'          => 'Chandelier Choir',
                'nama_singkat'  => 'Chandel',
                'logo'          => 'https://picsum.photos/id/6/2000/1000',
                'profil'        => 'https://picsum.photos/id/6/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Surabaya',
                'alamat'        => 'Surabaya, Jawa Timur',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
            [
                'nama'          => 'Petra Christian University Choir',
                'nama_singkat'  => 'PCU Choir',
                'logo'          => 'https://picsum.photos/id/7/2000/1000',
                'profil'        => 'https://picsum.photos/id/7/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Surabaya',
                'alamat'        => 'Universitas Petra, Surabaya, Jawa Timur',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
            [
                'nama'          => 'Paduan Suara Mahasiswa ITS',
                'nama_singkat'  => 'PSM ITS',
                'logo'          => 'https://picsum.photos/id/8/2000/1000',
                'profil'        => 'https://picsum.photos/id/8/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Surabaya',
                'alamat'        => 'Universitas Petra, Surabaya, Jawa Timur',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
            [
                'nama'          => 'Paduan Suara Mahasiswa Brawijaya',
                'nama_singkat'  => 'PSM Brawijaya',
                'logo'          => 'https://picsum.photos/id/14/2000/1000',
                'profil'        => 'https://picsum.photos/id/14/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Malang',
                'alamat'        => 'Universitas Brawijaya, Malang, Jawa Timur',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
            [
                'nama'          => 'Batavia Madrigal Singer',
                'nama_singkat'  => 'BMS',
                'logo'          => 'https://picsum.photos/id/15/2000/1000',
                'profil'        => 'https://picsum.photos/id/15/2000/1000',
                'tipe'          => 'SSAATTBB',
                'kota'          => 'Jakarta',
                'alamat'        => 'Jakarta, DKI Jakarta',
                'deskripsi'     => 'Lorem ipsum.',
                'users_id'      => 1, // Make sure this user exists in the 'users' table
            ],
        ]);
    }
}
