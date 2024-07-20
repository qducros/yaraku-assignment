<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Define the tests related to the BooksTable class
 * 
 * test_component_renders_successfully_on_the_page
 * test_component_contains_empty_table
 * test_component_contains_non_empty_table
 * test_sort_by_title_asc_by_default
 * test_can_sort_books_by_title_via_table_header_click
 * test_can_sort_books_by_title_via_url_query_string
 * test_can_sort_books_by_author_via_table_header_click
 * test_can_sort_books_by_author_via_url_query_string
 * test_can_sort_books_by_last_updated_via_table_header_click
 * test_can_sort_books_by_last_updated_via_url_query_string
 * test_no_filter_by_default
 * test_can_filter_books_by_title_and_author_via_search_input
 * test_can_clear_filters
 * test_can_filter_books_by_title_and_author_via_url_query_string
 * test_can_filter_and_sort_books_via_user_interface
 * test_can_filter_and_sort_books_via_url_query_string
 * test_can_paginate_books_via_user_interface
 * test_can_paginate_books_via_url_query_string
 * test_can_sort_filter_paginate_books_via_user_interface
 * test_can_sort_filter_paginate_books_via_url_query_string
 * test_page_reset_on_page_size_update
 * test_bookIds_change_on_page_update
 * test_bookIds_change_on_page_size_update
 * test_bookIds_change_on_search_update
 * test_bookIds_change_on_sort_update
 * test_select_all_and_selection_reset_on_page_update
 * test_select_all_and_selection_reset_on_search_update
 * test_select_all_and_selection_reset_on_order_update
 * test_select_all_and_selection_reset_on_page_size_update
 */
class BooksTableTest extends TestCase
{
    use RefreshDatabase;

    // Test on component rendering (1)
    public function test_component_renders_successfully_on_the_page()
    {
        Livewire::test(BooksTable::class)
            ->assertStatus(200);
    }

    // Tests on table content (2)
    public function test_component_contains_empty_table()
    {
        Livewire::test(BooksTable::class)
            ->assertSee('No books found.');
    }

    public function test_component_contains_non_empty_table()
    {
        Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->assertSee('Lord of the Rings')
            ->assertSee('Tolkien')
            ->assertDontSee('No books found.');
    }

