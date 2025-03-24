<?php

namespace TomatoPHP\FilamentDocs\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TomatoPHP\FilamentDocs\Tests\Models\Document;
use TomatoPHP\FilamentDocs\Tests\Models\User;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'ref' => $this->faker->word(),
            'body' => $this->faker->text(),
            'is_send' => $this->faker->boolean(),
            'model_id' => $this->faker->uuid(),
            'model_type' => $this->faker->word(),
        ];
    }

    public function withId($id)
    {
        return $this->state([
            "document_template_id" => $id,
        ]);
    }

    public function withTeam($id)
    {
        return $this->state([
            "team_id" => $id,
        ]);
    }
}
