<?php

namespace App\Livewire\Forms;

use App\Models\Book;
use Livewire\Form;

/**
 * Define BookForm Livewire Form class used for create / update.
 *
 * @property Book $book defines the book used for update (optional, default: null)
 * @property string $title defines the book title to display in the form in update case (default: '')
 * @property string $author defines the book author to display in the form in update case (default: '')
 */
class BookForm extends Form
{
    public ?Book $book = null;

    public string $title = '';

    public string $author = '';

    /**
     * Method to allow for filling the form with existing data and storing the book on the form object later use.
     *
     * @param  Book  $book  defines the book used for update (optional, default: null)
     */
    public function setBook(?Book $book = null): void
    {
        $this->book = $book;
        $this->title = $book->title;
        $this->author = $book->author;
    }

    /**
     * Method to validate input data before either creating or updating a book.
     */
    public function save(): void
    {
        $this->validate();

        if (empty($this->book)) {
            Book::create($this->only(['title', 'author']));
        } else {
            $this->book->update($this->only(['title', 'author']));
        }
    }

    /**
     * Returns an array of validation rules.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5',
            'author' => 'required|string|min:5',
        ];
    }
}
