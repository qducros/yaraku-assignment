<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

/**
    * Define BooksTable Livewire Component class.
    *
    * @property string $orderField  defines field used for book ordering (default: 'title')
    * @property string $orderDirection  defines direction used for book ordering (default: 'ASC')
    * @property array $search  defines search parameters for table filtering
    * @property array $perPage  defines number of elements per page for pagination
    * @property string $action  defines the action the user is about to make
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
    public string $action = '';
    protected array $queryString = [
        'orderField' => ['except' => '', 'as' => 'sort_field'],
        'orderDirection' => ['except' => '', 'as' => 'sort_direction'],
        'search.title' => ['except' => '', 'as' => 'title'],
        'search.author' => ['except' => '', 'as' => 'author'],
        'perPage' => ['except' => '', 'as' => 'pagesize']
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
        * On perPage change, the selected page number remains the same.
        * To avoid issues of the user staying on a page that doesn't have results anymore,
        * we reset the page (going back to page 1)
        *
        * @return void
        */
    public function updatingPerPage(): void
    {
        $this->resetPage();
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
        * Set the action chosen by the user to display corresponding UI.
        * 
        * @return void
        */
    public function setAction($action): void
    {
        if($action === 'create') {
            $this->action = $action;
        }
    }

    /**
        * Listen to createUpdateBook event dispatched from CreateUpdateBookForm class.
        * Clean the UI and add feedback to the user.
        * 
        * @return void
        */
    #[On('createUpdateBook')]
    public function onCreateUpdateBook($action): void
    {
        if($action === 'create') {
            $this->reset('action');
        }
    }

    /**
        * Listen to cancelAction event dispatched all form cancel button (create, update, export and delete).
        * Clean the UI.
        * 
        * @return void
        */
    #[On('cancelAction')]
    public function onCancelAction(): void
    {
        $this->reset('action');
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
