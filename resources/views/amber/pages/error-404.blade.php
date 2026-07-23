<x-amber::layout>
    <section class="ac-section ac-section--cream" style="min-height: 60vh; display: flex; align-items: center;">
        <div class="ac-container">
            <p class="ac-eyebrow">{{ __('error-404.pre-heading') }}</p>
            <h1 class="ac-h1" style="max-width: 22ch;">{{ __('error-404.heading') }}</h1>
            <p class="ac-editorial" style="margin-top:var(--space-4);max-width:620px;">{{ __('error-404.text') }}</p>
            <div class="ac-hero__cta" style="margin-top:var(--space-8);">
                <a class="ac-btn ac-btn--primary" href="{{ locale_url('/') }}">{{ __('error-404.button-1') }}</a>
                <a class="ac-btn ac-btn--secondary" href="{{ locale_url('catalog') }}">{{ __('error-404.button-2') }}</a>
            </div>
        </div>
    </section>

</x-amber::layout>
