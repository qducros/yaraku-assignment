<form action="" wire:submit.prevent="export" class="p-3 mb-5">
    <div class="field">
        @if($action === 'export_all')
            <h5>{{ __('messages.action.export_all.info') }}</h5>
        @else
            <h5>{{ __('messages.action.bulk.export.info') }}</h5>
        @endif
    </div>

    <div class="field">
        <div class="field-body">
            <div class="select pr-1">
                <select wire:model="form.fields">
                    <option value="all">{{ __('messages.action.export_all.select.fields.all') }}</option>
                    <option value="title">{{ __('messages.action.export_all.select.fields.title') }}</option>
                    <option value="author">{{ __('messages.action.export_all.select.fields.author') }}</option>
                </select>
            </div>

            <div class="select">
                <select wire:model="form.filetype">
                    <option value="csv">{{ __('messages.action.export_all.select.filteype.csv') }}</option>
                    <option value="xml">{{ __('messages.action.export_all.select.filteype.xml') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="field is-grouped">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <button class="button is-warning" type="submit">
                        {{ __('messages.action.bulk.export.button') }}
                    </button>
                    <button class="button" type="button" wire:click="cancel">
                        {{ __('messages.action.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>