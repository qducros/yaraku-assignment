<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
    * Define BooksTable Livewire Component class.
    *
    * @property string $orderField  field used for book ordering (default: 'title')
    * @property string $orderDirection  direction used for book ordering (default: 'ASC')
    * @property array $queryString  defines query string elements with exception values
    */
class BooksTable extends Component
{
    public string $orderField = 'title';
    public string $orderDirection = 'ASC';
    protected array $queryString = [
        'orderField' => ['except' => 'name'],
        'orderDirection' => ['except' => 'ASC']
    ];

    /**
        * Is triggered when a table header is clicked.
        * If the clicked header was the last one to be clicked, just change orderDirection.
        * If a new header clicked, change orderField and reset orderDirection (to ASC).
        *
        * @param string $name  value of orderField property corresponding to table header
        * @return void
        */
    public function setOrderField(string $name): void
    {
        if ($name === $this->orderField) {
            $this->orderDirection = $this->orderDirection === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->orderField = $name;
            $this->reset('orderDirection');
        }
    }

    /**
     * Get the view / contents that represent the component.
     * Pass filtered / sorted books to the view.
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.books-table', [
            'books' => Book::orderBy($this->orderField, $this->orderDirection)->get(),
        ]);
    }
}
