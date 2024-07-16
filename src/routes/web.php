<?php

use App\Livewire\BooksTable;
use Illuminate\Support\Facades\Route;

Route::get('', fn () => to_route('books'));
Route::get('books', BooksTable::class)->name('books');
