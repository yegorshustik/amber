@if($content['image']?->exists())
    <figure {{ $attributes->merge(['class' => 'ac-figure']) }}>
        <img class="ac-photo ac-photo--wide"
             src="{{ $content['image']->url() }}"
             alt="{{ $content['image']->alt() }}"
             style="object-position: 50% 28%;"
             loading="lazy"
             width="1122"
             height="1402">
        @unless($content['signature']?->empty())
        <figcaption>{{ $content['signature'] }}</figcaption>
        @endunless
    </figure>
@endif
