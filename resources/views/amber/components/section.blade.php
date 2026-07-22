<section {{ $attributes->merge(['class' => implode(' ', array_filter([
    'ac-section',
    'ac-section--' . $color
]))]) }}>

    @isset($head)
        {{ $head }}
    @endisset

    @isset($body)
        {{ $body }}
    @else
        {{ $slot }}
    @endisset
</section>
