<div>
    @if(isset($record))
        {!! $record->body !!}
    @else
        {!! $this->getRecord()->body !!}
    @endif
</div>
