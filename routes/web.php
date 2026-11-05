<?php

use App\Models\DailyMenu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('home');
})->name('home');

Route::get('/login', fn () => redirect()->route('home'))->name('login');

Route::get('/cardapio-do-dia', function () {
    $menu = DailyMenu::query()
        ->with(['items' => fn ($query) => $query->orderBy('name')])
        ->whereDate('menu_date', today())
        ->first();

    return view('public-menu', compact('menu'));
})->name('public-menu');

Route::get('/dashboard', function () {
    return view('dashboard', [
        'menuCount' => DailyMenu::count(),
        'foodCount' => MenuItem::count(),
        'todayMenuExists' => DailyMenu::whereDate('menu_date', today())->exists(),
    ]);
})
    ->middleware('auth')
    ->name('dashboard');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');
