<?php

namespace TomatoPHP\FilamentDocs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use TomatoPHP\FilamentDocs\Models\Document;

trait InteractsWithDocs
{
    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'model');
    }
}
