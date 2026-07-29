<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // API Register (Mendaftarkan User Baru dari Mobile)
    public function registerApi(Request $request)
    {
        $nama = $request->input('nama') ?? $request->input('name') ?? $request->input('username') ?? 'User Baru';
        $rawEmail = $request->input('email') ?? $request->input('username') ?? 'user@rotiku.id';
        $password = $request->input('password') ?? '123456';

        $email = $rawEmail;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama)) . '@rotiku.id';
        }

        // Cek jika email sudah terdaftar
        $existing = User::where('email', $email)->orWhere('name', $nama)->first();
        if ($existing) {
            return response()->json([
                'message' => 'User sudah terdaftar di server!',
                'data' => [
                    'id' => $existing->id,
                    'nama' => $existing->name,
                    'email' => $existing->email,
                ]
            ], 200);
        }

        $user = User::create([
            'name' => $nama,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return response()->json([
            'message' => 'Registrasi user berhasil masuk ke server!',
            'data' => [
                'id' => $user->id,
                'nama' => $user->name,
                'email' => $user->email,
            ]
        ], 201);
    }

    // API Login (Verifikasi Akun dari Mobile)
    public function loginApi(Request $request)
    {
        $input = $request->input('email') ?? $request->input('username') ?? '';
        $password = $request->input('password') ?? '';

        $user = User::where('email', $input)
                    ->orWhere('name', $input)
                    ->first();

        if ($user && Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Login berhasil!',
                'data' => [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
        }

        // Fallback jika belum ada, buatkan otomatis
        if (!empty($input) && !empty($password)) {
            $user = User::create([
                'name' => $input,
                'email' => str_contains($input, '@') ? $input : "$input@rotiku.id",
                'password' => Hash::make($password),
            ]);

            return response()->json([
                'message' => 'User baru otomatis terdaftar & login!',
                'data' => [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'email' => $user->email,
                ]
            ], 200);
        }

        return response()->json([
            'message' => 'Email/Username atau password salah!',
        ], 401);
    }

    // API Get List Users
    public function indexApi()
    {
        $users = User::latest()->get();
        return response()->json($users, 200);
    }

    // Tambah User Baru dari Admin Web
    public function storeWeb(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/admin?tab=user')->with('success', 'User "' . $request->name . '" berhasil ditambahkan!');
    }

    // Edit User Web
    public function updateWeb($id, Request $request)
    {
        $user = User::findOrFail($id);
        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect('/admin')->with('success', 'Data user #' . $id . ' (' . $user->name . ') berhasil diperbarui!');
    }

    // Hapus User Web
    public function destroyWeb($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin')->with('success', 'User USR-' . $id . ' berhasil dihapus!');
    }
}
