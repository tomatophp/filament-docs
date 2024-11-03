<?php

namespace TomatoPHP\FilamentDocs\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'body',
        'is_active',
        'icon',
        'color',
        'team_id'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(config('filament-docs.team_model'), 'team_id');
    }


    /**
     * @return HasMany
     */
    public function documentTemplateVars(): HasMany
    {
        return $this->hasMany(DocumentTemplateVar::class);
    }
}
