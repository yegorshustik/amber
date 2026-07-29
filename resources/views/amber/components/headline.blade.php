<section {{ $attributes->merge(['class' => 'ac-page-hero ac-page-hero--' . ($content['color'] ?? 'paper')]) }}>
    <div class="ac-container">
        @unless($content['pre_heading']?->empty())
            <p class="ac-eyebrow">{{ $content['pre_heading'] }}</p>
        @else
            @if(seo()->breadcrumbs->count() > 1)
                <p class="ac-eyebrow">
                @foreach(seo()->breadcrumbs->items() as $item)
                    @if($loop->last)
                        {{ $item['title'] }}
                    @else
                        <a href="{{ $item['url'] }}" style="color:inherit;text-decoration:none;">{{ $item['title'] }}</a> &middot;
                    @endif
                @endforeach
                </p>
            @endif
        @endunless


        @unless($content['heading']['text']?->empty())
            <x-amber::heading :max-characters="22" :level="$content['heading']['level'] ?? 'h2'" :style="$content['heading']['style'] ?? null">
                {!! $content['heading']['text'] !!}
            </x-amber::heading>
        @endunless

        @unless($content['text']?->empty())
            <p class="ac-editorial" style="margin-top:var(--space-4);max-width:680px;">{!! nl2br($content['text']) !!}</p>
        @endunless

        <div class="ac-hero__cta" style="margin-top:var(--space-8);">
            @unless($content['button_1']?->empty())
                <a class="ac-btn ac-btn--primary"
                   @if(isExternalUrl($content['button_1_url'])) target="_blank" rel="noopener" @endif
                   href="{{ isExternalUrl($content['button_1_url']) ? $content['button_1_url'] : locale_url($content['button_1_url']) }}">
                    {{ $content['button_1'] }}
                </a>
            @endunless

            @unless($content['button_2']?->empty())
                <a class="ac-btn ac-btn--secondary"
                   @if(isExternalUrl($content['button_2_url'])) target="_blank" rel="noopener" @endif
                   href="{{ isExternalUrl($content['button_2_url']) ? $content['button_2_url'] : locale_url($content['button_2_url']) }}">
                    {{ $content['button_2'] }}
                </a>
            @endunless
        </div>
    </div>
</section>
