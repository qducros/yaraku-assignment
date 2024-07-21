<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BooksTable;
use App\Livewire\ExportBookForm;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Define the tests related to the ExportBookForm class.
 * 
 * test_can_export_all_books_title_and_author_in_csv
 * test_can_export_all_books_title_in_csv
 * test_can_export_all_books_author_in_csv
 * test_can_export_all_books_title_and_author_in_xml
 * test_can_export_all_books_title_in_xml
 * test_can_export_all_books_author_in_xml
 * test_can_export_all_books_regardless_of_search
 * test_can_cancel_all_export
 * test_can_cancel_all_export_by_reclicking_all_export
 * test_can_cancel_all_export_by_clicking_another_action
 * test_export_bulk_component_displays_bulk_text
 * test_can_bulk_export_books_title_and_author_in_csv
 * test_can_bulk_export_books_title_in_csv
 * test_can_bulk_export_books_author_in_csv
 * test_can_bulk_export_books_title_and_author_in_xml
 * test_can_bulk_export_books_title_in_xml
 * test_can_bulk_export_books_author_in_xml
 * test_can_bulk_export_books_all
 * test_can_bulk_export_books_all_with_search
 * test_bulk_export_books_does_nothing_if_no_book_selected
 * test_can_cancel_bulk_export
 * test_can_cancel_bulk_export_by_clicking_cother_action
 */
class ExportBookFormTest extends TestCase
{
    use RefreshDatabase;

