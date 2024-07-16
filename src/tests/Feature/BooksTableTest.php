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

    // Test on component rendering (1)
    public function test_component_exists_on_the_page()
    {
        $this->get('/books')
            ->assertSeeLivewire(BooksTable::class);
    }

    // Tests on table content (2)
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

    // Tests on table sort (7)
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

    // Tests on table filter (4)
    public function test_no_filter_by_default()
    {
        Book::factory()->create(['title' => 'yyyyyyyy']);
        Book::factory()->create(['title' => 'zzzzzzzz']);
 
        Livewire::test(BooksTable::class)
            ->assertSeeInOrder(['yyyyyyyy', 'zzzzzzzz']);
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

    // Tests on table pagination (5)
    public function test_can_paginate_books_via_user_interface()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
 
        Livewire::test(BooksTable::class)
            ->set('perPage', '2')
            ->call('nextPage')
            ->assertSee('Troy')
            ->assertDontSee('LoTR')
            ->assertDontSee('Silmarillion')
            ->assertSee('3 - 3 out of 3');
    }

    public function test_can_paginate_books_via_url_query_string()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
 
        Livewire::withQueryParams(['page' => '2', 'pagesize' => '2'])
            ->test(BooksTable::class)
            ->assertSee('Troy')
            ->assertDontSee('LoTR')
            ->assertDontSee('Silmarillion')
            ->assertSee('3 - 3 out of 3');
    }

    public function test_can_sort_filter_paginate_books_via_user_interface()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Hobbit', 'author' => 'Tolkien']);
         
        Livewire::test(BooksTable::class)
            ->set('perPage', '2')
            ->set('search.author', 'to')
            ->set('orderField', 'title')
            ->set('orderDirection', 'DESC')
            ->call('nextPage')
            ->assertSee('Hobbit')
            ->assertDontSee('LoTR')
            ->assertDontSee('Silmarillion')
            ->assertSee('3 - 3 out of 3');
    }

    public function test_can_sort_filter_paginate_books_via_url_query_string()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Hobbit', 'author' => 'Tolkien']);
 
        Livewire::withQueryParams(['page' => '2', 'pagesize' => '2', 'author' => 'to', 'sort_field' => 'title', 'sort_direction' => 'DESC'])
            ->test(BooksTable::class)
            ->assertSee('Hobbit')
            ->assertDontSee('LoTR')
            ->assertDontSee('Silmarillion')
            ->assertSee('3 - 3 out of 3');
    }

    public function test_page_reset_on_page_size_update()
    {
        Book::factory()->create(['title' => 'LoTR', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        Book::factory()->create(['title' => 'Hobbit', 'author' => 'Tolkien']);
 
        Livewire::test(BooksTable::class)
            ->set('perPage', '2')
            ->call('nextPage')
            ->set('perPage', '5')
            ->assertSeeInOrder(['Hobbit', 'LoTR', 'Silmarillion'])
            ->assertSee('1 - 3 out of 3');
    }
}
