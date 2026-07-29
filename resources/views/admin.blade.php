<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel — Rotiku Express 🥐</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── RESET & BASE ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --amber:       #F59E0B;
            --amber-dark:  #B45309;
            --amber-light: #FEF3C7;
            --sidebar-bg:  #1C1917;
            --sidebar-w:   240px;
            --bg:          #F8FAFC;
            --card:        #FFFFFF;
            --border:      #E2E8F0;
            --text:        #0F172A;
            --muted:       #64748B;
            --red:         #EF4444;
            --blue:        #3B82F6;
            --green:       #10B981;
            --purple:      #8B5CF6;
            --transition:  .2s cubic-bezier(.4,0,.2,1);
        }
        html, body { height: 100%; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); display: flex; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w); min-height: 100vh; background: var(--sidebar-bg);
            display: flex; flex-direction: column; position: fixed; top: 0; left: 0; z-index: 50;
            padding: 0 0 24px 0; overflow-y: auto;
        }
        .sidebar-logo {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: 8px;
        }
        .sidebar-logo h2 { color: var(--amber); font-size: 18px; font-weight: 800; letter-spacing: -.3px; }
        .sidebar-logo p  { color: #A8A29E; font-size: 11px; margin-top: 2px; }
        .nav-label { color: #78716C; font-size: 10px; font-weight: 600; text-transform: uppercase;
                     letter-spacing: 1px; padding: 16px 20px 6px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; color: #A8A29E; font-size: 13.5px; font-weight: 500;
            cursor: pointer; border-radius: 0; transition: all var(--transition); text-decoration: none;
            border-left: 3px solid transparent;
        }
        .nav-item .icon { font-size: 16px; width: 20px; text-align: center; }
        .nav-item:hover { color: #FFF; background: rgba(255,255,255,.05); }
        .nav-item.active { color: var(--amber); background: rgba(245,158,11,.1); border-left-color: var(--amber); }
        .sidebar-footer { margin-top: auto; padding: 16px 20px 0; }
        .sidebar-footer p { color: #57534E; font-size: 11px; line-height: 1.6; }

        /* ── MAIN ── */
        .main { margin-left: var(--sidebar-w); flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--card); border-bottom: 1px solid var(--border);
            padding: 16px 28px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-size: 18px; font-weight: 700; color: var(--text); }
        .topbar-title span { color: var(--amber); }
        .topbar-meta { color: var(--muted); font-size: 12.5px; }
        .badge-live {
            display: inline-flex; align-items: center; gap: 6px;
            background: #ECFDF5; color: #065F46; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;
        }
        .dot-live { width: 6px; height: 6px; background: var(--green); border-radius: 50%; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* ── CONTENT ── */
        .content { padding: 28px; flex: 1; }

        /* ── SECTION PAGES ── */
        .page { display: none; }
        .page.active { display: block; }

        /* ── ALERT ── */
        .alert-success {
            background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0;
            padding: 12px 18px; border-radius: 10px; margin-bottom: 24px;
            font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;
            animation: slideIn .3s ease;
        }
        @keyframes slideIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }

        /* ── STAT CARDS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 28px; }
        .stat-card {
            background: var(--card); border-radius: 14px; padding: 20px;
            border: 1px solid var(--border); position: relative; overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        .stat-card::before {
            content: ''; position: absolute; top: 0; right: 0; width: 80px; height: 80px;
            border-radius: 0 14px 0 80px; opacity: .08;
        }
        .stat-card.amber::before  { background: var(--amber); }
        .stat-card.green::before  { background: var(--green); }
        .stat-card.blue::before   { background: var(--blue); }
        .stat-card.purple::before { background: var(--purple); }
        .stat-icon { font-size: 22px; margin-bottom: 12px; }
        .stat-lbl  { font-size: 11.5px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
        .stat-val  { font-size: 26px; font-weight: 800; color: var(--text); margin-top: 4px; line-height: 1; }
        .stat-sub  { font-size: 11.5px; color: var(--muted); margin-top: 6px; }

        /* ── CARD ── */
        .card {
            background: var(--card); border-radius: 14px; border: 1px solid var(--border);
            overflow: hidden; margin-bottom: 24px;
        }
        .card-head {
            padding: 18px 22px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-head-title { font-size: 15px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
        .card-body { padding: 0; }

        /* ── TABLE ── */
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #F8FAFC; padding: 12px 18px; font-size: 11.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .5px; color: var(--muted);
            border-bottom: 1px solid var(--border); text-align: left; white-space: nowrap;
        }
        tbody td {
            padding: 13px 18px; font-size: 13.5px; border-bottom: 1px solid #F1F5F9;
            vertical-align: middle; color: var(--text);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background var(--transition); }
        tbody tr:hover { background: #FFFBEB; }
        .empty-row { text-align: center; color: var(--muted); padding: 40px !important; font-size: 13px; }

        /* ── BADGE ── */
        .badge {
            display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 700; letter-spacing: .2px;
        }
        .badge-gps    { background: var(--amber-light); color: var(--amber-dark); }
        .badge-status { background: #EFF6FF; color: #1D4ED8; }
        .badge-cat    { background: #F3E8FF; color: #6D28D9; }
        .badge-done   { background: #ECFDF5; color: #065F46; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 13px; border-radius: 8px; font-size: 12px; font-weight: 600;
            border: none; cursor: pointer; text-decoration: none; transition: all var(--transition); white-space: nowrap;
        }
        .btn-primary  { background: var(--amber);  color: white; }
        .btn-primary:hover { background: var(--amber-dark); transform: translateY(-1px); }
        .btn-edit     { background: #EFF6FF; color: var(--blue); border: 1px solid #BFDBFE; }
        .btn-edit:hover { background: var(--blue); color: white; }
        .btn-delete   { background: #FEF2F2; color: var(--red); border: 1px solid #FECACA; }
        .btn-delete:hover { background: var(--red); color: white; }
        .btn-maps     { background: var(--amber); color: white; font-size: 11px; padding: 4px 9px; }
        .btn-maps:hover { background: var(--amber-dark); }
        .btn-cancel   { background: #F1F5F9; color: var(--muted); }
        .btn-cancel:hover { background: var(--border); }
        .btn-sm { padding: 5px 10px; font-size: 11.5px; }

        /* ── MODAL ── */
        .overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
            z-index: 200; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .overlay.active { display: flex; animation: fadeIn .2s ease; }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }
        .modal-box {
            background: var(--card); border-radius: 18px; padding: 28px; width: 100%;
            max-width: 480px; box-shadow: 0 20px 60px rgba(0,0,0,.2);
            animation: scaleIn .25s cubic-bezier(.34,1.56,.64,1);
            max-height: 90vh; overflow-y: auto;
        }
        @keyframes scaleIn { from{opacity:0;transform:scale(.94)} to{opacity:1;transform:scale(1)} }
        .modal-title { font-size: 17px; font-weight: 800; color: var(--text); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .form-group { margin-bottom: 14px; }
        .form-group label {
            display: block; font-size: 12px; font-weight: 700; color: var(--muted);
            text-transform: uppercase; letter-spacing: .3px; margin-bottom: 6px;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 13px; border: 1.5px solid var(--border);
            border-radius: 9px; font-size: 13.5px; font-family: 'Inter', sans-serif;
            color: var(--text); transition: border-color var(--transition), box-shadow var(--transition);
            background: var(--card);
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; border-color: var(--amber); box-shadow: 0 0 0 3px rgba(245,158,11,.15);
        }
        .form-group textarea { resize: vertical; min-height: 75px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; padding-top: 16px; border-top: 1px solid var(--border); }

        /* ── AVATAR / EMOJI ── */
        .produk-emoji { font-size: 26px; text-align: center; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%; background: linear-gradient(135deg, var(--amber), var(--amber-dark));
            color: white; font-weight: 700; font-size: 13px; display: flex; align-items: center; justify-content: center;
        }
        .price { font-weight: 700; color: var(--amber-dark); }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
    </style>
</head>
<body>

<!-- ═══════════════ SIDEBAR ═══════════════ -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>🥐 Rotiku Express</h2>
        <p>Admin Dashboard Panel</p>
    </div>

    <span class="nav-label">Menu</span>
    <a class="nav-item active" onclick="switchPage('dashboard')" id="nav-dashboard">
        <span class="icon">📊</span> Dashboard
    </a>
    <a class="nav-item" onclick="switchPage('pesanan')" id="nav-pesanan">
        <span class="icon">📦</span> Pesanan
    </a>
    <a class="nav-item" onclick="switchPage('produk')" id="nav-produk">
        <span class="icon">🍞</span> Produk
    </a>
    <a class="nav-item" onclick="switchPage('user')" id="nav-user">
        <span class="icon">👥</span> User
    </a>

    <div class="sidebar-footer">
        <p>Rotiku Express &copy; {{ date('Y') }}<br>Backend v2.0 — Laravel</p>
    </div>
</aside>

<!-- ═══════════════ MAIN ═══════════════ -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div>
            <div class="topbar-title">Admin <span>Rotiku Express</span></div>
            <div class="topbar-meta">{{ now()->format('l, d F Y') }}</div>
        </div>
        <div class="badge-live"><span class="dot-live"></span> Server Online</div>
    </div>

    <div class="content">

        <!-- ALERT SUCCESS -->
        @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
        @endif

        <!-- ══════ PAGE: DASHBOARD ══════ -->
        <div class="page active" id="page-dashboard">
            <div class="stats-grid">
                <div class="stat-card amber">
                    <div class="stat-icon">📦</div>
                    <div class="stat-lbl">Total Pesanan</div>
                    <div class="stat-val">{{ $totalPesanan ?? 0 }}</div>
                    <div class="stat-sub">{{ $pesananHariIni ?? 0 }} pesanan hari ini</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">💰</div>
                    <div class="stat-lbl">Estimasi Omset</div>
                    <div class="stat-val" style="font-size:18px">Rp {{ number_format($totalOmset ?? 0, 0, ',', '.') }}</div>
                    <div class="stat-sub">Dari semua pesanan</div>
                </div>
                <div class="stat-card blue">
                    <div class="stat-icon">👥</div>
                    <div class="stat-lbl">User Terdaftar</div>
                    <div class="stat-val">{{ count($users ?? []) }}</div>
                    <div class="stat-sub">Pelanggan aktif</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon">🍞</div>
                    <div class="stat-lbl">Total Produk</div>
                    <div class="stat-val">{{ $totalProduk ?? 0 }}</div>
                    <div class="stat-sub">Di katalog menu</div>
                </div>
            </div>

            <!-- Pesanan Terbaru (ringkasan 5) -->
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">🕒 Pesanan Terbaru</div>
                    <button class="btn btn-edit btn-sm" onclick="switchPage('pesanan')">Lihat Semua →</button>
                </div>
                <div class="card-body">
                    <table>
                        <thead><tr>
                            <th>#</th><th>Pelanggan</th><th>Detail</th><th>Total</th><th>Status</th>
                        </tr></thead>
                        <tbody>
                        @forelse(($pesanans ?? collect())->take(5) as $p)
                        <tr>
                            <td><strong>#{{ $p->id }}</strong></td>
                            <td>{{ $p->nama_pelanggan }}</td>
                            <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p->pesanan }}</td>
                            <td class="price">{{ $p->total_harga }}</td>
                            <td><span class="badge badge-status">{{ $p->status ?? 'Diproses' }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="empty-row">📭 Belum ada pesanan</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══════ PAGE: PESANAN ══════ -->
        <div class="page" id="page-pesanan">
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">📦 Data Pesanan Masuk</div>
                    <button class="btn btn-primary" onclick="openModal('modalTambahPesanan')">＋ Tambah Pesanan</button>
                </div>
                <div class="card-body">
                    <table>
                        <thead><tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Detail Pesanan</th>
                            <th>Total Harga</th>
                            <th>Koordinat GPS</th>
                            <th>Status</th>
                            <th style="text-align:center">Aksi</th>
                        </tr></thead>
                        <tbody>
                        @forelse($pesanans as $p)
                        <tr>
                            <td><strong>#{{ $p->id }}</strong></td>
                            <td>
                                <div style="font-weight:600">{{ $p->nama_pelanggan }}</div>
                                <div style="font-size:11.5px;color:var(--muted)">{{ $p->metode_pembayaran ?? '-' }}</div>
                            </td>
                            <td style="max-width:200px">
                                <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:190px">{{ $p->pesanan }}</div>
                                @if($p->tanggal)
                                <div style="font-size:11px;color:var(--muted)">📅 {{ $p->tanggal }}</div>
                                @endif
                            </td>
                            <td class="price">{{ $p->total_harga }}</td>
                            <td>
                                <span class="badge badge-gps">📍 GPS</span><br>
                                <small style="color:var(--muted);font-size:11px">{{ $p->latitude }}, {{ $p->longitude }}</small><br>
                                <a href="https://maps.google.com/?q={{$p->latitude}},{{$p->longitude}}" target="_blank" class="btn btn-maps btn-sm" style="margin-top:5px">🗺 Maps</a>
                            </td>
                            <td><span class="badge badge-status">{{ $p->status ?? 'Diproses' }}</span></td>
                            <td style="text-align:center;white-space:nowrap">
                                <button class="btn btn-edit btn-sm" onclick="openEditPesanan({{$p->id}},'{{addslashes($p->nama_pelanggan)}}','{{addslashes($p->pesanan)}}','{{addslashes($p->total_harga)}}','{{addslashes($p->status ?? '')}}')">✏️ Edit</button>
                                <form action="/admin/pesanan/{{$p->id}}/delete" method="POST" style="display:inline" onsubmit="return confirm('Hapus pesanan #{{$p->id}}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-delete btn-sm">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="empty-row">📭 Belum ada pesanan masuk</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══════ PAGE: PRODUK ══════ -->
        <div class="page" id="page-produk">
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">🍞 Data Produk</div>
                    <button class="btn btn-primary" onclick="openModal('modalTambahProduk')">＋ Tambah Produk</button>
                </div>
                <div class="card-body">
                    <table>
                        <thead><tr>
                            <th>ID</th>
                            <th>Emoji</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Rating</th>
                            <th>Badge</th>
                            <th style="text-align:center">Aksi</th>
                        </tr></thead>
                        <tbody>
                        @forelse($produks as $pr)
                        <tr>
                            <td><strong>#{{ $pr->id }}</strong></td>
                            <td class="produk-emoji">{{ $pr->gambar }}</td>
                            <td>
                                <div style="font-weight:600">{{ $pr->nama }}</div>
                                <div style="font-size:11.5px;color:var(--muted);max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $pr->deskripsi }}</div>
                            </td>
                            <td><span class="badge badge-cat">{{ $pr->kategori }}</span></td>
                            <td class="price">Rp {{ number_format((int)$pr->harga, 0, ',', '.') }}</td>
                            <td>⭐ {{ $pr->rating }}</td>
                            <td><span class="badge badge-done">{{ $pr->badge }}</span></td>
                            <td style="text-align:center;white-space:nowrap">
                                <button class="btn btn-edit btn-sm" onclick="openEditProduk({{$pr->id}},'{{addslashes($pr->nama)}}','{{addslashes($pr->harga)}}','{{addslashes($pr->gambar)}}','{{addslashes($pr->kategori ?? '')}}','{{addslashes($pr->rating ?? '')}}','{{addslashes($pr->badge ?? '')}}','{{addslashes($pr->deskripsi ?? '')}}')">✏️ Edit</button>
                                <form action="/admin/produk/{{$pr->id}}/delete" method="POST" style="display:inline" onsubmit="return confirm('Hapus produk {{addslashes($pr->nama)}}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-delete btn-sm">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="empty-row">📭 Belum ada produk, klik Tambah Produk</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ══════ PAGE: USER ══════ -->
        <div class="page" id="page-user">
            <div class="card">
                <div class="card-head">
                    <div class="card-head-title">👥 Data User Terdaftar</div>
                    <button class="btn btn-primary" onclick="openModal('modalTambahUser')">＋ Tambah User</button>
                </div>
                <div class="card-body">
                    <table>
                        <thead><tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Terdaftar</th>
                            <th style="text-align:center">Aksi</th>
                        </tr></thead>
                        <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td><strong>USR-{{ $u->id }}</strong></td>
                            <td><div class="user-avatar">{{ strtoupper(substr($u->name,0,1)) }}</div></td>
                            <td style="font-weight:600">{{ $u->name }}</td>
                            <td style="color:var(--muted)">{{ $u->email }}</td>
                            <td style="font-size:12px;color:var(--muted)">{{ $u->created_at ? $u->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td style="text-align:center;white-space:nowrap">
                                <button class="btn btn-edit btn-sm" onclick="openEditUser({{$u->id}},'{{addslashes($u->name)}}','{{addslashes($u->email)}}')">✏️ Edit</button>
                                <form action="/admin/user/{{$u->id}}/delete" method="POST" style="display:inline" onsubmit="return confirm('Hapus user {{addslashes($u->name)}}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-delete btn-sm">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-row">📭 Belum ada user terdaftar</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /content -->
</div><!-- /main -->


<!-- ════════════════════════════════════════════
     MODALS
═════════════════════════════════════════════ -->

<!-- MODAL: TAMBAH PESANAN -->
<div class="overlay" id="modalTambahPesanan">
    <div class="modal-box">
        <div class="modal-title">📦 Tambah Pesanan Baru</div>
        <form action="/admin/pesanan/store" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" placeholder="contoh: Budi Santoso" required>
            </div>
            <div class="form-group">
                <label>Detail Pesanan</label>
                <textarea name="pesanan" placeholder="contoh: 2x Croissant Keju, 1x Blackforest Cake" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Total Harga (Rp)</label>
                    <input type="text" name="total_harga" placeholder="contoh: Rp 95.000" required>
                </div>
                <div class="form-group">
                    <label>Metode Bayar</label>
                    <select name="metode_pembayaran">
                        <option>Tunai (COD)</option>
                        <option>Transfer Bank</option>
                        <option>QRIS</option>
                        <option>GoPay</option>
                        <option>OVO</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Latitude GPS</label>
                    <input type="text" name="latitude" placeholder="-6.2088">
                </div>
                <div class="form-group">
                    <label>Longitude GPS</label>
                    <input type="text" name="longitude" placeholder="106.8456">
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option>Diproses Admin</option>
                    <option>Sedang Dikirim</option>
                    <option>Selesai</option>
                    <option>Dibatalkan</option>
                </select>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalTambahPesanan')">Batal</button>
                <button type="submit" class="btn btn-primary">＋ Simpan Pesanan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT PESANAN -->
<div class="overlay" id="modalEditPesanan">
    <div class="modal-box">
        <div class="modal-title">✏️ Edit Pesanan</div>
        <form id="formEditPesanan" action="" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Pelanggan</label>
                <input type="text" id="ep_nama" name="nama_pelanggan" required>
            </div>
            <div class="form-group">
                <label>Detail Pesanan</label>
                <textarea id="ep_pesanan" name="pesanan" required></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Total Harga</label>
                    <input type="text" id="ep_harga" name="total_harga" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="ep_status" name="status">
                        <option>Diproses Admin</option>
                        <option>Sedang Dikirim</option>
                        <option>Selesai</option>
                        <option>Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalEditPesanan')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: TAMBAH PRODUK -->
<div class="overlay" id="modalTambahProduk">
    <div class="modal-box">
        <div class="modal-title">🍞 Tambah Produk Baru</div>
        <form action="/admin/produk/store" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" name="nama" placeholder="contoh: Croissant Coklat" required>
                </div>
                <div class="form-group">
                    <label>Emoji / Ikon</label>
                    <input type="text" name="gambar" placeholder="🍞" maxlength="5">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Harga (angka, tanpa Rp)</label>
                    <input type="number" name="harga" placeholder="35000" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori">
                        <option>Tart</option>
                        <option>Roti Manis</option>
                        <option>Pastry</option>
                        <option>Donat</option>
                        <option>Cupcake</option>
                        <option>Dessert</option>
                        <option>Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Rating (1.0 – 5.0)</label>
                    <input type="text" name="rating" placeholder="4.8" maxlength="4">
                </div>
                <div class="form-group">
                    <label>Badge / Label</label>
                    <input type="text" name="badge" placeholder="Best Seller" maxlength="20">
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" placeholder="Deskripsi singkat produk..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalTambahProduk')">Batal</button>
                <button type="submit" class="btn btn-primary">＋ Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT PRODUK -->
<div class="overlay" id="modalEditProduk">
    <div class="modal-box">
        <div class="modal-title">✏️ Edit Produk</div>
        <form id="formEditProduk" action="" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Produk</label>
                    <input type="text" id="epr_nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Emoji / Ikon</label>
                    <input type="text" id="epr_gambar" name="gambar" maxlength="5">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Harga (angka)</label>
                    <input type="number" id="epr_harga" name="harga" required>
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select id="epr_kategori" name="kategori">
                        <option>Tart</option>
                        <option>Roti Manis</option>
                        <option>Pastry</option>
                        <option>Donat</option>
                        <option>Cupcake</option>
                        <option>Dessert</option>
                        <option>Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Rating</label>
                    <input type="text" id="epr_rating" name="rating" maxlength="4">
                </div>
                <div class="form-group">
                    <label>Badge</label>
                    <input type="text" id="epr_badge" name="badge" maxlength="20">
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea id="epr_deskripsi" name="deskripsi"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalEditProduk')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: TAMBAH USER -->
<div class="overlay" id="modalTambahUser">
    <div class="modal-box">
        <div class="modal-title">👤 Tambah User Baru</div>
        <form action="/admin/user/store" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" placeholder="contoh: Siti Rahayu" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="contoh: siti@email.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalTambahUser')">Batal</button>
                <button type="submit" class="btn btn-primary">＋ Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EDIT USER -->
<div class="overlay" id="modalEditUser">
    <div class="modal-box">
        <div class="modal-title">✏️ Edit User</div>
        <form id="formEditUser" action="" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" id="eu_name" name="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="eu_email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah">
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeModal('modalEditUser')">Batal</button>
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>


<script>
    // ── NAVIGASI ──────────────────────────────────────────────────────────────
    function switchPage(name) {
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
        document.getElementById('page-' + name).classList.add('active');
        document.getElementById('nav-' + name).classList.add('active');
        window.location.hash = name;
    }

    // Buka halaman sesuai tab param/hash
    (function() {
        const params = new URLSearchParams(window.location.search);
        const hash   = window.location.hash.replace('#', '');
        const tab    = params.get('tab') || hash;
        if (tab && document.getElementById('page-' + tab)) switchPage(tab);
    })();

    // ── MODAL HELPERS ─────────────────────────────────────────────────────────
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
    // Tutup modal jika klik overlay
    document.querySelectorAll('.overlay').forEach(ov => {
        ov.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });

    // ── EDIT PESANAN ──────────────────────────────────────────────────────────
    function openEditPesanan(id, nama, pesanan, harga, status) {
        document.getElementById('formEditPesanan').action = '/admin/pesanan/' + id + '/update';
        document.getElementById('ep_nama').value    = nama;
        document.getElementById('ep_pesanan').value = pesanan;
        document.getElementById('ep_harga').value   = harga;
        const sel = document.getElementById('ep_status');
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].value === status || sel.options[i].text === status) {
                sel.selectedIndex = i; break;
            }
        }
        openModal('modalEditPesanan');
    }

    // ── EDIT PRODUK ───────────────────────────────────────────────────────────
    function openEditProduk(id, nama, harga, gambar, kategori, rating, badge, deskripsi) {
        document.getElementById('formEditProduk').action = '/admin/produk/' + id + '/update';
        document.getElementById('epr_nama').value     = nama;
        document.getElementById('epr_harga').value    = harga;
        document.getElementById('epr_gambar').value   = gambar;
        document.getElementById('epr_rating').value   = rating;
        document.getElementById('epr_badge').value    = badge;
        document.getElementById('epr_deskripsi').value = deskripsi;
        const sel = document.getElementById('epr_kategori');
        for (let i = 0; i < sel.options.length; i++) {
            if (sel.options[i].text === kategori) { sel.selectedIndex = i; break; }
        }
        openModal('modalEditProduk');
    }

    // ── EDIT USER ─────────────────────────────────────────────────────────────
    function openEditUser(id, name, email) {
        document.getElementById('formEditUser').action = '/admin/user/' + id + '/update';
        document.getElementById('eu_name').value  = name;
        document.getElementById('eu_email').value = email;
        openModal('modalEditUser');
    }

    // ── ESC CLOSE MODAL ───────────────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.overlay.active').forEach(m => m.classList.remove('active'));
        }
    });
</script>

</body>
</html>