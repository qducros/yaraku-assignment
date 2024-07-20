<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
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

    private array $possibleActions = ['create', 'edit', 'delete', 'export_all', 'delete_bulk', 'export_bulk'];

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
     * reset the page (going back to page 1)
     * Reset the selection related properties (selection and selectAll).
     */
    public function updatedPerPage(): void
    {
        $this->reset('selection');
        $this->reset('selectAll');
        $this->resetPage();
    }

    /**
     * On search change, the selected page number remains the same.
     * To avoid issues of the user staying on a page that doesn't have results anymore,
     * reset the page (going back to page 1)
     * Reset the selection related properties (selection and selectAll).
     */
    public function updatedSearch(): void
    {
        $this->reset('selection');
        $this->reset('selectAll');
        $this->reset('orderDirection');
        $this->reset('orderField');
        $this->resetPage();
    }

    /**
     * Clear the corresponding search param.
     *
     * @param  string  $name  defines the search param name
     */
    public function clearSearch($name): void
    {
        $this->search[$name] = '';
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
        $this->reset('selection');
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
        $this->reset('selection');
        $this->reset('selectAll');
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
     * Clean the UI by resetting the action and display feedback to the user.
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

        switch ($actionExploded[0]) {
            case 'create':
                Session::flash('success', __('messages.feedback.success.create'));
                break;
            case 'edit':
                Session::flash('success', __('messages.feedback.success.edit'));
                break;
            case 'delete':
                Session::flash('success', __('messages.feedback.success.delete'));
                break;
            case 'export_all':
                Session::flash('success', __('messages.feedback.success.export_all'));
                break;
            case 'delete_bulk':
                Session::flash('success', __('messages.feedback.success.delete_bulk'));
                break;
            case 'export_bulk':
                Session::flash('success', __('messages.feedback.success.export_bulk'));
                break;
            default:
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
     * If the selection is empty (meaning no book were selected), display feedback to the user.
     * $selectedOnPage possible values:
     * - ['ids' => []]: no selection, warning feedback to user
     * - ['ids' => ['1', '2']]: individual books/page selection using array of ids. dispatch event to bulk
     * - ['title' => '', 'author' => '']: all selection using the search params, dispatch event to bulk
     *
     * @param  string  $action  defines the action the user is about to make
     */
    #[On('requestSelectionFromParent')]
    public function onRequestSelectionFromParent(string $action): void
    {
        $selectedOnPage = $this->selectAll ? $this->search : ['ids' => array_intersect($this->selection, $this->bookIds)];
        if (array_key_exists('ids', $this->selection) && empty($this->selection['ids'])) {
            Session::flash('warning', __('messages.feedback.warning.no_selection'));
        } elseif ($action === 'delete_bulk') {
            $this->dispatch('deleteSelectionFromParent', selectedOnPage: $selectedOnPage);
        } elseif ($action === 'export_bulk') {
            $this->dispatch('exportSelectionFromParent', selectedOnPage: $selectedOnPage);
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
