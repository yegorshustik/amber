{!! '<' . $level . ' ' . $attributes->merge(['class' => 'ac-'.$level.' ' . $style, 'style' => 'max-width:' . $maxCharacters . 'ch']) . '>' !!}
{!! $slot !!}
{!! '</' . $level . '>' !!}
