<?php

namespace App\Http\Controllers;

use App\Models\Book;
use DOMDocument;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Define ExportController class for csv / xml file export.
 *
 * @property array $fields defines fields to display inside the downloaded file
 * @property array $selection defines the selected data to display inside the downloaded file
 */
class ExportController extends Controller
{
    private array $fields;

    private array $selection;

    /**
     * @param  array  $fields  defines fields to display inside the downloaded file
     * @param  array  $selection  defines the selected data to display inside the downloaded file
     */
    public function __construct(array $fields, array $selection)
    {
        $this->fields = $fields;
        $this->selection = $selection;
    }

    /**
     * On call returns a StreamedResponse containing the csv file with all fields asked by the user.
     * It writes to the output buffer mechanism and sends the file ready to be downloaded.
     */
    public function exportCsv(): StreamedResponse
    {
        $books = $this->queryBulkBooks()->get()->toArray();
        $columns = array_map('ucfirst', $this->fields);
        $fileName = 'books';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$fileName.'.csv',
        ];

        $callback = function () use ($books, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($books as $book) {
                fputcsv($file, $book);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * On call returns a StreamedResponse containing the xml file with all fields asked by the user.
     * It creates a DOMDocument to allow xml indent format.
     */
    public function exportXml(): StreamedResponse
    {
        $books = $this->queryBulkBooks()->get()->toArray();
        $fileName = 'books';
        $headers = [
            'Content-type' => 'text/xml',
            'Content-Disposition' => 'attachment; filename='.$fileName.'.xml',
        ];

        $dom = new DOMDocument('1.0', 'UTF-8');

        $root = $dom->createElement('root');
        $dom->appendChild($root);

        foreach ($books as $book) {
            $parent = $dom->createElement('book');
            $root->appendChild($parent);

            foreach ($book as $key => $value) {
                $child = $dom->createElement($key, $value);
                $parent->appendChild($child);
            }
        }

        $dom->formatOutput = true;

        $xmlContent = $dom->saveXML();

        return response()->stream(function () use ($xmlContent) {
            echo $xmlContent;
        }, 200, $headers);
    }

    /**
     * Return the book query corresponding to the selection.
     * $this->selection possible values:
     * - ['all']: all selection regardless of search params
     * - ['ids' => ['1', '2']]: individual books/page selection using array of ids
     * - ['title' => '', 'author' => '']: all selection using the search params
     */
    private function queryBulkBooks(): Builder
    {
        $query = Book::query()->select($this->fields);
        if (array_key_exists('title', $this->selection) && ! empty($this->selection['title'])) {
            $query->where('title', 'LIKE', "%{$this->selection['title']}%");
        }
        if (array_key_exists('author', $this->selection) && ! empty($this->selection['author'])) {
            $query->where('author', 'LIKE', "%{$this->selection['author']}%");
        }
        if (array_key_exists('ids', $this->selection) && ! empty($this->selection['ids'])) {
            $query->whereIn('id', $this->selection['ids']);
        }

        return $query;
    }
}
