<?php
 
namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
 
class BooksTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_exists_on_the_page()
    {
        $this->get('/books')
            ->assertSeeLivewire(BooksTable::class);
    }

    public function test_component_contains_empty_table()
    {
        Livewire::test(BooksTable::class)
            ->assertSee('No books found.');
    }

    public function test_component_contains_non_empty_table()
    {
        Book::factory()->create(['title' => 'LoTR']);

        Livewire::test(BooksTable::class)
            ->assertSee('LoTR');
    }

    public function test_sort_by_title_asc_by_default()
    {
        Book::factory()->create(['title' => 'LoTR']);
        Book::factory()->create(['title' => 'Harry Potter']);
 
        Livewire::test(BooksTable::class)
            ->assertSeeInOrder(['Harry Potter', 'LoTR']);
    }

    public function test_can_sort_books_by_title_via_table_header_click()
    {
        Book::factory()->create(['title' => 'LoTR']);
        Book::factory()->create(['title' => 'Harry Potter']);
        
        // Since title/ASC is the default order, call setOrderField('title') will set title/DESC order
        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'title')
            ->assertSeeInOrder(['LoTR', 'Harry Potter']);
    }

    public function test_can_sort_books_by_title_via_url_query_string()
    {
        Book::factory()->create(['title' => 'LoTR']);
        Book::factory()->create(['title' => 'Harry Potter']);
 
        Livewire::withQueryParams(['orderField' => 'title', 'orderDirection' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Harry Potter', 'LoTR']);
    }

    public function test_can_sort_books_by_author_via_table_header_click()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmel']);
        
        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'author')
            ->assertSeeInOrder(['Gemmel', 'Tolkien']);
    }

    public function test_can_sort_books_by_author_via_url_query_string()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmel']);
 
        Livewire::withQueryParams(['orderField' => 'author', 'orderDirection' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Gemmel', 'Tolkien']);
    }

    public function test_can_sort_books_by_last_updated_via_table_header_click()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmel']);
        
        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'updated_at')
            ->assertSeeInOrder(['Tolkien', 'Gemmel']);
    }

    public function test_can_sort_books_by_last_updated_via_url_query_string()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmel']);
 
        Livewire::withQueryParams(['orderField' => 'updated_at', 'orderDirection' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Tolkien', 'Gemmel']);
    }
}
