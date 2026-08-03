<div {{ $attributes->merge(['class' => 'ac-lang-picker']) }}>
    <button type="button" class="ac-lang-picker__toggle" aria-expanded="false" aria-haspopup="listbox">
        <svg class="ac-lang-picker__globe" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
        </svg>
        <span class="ac-lang-picker__current">{{ (new \App\Services\Localization())->current()['name'] }}</span>
    </button>
    <ul class="ac-lang-picker__menu" role="listbox" aria-label="Select language">
        @foreach((new \App\Services\Localization())->available() as $item)
            <li role="option"
                onclick="location.href = '{{ toggle_language($item['locale']) }}';"
                aria-selected="{{ $item['locale'] == (new \App\Services\Localization())->current()['locale'] ? 'true' : 'false' }}"
                data-lang="{{ $item['locale'] }}">
                {{ $item['name'] }}
            </li>
        @endforeach
    </ul>
</div>
