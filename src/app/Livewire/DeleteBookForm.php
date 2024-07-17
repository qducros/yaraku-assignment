<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Define DeleteBookForm Livewire Component class.
 *
 * @property int $bookId defines the book used for delete (optional, default: null)
 * @property string $action defines the current action (optional, default: '')
 */
class DeleteBookForm extends Component
{
    public ?int $bookId = 0;

    public ?string $action = '';

    /**
     * Mount lifecycle hook called when a component is created to set the class properties.
     *
     * @param  int  $bookId  defines the id of the book to delete (optional, default: 0)
     * @param  string  $action  defines the current action (optional, default: '')
     */
    public function mount(?int $bookId = 0, ?string $action = ''): void
    {
        $this->bookId = $bookId;
        $this->action = $action;
    }

    /**
     * Method called upon submission of the delete-book form.
     * Delete the book and dispatch a livewire event to the main BookTable component
     * for user feedback.
     */
    public function delete(): void
    {
        if ($this->bookId > 0) {
            Book::destroy($this->bookId);
        }

        $this->dispatch('completeAction', action: $this->action);
    }

    /**
     * Method called upon cancellation of the delete-book form.
     * Dispatch a livewire event to the main BookTable component for user feedback.
     */
    public function cancel(): void
    {
        $this->dispatch('cancelAction');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('livewire.delete-book-form');
    }
}
