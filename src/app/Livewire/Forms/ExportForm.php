<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;

/**
 * Define ExportForm Livewire Form class used for export validation.
 *
 * @property string $fields  defines the fields we want to export (default: 'all')
 * @property string $filetype  defines the type of the file we want to export (default: 'csv')
 * @property array $possibleFields  used for fields validation
 * @property array $possibleFileType  used for filetype validation
 */
class ExportForm extends Form
{
    public string $fields = 'all';
    public string $filetype = 'csv';
    protected array $possibleFields = ['all', 'title', 'author'];
    protected array $possibleFileType = ['csv', 'xml'];

    /**
     * Returns an array of validation rules.
     */
    public function rules(): array
    {
        return [
            'fields' => Rule::in($this->possibleFields),
            'filetype' => Rule::in($this->possibleFileType),
        ];
    }
}