    // Test on book export (10)
    public function test_can_export_all_books_title_and_author_in_csv()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'all', 'form.filetype' => 'csv'])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('export')
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download', selectedOnPage: ['all'])
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All books were exported successfully.')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_export_all_books_title_in_csv()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'title', 'form.filetype' => 'csv'])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('export')
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download', selectedOnPage: ['all'])
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All books were exported successfully.')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_export_all_books_author_in_csv()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'author', 'form.filetype' => 'csv'])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('export')
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download', selectedOnPage: ['all'])
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All books were exported successfully.')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_export_all_books_title_and_author_in_xml()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'all', 'form.filetype' => 'xml'])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('export')
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download', selectedOnPage: ['all'])
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All books were exported successfully.')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_export_all_books_title_in_xml()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'title', 'form.filetype' => 'xml'])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('export')
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download', selectedOnPage: ['all'])
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All books were exported successfully.')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_export_all_books_author_in_xml()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'author', 'form.filetype' => 'xml'])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('export')
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download', selectedOnPage: ['all'])
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All books were exported successfully.')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_cancel_all_export()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSee('You are about to export all books regardless of the table filters.')
            ->assertSet('action', 'export_all')
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->assertSet('action', 'export_all')
            ->call('onCancelAction')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_cancel_all_export_by_reclicking_all_export()
    {
        Book::factory(10)->create();

        $this->assertDatabaseCount('books', 10);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'export_all')
            ->assertSet('action', '');

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_cancel_all_export_by_clicking_another_action()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($book->title);
        
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->assertSeeHtml($book->title);

        $this->assertDatabaseCount('books', 1);
    }

    // Test on bulk book export (10)
    public function test_export_bulk_component_displays_bulk_text()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk');
        Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSeeHtml('You are about to export only the selected books.');
    }

    public function test_can_bulk_export_books_title_and_author_in_csv()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'all', 'form.filetype' => 'csv'])
            ->assertSet('action', $booksTable->action)
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_export_books_title_in_csv()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'title', 'form.filetype' => 'csv'])
            ->assertSet('action', $booksTable->action)
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_export_books_author_in_csv()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'author', 'form.filetype' => 'csv'])
            ->assertSet('action', $booksTable->action)
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_export_books_title_and_author_in_xml()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'all', 'form.filetype' => 'xml'])
            ->assertSet('action', $booksTable->action)
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_export_books_title_in_xml()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'title', 'form.filetype' => 'xml'])
            ->assertSet('action', $booksTable->action)
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_export_books_author_in_xml()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $selection = array_map('strval', $books->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $selection)
            ->set('selection', $selection);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->set(['form.fields' => 'author', 'form.filetype' => 'xml'])
            ->assertSet('action', $booksTable->action)
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['ids' => $selection]);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: ['ids' => $selection])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_bulk_export_books_all()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);
        $selectAll = true;
        $selection = array_map('strval', $books1->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title)
            ->assertSet('bookIds', $selection)
            ->set('selectAll', $selectAll);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSet('action', 'export_bulk')
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'export_bulk')
            ->call('onRequestSelectionFromParent', action: $exportBookForm->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: ['title' => $booksTable->search['title'], 'author' => $booksTable->search['author']]);
        $exportBookForm->assertSet('action', 'export_bulk')
            ->call('onExportSelectionFromParent', selectedOnPage: ['title' => $booksTable->search['title'], 'author' => $booksTable->search['author']])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', 'export_bulk')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All selected books were exported successfully.')
            ->assertSet('action', '')
            ->assertSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title);

        $this->assertDatabaseCount('books', 10);
    }

    public function test_can_bulk_export_books_all_with_search()
    {
        $books1 = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $books2 = Book::factory(5)->create(['title' => 'Notre Dame de Paris', 'author' => 'Victor Hugo']);
        $selectAll = true;
        $search = ['title' => '', 'author' => 'victor'];
        $selection = array_map('strval', $books2->pluck('id')->toArray());

        $this->assertDatabaseCount('books', 10);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($books1[0]->title)
            ->assertDontSeeHtml($books2[0]->title)
            ->set('search', $search)
            ->assertSet('bookIds', $selection)
            ->set('selectAll', $selectAll);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSet('action', 'export_bulk')
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'export_bulk')
            ->assertSet('search', ['title' => '', 'author' => 'victor'])
            ->assertSet('selectAll', true)
            ->call('onRequestSelectionFromParent', action: $exportBookForm->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $search);
        $exportBookForm->assertSet('action', 'export_bulk')
            ->call('onExportSelectionFromParent', selectedOnPage: ['title' => $booksTable->search['title'], 'author' => $booksTable->search['author']])
            ->assertDispatched('completeAction', action: $booksTable->action)
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', 'export_bulk')
            ->call('onCompleteAction', action: $exportBookForm->action)
            ->assertSee('All selected books were exported successfully.')
            ->assertSet('action', '')
            ->assertSeeHtml($books2[0]->title)
            ->assertDontSeeHtml($books1[0]->title);

        $this->assertDatabaseCount('books', 10);
    }

    public function test_bulk_export_books_does_nothing_if_no_book_selected()
    {
        $books = Book::factory(5)->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $bookIds = array_map('strval', $books->pluck('id')->toArray());
        $selection = [];

        $this->assertDatabaseCount('books', 5);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($books[0]->title)
            ->assertSet('bookIds', $bookIds);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSet('action', 'export_bulk')
            ->call('export')
            ->assertDispatched('requestSelectionFromParent', action: $booksTable->action);
        $booksTable->assertSet('action', 'export_bulk')
            ->call('onRequestSelectionFromParent', action: $exportBookForm->action)
            ->assertSet('selectAll', false)
            ->assertSet('bookIds', $bookIds)
            ->assertSet('selection', $selection)
            ->assertNotDispatched('exportSelectionFromParent')
            ->assertSee('You need at least one selected book to perform this action.')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($books[0]->title);

        $this->assertDatabaseCount('books', 5);
    }

    public function test_can_cancel_bulk_export()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $bookIds = [$book->id];

        $this->assertDatabaseCount('books', 1);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeHtml($book->title)
            ->assertSet('bookIds', $bookIds);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSet('action', 'export_bulk')
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->assertSet('action', 'export_bulk')
            ->call('onCancelAction')
            ->assertSet('action', '')
            ->assertSeeHtml($book->title);

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_bulk_export_by_clicking_cother_action()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->assertSeeHtml($book->title);
        
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'delete_bulk')
            ->assertSet('action', 'delete_bulk')
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'edit-'.$book->id)
            ->assertSet('action', 'edit-'.$book->id)
            ->assertSeeHtml($book->title);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'delete-'.$book->id)
            ->assertSet('action', 'delete-'.$book->id)
            ->assertSeeHtml($book->title);

        $this->assertDatabaseCount('books', 1);
    }
}