    // Tests on table sort (7)
    public function test_sort_by_title_asc_by_default()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->assertSeeInOrder([$book1->title, $book2->title])
            ->assertSeeInOrder([$book1->author, $book2->author]);
        }

    public function test_can_sort_books_by_title_via_table_header_click()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'title')
            ->assertSeeInOrder([$book2->title, $book1->title])
            ->assertSeeInOrder([$book2->author, $book1->author]);
    }

    public function test_can_sort_books_by_title_via_url_query_string()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::withQueryParams(['sort_field' => 'title', 'sort_direction' => 'DESC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder([$book2->title, $book1->title])
            ->assertSeeInOrder([$book2->author, $book1->author]);
    }

    public function test_can_sort_books_by_author_via_table_header_click()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'author')
            ->assertSeeInOrder([$book2->title, $book1->title])
            ->assertSeeInOrder([$book2->author, $book1->author]);
    }

    public function test_can_sort_books_by_author_via_url_query_string()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::withQueryParams(['sort_field' => 'author', 'sort_direction' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder([$book2->title, $book1->title])
            ->assertSeeInOrder([$book2->author, $book1->author]);
    }

    public function test_can_sort_books_by_last_updated_via_table_header_click()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->call('setOrderField', 'updated_at')
            ->assertSeeInOrder([$book1->title, $book2->title])
            ->assertSeeInOrder([$book1->author, $book2->author]);
    }

    public function test_can_sort_books_by_last_updated_via_url_query_string()
    {
        $book1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Waylander', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::withQueryParams(['sort_field' => 'updated_at', 'sort_direction' => 'ASC'])
            ->test(BooksTable::class)
            ->assertSeeInOrder([$book1->title, $book2->title])
            ->assertSeeInOrder([$book1->author, $book2->author]);
    }

    // Tests on table filter (4)
    public function test_no_filter_by_default()
    {
        $book1 = Book::factory()->create(['title' => 'yyyyyyyyy', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'zzzzzzzzz', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 2);

        Livewire::test(BooksTable::class)
            ->assertSeeInOrder([$book1->title, $book2->title])
            ->assertSeeInOrder([$book1->author, $book2->author]);
    }

    public function test_can_filter_books_by_title_and_author_via_search_input()
    {
        $book_no_filter1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book_no_filter2 = Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        $book_no_filter3 = Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        $book_filter1 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 4);

        Livewire::test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('search.author', 'to')
            ->assertSeeInOrder([$book_filter1->title, $book_filter1->author])
            ->assertDontSee($book_no_filter1->title)
            ->assertDontSee($book_no_filter2->author)
            ->assertDontSee($book_no_filter3->title);
    }

    public function test_can_clear_filters()
    {
        $book_no_filter1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book_no_filter2 = Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        $book_no_filter3 = Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        $book_filter1 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 4);

        Livewire::test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('search.author', 'to')
            ->assertSeeInOrder([$book_filter1->title, $book_filter1->author])
            ->assertDontSee($book_no_filter1->title)
            ->assertDontSee($book_no_filter2->author)
            ->assertDontSee($book_no_filter3->title)
            ->call('clearSearch', 'title')
            ->call('clearSearch', 'author')
            ->assertSeeInOrder([$book_filter1->title, $book_filter1->author])
            ->assertSeeInOrder([$book_no_filter2->title, $book_no_filter2->author])
            ->assertSeeInOrder([$book_no_filter3->title, $book_no_filter3->author])
            ->assertSeeInOrder([$book_no_filter1->title, $book_no_filter1->author]);
    }

    public function test_can_filter_books_by_title_and_author_via_url_query_string()
    {
        $book_no_filter1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book_no_filter2 = Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        $book_no_filter3 = Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        $book_filter1 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 4);

        Livewire::withQueryParams(['title' => 'si', 'author' => 'to'])
            ->test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('search.author', 'to')
            ->assertSeeInOrder([$book_filter1->title, $book_filter1->author])
            ->assertDontSee($book_no_filter1->title)
            ->assertDontSee($book_no_filter2->author)
            ->assertDontSee($book_no_filter3->title);
    }

    public function test_can_filter_and_sort_books_via_user_interface()
    {
        $book_no_filter1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book_no_filter2 = Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        $book_filter1 = Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        $book_filter2 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 4);

        Livewire::test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('orderField', 'author')
            ->set('orderDirection', 'DESC')
            ->assertSeeInOrder([$book_filter2->author, $book_filter1->author])
            ->assertDontSee($book_no_filter1->title)
            ->assertDontSee($book_no_filter2->title);
    }

    public function test_can_filter_and_sort_books_via_url_query_string()
    {
        $book_no_filter1 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $book_no_filter2 = Book::factory()->create(['title' => 'Troy', 'author' => 'Gemmell']);
        $book_filter1 = Book::factory()->create(['title' => 'A book with si', 'author' => 'Gemmell']);
        $book_filter2 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 4);

        Livewire::withQueryParams(['title' => 'si', 'sort_field' => 'author', 'sort_direction' => 'DESC'])
            ->test(BooksTable::class)
            ->set('search.title', 'si')
            ->set('orderField', 'author')
            ->set('orderDirection', 'DESC')
            ->assertSeeInOrder([$book_filter2->author, $book_filter1->author])
            ->assertDontSee($book_no_filter1->title)
            ->assertDontSee($book_no_filter2->title);
    }

    // Tests on table pagination (5)
    public function test_can_paginate_books_via_user_interface()
    {
        $books1 = Book::factory(10)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(10)->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $books3 = Book::factory(10)->create(['title' => 'Troy', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 30);

        Livewire::test(BooksTable::class)
            ->set('perPage', '10')
            ->assertSee($books1[0]->title)
            ->assertDontSee($books2[0]->title)
            ->assertDontSee($books3[0]->title)
            ->assertSee('1 - 10 / 30')
            ->call('nextPage')
            ->assertSee($books2[0]->title)
            ->assertDontSee($books1[0]->title)
            ->assertDontSee($books3[0]->title)
            ->assertSee('11 - 20 / 30');
    }

    public function test_can_paginate_books_via_url_query_string()
    {
        $books1 = Book::factory(10)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(10)->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $books3 = Book::factory(10)->create(['title' => 'Troy', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 30);

        Livewire::withQueryParams(['page' => '2', 'pagesize' => '10'])
            ->test(BooksTable::class)
            ->assertSee($books2[0]->title)
            ->assertDontSee($books1[0]->title)
            ->assertDontSee($books3[0]->title)
            ->assertSee('11 - 20 / 30');
    }

    public function test_can_sort_filter_paginate_books_via_user_interface()
    {        
        $books1 = Book::factory(10)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(10)->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $books3 = Book::factory(10)->create(['title' => 'Troy', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 30);

        Livewire::test(BooksTable::class)
            ->set('perPage', '10')
            ->set('search.author', 'to')
            ->set('orderField', 'title')
            ->set('orderDirection', 'DESC')
            ->call('nextPage')
            ->assertSee($books1[0]->title)
            ->assertDontSee($books2[0]->title)
            ->assertDontSee($books3[0]->title)
            ->assertSee('11 - 20 / 20');
    }

    public function test_can_sort_filter_paginate_books_via_url_query_string()
    {
        $books1 = Book::factory(10)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(10)->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $books3 = Book::factory(10)->create(['title' => 'Troy', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 30);

        Livewire::withQueryParams(['page' => '2', 'pagesize' => '10', 'author' => 'to', 'sort_field' => 'title', 'sort_direction' => 'DESC'])
            ->test(BooksTable::class)
            ->assertSee($books1[0]->title)
            ->assertDontSee($books2[0]->title)
            ->assertDontSee($books3[0]->title)
            ->assertSee('11 - 20 / 20');
    }

    public function test_page_reset_on_page_size_update()
    {
        $books1 = Book::factory(10)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(10)->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $books3 = Book::factory(10)->create(['title' => 'Troy', 'author' => 'Gemmell']);

        $this->assertDatabaseCount('books', 30);

        Livewire::test(BooksTable::class)
            ->set('perPage', '25')
            ->call('nextPage')
            ->set('perPage', '10')
            ->assertSee($books1[0]->title)
            ->assertDontSee($books2[0]->title)
            ->assertDontSee($books3[0]->title)
            ->assertSee('1 - 10 / 30');
    }

    // Tests on row selection (8)
    public function test_bookIds_change_on_page_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Les Miserables']);
        $books2 = Book::factory(5)->create(['title' => 'Lord of the Rings']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('bookIds', array_map('strval', $books1->pluck('id')->toArray()))
            ->call('nextPage')
            ->assertSet('bookIds', array_map('strval', $books2->pluck('id')->toArray()));
    }

    public function test_bookIds_change_on_page_size_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Les Miserables']);
        $books2 = Book::factory(5)->create(['title' => 'Lord of the Rings']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('bookIds', array_map('strval', $books1->pluck('id')->toArray()))
            ->set('perPage', 10)
            ->assertSet('bookIds', array_map('strval', array_merge($books1->pluck('id')->toArray(), $books2->pluck('id')->toArray())));
    }

    public function test_bookIds_change_on_search_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Les Miserables']);
        $books2 = Book::factory(5)->create(['title' => 'Lord of the Rings']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('bookIds', array_map('strval', $books1->pluck('id')->toArray()))
            ->set('search.title', 'the')
            ->assertSet('bookIds', array_map('strval', $books2->pluck('id')->toArray()));
    }

    public function test_bookIds_change_on_sort_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('bookIds', array_map('strval', $books1->pluck('id')->toArray()))
            ->set(['orderDirection' => 'ASC', 'orderField' => 'author'])
            ->assertSet('bookIds', array_map('strval', $books1->pluck('id')->toArray()));
    }

    public function test_select_all_and_selection_reset_on_page_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('selectAll', false)
            ->assertSet('selection', [])
            ->set('selectAll', true)
            ->set('selection', array_map('strval', $books1->pluck('id')->toArray()))
            ->call('nextPage')
            ->assertSet('selectAll', false)
            ->assertSet('selection', []);
    }

    public function test_select_all_and_selection_reset_on_search_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('selectAll', false)
            ->assertSet('selection', [])
            ->set('selectAll', true)
            ->set('selection', array_map('strval', $books1->pluck('id')->toArray()))
            ->set('search.title', 't')
            ->assertSet('selectAll', false)
            ->assertSet('selection', []);
    }

    public function test_select_all_and_selection_reset_on_order_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('selectAll', false)
            ->assertSet('selection', [])
            ->set('selectAll', true)
            ->set('selection', array_map('strval', $books1->pluck('id')->toArray()))
            ->call('setOrderField', 'author')
            ->assertSet('selectAll', false)
            ->assertSet('selection', []);
    }

    public function test_select_all_and_selection_reset_on_page_size_update()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->assertSet('selectAll', false)
            ->assertSet('selection', [])
            ->set('selectAll', true)
            ->set('selection', array_map('strval', $books1->pluck('id')->toArray()))
            ->set('perPage', '25')
            ->assertSet('selectAll', false)
            ->assertSet('selection', []);
    }
}
