<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon; // Pastikan ini diimpor

class WasteExchangeSeeder extends Seeder
{
    // Map Poin per Kategori Ukuran
    protected $poinMap = [
        'Large' => 50,
        'Medium' => 30,
        'Small' => 15
    ];

    public function run(): void
    {
        // PENTING: Jika Anda belum menambahkan kolom 'foto_bukti' di transaksisampah
        // dan 'quantity' di detailsampah, SCRIPT INI AKAN GAGAL.

        // Drop Points (Data disesuaikan dengan skema drop_point yang singular)
        $dropPoints = [
            [
                'nama_lokasi' => 'Downtown Beauty Hub',
                'koordinat' => '-6.2088, 106.8456',
                'kapasitas_sampah' => 750.00,
                'alamat' => '123 Main Street, City Center',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_lokasi' => 'Green Market Plaza',
                'koordinat' => '-6.2185, 106.8371',
                'kapasitas_sampah' => 900.50,
                'alamat' => '456 Oak Avenue, Westside',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_lokasi' => 'Eco Community Center',
                'koordinat' => '-6.1944, 106.8229',
                'kapasitas_sampah' => 600.00,
                'alamat' => '789 Pine Road, Northside',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'nama_lokasi' => 'Sustainable Beauty Station',
                'koordinat' => '-6.2297, 106.8155',
                'kapasitas_sampah' => 500.50,
                'alamat' => '321 Elm Street, Downtown',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('drop_points')->insert($dropPoints);

        // --- Data Transaksi Dummy ---

        // Dummy Items for each transaction based on kode_transaksi
        $transactionItems = [
            '#RG-12345' => [
                ['nama_produk' => 'Large Bottle', 'packaging_category' => 'Plastic Bottle', 'size_category' => 'Large', 'quantity' => 5],
                ['nama_produk' => 'Small Jar', 'packaging_category' => 'Glass Jar', 'size_category' => 'Small', 'quantity' => 2],
            ],
            '#RG-12346' => [
                ['nama_produk' => 'Medium Tube', 'packaging_category' => 'Metal Tube', 'size_category' => 'Medium', 'quantity' => 3],
            ],
            '#RG-12343' => [
                ['nama_produk' => 'Small Bottles', 'packaging_category' => 'Plastic Bottle', 'size_category' => 'Small', 'quantity' => 8],
                ['nama_produk' => 'Medium Jars', 'packaging_category' => 'Glass Jar', 'size_category' => 'Medium', 'quantity' => 3],
            ]
        ];

        // Dummy Transaksi untuk user_id = 3
        $transactions = [
            [
                'kode_transaksi' => '#RG-12345',
                'id_user' => 3,
                'id_drop_point' => 2,
                'foto_bukti' => 'uploads/waste_proof/dummy1.jpg',
                'status' => 'Menunggu', // Sesuai default migrasi
                'tanggal_transaksi' => now()->subDays(1),
            ],
            [
                'kode_transaksi' => '#RG-12346',
                'id_user' => 3,
                'id_drop_point' => 1,
                'foto_bukti' => 'uploads/waste_proof/dummy2.jpg',
                'status' => 'Diproses', // Menggunakan 'Diproses' atau status lain di Enum Anda
                'tanggal_transaksi' => now()->subDays(5),
            ],
            [
                'kode_transaksi' => '#RG-12343',
                'id_user' => 3,
                'id_drop_point' => 3,
                'foto_bukti' => 'uploads/waste_proof/dummy3.jpg',
                'status' => 'Selesai', // Menggunakan 'Selesai' atau status lain di Enum Anda
                'tanggal_transaksi' => now()->subDays(10),
            ]
        ];

        // Memasukkan Transaksi dan Detail
        foreach ($transactions as $transaction) {
            $totalPoin = 0;
            $items = $transactionItems[$transaction['kode_transaksi']] ?? [];
            
            // Hitung Total Poin Estimasi
            foreach ($items as $item) {
                $poinPerItem = $this->poinMap[$item['size_category']] ?? 0;
                $totalPoin += ($poinPerItem * $item['quantity']);
            }

            // Data untuk tabel transaksisampah
            $transaksiData = [
                'id_user' => $transaction['id_user'],
                'id_drop_point' => $transaction['id_drop_point'],
                'tgl_tSampah' => $transaction['tanggal_transaksi'],
                'foto_bukti' => $transaction['foto_bukti'], // HARUS ADA DI MIGRATION
                'status' => $transaction['status'],
                'total_poin' => $totalPoin, // Estimasi total poin
                'created_at' => $transaction['tanggal_transaksi'],
                'updated_at' => $transaction['tanggal_transaksi']
            ];

            // 1. Masukkan Transaksi dan dapatkan ID-nya
            $id = DB::table('transaksisampah')->insertGetId($transaksiData);

            // 2. Masukkan Detail Transaksi (detailsampah)
            $detailToInsert = [];
            foreach ($items as $item) {
                $poinPerItem = $this->poinMap[$item['size_category']] ?? 0;

                $detailToInsert[] = [
                    'id_tSampah' => $id,
                    'jenis_sampah' => $item['packaging_category'], // Packaging Category -> jenis_sampah
                    'ukuran_sampah' => $item['size_category'],     // Size Category -> ukuran_sampah
                    'poin_per_sampah' => $poinPerItem,             // Poin per item
                    'quantity' => $item['quantity'],               // HARUS ADA DI MIGRATION
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            DB::table('detailsampah')->insert($detailToInsert);

            // 3. Masukkan Riwayat Transaksi Awal (riwayatsampah)
            DB::table('riwayatsampah')->insert([
                'id_tSampah' => $id,
                'status' => $transaction['status'],
                'tanggal_update' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            // Jika status sudah selesai, tambahkan riwayat status 'Selesai' (opsional)
            if ($transaction['status'] == 'Selesai') {
                 DB::table('riwayatsampah')->insert([
                    'id_tSampah' => $id,
                    'status' => 'Selesai',
                    'tanggal_update' => Carbon::now()->subDays(6), // Contoh waktu
                    'created_at' => Carbon::now()->subDays(6),
                    'updated_at' => Carbon::now()->subDays(6)
                ]);
            }
        }
    }
}