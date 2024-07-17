<?php

namespace App\Livewire;

use App\Http\Controllers\ExportController;
use App\Livewire\Forms\ExportForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Define ExportBookForm Livewire Component class.
 *
 * @property ExportForm $form defines the export form to access the export method
 * @property string $action defines the current action (optional, default: '')
 */
class ExportBookForm extends Component
{
    public ExportForm $form;

    public ?string $action = '';

    /**
     * Mount lifecycle hook called when a component is created to set the class properties.
     *
     * @param  string  $action  defines the current action (optional, default: '')
     */
    public function mount(?string $action = ''): void
    {
        $this->action = $action;
    }

    /**
     * Method called upon submission of the export-book form.
     * Call the ExportController related method to export the data and dispatch a livewire event to the main BookTable component
     * for user feedback.
     */
    public function export(): StreamedResponse
    {
        $this->validate();

        $exportFields = $this->form->fields === 'all' ?
            ['title', 'author'] : [$this->form->fields];
        $exporter = new ExportController($fields = $exportFields);

        if ($this->form->filetype === 'csv') {
            $response = $exporter->exportCsv();
        } else {
            $response = $exporter->exportXml();
        }

        $this->dispatch('completeAction', action: $this->action);

        return $response;
    }

    /**
     * Method called upon cancellation of the export-book form.
     * Dispatch a livewire event to the main BookTable component for user feedback.
     */
    public function cancel(): void
    {
        $this->dispatch('cancelAction');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('livewire.export-book-form');
    }
}
