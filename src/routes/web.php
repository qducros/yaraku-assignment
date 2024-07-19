<?php

use App\Livewire\BooksTable;
use Illuminate\Support\Facades\Route;

Route::get('', fn () => to_route('books'));
Route::get('books', BooksTable::class)->name('books');

Route::get('/language/{locale}', function ($locale) {
    if(array_key_exists($locale, config('app.supported_locales'))) {
        session(['locale' => $locale]);
    }
    
    return redirect()->back();
})->name('locale');
