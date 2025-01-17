<div class="card {{ $class }}">
    @isset($slots['header'])
        <div class="card-header">
            {!! $slots['header']  !!}
        </div>
    @elseif($header)
        <div class="card-header">
            {!! $header !!}
        </div>
    @endif

    <div class="card-body">
        {!! $slot !!}
    </div>

    @isset($slots['footer'])
        <div class="card-footer">
            {!! $slots['footer']  !!}
        </div>
    @elseif($footer)
        <div class="card-footer">
            {!! $footer !!}
        </div>
    @endif
</div>