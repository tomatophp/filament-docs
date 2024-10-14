<?php

namespace TomatoPHP\FilamentDocs\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InteractsWithDocs
{
    /**
     * @return MorphMany
     */
    public function documents(): MorphMany
    {
        return $this->morphMany(\TomatoPHP\FilamentDocs\Models\Document::class, 'model');
    }
}
