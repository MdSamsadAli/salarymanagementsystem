<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalaryController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('admin');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [EmployeeController::class, 'admin'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::resource("/categories", CategoryController::class);
    Route::resource("/news", NewsController::class);
    Route::resource("/salaries", SalaryController::class);
    Route::get("/salaryHistory/{id}", [EmployeeController::class, 'salaryHistory'])->name('employee.salary-history');
    Route::resource("/employee", EmployeeController::class);
});


Route::get('/', [FrontendController::class, 'index'])->name('front.index');
Route::get('/cate/{id}', [FrontendController::class, 'page'])->name('front.page');
Route::get('/detail/{id}', [FrontendController::class, 'detail'])->name('news.detail');
require __DIR__ . '/auth.php';
