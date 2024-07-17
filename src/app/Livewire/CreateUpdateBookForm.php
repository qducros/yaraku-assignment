<?php

namespace App\Livewire;

use App\Livewire\Forms\BookForm;
use App\Models\Book;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * Define CreateUpdateBookForm Livewire Component class.
 *
 * @property Book $book defines the book used for update (optional, default: null)
 * @property BookForm $form defines the book form to access the save method
 * @property string $action defines the current action (optional, default: '')
 */
class CreateUpdateBookForm extends Component
{
    public ?Book $book = null;

    public BookForm $form;

    public ?string $action = '';

    /**
     * Mount lifecycle hook called when a component is created to set the class properties.
     *
     * @param  Book  $book  defines the book used for update (optional, default: null)
     * @param  string  $action  defines the current action (optional, default: '')
     */
    public function mount(?Book $book = null, ?string $action = ''): void
    {
        if ($book->exists) {
            $this->form->setBook($book);
        }

        $this->action = $action;
    }

    /**
     * Method called upon submission of the create-update-book form.
     * Call the BookForm method to save the book and dispatch a livewire event to the main BookTable component
     * for user feedback.
     */
    public function save(): void
    {
        $this->form->save();

        $this->dispatch('completeAction', action: $this->action);
    }

    /**
     * Method called upon cancellation of the create-update-book form.
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
        return view('livewire.create-update-book-form');
    }
}
