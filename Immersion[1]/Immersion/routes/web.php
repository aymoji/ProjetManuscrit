<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvantHomeController;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DrawingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PredictionController;




Route::get('/', function () {
    return redirect()->route('avantHome');
})->name('home');

Route::get('/avantHome', [AvantHomeController::class, 'show'])->name('avantHome');

Route::get('/login', function() {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/home', function() {
    return view('home');
})->middleware('auth')->name('home');

Route::get('/register', [CreateUserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CreateUserController::class, 'register'])->name('register.submit');

Route::post('/save-drawing', [DrawingController::class, 'store'])->name('save-drawing');
Route::post('/upload', [UploadController::class, 'store'])->middleware('auth');
Route::post('/predict', [PredictionController::class, 'sendToApi']);
?>