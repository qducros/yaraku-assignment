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

    // Test on single book deletion
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
        Livewire::test(DeleteBookForm::class, ['bookId' => $book->id, 'action' => 'delete-'.$book->id])
            ->assertSeeHtml('Are you sure you want to delete this book? You won\'t be able to go back.');
    }

    public function test_can_delete_single_book()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id);
        Livewire::test(DeleteBookForm::class, ['bookId' => $book->id, 'action' => 'delete-'.$book->id])
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
        Livewire::test(DeleteBookForm::class, ['bookId' => $book->id, 'action' => 'delete-'.$book->id])
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
}