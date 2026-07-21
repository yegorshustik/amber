@if(($components = $content->components($children ?? null))->count() > 0)
    @foreach($components as $block)
        @if($block['name'] == 'Text')
            <div class="text">
                {!! $block['content']['text'] !!}
            </div>
        @endif
    @endforeach
@endif
