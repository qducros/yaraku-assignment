<?php

namespace App\Livewire;

use App\Http\Controllers\ExportController;
use App\Livewire\Forms\ExportForm;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
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
     * If export_all, dispatch a livewire event to the parent BookTable component for user feedback and download the file.
     * If export_bulk, dispatch an event to the parent BookTable component to get the selected data to export.
     */
    public function export(): ?StreamedResponse
    {
        $this->validate();

        if ($this->action === 'export_all') {
            $download = $this->download();

            $this->dispatch('completeAction', action: $this->action);

            return $download;
        } elseif ($this->action === 'export_bulk') {
            $this->dispatch('requestSelectionFromParent', action: $this->action);
        }

        return null;
    }

    /**
     * Listen to exportSelectionFromParent event dispatched from parent BookTable component.
     * If selectedOnPage length equals 0, no data were selected. Does nothing.
     * Else dispatch a livewire event to the parent BookTable component for user feedback and download the file. 
     * 
     * @param  array  $selectedOnPage  defines the elements to export (['all'] for all, [] for nothing or ['1', '2'] list of ids)
     */
    #[On('exportSelectionFromParent')]
    public function onExportSelectionFromParent(array $selectedOnPage): ?StreamedResponse
    {
        if (count($selectedOnPage) > 0) {
            $download = $this->download($selectedOnPage);
            
            $this->dispatch('completeAction', action: $this->action);

            return $download;
        }

        return null;
    }

    /**
     * Method called upon export.
     * Call the ExportController related method to return the streamed response with exported data.
     */
    public function download(?array $selectedOnPage = []): StreamedResponse
    {
        $exportFields = $this->form->fields === 'all' ?
            ['title', 'author'] : [$this->form->fields];
        $exporter = new ExportController($fields = $exportFields, $selection = $selectedOnPage);

        if ($this->form->filetype === 'csv') {
            $response = $exporter->exportCsv();
        } else {
            $response = $exporter->exportXml();
        }

        return $response;
    }

    /**
     * Method called upon cancellation of the export-book form.
     * Dispatch a livewire event to the parent BookTable component for user feedback.
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
