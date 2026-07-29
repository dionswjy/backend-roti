<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    // API — Return semua produk sebagai JSON (untuk Flutter)
    public function index()
    {
        $produk = Produk::latest()->get();

        // Jika database kosong, seed data default
        if ($produk->isEmpty()) {
            $defaults = [
                ['nama' => 'Blackforest Cake',    'harga' => '85000', 'gambar' => '🎂', 'kategori' => 'Tart',      'rating' => '4.9', 'badge' => 'Best Seller', 'deskripsi' => 'Kue tart coklat legendaris berlapis whipped cream lembut, coklat parut Belgia, dan buah ceri manis.'],
                ['nama' => 'Red Velvet Slice',     'harga' => '35000', 'gambar' => '🍰', 'kategori' => 'Tart',      'rating' => '4.8', 'badge' => 'Populer',     'deskripsi' => 'Potongan kue red velvet lembut dengan cream cheese premium dan taburan kacang renyah.'],
                ['nama' => 'Cheese Cake Soft',     'harga' => '75000', 'gambar' => '🧀', 'kategori' => 'Tart',      'rating' => '4.9', 'badge' => 'Favorit',     'deskripsi' => 'Kue keju Jepang panggang dengan tekstur spons ultra lembut yang lumer di mulut.'],
                ['nama' => 'Roti Gandum Sehat',    'harga' => '25000', 'gambar' => '🍞', 'kategori' => 'Roti Manis','rating' => '4.7', 'badge' => 'Nutrisi',     'deskripsi' => 'Roti gandum murni kaya serat alami dan nutrisi tinggi, tanpa pengawet sintesis.'],
                ['nama' => 'Croissant Keju',       'harga' => '30000', 'gambar' => '🥐', 'kategori' => 'Pastry',    'rating' => '4.8', 'badge' => 'Fresh',       'deskripsi' => 'Croissant renyah khas Prancis dengan lapisan butter gurih dan keju melimpah.'],
                ['nama' => 'Donut Glaze Special',  'harga' => '15000', 'gambar' => '🍩', 'kategori' => 'Donat',     'rating' => '4.6', 'badge' => 'Manis',       'deskripsi' => 'Donat empuk dengan siraman gula cair spesial yang dipanggang segar tiap pagi.'],
                ['nama' => 'Strawberry Cupcake',   'harga' => '20000', 'gambar' => '🧁', 'kategori' => 'Cupcake',   'rating' => '4.8', 'badge' => 'Imut',        'deskripsi' => 'Cupcake manis dengan hiasan cream stroberi dan potongan stroberi asli di atasnya.'],
                ['nama' => 'Chocolate Brownie',    'harga' => '40000', 'gambar' => '🍫', 'kategori' => 'Dessert',   'rating' => '4.9', 'badge' => 'Fudge',       'deskripsi' => 'Fudge brownies coklat pekat bertabur choco-chip manis dan kacang almond pilihan.'],
            ];

            foreach ($defaults as $d) {
                Produk::create($d);
            }

            $produk = Produk::latest()->get();
        }

        return response()->json($produk, 200);
    }

    // Web Admin — Tambah Produk Baru
    public function storeWeb(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'harga' => 'required',
        ]);

        Produk::create([
            'nama'      => $request->nama,
            'harga'     => (string) $request->harga,
            'gambar'    => $request->gambar    ?? '🍞',
            'kategori'  => $request->kategori  ?? 'Lainnya',
            'rating'    => $request->rating    ?? '4.5',
            'badge'     => $request->badge     ?? '',
            'deskripsi' => $request->deskripsi ?? '',
        ]);

        return redirect('/admin?tab=produk')->with('success', 'Produk "' . $request->nama . '" berhasil ditambahkan!');
    }

    // Web Admin — Update Produk
    public function updateWeb($id, Request $request)
    {
        $produk = Produk::findOrFail($id);
        $produk->update($request->only(['nama', 'harga', 'gambar', 'kategori', 'rating', 'badge', 'deskripsi']));

        return redirect('/admin?tab=produk')->with('success', 'Produk "' . $produk->nama . '" berhasil diperbarui!');
    }

    // Web Admin — Hapus Produk
    public function destroyWeb($id)
    {
        $produk = Produk::findOrFail($id);
        $nama   = $produk->nama;
        $produk->delete();

        return redirect('/admin?tab=produk')->with('success', 'Produk "' . $nama . '" berhasil dihapus!');
    }
}
