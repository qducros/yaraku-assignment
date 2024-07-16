<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;

class BooksTable extends Component
{
    public function render()
    {
        return view('livewire.books-table', [
            'books' => Book::all(),
        ]);
    }
}
