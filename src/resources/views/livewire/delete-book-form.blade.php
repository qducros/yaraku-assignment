<form action="" wire:submit.prevent="delete" class="p-3 {{ $action === 'delete_bulk' ? 'mb-5' : '' }}">
    <div class="field">
        @if($action === 'delete_bulk')
            <h5>Are you sure you want to delete the selected books? You won't be able to go back.</h5>
        @else
            <h5>Are you sure you want to delete this book? You won't be able to go back.</h5>
        @endif
    </div>

    <div class="field is-grouped">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <button class="button is-danger" type="submit">
                        Yes, delete
                    </button>
                    <button class="button" type="button" wire:click="cancel">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>