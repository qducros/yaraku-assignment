<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Livewire\DeleteBookForm;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DeleteBookFormTest extends TestCase
{
    use RefreshDatabase;

    // Test on single book deletion (7)
    public function test_delete_single_component_exists_on_the_page()
    {
        $book = Book::factory()->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->assertSeeLivewire(DeleteBookForm::class);
    }

    public function test_delete_single_component_displays_single_text()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id);
        Livewire::test(DeleteBookForm::class, ['action' => 'delete-'.$book->id])
            ->assertSeeHtml('Are you sure you want to delete this book? You won\'t be able to go back.');
    }

    public function test_can_delete_single_book()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id);
        Livewire::test(DeleteBookForm::class, ['action' => 'delete-'.$book->id])
            ->call('delete')
            ->assertDispatched('completeAction');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '')
            ->assertDontSeeHtml('Tolkien');
    }

    public function test_can_cancel_single_book_delete()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id);
        Livewire::test(DeleteBookForm::class, ['action' => 'delete-'.$book->id])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);
    }

    public function test_can_cancel_single_book_delete_by_reclicking_delete()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);
    }

    public function test_can_cancel_single_book_delete_by_clicking_another_delete()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book1->id)
            ->assertSet('action', 'delete-'.$book1->id)
            ->call('setAction', 'delete-'.$book2->id)
            ->assertSet('action', 'delete-'.$book2->id)
            ->assertSee(['Lord of the Rings', 'Tolkien', 'Silmarillion']);
    }

    public function test_can_cancel_single_book_delete_by_changing_page()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->assertSeeLivewire(DeleteBookForm::class)
            ->call('nextPage')
            ->call('previousPage')
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
    }

    // Test on bulk book deletion (7)
    public function test_delete_bulk_component_exists_on_the_page()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeLivewire(DeleteBookForm::class);
    }

    public function test_delete_bulk_component_displays_bulk_text()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk');
        Livewire::test(DeleteBookForm::class, ['action' => 'delete_bulk'])
            ->assertSeeHtml('Are you sure you want to delete the selected books? You won\'t be able to go back.');
    }

    public function test_can_bulk_delete_book()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => 'delete_bulk'])
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: 'delete_bulk');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onRequestSelectionFromParent', action: 'delete_bulk')
            ->assertDispatched('deleteSelectionFromParent', selectedOnPage: $selection);
        $deleteBookForm->assertSet('action', 'delete_bulk')
            ->call('onDeleteSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: 'delete_bulk');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCompleteAction', action: 'delete_bulk')
            ->assertSet('action', '')
            ->assertDontSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_can_bulk_delete_book_all()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);
        $selectAll = true;
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertDontSeeHtml('Victor Hugo')
            ->assertSet('bookIds', $selection)
            ->set('selectAll', $selectAll);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => 'delete_bulk'])
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: 'delete_bulk');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onRequestSelectionFromParent', action: 'delete_bulk')
            ->assertDispatched('deleteSelectionFromParent', selectedOnPage: ['all']);
        $deleteBookForm->assertSet('action', 'delete_bulk')
            ->call('onDeleteSelectionFromParent', selectedOnPage: ['all'])
            ->assertDispatched('completeAction', action: 'delete_bulk');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCompleteAction', action: 'delete_bulk')
            ->assertSet('action', '')
            ->assertDontSeeHtml('Tolkien')
            ->assertDontSeeHtml('Victor Hugo');

        $this->assertDatabaseCount('books', 0);
    }

    public function test_bulk_delete_does_nothing_if_no_book_selected()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $bookIds = array_map('strval', $books->pluck('id')->toArray());
        $selection = [];

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $bookIds);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => 'delete_bulk'])
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: 'delete_bulk');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onRequestSelectionFromParent', action: 'delete_bulk')
            ->assertNotDispatched('deleteSelectionFromParent', selectedOnPage: $selection)
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_cancel_bulk_book_delete()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $bookIds = [$book->id];

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $bookIds);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => 'delete_bulk'])
            ->assertSet('action', 'delete_bulk')
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCancelAction')
            ->assertSet('action', '')
            ->assertSee('Tolkien');

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_bulk_book_delete_by_clicking_create_or_export_all()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->assertSee('Tolkien');

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->assertSee('Tolkien');

        $this->assertDatabaseCount('books', 1);
    }
}
