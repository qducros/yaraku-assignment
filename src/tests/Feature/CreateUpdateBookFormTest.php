<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Livewire\CreateUpdateBookForm;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateUpdateBookFormTest extends TestCase
{
    use RefreshDatabase;

    // Test on book creation
    public function test_create_component_exists_on_the_page()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->assertSeeLivewire(CreateUpdateBookForm::class);
    }

    public function test_can_create_book_with_valid_input()
    {
        Livewire::test(BooksTable::class)
            ->assertDontSee('This is a book')
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        Livewire::test(CreateUpdateBookForm::class)
            ->set(['form.title' => 'This is a book', 'form.author' => 'This is an author'])
            ->call('save')
            ->assertDispatched('completeAction');
        Livewire::test(BooksTable::class)
            ->assertSeeInOrder(['This is a book', 'This is an author'])
            ->assertSet('action', '');
    }

    public function test_cant_create_book_with_too_short_input()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        Livewire::test(CreateUpdateBookForm::class)
            ->set(['form.title' => 'book', 'form.author' => 'auth'])
            ->call('save')
            ->assertHasErrors(['form.title' => ['min:5']])
            ->assertHasErrors(['form.author' => ['min:5']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The title field must be at least 5 characters.')
            ->assertSee('The author field must be at least 5 characters.');
    }

    public function test_cant_create_book_without_required_input()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        Livewire::test(CreateUpdateBookForm::class)
            ->set(['form.title' => '', 'form.author' => ''])
            ->call('save')
            ->assertHasErrors(['form.title' => ['required']])
            ->assertHasErrors(['form.author' => ['required']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The title field is required.')
            ->assertSee('The author field is required.');
    }

    public function test_can_cancel_book_creation()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        Livewire::test(CreateUpdateBookForm::class)
            ->set(['form.title' => 'This is a book ', 'form.author' => 'This is an author'])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_cancel_book_creation_by_reclicking_create()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->call('setAction', 'create')
            ->assertSet('action', '');
    }

    // Test on book edit
    public function test_edit_component_exists_on_the_page()
    {
        $book = Book::factory()->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->assertSeeLivewire(CreateUpdateBookForm::class);
    }

    public function test_edit_component_displays_current_book_title_and_author()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => 'edit-'.$book->id])
            ->assertSeeHtml('Lord of the Rings')
            ->assertSeeHtml('Tolkien')
            ->assertSet('form.title', 'Lord of the Rings')
            ->assertSet('form.author', 'Tolkien');
    }

    public function test_can_edit_book_with_valid_input()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => 'edit-'.$book->id])
            ->set(['form.title' => 'Waylander', 'form.author' => 'Gemmell'])
            ->call('save')
            ->assertDispatched('completeAction');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '')
            ->assertSeeInOrder(['Waylander', 'Gemmell']);
    }

    public function test_cant_edit_book_with_too_short_input()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => 'edit-'.$book->id])
            ->set(['form.title' => 'book', 'form.author' => 'auth'])
            ->call('save')
            ->assertHasErrors(['form.title' => ['min:5']])
            ->assertHasErrors(['form.author' => ['min:5']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The title field must be at least 5 characters.')
            ->assertSee('The author field must be at least 5 characters.');
    }

    public function test_cant_edit_book_without_required_input()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => 'edit-'.$book->id])
            ->set(['form.title' => '', 'form.author' => ''])
            ->call('save')
            ->assertHasErrors(['form.title' => ['required']])
            ->assertHasErrors(['form.author' => ['required']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The title field is required.')
            ->assertSee('The author field is required.');
    }

    public function test_can_cancel_book_edit()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => 'edit-'.$book->id])
            ->set(['form.title' => 'This is a book ', 'form.author' => 'This is an author'])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);
    }

    public function test_can_cancel_book_edit_by_reclicking_edit()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);
    }

    public function test_can_cancel_book_edit_by_clicking_another_edit()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book1->id)
            ->assertSet('action', 'edit-'.$book1->id)
            ->call('setAction', 'edit-'.$book2->id)
            ->assertSet('action', 'edit-'.$book2->id)
            ->assertSee(['Lord of the Rings', 'Tolkien', 'Silmarillion']);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book2, 'action' => 'edit-'.$book2->id])
            ->assertSeeHtml('Silmarillion')
            ->assertSeeHtml('Tolkien')
            ->assertSet('form.title', 'Silmarillion')
            ->assertSet('form.author', 'Tolkien');
    }

    public function test_can_cancel_book_edit_by_changing_page()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->assertSeeLivewire(CreateUpdateBookForm::class)
            ->call('nextPage')
            ->call('previousPage')
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
    }
}
