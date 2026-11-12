<?php

use App\Models\DailyMenu;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user instanceof User) {
            return redirect()->route($user->isAdmin() ? 'dashboard' : 'visitor.menu');
        }

        abort(403);
    }

    return view('home');
})->name('home');

Route::get('/login', fn () => redirect()->route('home'))->name('login');

Route::get('/visitante', function () {
    if (Auth::check()) {
        $user = Auth::user();

        if ($user instanceof User) {
            return redirect()->route($user->isAdmin() ? 'dashboard' : 'visitor.menu');
        }

        abort(403);
    }

    return view('visitor-login');
})->name('visitor.login');

Route::get('/visitante/cardapio', function () {
    if (! Auth::check()) {
        return redirect()->guest(route('visitor.login'));
    }

    $user = Auth::user();
    abort_unless($user instanceof User && $user->isVisitor(), 403);

    $menu = DailyMenu::query()
        ->with(['items' => fn ($query) => $query->orderBy('name')])
        ->whereDate('menu_date', today())
        ->first();

    return view('public-menu', compact('menu'));
})->name('visitor.menu');

Route::get('/dashboard', function () {
    $user = Auth::user();
    abort_unless($user instanceof User && $user->isAdmin(), 403);

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
