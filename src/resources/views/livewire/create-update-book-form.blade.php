<form action="" wire:submit.prevent="save" class="p-3 {{ $action === 'create' ? 'mb-5' : '' }}">
    <div class="field">
        <div class="field-body">
            <div class="field">
                <label for="title" class="label">Title</label>
                <div class="control">
                    <input type="text" wire:model="form.title" class="input" placeholder="Book title">
                </div>
                @error("form.title")
                    <span class="help is-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="author" class="label">Author</label>
                <div class="control">
                    <input type="text" wire:model="form.author" class="input" placeholder="Book author">
                </div>
                @error("form.author")
                    <span class="help is-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="field is-grouped">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <button class="button is-primary" type="submit">
                        @if($action === 'create')
                            Create
                        @else
                            Edit
                        @endif
                    </button>
                    <button class="button" type="button" wire:click="cancel">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>