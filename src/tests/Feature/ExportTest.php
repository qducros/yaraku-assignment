<?php

namespace Tests\Feature;

use App\Http\Controllers\ExportController;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    // Test on csv export (3)
    public function test_export_csv_with_title_and_author_fields()
    {
        $book1 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $exporter = new ExportController($fields = ['title', 'author'], $selection = []);
        $response = $this->toTestResponse($exporter->exportCsv());

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv')
            ->assertHeader('Content-Disposition', 'attachment; filename=books.csv');

        $output = '';
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('Title,Author', $output);
        $this->assertStringContainsString($book1->title.','.$book1->author, $output);
        $this->assertStringContainsString('"'.$book2->title.'",'.$book2->author, $output);
    }

    public function test_export_csv_with_only_title_field()
    {
        $book1 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $exporter = new ExportController($fields = ['title'], $selection = []);
        $response = $this->toTestResponse($exporter->exportCsv());

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv')
            ->assertHeader('Content-Disposition', 'attachment; filename=books.csv');

        $output = '';
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('Title', $output);
        $this->assertStringContainsString($book1->title, $output);
        $this->assertStringContainsString($book2->title, $output);
        $this->assertStringNotContainsString($book1->author, $output);
        $this->assertStringNotContainsString($book2->author, $output);
    }

    public function test_export_csv_with_only_author_field()
    {
        $book1 = Book::factory()->create(['title' => 'Silmarillion', 'author' => 'Tolkien']);
        $book2 = Book::factory()->create(['title' => 'Lord of the Rings', 'author' => 'Tolkien']);
        $exporter = new ExportController($fields = ['author'], $selection = []);
        $response = $this->toTestResponse($exporter->exportCsv());

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv')
            ->assertHeader('Content-Disposition', 'attachment; filename=books.csv');

        $output = '';
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('Author', $output);
        $this->assertStringContainsString($book1->author, $output);
        $this->assertStringContainsString($book2->author, $output);
        $this->assertStringNotContainsString($book1->title, $output);
        $this->assertStringNotContainsString($book2->title, $output);
    }

    // Test on xml export (3)
    public function test_export_xml_with_title_and_author_fields()
    {
        $books = Book::factory(5)->create();
        $exporter = new ExportController($fields = ['title', 'author'], $selection = []);
        $response = $this->toTestResponse($exporter->exportXml());

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/xml')
            ->assertHeader('Content-Disposition', 'attachment; filename=books.xml');

        $output = '';
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $output);
        $this->assertStringContainsString('<root>', $output);
        $this->assertStringContainsString('</root>', $output);

        foreach ($books as $book) {
            $this->assertStringContainsString('<title>'.$book->title.'</title>', $output);
            $this->assertStringContainsString('<author>'.$book->author.'</author>', $output);
        }
    }

    public function test_export_xml_with_only_title_field()
    {
        $books = Book::factory(5)->create();
        $exporter = new ExportController($fields = ['title'], $selection = []);
        $response = $this->toTestResponse($exporter->exportXml());

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/xml')
            ->assertHeader('Content-Disposition', 'attachment; filename=books.xml');

        $output = '';
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $output);
        $this->assertStringContainsString('<root>', $output);
        $this->assertStringContainsString('</root>', $output);

        foreach ($books as $book) {
            $this->assertStringContainsString('<title>'.$book->title.'</title>', $output);
            $this->assertStringNotContainsString('<author>'.$book->author.'</author>', $output);
        }
    }

    public function test_export_xml_with_only_author_field()
    {
        $books = Book::factory(5)->create();
        $exporter = new ExportController($fields = ['author'], $selection = []);
        $response = $this->toTestResponse($exporter->exportXml());

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/xml')
            ->assertHeader('Content-Disposition', 'attachment; filename=books.xml');

        $output = '';
        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $this->assertStringContainsString('<?xml version="1.0" encoding="UTF-8"?>', $output);
        $this->assertStringContainsString('<root>', $output);
        $this->assertStringContainsString('</root>', $output);

        foreach ($books as $book) {
            $this->assertStringNotContainsString('<title>'.$book->title.'</title>', $output);
            $this->assertStringContainsString('<author>'.$book->author.'</author>', $output);
        }
    }

    /**
     * Convert a raw response to a Laravel test response instance.
     *
     * @param  mixed  $response
     * @return \Illuminate\Testing\TestResponse
     */
    protected function toTestResponse($response)
    {
        return \Illuminate\Testing\TestResponse::fromBaseResponse($response);
    }
}
