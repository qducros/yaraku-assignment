<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableHeader extends Component
{
    /**
     * Create a new component instance.
     *
     * @property string $direction sorting direction
     * @property string $name used when header is clicked
     * @property string $field used to set the visible property passed to the view
     */
    public function __construct(
        public string $direction,
        public string $name,
        public string $field,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     * Pass visible property to the view, to indicate what column is used for sorting
     *
     * @return View
     */
    public function render(): View|Closure|string
    {
        return view('components.table-header', [
            'visible' => $this->field === $this->name,
        ]);
    }
}
