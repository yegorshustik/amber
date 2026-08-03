<x-amber::layout>

    <x-amber::section color="paper">
        <div class="ac-container">
            @if(seo()->breadcrumbs->count() > 1)
                <p class="ac-eyebrow">
                    @foreach(seo()->breadcrumbs->items() as $item)
                        @if($loop->last)
                            {{ $item['title'] }}
                        @else
                            <a href="{{ $item['url'] }}" style="color:inherit;text-decoration:none;">{{ $item['title'] }}</a> &middot;
                        @endif
                    @endforeach
                </p>
            @endif

            <h1 class="ac-h1 ac-reveal is-revealed" style="max-width: 24ch; transition-delay: 100ms;">{{ seo()->h1 }}</h1>

            <div class="ac-post-meta">
                <span>{{ $article->getPublishedDateFormatted() }}</span>
                <span>·</span>
                <span>{{ $article->estimatedReadTime() }}</span>
            </div>
        </div>
    </x-amber::section>


    <x-amber::page-composer :content="$article->content" />

    @if(($related = $article->related)->count() > 0)
        <x-amber::section color="cream">
            <div class="ac-container">
                <h3 class="ac-h2" style="margin-bottom:var(--space-8);">{{ __('related.heading') }}</h3>
                <div class="ac-blog-grid" style="margin-top:var(--space-6);">
                    @foreach($related as $item)
                        <x-amber::article :article="$item" />
                    @endforeach
                </div>
            </div>
        </x-amber::section>
        <x-amber::section
            style="padding-top: 0"
            color="cream">
            <div class="ac-container">
                <x-amber::cta :content="[
                'pre_heading' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.pre-heading')]),
                'heading' => [
                    'text' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.heading')]),
                    'level' => 'h2'
                ],
                'text' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.text')]),
                'button' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.button')]),
                'url' => locale_url('contacts'),
            ]" />
            </div>
        </x-amber::section>
    @else
        <x-amber::section color="cream">
            <div class="ac-container">
                <x-amber::cta :content="[
                'pre_heading' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.pre-heading')]),
                'heading' => [
                    'text' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.heading')]),
                    'level' => 'h2'
                ],
                'text' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.text')]),
                'button' => new \App\Services\Api\MultilingualService(['en' => __('article.cta.button')]),
                'url' => locale_url('contacts'),
            ]" />
            </div>
        </x-amber::section>
    @endif

</x-amber::layout>
