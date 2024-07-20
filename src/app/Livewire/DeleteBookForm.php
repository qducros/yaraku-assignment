<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Define DeleteBookForm Livewire Component class.
 *
 * @property string $action defines the current action (optional, default: '')
 */
class DeleteBookForm extends Component
{
    public ?string $action = '';

    /**
     * Mount lifecycle hook called when a component is created to set the class properties.
     *
     * @param  string  $action  defines the current action (optional, default: '')
     */
    public function mount(?string $action = ''): void
    {
        $this->action = $action;
    }

    /**
     * Method called upon submission of the delete-book form.
     * If action is a single delete, delete the book and dispatch a
     * livewire event to the parent BookTable component for user feedback.
     * If action is a bulk delete, dispatch an event to the parent BookTable
     * component to get the list of ids to delete.
     */
    public function delete(): void
    {
        $actionExploded = explode('-', $this->action);

        if ($actionExploded[0] === 'delete') {
            Book::destroy($actionExploded[1]);

            $this->dispatch('completeAction', action: $this->action);
        } elseif ($actionExploded[0] === 'delete_bulk') {
            $this->dispatch('requestSelectionFromParent', action: $this->action);
        }
    }

    /**
     * Listen to deleteSelectionFromParent event dispatched from parent component.
     * Called when bulk deleting in $this->delete() to get information on the elements to delete.
     * Dispatch a livewire event to the parent BookTable component for user feedback.
     *
     * @param  array  $selectedOnPage  defines the elements to delete
     */
    #[On('deleteSelectionFromParent')]
    public function onDeleteSelectionFromParent(array $selectedOnPage): void
    {
        $this->queryBulkBooks($selectedOnPage)->delete();

        $this->dispatch('completeAction', action: $this->action);
    }

    /**
     * Method called upon cancellation of the delete-book form.
     * Dispatch a livewire event to the parent BookTable component for user feedback.
     */
    public function cancel(): void
    {
        $this->dispatch('cancelAction');
    }

    /**
     * Return the book query corresponding to the selection.
     * $selectedOnPage possible values:
     * - ['ids' => ['1', '2']]: individual books/page selection using array of ids
     * - ['title' => '', 'author' => '']: all selection using the search params
     * 
     * @param  array  $selectedOnPage  defines the elements to delete
     */
    private function queryBulkBooks(array $selectedOnPage): Builder
    {
        $query = Book::query();
        if (array_key_exists('title', $selectedOnPage) && !empty($selectedOnPage['title'])) {
            $query->where('title', 'LIKE', "%{$selectedOnPage['title']}%");
        }
        if (array_key_exists('author', $selectedOnPage) && !empty($selectedOnPage['author'])) {
            $query->where('author', 'LIKE', "%{$selectedOnPage['author']}%");
        }
        if (array_key_exists('ids', $selectedOnPage) && !empty($selectedOnPage['ids'])) {
            $query->whereIn('id', $selectedOnPage['ids']);
        }
        return $query;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('livewire.delete-book-form');
    }
}
