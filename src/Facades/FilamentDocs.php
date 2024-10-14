<?php

namespace TomatoPHP\FilamentDocs\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;

/**
 * @method static void register(DocsVar|array $var)
 * @method static \Illuminate\Support\Collection load()
 * @method static void create(Model $model)
 * @method static string body(int $template, ?array $vars=[])
 */
class FilamentDocs extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'filament-docs';
    }
}
