<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Livewire\ExportBookForm;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExportBookFormTest extends TestCase
{
    use RefreshDatabase;

    // Test on book creation
    public function test_create_component_exists_on_the_page()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->assertSeeLivewire(ExportBookForm::class);
    }

    public function test_can_export_all_books_title_and_author_in_csv()
    {
        Book::factory(5)->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class)
            ->set(['form.fields' => 'all', 'form.filetype' => 'csv'])
            ->call('export')
            ->assertDispatched('completeAction')
            ->assertFileDownloaded('books.csv');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_export_all_books_title_in_csv()
    {
        Book::factory(5)->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class)
            ->set(['form.fields' => 'title', 'form.filetype' => 'csv'])
            ->call('export')
            ->assertDispatched('completeAction')
            ->assertFileDownloaded('books.csv');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_export_all_books_author_in_csv()
    {
        Book::factory(5)->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class)
            ->set(['form.fields' => 'author', 'form.filetype' => 'csv'])
            ->call('export')
            ->assertDispatched('completeAction')
            ->assertFileDownloaded('books.csv');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_export_all_books_title_and_author_in_xml()
    {
        Book::factory(5)->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class)
            ->set(['form.fields' => 'all', 'form.filetype' => 'xml'])
            ->call('export')
            ->assertDispatched('completeAction')
            ->assertFileDownloaded('books.xml');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_export_all_books_title_in_xml()
    {
        Book::factory(5)->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class)
            ->set(['form.fields' => 'title', 'form.filetype' => 'xml'])
            ->call('export')
            ->assertDispatched('completeAction')
            ->assertFileDownloaded('books.xml');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_export_all_books_author_in_xml()
    {
        Book::factory(5)->create();

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class)
            ->set(['form.fields' => 'author', 'form.filetype' => 'xml'])
            ->call('export')
            ->assertDispatched('completeAction')
            ->assertFileDownloaded('books.xml');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_cancel_all_export()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class, ['action' => 'export_all'])
            ->call('cancel')
            ->assertDispatched('cancelAction');
        Livewire::test(BooksTable::class)
            ->assertSet('action', '');
    }

    public function test_can_cancel_all_export_by_reclicking_export()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'export_all')
            ->assertSet('action', '');
    }

    public function test_can_cancel_all_export_by_clicking_another_action()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'create')
            ->assertSet('action', 'create');
    }
}
