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
 * @method static void header(string $view)
 * @method static void footer(string $view)
 * @method static void css(string $view)
 */
class FilamentDocs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'filament-docs';
    }
}
