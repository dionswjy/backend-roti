use App\Http\Controllers\BannerController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Halaman Utama Admin ───────────────────────────────────────────────────
Route::get('/admin', [PesananController::class, 'index']);

// ─── CRUD Pesanan Web Admin ────────────────────────────────────────────────
Route::post('/admin/pesanan/store',          [PesananController::class, 'storeWeb']);
Route::post('/admin/pesanan/{id}/update',    [PesananController::class, 'updateWeb']);
Route::post('/admin/pesanan/{id}/delete',    [PesananController::class, 'destroyWeb']);

// ─── CRUD User Web Admin ───────────────────────────────────────────────────
Route::post('/admin/user/store',             [UserController::class,    'storeWeb']);
Route::post('/admin/user/{id}/update',       [UserController::class,    'updateWeb']);
Route::post('/admin/user/{id}/delete',       [UserController::class,    'destroyWeb']);

// ─── CRUD Produk Web Admin ─────────────────────────────────────────────────
Route::post('/admin/produk/store',           [ProdukController::class,  'storeWeb']);
Route::post('/admin/produk/{id}/update',     [ProdukController::class,  'updateWeb']);
Route::post('/admin/produk/{id}/delete',     [ProdukController::class,  'destroyWeb']);

// ─── CRUD Banner Promo Web Admin ───────────────────────────────────────────
Route::post('/admin/banner/store',           [BannerController::class,  'storeWeb']);
Route::post('/admin/banner/{id}/update',     [BannerController::class,  'updateWeb']);
Route::post('/admin/banner/{id}/delete',     [BannerController::class,  'destroyWeb']);

// ─── Redirect Root ─────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect('/admin');
});
