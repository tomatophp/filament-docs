<?php

namespace TomatoPHP\FilamentDocs\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TomatoPHP\FilamentDocs\Tests\Database\Factories\DocumentTemplateVarFactory;

/**
 * @property string $id
 * @property DocumentTemplate $document_template_id
 * @property string $var
 * @property string $model
 * @property string $value
 */
class DocumentTemplateVar extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'document_template_id',
        'var',
        'model',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function documentTemplate(): BelongsTo
    {
        return $this->belongsTo(\TomatoPHP\FilamentDocs\Models\DocumentTemplate::class);
    }

    protected static function newFactory(): DocumentTemplateVarFactory
    {
        return DocumentTemplateVarFactory::new();
    }
}
