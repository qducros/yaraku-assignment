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

    // Test on book export (11)
    public function test_export_component_exists_on_the_page()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->assertSeeLivewire(ExportBookForm::class);
    }

    public function test_export_component_displays_bulk_text()
    {
        $BooksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all');
        Livewire::test(ExportBookForm::class, ['action' => $BooksTable->action])
            ->assertSeeHtml('<h5>You are about to export <strong>all</strong> books.</h5>');
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
            ->call('download')
            // ->assertDispatched('completeAction', action: 'export_all')
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
            ->call('download')
            // ->assertDispatched('completeAction', action: 'export_all')
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
            ->call('download')
            // ->assertDispatched('completeAction', action: 'export_all')
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
            ->call('download')
            // ->assertDispatched('completeAction', action: 'export_all')
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
            ->call('download')
            // ->assertDispatched('completeAction', action: 'export_all')
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
            ->call('download')
            // ->assertDispatched('completeAction', action: 'export_all')
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

    // Test on bulk book export (10)
    public function test_export_bulk_component_exists_on_the_page()
    {
        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->assertSeeLivewire(ExportBookForm::class);
    }

    public function test_export_bulk_component_displays_bulk_text()
    {
        $book = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $booksTable = Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk');
        Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSeeHtml('<h5>You are about to export <strong>only the selected</strong> books.</h5>');
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
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $selection);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: $booksTable->action);
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
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $selection);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: $booksTable->action);
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
            ->call('download')
            ->assertFileDownloaded('books.csv');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $selection);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: $booksTable->action);
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
            ->call('download')
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $selection);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: $booksTable->action);
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
            ->call('download')
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $selection);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: $booksTable->action);
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
            ->call('download')
            ->assertFileDownloaded('books.xml');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onRequestSelectionFromParent', action: $booksTable->action)
            ->assertDispatched('exportSelectionFromParent', selectedOnPage: $selection);
        $exportBookForm->assertSet('action', $booksTable->action)
            ->call('onExportSelectionFromParent', selectedOnPage: $selection)
            ->assertDispatched('completeAction', action: $booksTable->action);
        $booksTable->assertSet('action', $booksTable->action)
            ->call('onCompleteAction', action: $booksTable->action)
            ->assertSet('action', '')
            ->assertSeeHtml('Tolkien');

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
            ->assertSeeHtml('Tolkien')
            ->assertSet('bookIds', $bookIds);
        $exportBookForm = Livewire::test(ExportBookForm::class, ['action' => $booksTable->action])
            ->assertSet('action', $booksTable->action)
            ->call('cancel')
            ->assertDispatched('cancelAction');
        $booksTable->assertSet('action', $exportBookForm->action)
            ->call('onCancelAction')
            ->assertSet('action', '')
            ->assertSee('Tolkien');

        $this->assertDatabaseCount('books', 1);
    }

    public function test_can_cancel_bulk_export_by_clicking_create_or_export_all()
    {
        Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);

        $this->assertDatabaseCount('books', 1);

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'create')
            ->assertSet('action', 'create')
            ->assertSee('Tolkien');

        Livewire::test(BooksTable::class)
            ->call('setAction', 'export_bulk')
            ->assertSet('action', 'export_bulk')
            ->call('setAction', 'export_all')
            ->assertSet('action', 'export_all')
            ->assertSee('Tolkien');

        $this->assertDatabaseCount('books', 1);
    }
}
