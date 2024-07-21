<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Livewire\CreateUpdateBookForm;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Define the tests related to the CreateUpdateBookForm class.
 * 
 * test_can_create_book_with_valid_input
 * test_cant_create_book_with_too_short_input
 * test_cant_create_book_without_required_input
 * test_can_cancel_book_creation
 * test_can_cancel_book_creation_by_reclicking_create
 * test_edit_component_displays_current_book_title_and_author
 * test_can_edit_book_with_valid_input
 * test_cant_edit_book_with_too_short_input
 * test_cant_edit_book_without_required_input
 * test_can_cancel_book_edit
 * test_can_cancel_book_edit_by_reclicking_edit
 * test_can_cancel_book_edit_by_clicking_another_edit
 * test_can_cancel_book_edit_by_page_update
 * test_can_cancel_book_edit_by_page_size_update
 * test_can_cancel_book_edit_by_search_update
 * test_can_cancel_book_edit_by_order_update
 */
class CreateUpdateBookFormTest extends TestCase
{
    use RefreshDatabase;

    // Test on book creation (5)
    public function test_can_create_book_with_valid_input()
    {
        $this->assertDatabaseCount('books', 0);

        $booksTable = Livewire::test(BooksTable::class)
            ->assertDontSee('This is a book')
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        $createUpdateBookForm = Livewire::test(CreateUpdateBookForm::class, ['action' => $booksTable->action])
            ->set(['form.title' => 'This is a book', 'form.author' => 'This is an author'])
            ->call('save')
            ->assertDispatched('completeAction');

        $this->assertDatabaseCount('books', 1);

        $booksTable->call('onCompleteAction', $createUpdateBookForm->action)
            ->assertSeeInOrder(['This is a book', 'This is an author'])
            ->assertSee('1 - 1 / 1')
            ->assertSee('The book was created successfully.')
            ->assertSet('action', '');
    }

    public function test_cant_create_book_with_too_short_input()
    {
        $this->assertDatabaseCount('books', 0);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        Livewire::test(CreateUpdateBookForm::class, ['action' => $booksTable->action])
            ->set(['form.title' => 'b', 'form.author' => 'a'])
            ->call('save')
            ->assertHasErrors(['form.title' => ['min:2']])
            ->assertHasErrors(['form.author' => ['min:2']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The book title is too short.')
            ->assertSee('The book author is too short.');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_cant_create_book_without_required_input()
    {
        $this->assertDatabaseCount('books', 0);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        Livewire::test(CreateUpdateBookForm::class, ['action' => $booksTable->action])
            ->set(['form.title' => '', 'form.author' => ''])
            ->call('save')
            ->assertHasErrors(['form.title' => ['required']])
            ->assertHasErrors(['form.author' => ['required']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The book title is required.')
            ->assertSee('The book author is required.');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_can_cancel_book_creation()
    {
        $this->assertDatabaseCount('books', 0);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
        $createUpdateBookForm = Livewire::test(CreateUpdateBookForm::class, ['action' => $booksTable->action])
            ->set(['form.title' => 'This is a book', 'form.author' => 'This is an author'])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->call('onCancelAction', $createUpdateBookForm->action)
            ->assertSet('action', '');
        
        $this->assertDatabaseCount('books', 0);
    }

    public function test_can_cancel_book_creation_by_reclicking_create()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->call('setAction', 'create')
            ->assertSet('action', '');
    }

    // Test on book edit (11)
    public function test_edit_component_displays_current_book_title_and_author()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->assertDontSee('This is a book')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['action' => $booksTable->action])
            ->set(['form.title' => 'Lord of the Rings', 'form.author' => 'Tolkien'])
            ->assertSet('form.title', 'Lord of the Rings')
            ->assertSet('form.author', 'Tolkien');
    }

    public function test_can_edit_book_with_valid_input()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->assertSee('Lord of the Rings')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        $createUpdateBookForm = Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => $booksTable->action])
            ->set(['form.title' => 'Waylander', 'form.author' => 'Gemmell'])
            ->call('save')
            ->assertDispatched('completeAction');

        $this->assertDatabaseCount('books', 1);

        $booksTable->call('onCompleteAction', $createUpdateBookForm->action)
            ->assertSeeInOrder(['Waylander', 'Gemmell'])
            ->assertSee('1 - 1 / 1')
            ->assertSee('The book was edited successfully.')
            ->assertSet('action', '');
    }

    public function test_cant_edit_book_with_too_short_input()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => $booksTable->action])
            ->set(['form.title' => 'b', 'form.author' => 'a'])
            ->call('save')
            ->assertHasErrors(['form.title' => ['min:2']])
            ->assertHasErrors(['form.author' => ['min:2']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The book title is too short.')
            ->assertSee('The book author is too short.');

        $this->assertDatabaseCount('books', 1);
    }

    public function test_cant_edit_book_without_required_input()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => $booksTable->action])
            ->set(['form.title' => '', 'form.author' => ''])
            ->call('save')
            ->assertHasErrors(['form.title' => ['required']])
            ->assertHasErrors(['form.author' => ['required']])
            ->assertNotDispatched('completeAction')
            ->assertSee('The book title is required.')
            ->assertSee('The book author is required.');

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_book_edit()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id);
        $createUpdateBookForm = Livewire::test(CreateUpdateBookForm::class, ['book' => $book, 'action' => $booksTable->action])
            ->set(['form.title' => 'This is a book', 'form.author' => 'This is an author'])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->call('onCancelAction', $createUpdateBookForm->action)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);
        
        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_book_edit_by_reclicking_edit()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_book_edit_by_clicking_another_edit()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'edit-'.$book1->id)
            ->assertSet('action', 'edit-'.$book1->id)
            ->call('setAction', 'edit-'.$book2->id)
            ->assertSet('action', 'edit-'.$book2->id)
            ->assertSee(['Lord of the Rings', 'Tolkien', 'Silmarillion']);
        
        $this->assertDatabaseCount('books', 2);
    }

    public function test_can_cancel_book_edit_by_page_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->call('nextPage')
            ->call('previousPage')
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    public function test_can_cancel_book_edit_by_page_size_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->set('perPage', 10)
            ->set('perPage', 5)
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    public function test_can_cancel_book_edit_by_search_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->set('search.title', 'Lord')
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    public function test_can_cancel_book_edit_by_order_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->call('setOrderField', 'author')
            ->assertSet('action', '')
            ->call('setOrderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }
}
