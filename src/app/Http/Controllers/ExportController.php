<?php

namespace App\Http\Controllers;

use App\Models\Book;
use DOMDocument;
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
        switch ($this->selection) {
            case ['all']:
                $books = Book::select($this->fields)->get()->toArray();
                break;
            case []:
                $books = Book::select($this->fields)->get()->toArray();
                break;
            default:
                $books = Book::select($this->fields)->whereIn('id', $this->selection)->get()->toArray();
        }
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
        switch ($this->selection) {
            case ['all']:
                $books = Book::select($this->fields)->get()->toArray();
                break;
            case []:
                $books = Book::select($this->fields)->get()->toArray();
                break;
            default:
                $books = Book::select($this->fields)->whereIn('id', $this->selection)->get()->toArray();
        }
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
}
