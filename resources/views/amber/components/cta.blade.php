<a {{ $attributes->merge(['class' => 'ac-action', 'href' => isExternalUrl($content['url']) ? $content['url'] : locale_url($content['url']), 'target' => isExternalUrl($content['url']) ? '_blank' : false]) }}>
    <div class="ac-action__text">
        @unless($content['pre_heading']?->empty())
            <p class="ac-eyebrow">{{ $content['pre_heading'] }}</p>
        @endunless

        @unless($content['heading']['text']?->empty())
            <x-amber::heading :level="$content['heading']['level'] ?? 'h2'" :style="$content['heading']['style'] ?? null">
                {!! $content['heading']['text'] !!}
            </x-amber::heading>
        @endunless

        @unless($content['text']?->empty())
            <p>
                {!! nl2br($content['text']) !!}
            </p>
        @endunless
    </div>

    @unless($content['button']?->empty())
        <span class="ac-btn ac-btn--primary">{{ $content['button'] }}</span>
    @endunless
</a>
