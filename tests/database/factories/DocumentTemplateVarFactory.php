<?php

namespace TomatoPHP\FilamentDocs\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentDocs\Tests\Models\DocumentTemplateVar;
use TomatoPHP\FilamentDocs\Tests\Models\User;

class DocumentTemplateVarFactory extends Factory
{
    protected $model = DocumentTemplateVar::class;

    public function definition(): array
    {
        return [
            'var' => $this->faker->word(),
            'model' => $this->faker->word(),
            'value' => $this->faker->text(),
        ];
    }

    public function withId($id)
    {
        return $this->state([
            "document_template_id" => $id,
        ]);
    }
}
