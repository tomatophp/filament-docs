<?php

namespace TomatoPHP\FilamentDocs\Services\Contracts;

class DocsVar
{
    public string $key;

    public ?string $label = null;

    public ?string $model = null;

    public ?string $column = null;

    public string | null | \Closure $value = null;

    public static function make(string $key): static
    {
        return (new self)->key($key);
    }

    /**
     * @return $this
     */
    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return $this
     */
    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function model(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return $this
     */
    public function column(string $column): static
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return $this
     */
    public function value(string | \Closure | null $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'model' => $this->model,
            'column' => $this->column,
            'value' => $this->value,
        ];
    }
}
