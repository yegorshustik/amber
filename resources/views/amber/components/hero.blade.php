<section class="ac-section ac-section--paper ac-hero-section">
    <div class="ac-container">
        <div class="ac-hero">
            <div class="ac-hero__text">
                @unless($content['pre_heading']?->empty())
                    <p class="ac-eyebrow ac-reveal">{{ $content['pre_heading'] }}</p>
                @endunless

                @unless($content['heading']['text']?->empty())
                    <x-amber::heading
                        class="ac-reveal"
                        style="max-width:21ch;"
                        :level="$content['heading']['level'] ?? 'h2'"
                        :style="$content['heading']['style'] ?? null">
                        {!! $content['heading']['text'] !!}
                    </x-amber::heading>
                @endunless

                @unless($content['text']?->empty())
                    <p class="ac-editorial ac-reveal" style="margin-top:var(--space-4);max-width:680px;">
                        {!! nl2br($content['text']) !!}
                    </p>
                @endunless

                <div class="ac-hero__cta ac-reveal">
                    <a class="ac-btn ac-btn--primary" href="{{ locale_url('academic-roadmap') }}">{{ __('explore-roadmap') }}</a>
                    <a class="ac-btn ac-btn--secondary" href="{{ locale_url('catalog') }}">{{ __('view-schools') }}</a>
                </div>
            </div>
            <div class="ac-hero__media">
                <div class="ac-hero-collage">
                    @if($content['image']?->exists())
                        <img class="ac-photo ac-photo--portrait ac-hero-collage__back ac-reveal"
                             src="{{ $content['image']->url() }}"
                             alt="{{ $content['image']->alt() }}"
                             width="1672"
                             height="941">
                    @endif
                    @if($content['image_2']?->exists())
                        <img class="ac-photo ac-photo--portrait ac-hero-collage__front ac-reveal"
                             src="{{ $content['image_2']->url() }}"
                             alt="{{ $content['image_2']->alt() }}"
                             loading="lazy"
                             width="1122"
                             height="1402">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
