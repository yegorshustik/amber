@unless($content['text']?->empty())
<div {{ $attributes->merge(['class' => 'ac-quote']) }}>
    @unless($content['pre_heading']?->empty())
        <p class="ac-eyebrow">{{ $content['pre_heading'] }}</p>
    @endunless

    <blockquote>
        {{ $content['text'] }}
    </blockquote>
</div>
@endunless
