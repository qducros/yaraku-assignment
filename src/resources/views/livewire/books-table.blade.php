<div x-data="{ selection: $wire.entangle('selection'), books_ids: $wire.entangle('bookIds'), select_all: $wire.entangle('selectAll') }">
    @if(session('success'))
        <article x-data="{show:true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="message is-success">
            <div class="message-body">
                {{ session('success') }}
            </div>
        </article>
    @endif
    @if(session('warning'))
        <article x-data="{show:true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="message is-warning">
            <div class="message-body">
                {{ session('warning') }}
            </div>
        </article>
    @endif

    <div class="mb-5 field-body">
        <div class="field">
            <label for="title" class="label">{{ __('messages.search.title.label') }}</label>
            <p class="control has-icons-left has-icons-right">
                <input type="text" class="input" placeholder="{{ __('messages.search.title.placeholder') }}" wire:model.live.debounce.500ms="search.title">
                <span class="icon is-small is-left">
                    <svg style="height:1em;" viewBox="0 0 12 13"><g stroke-width="2" stroke="#999999" fill="none"><path d="M11.29 11.71l-4-4"/><circle cx="5" cy="5" r="4"/></g></svg>
                </span>
            </p>
        </div>
        <div class="field">
            <label for="title" class="label">{{ __('messages.search.author.label') }}</label>
            <p class="control has-icons-left has-icons-right">
                <input type="text" class="input" placeholder="{{ __('messages.search.author.placeholder') }}" wire:model.live.debounce.500ms="search.author">
                <span class="icon is-small is-left">
                    <svg style="height:1em;" viewBox="0 0 12 13"><g stroke-width="2" stroke="#999999" fill="none"><path d="M11.29 11.71l-4-4"/><circle cx="5" cy="5" r="4"/></g></svg>
                </span>
            </p>
        </div>
    </div>

    <div class="is-flex is-justify-content-space-between mb-5 buttons">
        <div class="select">
            <select name="" id="" wire:model.live="action">
                <option value="">{{ __('messages.action.bulk.placeholder') }}</option>
                <option value="delete_bulk">{{ __('messages.action.bulk.delete.select') }}</option>
                <option value="export_bulk">{{ __('messages.action.bulk.export.select') }}</option>
            </select>
        </div>

        <div>
            <button class="button is-primary" wire:click="setAction('create')">
                {{ __('messages.action.create.button') }}
            </button>
            <button class="button is-warning" wire:click="setAction('export_all')">
                {{ __('messages.action.export_all.button') }}
            </button>
        </div>
    </div>

    <div class="has-background-light">
        @switch($action)
            @case('create')
                <livewire:create-update-book-form :action="$action"/>
                @break
            
            @case('delete_bulk')
                <livewire:delete-book-form :action="$action"/>
                @break

            @case('export_all')
                <livewire:export-book-form :action="$action"/>
                @break
            
            @case('export_bulk')
                <livewire:export-book-form :action="$action"/>
                @break

            @default

        @endswitch
    </div>

    <div class="table-container">
        <table class="table is-fullwidth">
            <thead class="has-background-link">
                <tr>    
                    @if(count($books) > 0)
                        <th>
                            <input id="page-checkbox-{{ $books->currentPage() }}" type="checkbox" @click="$dispatch('page_checkbox_clicked')"
                            :checked="books_ids.every(r => selection.includes(r))">
                        </th>
                    @endif
                    <x-table-header :direction="$orderDirection" name="title" :field="$orderField">{{ __('messages.table.header.title') }}</x-table-header>
                    <x-table-header :direction="$orderDirection" name="author" :field="$orderField">{{ __('messages.table.header.author') }}</x-table-header>
                    <x-table-header :direction="$orderDirection" name="updated_at" :field="$orderField">{{ __('messages.table.header.last_modified') }}</x-table-header>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($books) > 0)
                    <tr x-show="books_ids.every(r => selection.includes(r))">
                        <td colspan="5" class="has-background-light has-text-centered" x-show="select_all">
                            <span>{{ __('messages.table.selection.all') }}
                            @if($books->total() !== count($books))
                                <a href="#" class="ml-2" x-on:click.prevent="() => {select_all = false; selection = [];}">
                                    {{ __('messages.table.selection.deselect') }}{{' '}}<strong>{{ $books->total() }}</strong></a>
                            @endif
                            </span>
                        </td>
                        <td colspan="5" class="has-background-light has-text-centered" x-show="!select_all">
                            <span><strong>{{ count($books) }}</strong>{{' '}}{{ __('messages.table.selection.page') }}
                            @if($books->total() !== count($books))
                                <a href="#" class="ml-2" x-on:click.prevent="select_all = true">
                                    {{ __('messages.table.selection.select') }}{{' '}}<strong>{{ $books->total() }}</strong></a>
                            @endif
                            </span>
                        </td>
                    </tr>
                @endif

                @forelse($books as $book)
                    <div wire:key="{{ $book->id }}">
                        <tr>
                            <td>
                                <input id="checkbox-{{ $book->id }}" type="checkbox" wire:model="selection" value="{{ $book->id }}"
                                @page_checkbox_clicked.window="(e) => e.target.checked !== $el.checked && $el.click()" @click="(select_all && !$el.checked) && (select_all = false)">
                            </td>
                            <td>
                                <span class="has-text-black has-text-weight-bold">
                                    {{ $book->title }}
                                </span>
                            </td>
                            <td>
                                {{ $book->author }}
                            </td>
                            <td title="{{ $book->updated_at->diffForHumans() }}">
                                {{ $book->updated_at->setTimezone('Asia/Tokyo') }}
                            </td>
                            <td>
                                <div class="buttons">
                                    <button class="button is-info is-light" wire:click="setAction('{{ 'edit-'.$book->id }}')">{{ __('messages.action.edit.button') }}</button>
                                    <button class="button is-danger is-light" wire:click="setAction('{{ 'delete-'.$book->id }}')">{{ __('messages.action.delete.button') }}</button>
                                </div>
                            </td>
                        </tr>
                        @switch($action)
                            @case('edit-'.$book->id)
                                <tr>
                                    <td colspan="5" class="has-background-light">
                                        <livewire:create-update-book-form :action="$action" :book="$book" :key="'edit'.$book->id" />
                                    </td>
                                </tr>
                                @break
                            @case('delete-'.$book->id)
                                <tr>
                                    <td colspan="5" class="has-background-light">
                                        <livewire:delete-book-form :action="$action" :bookId="$book->id" :key="'delete'.$book->id" />
                                    </td>
                                </tr>
                                @break

                            @default

                        @endswitch
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="has-background-light">
                            {{ __('messages.table.no_results') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $books->links() }}

    <div class="is-flex is-justify-content-center is-align-items-center my-1">
        <span class="mr-2">
            {{ ($books->currentPage() - 1) * $books->perPage() + 1 }} - {{ ($books->currentPage() - 1) * $books->perPage() + $books->count() }} / {{ $books->total() }}
        </span>
        @if($books->total() > 5)
            <div class="select is-small">
                <select wire:model.live="perPage">
                    <option value="5">5 {{ __('messages.table.pagination.per_page') }}</option>
                    <option value="10">10 {{ __('messages.table.pagination.per_page') }}</option>
                    <option value="25">25 {{ __('messages.table.pagination.per_page') }}</option>
                    <option value="50">50 {{ __('messages.table.pagination.per_page') }}</option>
                </select>
            </div>
        @endif
    </div>
</div>