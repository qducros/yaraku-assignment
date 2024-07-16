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
 
        Livewire::withQueryParams(['sort_field' => 'title', 'sort_direction' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Harry Potter', 'LoTR']);
    }

    public function test_can_sort_books_by_author_via_table_header_click()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmell']);
        
        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'author')
            ->assertSeeInOrder(['Gemmell', 'Tolkien']);
    }

    public function test_can_sort_books_by_author_via_url_query_string()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmell']);
 
        Livewire::withQueryParams(['sort_field' => 'author', 'sort_direction' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Gemmell', 'Tolkien']);
    }

    public function test_can_sort_books_by_last_updated_via_table_header_click()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmell']);
        
        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'updated_at')
            ->assertSeeInOrder(['Tolkien', 'Gemmell']);
    }

    public function test_can_sort_books_by_last_updated_via_url_query_string()
    {
        Book::factory()->create(['author' => 'Tolkien']);
        Book::factory()->create(['author' => 'Gemmell']);
 
        Livewire::withQueryParams(['sort_field' => 'updated_at', 'sort_direction' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Tolkien', 'Gemmell']);
    }

    public function test_can_filter_books_by_title_and_author_via_search_input()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        
        Livewire::test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('search.author', 'to')
            ->assertSeeInOrder(['Silmarillion', 'Tolkien'])
            ->assertDontSee('LoTR')
            ->assertDontSee('Gemmell');
    }

    public function test_can_filter_books_by_title_and_author_via_url_query_string()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
 
        Livewire::withQueryParams(['title' => 'si', 'author' => 'to'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Silmarillion', 'Tolkien'])
            ->assertDontSee('LoTR')
            ->assertDontSee('Gemmell');
    }

    public function test_can_filter_and_sort_books_via_user_interface()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        
        Livewire::test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('orderField', 'author')
            ->set('orderDirection', 'DESC')
            ->assertSeeInOrder(['Tolkien', 'Gemmel'])
            ->assertDontSee('LoTR')
            ->assertDontSee('Troy');
    }

    public function test_can_filter_and_sort_books_via_url_query_string()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
 
        Livewire::withQueryParams(['title' => 'si', 'sort_field' => 'author', 'sort_direction' => 'DESC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder(['Tolkien', 'Gemmel'])
            ->assertDontSee('LoTR')
            ->assertDontSee('Troy');
    }
}
