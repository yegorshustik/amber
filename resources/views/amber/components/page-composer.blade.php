@if(($components = $content->components($children ?? null))->count() > 0)
    @foreach($components as $block)
        @if($block['name'] == 'Hero')
            <x-amber::hero :content="$block['content']" />
        @elseif($block['name'] == 'Quote')
            <x-amber::quote :style="$level > 0 ? 'margin-top:var(--space-16);' : ''" :content="$block['content']['quote']" />
        @elseif($block['name'] == 'Section')
            <x-amber::section
                :id="$block['content']['id'] ?? null"
                @style([
                    'padding-top: 0;' => ($block['content']['id'] ?? null) == 'pricing'
                ])
                :color="$block['content']['color'] ?? 'default'">
                @unless($block['content']['text']?->empty())
                    @if(!$block['content']['pre_heading']?->empty() || !$block['content']['heading']['text']?->empty() || !$block['content']['text']?->empty())
                        <x-slot name="head">
                            <div class="ac-container">
                                <div class="ac-cols">
                                    <div class="ac-cols__label">
                                        @unless($block['content']['pre_heading']?->empty())
                                            <p class="ac-eyebrow">{{ $block['content']['pre_heading'] }}</p>
                                        @endunless

                                        @unless($block['content']['heading']['text']?->empty())
                                            <x-amber::heading :max-characters="$block['content']['heading_max_characters'] ?? 1000" :level="$block['content']['heading']['level'] ?? 'h2'" :style="$block['content']['heading']['style'] ?? null">
                                                {!! $block['content']['heading']['text'] !!}
                                            </x-amber::heading>
                                        @endunless
                                    </div>
                                    <div class="ac-cols__body">
                                        {!! str_replace('<p', '<p class="ac-body"', $block['content']['text']) !!}
                                    </div>
                                </div>
                            </div>
                        </x-slot>
                    @endif
                    <x-amber::page-composer :level="$level + 1" :content="$content" :children="$block['children']" />
                @else
                    <div class="ac-container">
                        @unless($block['content']['pre_heading']?->empty())
                            <p class="ac-eyebrow">{{ $block['content']['pre_heading'] }}</p>
                        @endunless

                        @unless($block['content']['heading']['text']?->empty())
                            <x-amber::heading :max-characters="$block['content']['heading_max_characters'] ?? 1000" :level="$block['content']['heading']['level'] ?? 'h2'" :style="$block['content']['heading']['style'] ?? null">
                                {!! $block['content']['heading']['text'] !!}
                            </x-amber::heading>
                        @endunless

                        <x-amber::page-composer :level="$level + 1" :content="$content" :children="$block['children']" />
                    </div>
                @endunless
            </x-amber::section>
        @elseif($block['name'] == 'Reviews')
            <x-amber::reviews />
        @elseif($block['name'] == 'Cta')
            <x-amber::cta :content="$block['content']" />
        @elseif($block['name'] == 'Headline')
            <x-amber::headline :content="$block['content']" />
        @elseif($block['name'] == 'Cards')
            <x-amber::cards :type="$block['content']['type']" :image="$block['content']['image']" :cards="$block['content']['items']" :style="$level > 0 ? 'margin-top:var(--space-12);' : ''" />

            @unless($block['content']['button']?->empty())
                <div style="margin-top:var(--space-12);">
                    <a class="ac-btn ac-btn--ghost"
                       @if(isExternalUrl($block['content']['url'])) target="_blank" rel="noopener noreferrer" @endif
                       href="{{ isExternalUrl($block['content']['url']) ? $block['content']['url'] : locale_url($block['content']['url']) }}">
                        {{ $block['content']['button'] }}
                    </a>
                </div>
            @endunless
        @elseif($block['name'] == 'Text')
            <div class="text">
                {!! $block['content']['text'] !!}
            </div>
        @endif
    @endforeach
@endif
