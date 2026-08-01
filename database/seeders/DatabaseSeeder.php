<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        User::firstOrCreate(
            ['email' => 'admin@tokoroti.com'],
            [
                'name' => 'Admin Toko Roti',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@tokoroti.com'],
            [
                'name' => 'User BNSP',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Seed Produk
        $produks = [
            ['nama' => 'Blackforest Cake',    'harga' => '85000', 'gambar' => '🎂', 'kategori' => 'Tart',      'rating' => '4.9', 'badge' => 'Best Seller', 'deskripsi' => 'Kue tart coklat legendaris berlapis whipped cream lembut, coklat parut Belgia, dan buah ceri manis.'],
            ['nama' => 'Red Velvet Slice',     'harga' => '35000', 'gambar' => '🍰', 'kategori' => 'Tart',      'rating' => '4.8', 'badge' => 'Populer',     'deskripsi' => 'Potongan kue red velvet lembut dengan cream cheese premium dan taburan kacang renyah.'],
            ['nama' => 'Cheese Cake Soft',     'harga' => '75000', 'gambar' => '🧀', 'kategori' => 'Tart',      'rating' => '4.9', 'badge' => 'Favorit',     'deskripsi' => 'Kue keju Jepang panggang dengan tekstur spons ultra lembut yang lumer di mulut.'],
            ['nama' => 'Roti Gandum Sehat',    'harga' => '25000', 'gambar' => '🍞', 'kategori' => 'Roti Manis','rating' => '4.7', 'badge' => 'Nutrisi',     'deskripsi' => 'Roti gandum murni kaya serat alami dan nutrisi tinggi, tanpa pengawet sintesis.'],
            ['nama' => 'Croissant Keju',       'harga' => '30000', 'gambar' => '🥐', 'kategori' => 'Pastry',    'rating' => '4.8', 'badge' => 'Fresh',       'deskripsi' => 'Croissant renyah khas Prancis dengan lapisan butter gurih dan keju melimpah.'],
            ['nama' => 'Donut Glaze Special',  'harga' => '15000', 'gambar' => '🍩', 'kategori' => 'Donat',     'rating' => '4.6', 'badge' => 'Manis',       'deskripsi' => 'Donat empuk dengan siraman gula cair spesial yang dipanggang segar tiap pagi.'],
            ['nama' => 'Strawberry Cupcake',   'harga' => '20000', 'gambar' => '🧁', 'kategori' => 'Cupcake',   'rating' => '4.8', 'badge' => 'Imut',        'deskripsi' => 'Cupcake manis dengan hiasan cream stroberi dan potongan stroberi asli di atasnya.'],
            ['nama' => 'Chocolate Brownie',    'harga' => '40000', 'gambar' => '🍫', 'kategori' => 'Dessert',   'rating' => '4.9', 'badge' => 'Fudge',       'deskripsi' => 'Fudge brownies coklat pekat bertabur choco-chip manis dan kacang almond pilihan.'],
        ];

        foreach ($produks as $p) {
            Produk::firstOrCreate(['nama' => $p['nama']], $p);
        }

        // 3. Seed Banner
        $banners = [
            [
                'badge' => 'PROMO SPESIAL 30%',
                'judul' => 'Kue Lezat Untuk Momen Spesial Anda',
                'deskripsi' => 'Pesan sekarang & antar presisi ke rumah via GPS!',
                'gambar' => '🎂',
            ],
            [
                'badge' => 'DISKON PASTRY',
                'judul' => 'Croissant Butter Fresh Setiap Pagi',
                'deskripsi' => 'Nikmati croissant renyah khas Prancis dengan butter gurih.',
                'gambar' => '🥐',
            ]
        ];

        foreach ($banners as $b) {
            Banner::firstOrCreate(['judul' => $b['judul']], $b);
        }

        // 4. Seed Sampel Pesanan
        $pesanans = [
            [
                'nama_pelanggan' => 'User BNSP',
                'pesanan' => '1x Blackforest Cake, 2x Croissant Keju',
                'total_harga' => '145000',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'metode_pembayaran' => 'Tunai (COD)',
                'tanggal' => date('Y-m-d H:i'),
                'status' => 'Diproses Admin',
            ],
            [
                'nama_pelanggan' => 'Budi Santoso',
                'pesanan' => '2x Donut Glaze Special',
                'total_harga' => '30000',
                'latitude' => '-6.1754',
                'longitude' => '106.8272',
                'metode_pembayaran' => 'Transfer Bank',
                'tanggal' => date('Y-m-d H:i'),
                'status' => 'Selesai',
            ]
        ];

        foreach ($pesanans as $ps) {
            Pesanan::firstOrCreate(['nama_pelanggan' => $ps['nama_pelanggan'], 'pesanan' => $ps['pesanan']], $ps);
        }
    }
}

