@php
    $rubric = $article->rubrics->where('slug', '!=', 'blog')->first()
@endphp
<a class="ac-blog-card" href="{{ $article->url }}" data-category="{{ $rubric?->slug }}">
    <span class="ac-blog-card__cat">{{ $rubric?->title }}</span>
    <h3 class="ac-blog-card__t">{{ $article->title }}</h3>
    <p class="ac-blog-card__d">{{ $article->announcement }}</p>
    <div class="ac-blog-card__meta">
        <span>{{ $article->getPublishedDateFormatted() }}</span>
        <span>{{ $article->estimatedReadTime() }}</span>
    </div>
</a>
