<?php

namespace TomatoPHP\FilamentDocs\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TomatoPHP\FilamentDocs\Tests\Database\Factories\DocumentTemplateFactory;

/**
 * @property string $id
 * @property string $name
 * @property string $body
 * @property string $is_active
 * @property string $icon
 * @property string $color
 */
class DocumentTemplate extends Model
{
    use HasFactory;
    
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'body',
        'is_active',
        'icon',
        'color',
        'team_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(config('filament-docs.team_model'), 'team_id');
    }

    public function documentTemplateVars(): HasMany
    {
        return $this->hasMany(DocumentTemplateVar::class);
    }

    protected static function newFactory(): DocumentTemplateFactory
    {
        return DocumentTemplateFactory::new();
    }
}
