<?php

namespace TomatoPHP\FilamentDocs\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TomatoPHP\FilamentDocs\Tests\Database\Factories\DocumentFactory;

/**
 * @property string $id
 * @property DocumentTemplate $document_template_id
 * @property string $body
 * @property string $ref
 * @property string $is_send
 * @property string $team_id
 * @property string $model_id
 * @property string $model_type
 */
class Document extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'ref',
        'document_template_id',
        'body',
        'is_send',
        'team_id',
        'model_id',
        'model_type',
    ];

    protected $casts = [
        'is_send' => 'boolean',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function documentTemplate(): BelongsTo
    {
        return $this->belongsTo(\TomatoPHP\FilamentDocs\Models\DocumentTemplate::class, 'document_template_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(config('filament-docs.team_model'), 'team_id');
    }

    protected static function newFactory(): DocumentFactory
    {
        return DocumentFactory::new();
    }
}
