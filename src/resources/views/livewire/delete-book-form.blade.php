<form action="" wire:submit.prevent="delete" class="p-3 {{ $action === 'delete_bulk' ? 'mb-5' : '' }}">
    <div class="field">
        @if($action === 'delete_bulk')
            <h5>{{ __('messages.action.bulk.delete.info') }}</h5>
        @else
            <h5>{{ __('messages.action.delete.info') }}</h5>
        @endif
    </div>

    <div class="field is-grouped">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <button class="button is-danger" type="submit">
                        {{ __('messages.action.delete.button_confirm') }}
                    </button>
                    <button class="button" type="button" wire:click="cancel">
                        {{ __('messages.action.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>