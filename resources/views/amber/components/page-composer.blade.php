@if(($components = $content->components($children ?? null))->count() > 0)
    @foreach($components as $block)
        @if($block['name'] == 'Hero')
            <x-amber::hero :content="$block['content']" />
        @elseif($block['name'] == 'Quote')
            <x-amber::quote :style="$level > 0 ? 'margin-top:var(--space-16);' : ''" :content="$block['content']['quote']" />
        @elseif($block['name'] == 'Section')
            <x-amber::section :color="$block['content']['color'] ?? 'default'" :shrink="($block['content']['layout'] ?? 'default') == 'shrink'">
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
        @elseif($block['name'] == 'Text')
            <div class="text">
                {!! $block['content']['text'] !!}
            </div>
        @endif
    @endforeach
@endif
