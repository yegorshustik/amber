<header {{ $attributes->merge(['class' => 'ac-header']) }}>
    <div class=ac-header__inner>
        <a href="{{ locale_url('/') }}"
           class="ac-header__logo-link"
           aria-label="{{ __('amber-council') }}">
            <img class="ac-header__logo" src="/assets/ac-lockup-dark.svg?v=61" alt="{{ __('amber-council') }}">
        </a>
        <button
            type="button"
            class="ac-burger"
            aria-label="Menu"
            aria-expanded="false"
            aria-controls="site-nav"
            onclick="var n = document.getElementById('site-nav'); if(n) { var open = n.classList.toggle('is-open'); this.setAttribute('aria-expanded', open ? 'true' : 'false'); var h = this.closest('.ac-header'); if(open){ h.classList.add('is-menu-open'); } else { setTimeout(function(){ h.classList.remove('is-menu-open'); }, 1200); } }">
            <div class="ac-burger__lines"><span></span><span></span><span></span></div>
            <span class="ac-burger__text">{{ __('menu') }}</span>
        </button>
        <nav class="ac-header__nav" id="site-nav">
            <div class="ac-header__nav-scroll">
                <div class="ac-nav-col">
                    <p class="ac-nav-col-title">{{ __('menu.explore') }}</p>
                    <a class="ac-nav-link" href="{{ locale_url('academic-roadmap') }}">{{ __('menu.academic-roadmap') }}</a>
                    <a class="ac-nav-link" href="{{ locale_url('services') }}">{{ __('menu.services') }}</a>
                    <a class="ac-nav-link" href="{{ locale_url('catalog') }}">{{ __('menu.schools-universities') }}</a>
                    <a class="ac-nav-link" href="{{ locale_url('pricing-policy') }}">{{ __('menu.pricing') }}</a>
                </div>
                <div class="ac-nav-col">
                    <p class="ac-nav-col-title">{{ __('menu.company') }}</p>
                    <a class="ac-nav-link" href="{{ locale_url('about') }}">{{ __('menu.about') }}</a>
                    <a class="ac-nav-link ac-hide-on-desktop" href="{{ locale_url('faq') }}">{{ __('menu.faq') }}</a>
                    <a class="ac-nav-link" href="{{ locale_url('contacts') }}">{{ __('menu.contacts') }}</a>
                    <a class="ac-nav-link ac-hide-on-desktop" href="{{ locale_url('blog') }}">{{ __('menu.blog') }}</a>
                </div>

                <a class="ac-btn ac-btn--primary ac-nav-cta" href="{{ locale_url('contacts') }}#lead">{{ __('menu.free-consultation') }}</a>

                <x-amber::locale class="ac-lang-picker--menu" />

            </div>
        </nav>
        <a class="ac-btn ac-btn--primary" href="{{ locale_url('contacts') }}#lead">{{ __('menu.free-consultation') }}</a>
    </div>
</header>
