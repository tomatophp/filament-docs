<?php

namespace TomatoPHP\FilamentDocs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
* @property DocumentTemplate $document_template_id
* @property string $var
* @property string $model
* @property string $value
 */
class DocumentTemplateVar extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'document_template_id',
        'var',
        'model',
        'value'
    ];

    protected $casts = [
        'value' => 'json'
    ];


    /**
     * @return BelongsTo
     */
    public function documentTemplate(): BelongsTo
    {
        return $this->belongsTo(\TomatoPHP\FilamentDocs\Models\DocumentTemplate::class);
    }
}
