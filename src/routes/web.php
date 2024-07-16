<?php

use App\Livewire\BooksTable;
use Illuminate\Support\Facades\Route;

Route::get('books', BooksTable::class)->name('books');
