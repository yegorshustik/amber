@if($type == 'stage')
    <div {{ $attributes->merge(['class' => 'ac-path']) }}>
        @foreach ($cards as $card)
            <div class="ac-step">
                @unless($card['pre_heading']?->empty())
                    <p class="ac-step__k">{{ $card['pre_heading'] }}</p>
                @endunless
                @unless($card['heading']?->empty())
                    <h3 class="ac-step__t">{{ $card['heading'] }}</h3>
                @endunless
                @unless($card['text']?->empty())
                    <p class="ac-step__d">{!! nl2br($card['text']) !!}</p>
                @endunless
            </div>
        @endforeach
    </div>
@elseif($type == 'feature')
    <div {{ $attributes->merge(['class' => 'ac-features']) }}>
        @foreach ($cards as $card)
            <div class="ac-feature">
                @if($card['image']?->exists())
                    <span class="ac-feature__icon" aria-hidden="true">
                        {!! $card['image']->body() !!}
                    </span>
                @endif
                @unless($card['heading']?->empty())
                    <h3 class="ac-feature__t">{{ $card['heading'] }}</h3>
                @endunless
                @unless($card['text']?->empty())
                    <p class="ac-feature__d">{!! nl2br($card['text']) !!}</p>
                @endunless
            </div>
        @endforeach
    </div>
@elseif($type == 'stat')
    <div {{ $attributes->merge(['class' => 'ac-stats']) }}>
        @foreach ($cards as $card)
            <div class="ac-stat-tile">
                @unless($card['pre_heading']?->empty())
                    <p class="ac-stat-tile__k">{{ $card['pre_heading'] }}</p>
                @endunless

                @unless($card['heading']?->empty())
                    <p class="ac-stat-tile__n">{{ $card['heading'] }}</p>
                @endunless
            </div>
        @endforeach
    </div>
@elseif($type == 'step')
    <div {{ $attributes->merge(['class' => 'ac-process']) }}>
        @if($image?->exists())
        <figure class="ac-process__media">
            <img class="ac-photo ac-photo--portrait" src="{{ $image->url() }}" alt="{{ $image->alt() }}" loading="lazy" width="1536" height="1024">
        </figure>
        @endif
        <div class="ac-process__steps">
            @foreach ($cards as $card)
                <div class="ac-step">
                    @unless($card['pre_heading']?->empty())
                        <p class="ac-step__k">{{ $card['pre_heading'] }}</p>
                    @endunless

                    @unless($card['heading']?->empty())
                        <h3 class="ac-step__t">{{ $card['heading'] }}</h3>
                    @endunless

                    @unless($card['text']?->empty())
                        <p class="ac-step__d">{!! nl2br($card['text']) !!}</p>
                    @endunless
                </div>
            @endforeach
        </div>
    </div>
@endif
