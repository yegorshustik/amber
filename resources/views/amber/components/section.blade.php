<section {{ $attributes->except('style')->merge(['class' => implode(' ', array_filter([
    'ac-section',
    'ac-section--' . $color
]))]) }} @if($attributes->has('style') && $attributes->get('style')) style="{{ $attributes->get('style') }}" @endif>

    @isset($head)
        {{ $head }}
    @endisset

    @isset($body)
        {{ $body }}
    @else
        {{ $slot }}
    @endisset
</section>
