<div>
    <table class="table is-fullwidth">
        <thead class="has-background-info">
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Last modified</th>
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
</div>