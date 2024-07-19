<header class="navbar is-light mb-3 is-flex is-justify-content-space-between">
    <div>
    </div>
    <div>
        <span class="navbar-item">
            @foreach (config('app.supported_locales') as $locale => $data)
                <a href="{{ route('locale', $locale)}}" class="mr-3">{{ html_entity_decode($data['icon']) }}</a>
            @endforeach
        </span>
    </div>
</header>