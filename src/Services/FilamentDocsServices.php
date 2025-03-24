<?php

namespace TomatoPHP\FilamentDocs\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use TomatoPHP\FilamentDocs\Facades\FilamentDocs;
use TomatoPHP\FilamentDocs\Models\DocumentTemplate;
use TomatoPHP\FilamentDocs\Services\Contracts\DocsVar;

class FilamentDocsServices
{
    public array $vars = [];

    public ?string $header = null;

    public ?string $footer = null;

    public ?string $css = null;

    public function register(DocsVar | array $var): void
    {
        if (is_array($var)) {
            foreach ($var as $item) {
                $this->register($item);
            }
        } else {
            $this->vars[] = $var;
        }
    }

    public function load(): Collection
    {
        return collect($this->vars);
    }

    public function create(Model $model): void
    {
        foreach ($this->load()->where('value', null) as $item) {
            if (str($model->body)->contains($item->key)) {
                $exists = $model->documentTemplateVars()->where('var', $item->key)->first();
                if (! $exists) {
                    $model->documentTemplateVars()->create([
                        'var' => $item->key,
                        'model' => $item->model,
                        'value' => $item->column,
                    ]);
                }
            }
        }
    }

    public function body(int $template, ?array $vars = null): string
    {
        $templateBody = '';
        $getDocumentTemplate = DocumentTemplate::query()->find($template);
        if ($getDocumentTemplate) {
            $templateBody = $getDocumentTemplate->body;
            if (! empty($vars)) {
                foreach ($vars as $var) {
                    if (is_array($var) && isset($var['var'])) {
                        $templateBody = str($templateBody)->replace($var['var'], is_array($var['model']::query()->find($var['value'])?->toArray()[$var['key']]) ? $var['model']::query()->find($var['value'])?->toArray()[$var['key']][app()->getLocale()] : $var['model']::query()->find($var['value'])?->toArray()[$var['key']])->toString();
                    }
                }
            }

            Carbon::setLocale(app()->getLocale());
            $templateBody = str($templateBody)
                ->replace('$DAY', Carbon::now()->translatedFormat(config('filament-docs.dates.day')))
                ->replace('$DATE', Carbon::now()->translatedFormat(config('filament-docs.dates.date')))
                ->replace('$TIME', Carbon::now()->translatedFormat(config('filament-docs.dates.time')))
                ->replace('$UUID', Str::uuid())
                ->replace('$RANDOM', Str::random(6))
                ->toString();

            $fixedVars = array_merge(FilamentDocs::load()->where('value', '!=', null)->toArray(), $vars ?? []);
            foreach ($fixedVars as $item) {
                if (! is_array($item)) {
                    $value = '';
                    if ($item->value instanceof \Closure) {
                        $value = call_user_func($item->value);
                    } else {
                        $value = $item->value;
                    }
                    $templateBody = str($templateBody)->replace($item->key, $value)->toString();
                }
            }
        }

        return $templateBody;
    }

    public function header(string $header)
    {
        $this->header = $header;
    }

    public function getHeader()
    {
        if ($this->header) {
            return view($this->header)->render();
        }
    }

    public function footer(string $footer)
    {
        $this->footer = $footer;
    }

    public function getFooter()
    {
        if ($this->footer) {
            return view($this->footer)->render();
        }
    }

    public function css(string $css)
    {
        $this->css = $css;
    }

    public function getCss()
    {
        if ($this->css) {
            return view($this->css)->render();
        }
    }
}
