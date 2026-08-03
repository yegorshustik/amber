@if($hideToc)
    <div class="ac-prose" style="max-width: 100%;">
        {{ $slot }}
    </div>
@else
<div class="ac-doc">
    <nav class="ac-toc" data-toc aria-label="On this page"></nav>
    <article class="ac-prose">
        {{ $slot }}
    </article>
</div>
@endif
