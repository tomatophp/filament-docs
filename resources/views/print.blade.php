<div>
    {!! \Illuminate\Support\Str::sanitizeHtml((string) (isset($record) ? $record->body : $this->getRecord()->body)) !!}
</div>
