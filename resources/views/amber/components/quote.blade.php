@unless($content['text']?->empty())
    @if(($content['type'] ?? 'default') == 'full')
        <blockquote>
            "{{ $content['text'] }}"

            <div class="ac-slide__by" style="margin-top: var(--space-6);">
                @if($content['image']?->exists())
                <span class="ac-slide__avatar"><img src="{{ $content['image']->url() }}" alt="{{ $content['image']->alt() ?: $content['name'] ?? null }}"></span>
                @endif
                @unless($content['name']?->empty())
                    <span class="ac-slide__id">
                        <span class="ac-slide__name" style="color: var(--amber-deep);">{{ $content['name'] }}</span>
                        @unless($content['job']?->empty())
                            <span class="ac-slide__role">{{ $content['job'] }}</span>
                        @endunless
                    </span>
                @endunless
            </div>
        </blockquote>
    @else
        <div {{ $attributes->merge(['class' => 'ac-quote']) }}>
            @unless($content['pre_heading']?->empty())
                <p class="ac-eyebrow">{{ $content['pre_heading'] }}</p>
            @endunless

            <blockquote>
                {{ $content['text'] }}
            </blockquote>
        </div>
    @endif
@endunless
