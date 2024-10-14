<?php

namespace TomatoPHP\FilamentDocs\Services\Contracts;


class DocsVar
{
    /**
     * @var string
     */
    public string $key;
    /**
     * @var ?string
     */
    public ?string $label = null;
    /**
     * @var ?string
     */
    public ?string $model = null;
    /**
     * @var ?string
     */
    public ?string $column = null;

    public string|null|\Closure $value = null;

    /**
     * @param string $key
     * @return static
     */
    public static function make(string $key):static
    {
        return (new self())->key($key);
    }

    /**
     * @param string $key
     * @return $this
     */
    public function key(string $key):static
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function label(string $label):static
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function model(string $model):static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function column(string $column):static
    {
        $this->column = $column;
        return $this;
    }

    /**
     * @param string|\Closure|null $value
     * @return $this
     */
    public function value(string|\Closure|null $value):static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray():array
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
