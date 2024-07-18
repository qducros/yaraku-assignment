<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Define BooksTable Livewire Component class.
 *
 * @property string $orderField defines field used for book ordering (default: 'title')
 * @property string $orderDirection defines direction used for book ordering (default: 'ASC')
 * @property array $search defines search parameters for table filtering
 * @property array $perPage defines number of elements per page for pagination
 * @property string $action defines the action the user is about to make
 * @property array $possibleActions defines the authorized actions
 * @property array $selection defines the ids of all selected books
 * @property array $bookIds defines the ids of all current page's books
 * @property bool $selectAll defines if current selection must be all filtered elements
 * @property array $queryString defines query string elements with exception values
 */
class BooksTable extends Component
{
    use WithPagination;

    public string $orderField = 'title';

    public string $orderDirection = 'ASC';

    public array $search = [
        'title' => '',
        'author' => '',
    ];

    public string $perPage = '5';

    public string $action = '';

    private array $possibleActions = ['create', 'edit', 'delete', 'export_all', 'delete_bulk'];

    public array $selection = [];

    public array $bookIds = [];

    public bool $selectAll = false;

    protected array $queryString = [
        'orderField' => ['except' => '', 'as' => 'sort_field'],
        'orderDirection' => ['except' => '', 'as' => 'sort_direction'],
        'search.title' => ['except' => '', 'as' => 'title'],
        'search.author' => ['except' => '', 'as' => 'author'],
        'perPage' => ['except' => '', 'as' => 'pagesize'],
    ];

    /**
     * Returns the reference of our custom pagination view.
     */
    public function paginationView(): string
    {
        return 'livewire.pagination';
    }

    /**
     * On perPage change, the selected page number remains the same.
     * To avoid issues of the user staying on a page that doesn't have results anymore,
     * we reset the page (going back to page 1)
     */
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * On search change, we reset the selection related properties (selection and selectAll).
     */
    public function updatingSearch(): void
    {
        $this->reset('selection');
        $this->reset('selectAll');
    }

    /**
     * On page change, we reset action in case it's an action on a specific row (edit-id or delete-id).
     * If the selectAll option is selected, reset the selection.
     * Reset selectAll.
     */
    public function updatedPage(): void
    {
        $actionExploded = explode('-', $this->action);

        if (count($actionExploded) === 2) {
            $this->reset('action');
        }

        if ($this->selectAll) $this->reset('selection');
        $this->reset('selectAll');
    }

    /**
     * Is triggered when a table header is clicked.
     * If the clicked header was the last one to be clicked, just change orderDirection.
     * If a new header clicked, change orderField and reset orderDirection (to ASC).
     *
     * @param  string  $name  value of orderField property corresponding to table header
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
     * If the previous action is the same as the new one, action is reset (if edit form is displayed
     * and the user clicks the same edit button, remove the form)
     *
     * @param  string  $action  defines the action the user is about to make
     */
    public function setAction($action): void
    {
        $actionExploded = explode('-', $action);

        if ($this->action === $action) {
            $this->reset('action');
        } elseif (in_array($actionExploded[0], $this->possibleActions)) {
            $this->action = $action;
        }
    }

    /**
     * Listen to createUpdateBook event dispatched from CreateUpdateBookForm and DeleteBookForm classes.
     * Clean the UI by resetting the action and add feedback to the user.
     *
     * @param  string  $action  defines the action the user just completed
     */
    #[On('completeAction')]
    public function onCompleteAction($action): void
    {
        $actionExploded = explode('-', $action);

        if (in_array($actionExploded[0], $this->possibleActions)) {
            $this->reset('action');
        }
    }

    /**
     * Listen to cancelAction event dispatched from all form cancel button (create, update, export and delete).
     * Clean the UI.
     */
    #[On('cancelAction')]
    public function onCancelAction(): void
    {
        $this->reset('action');
    }

    /**
     * Listen to askSelectionFromParent event dispatched from bulk action (export and delete).
     * @param  string  $action  defines the action the user is about to make
     */
    #[On('requestSelectionFromParent')]
    public function onRequestSelectionFromParent(string $action): void
    {
        $selectedOnPage = $this->selectAll ? ['all'] : array_intersect($this->selection, $this->bookIds);
        if ($action === 'delete_bulk') {
            $this->dispatch('deleteSelectionFromParent', selectedOnPage: $selectedOnPage);
        }
    }

    /**
     * Get the view / contents that represent the component.
     * Pass filtered / sorted / paginated books to the view.
     * Set bookIds used for row selection.
     */
    public function render(): View
    {
        $books = Book::where(
            [['title', 'LIKE', "%{$this->search['title']}%"],
                ['author', 'LIKE', "%{$this->search['author']}%"]])
            ->orderBy($this->orderField, $this->orderDirection)
            ->paginate($this->perPage);

        $this->bookIds = array_map('strval', $books->pluck('id')->toArray());

        return view('livewire.books-table', [
            'books' => $books,
        ]);
    }
}
