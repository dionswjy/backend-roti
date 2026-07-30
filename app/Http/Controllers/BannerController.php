<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    // API GET: Ambil daftar promo banner untuk Flutter
    public function indexApi()
    {
        $banners = Banner::latest()->get();
        if ($banners->isEmpty()) {
            $banners = [
                [
                    'id' => 1,
                    'badge' => 'PROMO SPESIAL 30%',
                    'judul' => 'Kue Lezat Untuk Momen Spesial Anda',
                    'deskripsi' => 'Pesan sekarang & antar presisi ke rumah via GPS!',
                    'gambar' => '🎂',
                ]
            ];
        }
        return response()->json([
            'status' => 'success',
            'data' => $banners
        ], 200);
    }

    // Web POST: Tambah Promo Banner Baru dari Admin Web
    public function storeWeb(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
        ]);

        Banner::create([
            'badge' => $request->input('badge') ?? 'PROMO SPESIAL',
            'judul' => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi') ?? 'Pesan sekarang & antar presisi ke rumah!',
            'gambar' => $request->input('gambar') ?? '🥐',
        ]);

        return redirect('/admin?tab=banner')->with('success', 'Promo Banner baru "' . $request->judul . '" berhasil ditambahkan!');
    }

    // Web PUT: Edit Promo Banner dari Admin Web
    public function updateWeb($id, Request $request)
    {
        $banner = Banner::findOrFail($id);
        $banner->update($request->only(['badge', 'judul', 'deskripsi', 'gambar']));

        return redirect('/admin?tab=banner')->with('success', 'Promo Banner #' . $id . ' berhasil diperbarui!');
    }

    // Web DELETE: Hapus Promo Banner dari Admin Web
    public function destroyWeb($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect('/admin?tab=banner')->with('success', 'Promo Banner #' . $id . ' berhasil dihapus!');
    }
}
