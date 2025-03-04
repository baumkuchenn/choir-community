<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            [
                'nama'            => 'Divya Gatha',
                'tipe'            => 'Konser',
                'tanggal_mulai'   => Carbon::parse('2025-02-15'),
                'tanggal_selesai' => Carbon::parse('2025-02-15'),
                'jam_mulai'       => Carbon::parse('17:00:00')->format('H:i:s'),
                'jam_selesai'     => Carbon::parse('21:00:00')->format('H:i:s'),
                'lokasi'          => 'Perpustakaan Lt 5, Universitas Surabaya, Surabaya',
                'peran'           => 'Keduanya',
                'panitia_eksternal' => 'Tidak',
                'metode_rekrut_panitia' => 'Pilih',
                'metode_rekrut_penyanyi' => 'Pilih',
            ],
            [
                'nama' => 'Cadencia Formosa',
                'tipe' => 'Konser',
                'tanggal_mulai'   => Carbon::parse('2025-02-16'),
                'tanggal_selesai' => Carbon::parse('2025-02-16'),
                'jam_mulai'       => Carbon::parse('17:00:00')->format('H:i:s'),
                'jam_selesai'     => Carbon::parse('21:00:00')->format('H:i:s'),
                'lokasi'          => 'Citra Hati Christian School, Surabaya',
                'peran'           => 'Keduanya',
                'panitia_eksternal' => 'Tidak',
                'metode_rekrut_panitia' => 'Pilih',
                'metode_rekrut_penyanyi' => 'Pilih',
            ],
            [
                'nama' => 'Sanguinis Choraliensis!',
                'tipe' => 'Konser',
                'tanggal_mulai'   => Carbon::parse('2025-02-22'),
                'tanggal_selesai' => Carbon::parse('2025-02-22'),
                'jam_mulai'       => Carbon::parse('17:00:00')->format('H:i:s'),
                'jam_selesai'     => Carbon::parse('21:00:00')->format('H:i:s'),
                'lokasi'          => 'Aula Simfonia Jakarta, Jakarta Pusat',
                'peran'           => 'Keduanya',
                'panitia_eksternal' => 'Tidak',
                'metode_rekrut_panitia' => 'Pilih',
                'metode_rekrut_penyanyi' => 'Pilih',
            ],
            [
                'nama' => 'Ritorna Vincitor',
                'tipe' => 'Konser',
                'tanggal_mulai'   => Carbon::parse('2025-02-23'),
                'tanggal_selesai' => Carbon::parse('2025-02-23'),
                'jam_mulai'       => Carbon::parse('17:00:00')->format('H:i:s'),
                'jam_selesai'     => Carbon::parse('21:00:00')->format('H:i:s'),
                'lokasi'          => 'Auditorium Universitas Kristen Petra, Surabaya',
                'peran'           => 'Keduanya',
                'panitia_eksternal' => 'Tidak',
                'metode_rekrut_panitia' => 'Pilih',
                'metode_rekrut_penyanyi' => 'Pilih',
            ],
            [
                'nama' => 'Tracing the Horizon',
                'tipe' => 'Konser',
                'tanggal_mulai'   => Carbon::parse('2025-03-01'),
                'tanggal_selesai' => Carbon::parse('2025-03-01'),
                'jam_mulai'       => Carbon::parse('17:00:00')->format('H:i:s'),
                'jam_selesai'     => Carbon::parse('21:00:00')->format('H:i:s'),
                'lokasi'          => 'Auditorium Universitas Kristen Petra, Surabaya',
                'peran'           => 'Keduanya',
                'panitia_eksternal' => 'Tidak',
                'metode_rekrut_panitia' => 'Pilih',
                'metode_rekrut_penyanyi' => 'Pilih',
            ],
        ]);

        DB::table('collabs')->insert([
            [
                'choirs_id'            => 1,
                'events_id'            => 1,
            ],
            [
                'choirs_id'            => 2,
                'events_id'            => 2,
            ],
            [
                'choirs_id'            => 7,
                'events_id'            => 3,
            ],
            [
                'choirs_id'            => 3,
                'events_id'            => 4,
            ],
            [
                'choirs_id'            => 4,
                'events_id'            => 5,
            ],
        ]);
    }
}
