<th class="has-text-white-bis is-clickable" wire:click="setOrderField('{{ $name }}')">
    {{ $slot }}
    @if($visible)
        @if($direction === 'ASC')
            &#x25B2;
        @else
            &#x25BC;
        @endif
    @else
        <span class="pl-1">&#x25b5;</span>
    @endif
</th>