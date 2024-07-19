<form action="" wire:submit.prevent="save" class="p-3 {{ $action === 'create' ? 'mb-5' : '' }}">
    <div class="field">
        <div class="field-body">
            <div class="field">
                <label for="title" class="label">{{ __('messages.action.create.title.label') }}</label>
                <div class="control">
                    <input type="text" wire:model="form.title" class="input" placeholder="{{ __('messages.action.create.title.placeholder') }}">
                </div>
                @error("form.title")
                    <span class="help is-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="author" class="label">{{ __('messages.action.create.author.label') }}</label>
                <div class="control">
                    <input type="text" wire:model="form.author" class="input" placeholder="{{ __('messages.action.create.author.placeholder') }}">
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
                    @if($action === 'create')
                        <button class="button is-primary" type="submit">
                            {{ __('messages.action.create.button') }}
                        </button>
                    @else
                        <button class="button is-info" type="submit">
                            {{ __('messages.action.edit.button') }}
                        </button>
                    @endif
                    <button class="button" type="button" wire:click="cancel">
                        {{ __('messages.action.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>