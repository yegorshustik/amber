<x-amber::layout>
    <x-amber::section>
        <div class="py-lg-32 py-xl-48 d-flex flex-column align-items-center justify-content-center gap-lg-32 gap-24 rounded bg-white p-16">
            <img src="/images/error-404.webp" class="w-100 max-w-512" alt="" />
            <div class="h1">{{ __('error-404.heading') }}</div>
            <div class="text-secondary">{{ __('error-404.text') }}</div>
            <a href="{{ locale_url('/') }}" class="btn btn-primary btnlg">{{ __('error-404.cta') }}</a>
        </div>
    </x-amber::section>
</x-amber::layout>
