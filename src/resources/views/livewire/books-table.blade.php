<div>
    <div class="mb-5 field-body">
        <div class="field">
            <label for="title" class="label">Search by Title</label>
            <p class="control has-icons-left has-icons-right">
                <input type="text" class="input" placeholder="Search a book title" wire:model.live.debounce.500ms="search.title">
                <span class="icon is-small is-left">
                    <svg style="height:1em;" viewBox="0 0 12 13"><g stroke-width="2" stroke="#999999" fill="none"><path d="M11.29 11.71l-4-4"/><circle cx="5" cy="5" r="4"/></g></svg>
                </span>
            </p>
        </div>
        <div class="field">
            <label for="title" class="label">Search by Author</label>
            <p class="control has-icons-left has-icons-right">
                <input type="text" class="input" placeholder="Search a book author" wire:model.live.debounce.500ms="search.author">
                <span class="icon is-small is-left">
                    <svg style="height:1em;" viewBox="0 0 12 13"><g stroke-width="2" stroke="#999999" fill="none"><path d="M11.29 11.71l-4-4"/><circle cx="5" cy="5" r="4"/></g></svg>
                </span>
            </p>
        </div>
    </div>

    <table class="table is-fullwidth">
        <thead class="has-background-info">
            <tr>
                <x-table-header :direction="$orderDirection" name="title" :field="$orderField">Title</x-table-header>
                <x-table-header :direction="$orderDirection" name="author" :field="$orderField">Author</x-table-header>
                <x-table-header :direction="$orderDirection" name="updated_at" :field="$orderField">Last modified</x-table-header>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $book)
                <div wire:key="{{ $book->id }}">
                    <tr>
                        <td>
                            <span class="has-text-black has-text-weight-bold">
                                {{ $book->title }}
                            </span>
                        </td>
                        <td>
                            {{ $book->author }}
                        </td>
                        <td>
                            {{ $book->updated_at }}
                        </td>
                        <td>
                            Actions
                        </td>
                    </tr>
                </div>
            @empty
                <tr>
                    <td colspan="4" class="has-background-light">
                        No books found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $books->links() }}

    <div class="is-flex is-justify-content-center is-align-items-center my-1">
        <span class="mr-2">
            {{ ($books->currentPage() - 1) * $books->perPage() + 1 }} - {{ ($books->currentPage() - 1) * $books->perPage() + $books->count() }} out of {{ $books->total() }}
        </span>
        @if($books->total() > 5)
            <div class="select is-small">
                <select wire:model.live="perPage">
                    <option value="5">5 per page</option>
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
        @endif
    </div>
</div>