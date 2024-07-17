<form action="" wire:submit.prevent="export" class="p-3 mb-5">
    <div class="field">
        @if($action === 'export_all')
            <h5>You are about to export <strong>all</strong> books.</h5>
        @else
            <h5>You are about to export <strong>only the selected</strong> books.</h5>
        @endif
    </div>

    <div class="field">
        <div class="field-body">
            <div class="select pr-1">
                <select wire:model="form.fields">
                    <option value="all">Title and author</option>
                    <option value="title">Title only</option>
                    <option value="author">Author only</option>
                </select>
            </div>

            <div class="select">
                <select wire:model="form.filetype">
                    <option value="csv">Export .csv</option>
                    <option value="xml">Export .xml</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field is-grouped">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <button class="button is-warning" type="submit">
                        Export
                    </button>
                    <button class="button" type="button" wire:click="cancel">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>