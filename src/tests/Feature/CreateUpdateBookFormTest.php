<?php
 
namespace Tests\Feature\Livewire;

use App\Livewire\CreateUpdateBookForm;
use App\Livewire\BooksTable;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class CreateUpdateBookFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_exists_on_the_page()
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
            ->assertDispatched('createUpdateBook');
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
            ->assertNotDispatched('createUpdateBook')
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
            ->assertNotDispatched('createUpdateBook')
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
}
