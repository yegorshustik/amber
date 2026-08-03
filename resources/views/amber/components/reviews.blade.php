@if(($items = $reviews())->count() > 0)
    <section class="ac-section ac-section--cream">
        <div class="ac-container">
            <p class="ac-eyebrow" data-slider-eyebrow>{{ __('reviews.heading') }}</p>
            <div class="ac-slider" data-testimonials style="margin-top:var(--space-8);">
                <div class="ac-slider__track">

                    @foreach($items as $item)
                    <figure class="ac-slide">
                        <blockquote>"{!! $item->content !!}"</blockquote>
                        <figcaption class="ac-slide__by">
                            <span class="ac-slide__avatar" aria-hidden="true">
                                @if($item->image?->exists())
                                    <img src="{{ $item->image->url() }}" alt="{{ $item->image->alt() ?: $item->name }}">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                @endif
                            </span>
                            <span class="ac-slide__id">
                                <span class="ac-slide__name">{{ $item->name }}</span>
                                <span class="ac-slide__role">{{ $item->job }}</span>
                            </span>
                        </figcaption>
                    </figure>
                    @endforeach

                </div>

                <div class="ac-slider__nav">
                    <div class="ac-slider__arrows">
                        <button class="ac-slider__btn" type="button" data-prev aria-label="Previous testimonial">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                        </button>
                        <span class="ac-slider__counter" data-counter></span>
                        <button class="ac-slider__btn" type="button" data-next aria-label="Next testimonial">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endif
