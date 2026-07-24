{!! '<' . $level . ' ' . $attributes->merge(['class' => 'ac-'.($style ?? $level), 'style' => 'max-width:' . $maxCharacters . 'ch']) . '>' !!}
{!! $slot !!}
{!! '</' . $level . '>' !!}
