<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Livewire\DeleteBookForm;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Define the tests related to the DeleteBookForm class.
 * 
 * test_can_delete_single_book
 * test_can_cancel_single_book_delete
 * test_can_cancel_single_book_delete_by_reclicking_delete
 * test_can_cancel_single_book_delete_by_clicking_another_delete
 * test_can_cancel_single_book_delete_by_page_update
 * test_can_cancel_single_book_delete_by_page_size_update
 * test_can_cancel_single_book_delete_by_search_update
 * test_can_cancel_single_book_delete_by_order_update
 * test_can_bulk_delete_book
 * test_can_bulk_delete_book_all
 * test_can_bulk_delete_book_all_with_search
 * test_bulk_delete_book_does_nothing_if_no_book_selected
 * test_can_cancel_bulk_delete_book
 * test_can_cancel_bulk_delete_book_by_clicking_other_action
 */
class DeleteBookFormTest extends TestCase
{
    use RefreshDatabase;

    // Test on single book deletion (8)
    public function test_can_delete_single_book()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->assertSee('Lord of the Rings')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->assertSee('Are you sure you want to delete this book? You won\'t be able to go back.')    
            ->call('delete')
            ->assertDispatched('completeAction');

        $this->assertDatabaseCount('books', 0);

        $booksTable->call('onCompleteAction', $deleteBookForm->action)
            ->assertDontSee('Tolkien')
            ->assertSee('No books found.')
            ->assertSee('The book was deleted successfully.')
            ->assertSet('action', '');
    }

    public function test_can_cancel_single_book_delete()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->call('onCancelAction', $deleteBookForm->action)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);
        
        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_single_book_delete_by_reclicking_delete()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        
        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', '')
            ->assertSee(['Lord of the Rings', 'Tolkien']);

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_single_book_delete_by_clicking_another_delete()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book1->id)
            ->assertSet('action', 'delete-'.$book1->id)
            ->call('setAction', 'delete-'.$book2->id)
            ->assertSet('action', 'delete-'.$book2->id)
            ->assertSee(['Lord of the Rings', 'Tolkien', 'Silmarillion']);
            
        $this->assertDatabaseCount('books', 2);
        }

    public function test_can_cancel_single_book_delete_by_page_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->call('nextPage')
            ->call('previousPage')
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    public function test_can_cancel_single_book_delete_by_page_size_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->set('perPage', 10)
            ->set('perPage', 5)
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    public function test_can_cancel_single_book_delete_by_search_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->set('search.title', 'Lord')
            ->assertSet('action', '')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    public function test_can_cancel_single_book_delete_by_order_update()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(30)->create();

        $this->assertDatabaseCount('books', 31);

        Livewire::test(BooksTable::class)
            ->set('orderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->call('setOrderField', 'author')
            ->assertSet('action', '')
            ->call('setOrderField', 'updated_at')
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien');
        
        $this->assertDatabaseCount('books', 31);
    }

    // Test on bulk book deletion (6)
    public function test_can_bulk_delete_book()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($books[0]->title)
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->assertSee('Are you sure you want to delete the selected books? You won\'t be able to go back.')        
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onRequestSelectionFromParent', action: $deleteBookForm->action)
            ->assertDispatched('deleteSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $deleteBookForm->assertSet('action', 'delete_bulk')
            ->call('onDeleteSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCompleteAction', action: $deleteBookForm->action)
            ->assertSee('All selected books were deleted successfully.')
            ->assertSet('action', '')
            ->assertDontSeeHtml($books[0]->title);

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_delete_book_all()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);
        $selectAll = true;
        $selection = array_map('strval', $books1->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title)
            ->assertSet('bookIds', $selection)
            ->set('selectAll', $selectAll);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->assertSee('Are you sure you want to delete the selected books? You won\'t be able to go back.')        
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onRequestSelectionFromParent', action: $deleteBookForm->action)
            ->assertDispatched('deleteSelectionFromParent', selectedOnPage: ['title' => $booksTable->search['title'], 'author' => $booksTable->search['author']]);
        $deleteBookForm->assertSet('action', 'delete_bulk')
            ->call('onDeleteSelectionFromParent', selectedOnPage: ['title' => $booksTable->search['title'], 'author' => $booksTable->search['author']])
            ->assertDispatched('completeAction', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCompleteAction', action: $deleteBookForm->action)
            ->assertSee('All selected books were deleted successfully.')
            ->assertSet('action', '')
            ->assertDontSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title);

        $this->assertDatabaseCount('books', 0);
    }

    public function test_can_bulk_delete_book_all_with_search()
    {
        $books1 = Book::factory(5)->create(['title' => 'Les Miserables', 'author' => 'Victor Hugo']);
        $books2 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books3 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);
        $selectAll = true;
        $search = ['title' => '', 'author' => 'victor'];
        $selection = array_map('strval', $books1->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 15);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title)
            ->assertDontSeeHtml($books3[0]->title)
            ->set('search', $search)
            ->assertSet('bookIds', $selection)
            ->set('selectAll', $selectAll);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->assertSee('Are you sure you want to delete the selected books? You won\'t be able to go back.')        
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->assertSet('search', ['title' => '', 'author' => 'victor'])
            ->assertSet('selectAll', true)
            ->call('onRequestSelectionFromParent', action: $deleteBookForm->action)
            ->assertDispatched('deleteSelectionFromParent', selectedOnPage: $search);
        $deleteBookForm->assertSet('action', 'delete_bulk')
            ->call('onDeleteSelectionFromParent', selectedOnPage: ['title' => $booksTable->search['title'], 'author' => $booksTable->search['author']])
            ->assertDispatched('completeAction', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCompleteAction', action: $deleteBookForm->action)
            ->assertSee('All selected books were deleted successfully.')
            ->assertSet('action', '')
            ->assertDontSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title)
            ->assertDontSeeHtml($books3[0]->title);

        $this->assertDatabaseCount('books', 5);
    }

    public function test_bulk_delete_book_does_nothing_if_no_book_selected()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $bookIds = array_map('strval', $books->pluck('id')->toArray());
        $selection = [];

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($books[0]->title)
            ->assertSet('bookIds', $bookIds);
        $deleteBookForm = Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->assertSee('Are you sure you want to delete the selected books? You won\'t be able to go back.')        
            ->assertSet('action', 'delete_bulk')
            ->call('delete')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onRequestSelectionFromParent', action: $deleteBookForm->action)
            ->assertSet('selectAll', false)
            ->assertSet('bookIds', $bookIds)
            ->assertSet('selection', $selection)
            ->assertNotDispatched('deleteSelectionFromParent')
            ->assertSee('You need at least one selected book to perform this action.')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($books[0]->title);
        $deleteBookForm->assertSee('Are you sure you want to delete the selected books? You won\'t be able to go back.');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_cancel_bulk_delete_book()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $bookIds = [$book->id];

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($book->title)
            ->assertSet('bookIds', $bookIds);
        Livewire::test(DeleteBookForm::class, ['action' => $booksTable->action])
            ->assertSee('Are you sure you want to delete the selected books? You won\'t be able to go back.')        
            ->assertSet('action', 'delete_bulk')
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->assertSet('action', 'delete_bulk')
            ->call('onCancelAction')
            ->assertSet('action', '')
            ->assertSeeHtml($book->title);

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_bulk_delete_book_by_clicking_other_action()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->assertSeeHtml($book->title);
        
        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->assertSeeHtml($book->title);

        $this->assertDatabaseCount('books', 1);
    }
}
