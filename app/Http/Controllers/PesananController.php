<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Fungsi 1: Ambil daftar pesanan (API GET)
    public function indexApi(Request $request)
    {
        $nama = $request->query('nama_pelanggan');
        $query = Pesanan::latest();

        if (!empty($nama) && $nama !== 'User BNSP' && $nama !== 'Tamu BNSP') {
            $query->where('nama_pelanggan', 'LIKE', '%' . $nama . '%');
        }

        $pesanans = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $pesanans
        ], 200);
    }

    // Fungsi 2: Simpan pesanan baru (API POST)
    public function storeApi(Request $request)
    {
        $namaPelanggan = $request->input('nama_pelanggan') ?? $request->input('nama') ?? 'Pelanggan Express';
        $pesananDetail = $request->input('pesanan') ?? 'Roti / Kue';
        $totalHarga = $request->input('total_harga') ?? 'Rp 0';
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        if (empty($latitude)) $latitude = '-6.2088';
        if (empty($longitude)) $longitude = '106.8456';

        $metodePembayaran = $request->input('metode_pembayaran') ?? 'Tunai (COD)';
        $tanggal = $request->input('tanggal') ?? date('Y-m-d H:i');
        $status = $request->input('status') ?? 'Diproses Admin';

        $pesanan = Pesanan::create([
            'nama_pelanggan' => $namaPelanggan,
            'pesanan' => $pesananDetail,
            'total_harga' => (string)$totalHarga,
            'latitude' => (string)$latitude,
            'longitude' => (string)$longitude,
            'metode_pembayaran' => $metodePembayaran,
            'tanggal' => $tanggal,
            'status' => $status,
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil masuk ke server!', 
            'data' => $pesanan
        ], 201);
    }

    // Fungsi 2: Untuk Web Admin (menampilkan data & management ke browser)
    public function index()
    {
        try { $pesanans = Pesanan::latest()->get(); } catch (\Throwable $e) { $pesanans = collect(); }
        try { $users    = User::latest()->get(); } catch (\Throwable $e) { $users = collect(); }
        try { $produks  = Produk::latest()->get(); } catch (\Throwable $e) { $produks = collect(); }
        try { $banners  = Banner::latest()->get(); } catch (\Throwable $e) { $banners = collect(); }

        $totalPesanan = $pesanans->count();
        $totalOmset   = $pesanans->sum(function ($p) {
            $num = preg_replace('/[^0-9]/', '', (string) $p->total_harga);
            return (int) $num;
        });
        $pesananHariIni = $pesanans->filter(function ($p) {
            return $p->created_at && $p->created_at->isToday();
        })->count();
        $totalProduk = $produks->count();

        return view('admin', compact('pesanans', 'users', 'produks', 'banners', 'totalPesanan', 'totalOmset', 'pesananHariIni', 'totalProduk'));
    }

    // Tambah Pesanan Baru dari Admin Web
    public function storeWeb(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'pesanan'        => 'required|string',
            'total_harga'    => 'required',
        ]);

        Pesanan::create([
            'nama_pelanggan'    => $request->nama_pelanggan,
            'pesanan'           => $request->pesanan,
            'total_harga'       => (string) $request->total_harga,
            'latitude'          => $request->latitude  ?? '-6.2088',
            'longitude'         => $request->longitude ?? '106.8456',
            'metode_pembayaran' => $request->metode_pembayaran ?? 'Tunai (COD)',
            'tanggal'           => now()->format('Y-m-d H:i'),
            'status'            => $request->status ?? 'Diproses Admin',
        ]);

        return redirect('/admin?tab=pesanan')->with('success', 'Pesanan baru untuk "' . $request->nama_pelanggan . '" berhasil ditambahkan!');
    }

    // Edit Pesanan Web
    public function updateWeb($id, Request $request)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->update($request->only(['nama_pelanggan', 'pesanan', 'total_harga', 'status']));

        return redirect('/admin')->with('success', 'Data pesanan #' . $id . ' berhasil diperbarui!');
    }

    // Hapus Pesanan Web
    public function destroyWeb($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return redirect('/admin')->with('success', 'Data pesanan #' . $id . ' berhasil dihapus!');
    }
}