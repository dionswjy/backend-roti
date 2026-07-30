use App\Http\Controllers\BannerController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route API Pesanan
Route::get('/pesanan', [PesananController::class, 'indexApi']);
Route::post('/pesanan', [PesananController::class, 'storeApi']);

// Route API Produk
Route::get('/produk', [ProdukController::class, 'index']);

// Route API Banner Promo
Route::get('/banners', [BannerController::class, 'indexApi']);

// Route API User Management & Auth
Route::post('/register', [UserController::class, 'registerApi']);
Route::post('/login', [UserController::class, 'loginApi']);
Route::get('/users', [UserController::class, 'indexApi']);