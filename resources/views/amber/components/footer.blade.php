<footer {{ $attributes->merge(['class' => 'ac-footer']) }}>
    <div class="ac-footer__inner">
        <div>
            <img src="/assets/ac-lockup-dark.svg?v=61" alt="{{ __('amber-council') }}" height="64">
            <p class="ac-small" style="margin-top:var(--space-3);max-width:280px;color:rgba(237,228,210,.72);">
                {{ __('footer.text') }}
            </p>
        </div>
        <div>
            <p class="ac-footer__title">{{ __('explore') }}</p>
            <a href="{{ locale_url('academic-roadmap') }}">{{ __('menu.academic-roadmap') }}</a>
            <a href="{{ locale_url('services') }}">{{ __('menu.services') }}</a>
            <a href="{{ locale_url('catalog') }}">{{ __('menu.schools-universities') }}</a>
            <a href="{{ locale_url('pricing-policy') }}">{{ __('menu.pricing') }}</a>
        </div>
        <div>
            <p class="ac-footer__title">{{ __('company') }}</p>
            <a href="{{ locale_url('about') }}">{{ __('menu.about') }}</a>
            <a href="{{ locale_url('faq') }}">{{ __('menu.faq') }}</a>
            <a href="{{ locale_url('contacts') }}">{{ __('menu.contacts') }}</a>
            <a href="{{ locale_url('blog') }}">{{ __('menu.blog') }}</a>
        </div>
        <div>
            <p class="ac-footer__title">{{ __('get-in-touch') }}</p>
            <a href="tel:{{ clearPhone(config('system.contacts.phone')) }}">{{ config('system.contacts.phone') }}</a>
            <a href="mailto:{{ config('system.contacts.email') }}">{{ config('system.contacts.email') }}</a>
            <a href="{{ config('system.contacts.google-maps-link') }}" target="_blank" rel="noopener">
                {{ config('system.contacts.address') }}
            </a>
        </div>
    </div>
    <div class="ac-footer__bottom">
        <div class="ac-footer__bottom-row">
            <span>© {{ now()->year }} {{ __('amber-council') }}. {{ __('all-rights-reserved') }}</span>
            <span class="ac-foot-legal">
                <a href="{{ locale_url('privacy-policy') }}">{{ __('privacy-policy') }}</a>
                <a href="{{ locale_url('terms') }}">{{ __('terms') }}</a>
            </span>

            <x-amber::locale />
        </div>
    </div>
</footer>

<button type="button"
        class="ac-scroll-top"
        id="scroll-to-top"
        aria-label="{{ __('scroll-to-top') }}"
        title="{{ __('scroll-to-top') }}">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 19V5M5 12l7-7 7 7"/>
    </svg>
</button>
