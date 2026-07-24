@if($isAdditionalFilled())
    <div class="ac-pricing-commitment">
        <div>
            @unless($content['pre_heading']?->empty())
                <p class="ac-eyebrow">{{ $content['pre_heading'] }}</p>
            @endunless

            @unless($content['heading']['text']?->empty())
                <x-amber::heading style="margin-bottom: var(--space-4);" :max-characters="22" :level="$content['heading']['level'] ?? 'h2'" :style="$content['heading']['style'] ?? null">
                    {!! $content['heading']['text'] !!}
                </x-amber::heading>
            @endunless

            @unless($content['text']?->empty())
                <p class="ac-editorial">{!! nl2br($content['text']) !!}</p>
            @endunless
        </div>

        <div style="background: var(--paper); padding: var(--space-8); border-radius: 8px; border: 1px solid var(--line);">
            @unless($content['additional']['pre_heading']?->empty())
                <p class="ac-eyebrow" style="color: var(--amber-deep);">{{ $content['additional']['pre_heading'] }}</p>
            @endunless

            @unless($content['additional']['heading']['text']?->empty())
                <x-amber::heading style="margin-bottom: var(--space-2);" :level="$content['additional']['heading']['level'] ?? 'h2'" :style="$content['additional']['heading']['style'] ?? null">
                    {!! $content['additional']['heading']['text'] !!}
                </x-amber::heading>
            @endunless

            @unless($content['additional']['text']?->empty())
                <p class="ac-small" style="color: var(--muted); margin-bottom: 0;">{!! nl2br($content['additional']['text']) !!}</p>
            @endunless
        </div>
    </div>
@else
    <div {{ $attributes->merge(['style' => 'max-width: 680px;']) }}>
        @unless($content['pre_heading']?->empty())
            <p class="ac-eyebrow">{{ $content['pre_heading'] }}</p>
        @endunless

        @unless($content['heading']['text']?->empty())
            <x-amber::heading style="margin-bottom: var(--space-4);" :max-characters="22" :level="$content['heading']['level'] ?? 'h2'" :style="$content['heading']['style'] ?? null">
                {!! $content['heading']['text'] !!}
            </x-amber::heading>
        @endunless

        @unless($content['text']?->empty())
            <p class="ac-editorial">{!! nl2br($content['text']) !!}</p>
        @endunless
    </div>
@endif
