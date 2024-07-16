<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
    * Define BooksTable Livewire Component class.
    *
    * @property string $orderField  defines field used for book ordering (default: 'title')
    * @property string $orderDirection  defines direction used for book ordering (default: 'ASC')
    * @property array $search  defines search parameters for table filtering
    * @property array $perPage  defines number of elements per page for pagination
    * @property array $queryString  defines query string elements with exception values
    */
class BooksTable extends Component
{
    use WithPagination;

    public string $orderField = 'title';
    public string $orderDirection = 'ASC';
    public array $search = [
        'title' => '',
        'author' => ''
    ];
    public string $perPage = '5';
    protected array $queryString = [
        'orderField' => ['except' => '', 'as' => 'sort_field'],
        'orderDirection' => ['except' => '', 'as' => 'sort_direction'],
        'search.title' => ['except' => '', 'as' => 'title'],
        'search.author' => ['except' => '', 'as' => 'author']
    ];

    /**
        * Returns the reference of our custom pagination view.
        *
        * @return string
        */
    public function paginationView(): string
    {
        return 'livewire.pagination';
    }

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
     * Pass filtered / sorted / paginated books to the view.
     * 
     * @return View
     */
    public function render(): View
    {
        return view('livewire.books-table', [
            'books' => Book::where(
                    [['title', 'LIKE', "%{$this->search['title']}%"],
                    ['author', 'LIKE', "%{$this->search['author']}%"]])
                ->orderBy($this->orderField, $this->orderDirection)
                ->paginate($this->perPage)
        ]);
    }
}
